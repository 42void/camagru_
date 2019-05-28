<?php
  require 'class.user.php';
  session_start();

  $user = new USER();

  if (isset($_POST['btn-login'])) {
    $email = trim($_POST['txtemail']);
    $upass = trim($_POST['txtupass']);

    if($user->login($email, $upass)) {
      $user->redirect('home.php');
    }
  }
?>

<!DOCTYPE html>
<html>

<head>
  <link rel="stylesheet" href="index.css" type="text/css">
  <title>Camagru - Log in</title>
</head>

<body>
  <div class="container">
    <?php
    if (isset($_GET['inactive'])) {
      ?>
      <div class='alert alert-error'>
        This account is not activated.<br />
        Please go to your inbox and activate it.
      </div>
    <?php
  }
  ?>
    <h1 class='title'>Camagru</h1>
    <form class="form" method="post">
      <?php
    if (isset($_GET['error'])) {
      ?>
        <div class='alert alert-success'>
          <strong>Incorrect email and/or password</strong>
        </div>
      <?php
    }
    ?>

      <input type="email" class="input" placeholder="Email address" name="txtemail" autocomplete="on" required />
      <input type="password" class="input" placeholder="Password" name="txtupass" autocomplete="on" required />
      <div class="buttonsContainer">
        <button type="submit" class="btn" name="btn-login">
          Log in
        </button>
        <a href="signup.php" class="btn">
          Sign up
        </a>
      </div>
      <a class="forgotPswd" href="fpass.php">Forgot your password ? </a>
    </form>

  </div>
</body>

</html>