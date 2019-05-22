<?php
require_once 'class.user.php';
$user = new USER();

if (empty($_GET['id']) && empty($_GET['code'])) {
  $user->redirect('index.php');
}
if (isset($_GET['id']) && isset($_GET['code'])) {
  $id = base64_decode($_GET['id']);
  $code = $_GET['code'];
  $statusY = "Y";
  $statusN = "N";
  $stmt = $user->runQuery("SELECT * FROM tbl_users WHERE userID=:u_id AND tokenCode=:code LIMIT 1");

  $stmt->bindparam(":u_id", $id);
  $stmt->bindparam(":code", $code);
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($row) {
    if ($row['userStatus'] == $statusN) {
      $stmt = $user->runQuery("UPDATE tbl_users SET userStatus=:status WHERE userID=:uID");
      $stmt->bindparam(":status", $statusY);
      $stmt->bindparam(":uID", $id);
      $stmt->execute();

      $msg = "
               <div class='alert alert-success'>
         <button class='close' data-dismiss='alert'>&times;</button>
         <strong>WoW !</strong>  Your Account is now activated : <a href='index.php'>Login here</a>
            </div>
            ";
    } else {
      $msg = "
              <div class='alert alert-error'>
                <button class='close' data-dismiss='alert'>&times;</button>
                <div class='accountActivatedText'>Your account is already activated : <a href='index.php'>Login here</a></div>
              </div>
            ";
    }
  } else {
    $msg = "
         <div class='alert alert-error'>
      <button class='close' data-dismiss='alert'>&times;</button>
      <strong>Sorry !</strong>  No Account Found : <a href='signup.php'>Signup here</a>
      </div>
      ";
  }
}

?>
<!DOCTYPE html>
<html>

<head>
  <title>Confirm Registrationnnnn</title>
  <link rel="stylesheet" href="style.css" type="text/css">
  <link rel="stylesheet" href="homeStyle.css" type="text/css">
</head>
<nav>
  <a href="logout.php">Logout</a>
</nav>

<body class="logoutDisplay" id="login">
  <div class="container">
    <?php if (isset($msg)) {
      echo $msg;
    } ?>
  </div>
</body>

</html>