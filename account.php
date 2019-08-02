<?php
session_start();
require_once 'class.user.php';
$user = new USER();

if (!$user->is_logged_in()) {
  $user->redirect('index.php');
}

$stmt = $user->runQuery("SELECT userName, userEmail FROM tbl_users WHERE userID = :session");
$stmt->bindparam(":session", $_SESSION['userID']);
$select = $stmt->execute();
$row=$stmt->fetch(PDO::FETCH_ASSOC);
$phusername=htmlspecialchars($row['userName']);
$phemail=htmlspecialchars($row['userEmail']);

if (isset($_POST['btn-update'])) {
  $uname = trim($_POST['txtuname']);
  $email = trim($_POST['txtemail']);
  $stmt2 = $user->runQuery("UPDATE tbl_users SET userName = :uname, userEmail = :email WHERE userID = :session");
  $stmt2->bindparam(":uname", $uname);
  $clean_email = trim(strtolower($email));
  $stmt2->bindparam(":email", $clean_email);
  $stmt2->bindparam(":session", $_SESSION['userID']);
  $update = $stmt2->execute();
  if ($update) {
    $_SESSION['success'] = 1;
    Header('Location: '.htmlspecialchars($_SERVER['PHP_SELF']));
  }
}

?>
<!DOCTYPE html>
<html>

<head>
  <link rel="stylesheet" href="index.css" type="text/css">
  <title>My Account</title>
</head>

<body id="login">
  <div class="container">
    <nav>
      <a class='btn' href="home.php">Back to home</a>
      <a class='btn' href="logout.php">Logout</a>
    </nav>
    <h1 class="title">Account settings</h1>

    <form class="form" action="account.php" method="post">
      <input class="input" id="username" type="text" value=<?php if(isset($phusername)) echo $phusername; else echo "username"; ?> name="txtuname" required />
      <input class="input" id="email" type="email" value=<?php if(isset($phemail)) echo $phemail; else echo "email"; ?> name="txtemail" required />
      <div>
        <div id='msg'></div>
        <div class="signup-btn-container">
          <?php if(isset($_SESSION['success'])){echo "Your credentials have been updated";}?>
          <button type="submit" class="btn" name="btn-update">
            Update
          </button>
        </div>
        <div class="alreadyAccount">
          <p>
            You want to change your password?
          </p>
          <div>
            <a href="fpass.php" class="btn">
              Reset password
            </a>
          </div>
        </div>
      </div>
    </form>
  </div>
</body>

</html>