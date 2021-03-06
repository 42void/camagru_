<?php
  require_once 'class.user.php';
  session_start();
  unset($_SESSION['success']);
  $user = new USER();

  if (isset($_POST['btn-submit'])) {
    $email = htmlspecialchars($_POST['txtemail']);
    $stmt = $user->runQuery("SELECT userID FROM tbl_users WHERE userEmail=:email LIMIT 1");
    $stmt->bindparam(":email", $email);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
      $id = base64_encode($row['userID']);
      $code = hash('whirlpool', uniqid(rand()));

      $stmt = $user->runQuery("UPDATE tbl_users SET tokenCode=:token WHERE userEmail=:email");
      $stmt->bindparam(":token", $code);
      $stmt->bindparam(":email", $email);
      $stmt->execute();

      $message = "
          Hi !

          We got requested to reset your password. If you did this then just click the following link to reset your password, if not, just ignore this email.

          Click following link to reset your password :

          http://localhost:8080/resetpass.php?id=$id&code=$code

          Thanks!
          ";
      $subject = "Password Reset";

      $user->send_mail($email, $message, $subject);

      $msg = "<div>
                We've sent an email to $email.
                Please click on the password reset link in the email to generate new password.
              </div>";
    } else {
      $msg = "
        <div >
          <strong>Sorry!</strong> Email not found.
        </div>";
    }
  }
?>

<!DOCTYPE html>
<html>

<head>
  <title>Forgot Password</title>
  <link rel="stylesheet" href="index.css" type="text/css">
</head>

<nav>
  <a class='btn' href="home.php">Back to home</a>
  <a class='btn' href="logout.php">Logout</a>
</nav>

<body>

  <div class="container">

    <form class="form" method="post">
      <h1>Reset your password</h1>
      <?php
        if (isset($msg)) {
          echo $msg;
        } else {
      ?>
        <div>
          Please enter your email address and click on the link you receive to reset your password!
        </div>
      <?php
        }
      ?>
      <input type="email" class="input" placeholder="Email address" name="txtemail" required />
      <button type="submit" class="btn" name="btn-submit">Submit</button>
    </form>

  </div>
</body>
</html>