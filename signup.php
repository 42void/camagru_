<?php
require_once 'class.user.php';
session_start();

$user = new USER();

if ($user->is_logged_in() != "") {
  $user->redirect('home.php');
}

$success = "";

if (isset($_POST['btn-signup'])) {
  $uname = trim($_POST['txtuname']);
  $email = trim($_POST['txtemail']);
  $upass = trim($_POST['txtpass']);
  $error = "";
  if (strlen($upass) < 6) {
    $error = "Your password must be at least 6 characters long! ";
  }
  if (!preg_match("#[0-9]+#", $upass)) {
    $error = "Password must include at least one number! ";
  }
  if (!preg_match("#[a-z]+#", $upass)) {
    $error = "Password must include at least one lowercase letter! ";
  }
  if (!preg_match("#[A-Z]+#", $upass)) {
    $error = "Password must include at least one capital letter! ";
  }
  if (!preg_match("#\W+#", $upass)) {
    $error = "Password must include at least one symbol!";
  }

  if ($error === '') {
    $code = hash('whirlpool', uniqid(rand()));

    $stmt = $user->runQuery("SELECT * FROM tbl_users WHERE userEmail=:email");
    $stmt->bindparam(":email", $email);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
      if($row["userStatus"] == "Y") {
        $error = "You already have an account, please go to the login page";
      } else {
        $error = "You already signed up, please check your emails to validate the sign up process";
      }
    } else {
      if ($user->register($uname, $email, $upass, $code)) {
        echo "<script>console.log('code " . $code . "' );</script>";
        $message = "
      Hello $uname,

      Welcome to Camagru!
      To complete your registration, please just click on following link :

      http://localhost:8080/verify.php?code=$code

      Thanks :)";
        $subject = "Confirm Registration";

        $user->send_mail($email, $message, $subject);
        $success = "<strong>Success!</strong>  We've sent an email to $email.<br/>
         Please click on the confirmation link in the email to create your account.";
      } else {
        $error = "Internal server error: could not register new user";
      }
    }
  }
}
?>
<!DOCTYPE html>
<html>

<head>
  <link rel="stylesheet" href="index.css" type="text/css">
  <title>Camagru - Sign up</title>
</head>

<body id="login">
  <div class="container">
    <?php
    if ($error !== "") {
      ?>
      <div class='alert alert-success'>
        <strong><?php echo $error; ?></strong>
      </div>
    <?php
  }
  if ($success !== "") {
    ?>
    <div class='alert alert-success'>
      <strong><?php echo $success; ?></strong>
    </div>
  <?php
}
  ?>

    <form class="form" method="post">
      <h1>Sign up</h1>
      <input class="input" type="text" placeholder="Username" name="txtuname" required <?php if($error !== "") echo "value='$uname'"; ?> />
      <input class="input" type="email" placeholder="Email address" name="txtemail" required <?php if($error !== "") echo "value='$email'"; ?> />
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