<?php
require_once 'class.user.php';
$user = new USER();

if (empty($_GET['code'])) {
  $user->redirect('index.php');
}
if (isset($_GET['code'])) {
  $code = $_GET['code'];
  $statusY = "Y";
  $statusN = "N";
  $stmt = $user->runQuery("SELECT * FROM tbl_users WHERE tokenCode=:code LIMIT 1");
  $stmt->bindparam(":code", $code);
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($row) {
    if ($row['userStatus'] == $statusN) {
      $stmt = $user->runQuery("UPDATE tbl_users SET userStatus=:status WHERE userID=:uID");
      $stmt->bindparam(":status", $statusY);
      $stmt->bindparam(":uID", $row['userID']);
      $stmt->execute();

      $msg = "
              <div class='alert alert-success'>
                <strong>WoW !</strong>  Your Account is now activated : <a class='btn' href='index.php'>Login here</a>
              </div>
            ";
    } else {
      $msg = "
              <div class='alert alert-error'>
                Your account is already activated : <a class='btn' href='index.php'>Login here</a>
              </div>
            ";
    }
  } else {
    $msg = "
          <div class='alert alert-error'>
            Sorry! No account found : <a class='btn' href='signup.php'>Signup here</a>
          </div>
      ";
  }
}

?>
<!DOCTYPE html>
<html>

<head>
  <title>Registration verification</title>
  <link rel="stylesheet" href="index.css" type="text/css">
  <link rel="stylesheet" href="home.css" type="text/css">
</head>

<body class="logoutDisplay" id="login">
  <div class="container">
    <?php if (isset($msg)) {
      echo $msg;
    } ?>
  </div>
</body>

</html>