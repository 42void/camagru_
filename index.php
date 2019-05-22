<?php
// phpinfo();
session_start();
require 'class.user.php';

$user_login = new USER();

if ($user_login->is_logged_in() != "") {
  $user_login->redirect('home.php');
}

if (isset($_POST['btn-login'])) {
  $email = trim($_POST['txtemail']);
  $upass = trim($_POST['txtupass']);

  if ($user_login->login($email, $upass)) {
    $user_login->redirect('home.php');
  }
}
?>

<!DOCTYPE html>
<html>

<head>
  <link rel="stylesheet" href="style.css" type="text/css">
</head>

<body id="login">
  <div class="container">
    <?php
    if (isset($_GET['inactive'])) {
      ?>
      <div class='alert alert-error'>
        <button class='close' data-dismiss='alert'>&times;</button>
        <strong>Sorry!</strong> This Account is not activated.<br />
        Please go to your inbox and activate it.
      </div>
    <?php
  }
  ?>
    <form class="form-signin" method="post">
      <?php
      if (isset($_GET['passError'])) {
        ?>
        <div class='alert alert-success'>
          <button class='close' data-dismiss='alert'>&times;</button>
          <strong><?php echo $_GET['passError'] ?></strong>
        </div>
      <?php
    }
    if (isset($_GET['error'])) {
      ?>
        <div class='alert alert-success'>
          <button class='close' data-dismiss='alert'>&times;</button>
          <strong>Wrong Details!</strong>
        </div>
      <?php
    }

    ?>

      <h2 class="title">Camagrü.</h2>
      <hr />
      <input type="email" class="input" placeholder="Email address" name="txtemail" required />
      <input type="password" class="input" placeholder="Password" name="txtupass" required />
      <hr />
      <div class="buttonsContainer">
        <button type="submit" class="loginButton" name="btn-login">
          <span class="loginButtonText">Log in<span />
        </button>
        <a href="signup.php" class="signUpButton">
          <span class="signUpButtonText">
            Sign Up
            <span />
        </a>
      </div>
      <a class="forgotPswd" href="fpass.php">Lost your password ? </a>
    </form>

  </div>
</body>

</html>