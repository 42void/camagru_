<?php
require_once 'class.user.php';
$user = new USER();

if (empty($_GET['code'])) {
  $user->redirect('index.php');
}

if (isset($_GET['code'])) {
  $stmt = $user->runQuery("SELECT * FROM tbl_users WHERE tokenCode=:token");
  $stmt->bindparam(":token", $_GET['code']);
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  if ($row) {
    if (isset($_POST['btn-reset-pass'])) {
      $pass = $_POST['pass'];
      $cpass = $_POST['confirm-pass'];
      if (strlen($pass) < 6) {
        $msg = "<div class='alert alert-block'>
        Yous password must be at least 6 characters long!
       </div>";
      } else if (strlen($pass) > 20) {
        $msg = "<div class='alert alert-block'>
        Your password must be less than 20 characters long!
       </div>";
      } else if (!preg_match("#[0-9]+#", $pass)) {
        $msg = "<div class='alert alert-block'>
        Password must include at least one number!
       </div>";
      } else if (!preg_match("#[a-z]+#", $pass)) {
        $msg = "<div class='alert alert-block'>
        Password must include at least one letter!
       </div>";
      } else if (!preg_match("#[A-Z]+#", $pass)) {
        $msg = "<div class='alert alert-block'>
        Password must include at least one capital letter!
       </div>";
      } else if (!preg_match("#\W+#", $pass)) {
        $msg = "<div class='alert alert-block'>
        Password must include at least one symbol!
       </div>";
      } else if ($cpass !== $pass) {
        $msg = "<div class='alert alert-block'>
        Password Doesn't match.
        </div>";
      } else {
        $stmt = $user->runQuery("UPDATE tbl_users SET userPass=:upass WHERE userID=:uid");
        $stmt->bindparam(":upass", hash('whirlpool', $cpass));
        $stmt->bindparam(":uid", $row['userID']);
        $stmt->execute();

        $msg = "<div class='alert alert-success'>
        Password Changed. You will be redirected in about 5 secs.
        </div>";
        header("refresh:5;index.php");
      }
    }
  } else {
    'Token has expired';
  }
}

?>
<!DOCTYPE html>
<html>

<head>
  <title>Password reset</title>
  <link rel="stylesheet" href="index.css" type="text/css">
  <link rel="stylesheet" href="home.css" type="text/css">
</head>
<nav>
  <a class='btn' href="home.php">Back to home</a>
  <a class='btn' href="logout.php">Logout</a>
</nav>

<body class="logoutDisplay" id="login">
  <h3 class="title">Password reset</h3>
  <div>
    <form method="post">
      <?php
      if (isset($msg)) {
        echo $msg;
      }
      ?>
      <div class="resetPwInputContainer">
        <input class="input" type="password" placeholder="New Password" name="pass" required />
        <input class="input" type="password" placeholder="Confirm New Password" name="confirm-pass" required />
        <p>Password must be at least be 6 characters long, with 1 number, 1 symbol and 1 capital letter</p>
        <button type="submit" class="btn" name="btn-reset-pass"><span class="loginButtonText">Reset your Password</span></button>
      </div>

    </form>

  </div>
</body>

</html>