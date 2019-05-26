<?php
session_start();
require_once 'class.user.php';

$reg_user = new USER();

if ($reg_user->is_logged_in() != "") {
  $reg_user->redirect('home.php');
}

if (isset($_POST['btn-signup'])) {
  $uname = trim($_POST['txtuname']);
  $email = trim($_POST['txtemail']);
  $upass = trim($_POST['txtpass']);
  if (strlen($upass) < 6) {
    $error .= "Your password must be at least 6 characters long! ";
    header("Location: signup.php?passError=$error");
    exit;
  }
  if (!preg_match("#[0-9]+#", $upass)) {
    $error .= "Password must include at least one number! ";
    header("Location: signup.php?passError=$error");
    exit;
  }
  if (!preg_match("#[a-z]+#", $upass)) {
    $error .= "Password must include at least one letter! ";
    header("Location: signup.php?passError=$error");
    exit;
  }
  if (!preg_match("#[A-Z]+#", $upass)) {
    $error .= "Password must include at least one capital letter! ";
    header("Location: signup.php?passError=$error");
    exit;
  }
  if (!preg_match("#\W+#", $upass)) {
    $error .= "Password must include at least one symbol!";
    header("Location: signup.php?passError=$error");
    exit;
  }
  if (
    strlen($upass) > 6 && preg_match("#[0-9]+#", $upass) && preg_match("#[a-z]+#", $upass)
    && preg_match("#[A-Z]+#", $upass) && preg_match("#\W+#", $upass)
  ) {
    unset($_GET['passError']);
  }

  $code = hash('whirlpool', uniqid(rand()));

  $stmt = $reg_user->runQuery("SELECT * FROM tbl_users WHERE userEmail=:email");
  $stmt->bindparam(":email", $email);
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($row) {
    $msg = "<div class='alert alert-error'>
              You already have an account, please go to the login page
            </div>";
  } else {
    if ($reg_user->register($uname, $email, $upass, $code)) {
      $id = $reg_user->lasdID();
      $key = base64_encode($id);
      $id = $key;
      echo "<script>console.log('key " . $key . "' );</script>";
      echo "<script>console.log('code " . $code . "' );</script>";

      $message = "
      Hello $uname,

      Welcome to Camagru!
      To complete your registration, please just click on following link :

      http://localhost:8080/verify.php?id=$id&code=$code

      Thanks :)";
      $subject = "Confirm Registration";

      $reg_user->send_mail($email, $message, $subject);
      $msg = "
      <div class='alert alert-success'>
        <strong>Success!</strong>  We've sent an email to $email.<br/>
         Please click on the confirmation link in the email to create your account.
      </div>
     ";
    } else {
      echo "Sorry , query could no execute...\n";
    }
  }
}
?>
<!DOCTYPE html>
<html>

<head>
  <link rel="stylesheet" href="index.css" type="text/css">
  <title>Signup | Coding Cage</title>
</head>

<body id="login">
  <div class="container">
    <?php if (isset($msg)) {
      echo $msg;
    }

    if (isset($_GET['passError'])) {
      ?>
      <div class='alert alert-success'>
        <strong><?php echo $_GET['passError'] ?></strong>
      </div>
    <?php
  }
  ?>

    <form class="form" method="post">
      <h1>Sign up</h1>
      <input class="input" type="text" placeholder="Username" name="txtuname" required />
      <input class="input" type="email" placeholder="Email address" name="txtemail" required />
      <input class="input" type="password" placeholder="Password" name="txtpass" required />
      Password must be at least be 6 characters long, with 1 number, 1 symbol and 1 capital letter
      <div>
        <div class="signup-btn-container">
          <button type="submit" class="btn" name="btn-signup">
            Sign Up
          </button>
        </div>
        <div class="alreadyAccount">
          <p>
            Already have an account?
          </p>
          <div>
            <a href="index.php" class="btn">
                Log in
            </a>
          </div>
        </div>
      </div>
    </form>
  </div>
</body>

</html>