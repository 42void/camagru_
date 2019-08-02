<?php
require 'class.user.php';
session_start();

$user = new USER();

if (isset($_POST['btn-login'])) {
  $username = trim($_POST['txtusername']);
  $upass = trim($_POST['txtupass']);

  if ($user->login($username, $upass)) {
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
      <a class='btn' href="gallery.php">Offline gallery</a>
      <?php
      if (isset($_GET['error'])) {
        ?>
        <div class='alert alert-success'>
          <strong>Incorrect username and/or password</strong>
        </div>
      <?php
      }
      ?>

      <input type="text" class="input" placeholder="Username" name="txtusername" autocomplete="on" required />
      <input type="password" class="input" placeholder="Password" name="txtupass" autocomplete="on" required />
      <div class="buttonsContainer">
        <button type="submit" class="btn" name="btn-login">Log in</button>
        <a href="signup.php" class="btn">Sign up</a>
      </div>
      <a class="forgotPswd" href="fpass.php">Forgot your password ? </a>
    </form>

  </div>
</body>

</html>