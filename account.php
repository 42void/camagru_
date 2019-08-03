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
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$phusername = htmlspecialchars($row['userName']);
$phemail = htmlspecialchars($row['userEmail']);
$error_message = false;
$success_message = false;

if (isset($_POST['btn-update'])) {
  $uname = trim($_POST['txtuname']);
  $email = trim($_POST['txtemail']);

  if($phusername == $uname && $phemail == $email) {
    $error_message = "Error: trying to change to the same username and email";
  } else {
    $stmt2 = $user->runQuery("SELECT * FROM tbl_users WHERE userName = :uname AND userName != :usession");
    $stmt2->bindparam(":uname", $uname);
    $stmt2->bindparam(":usession", $phusername);
    $result2 = $stmt2->execute();

    $stmt4 = $user->runQuery("SELECT * FROM tbl_users WHERE userEmail = :email AND userName != :usession");
    $stmt4->bindparam(":email", $email);
    $stmt4->bindparam(":usession", $phusername);
    $result4 = $stmt4->execute();

    if(!$result2 || !$result4) {
      $error_message = "Data base error: could not check if user or email exist already.";
    } else {
      $countusernames = count($stmt2->fetchAll());
      $countemails = count($stmt4->fetchAll());
      if($countusernames > 0) {
        $error_message = "Error: username already exists.";
      } else if($countemails > 0) {
        $error_message = "Error: email already exists.";
      } else {
        $stmt3 = $user->runQuery("UPDATE tbl_users SET userName = :uname, userEmail = :email WHERE userID = :session");
        $stmt3->bindparam(":uname", $uname);
        $clean_email = trim(strtolower($email));
        $stmt3->bindparam(":email", $clean_email);
        $stmt3->bindparam(":session", $_SESSION['userID']);
        $update = $stmt3->execute();
        if ($update) {
          $success_message = "Your credentials have been updated";
          $phusername = $uname;
          $phemail = $email;
        }
      }
    }
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

    <form class="form" method="post">
      <input class="input" id="username" type="text" value=<?php if (isset($phusername)) echo $phusername;
                                                            else echo "username"; ?> name="txtuname" required />
      <input class="input" id="email" type="email" value=<?php if (isset($phemail)) echo $phemail;
                                                          else echo "email"; ?> name="txtemail" required />
      <div>
        <div id='msg'></div>
        <div class="signup-btn-container">
        <?php if ($success_message !== false) {
            echo $success_message;
          } ?>
          <?php if ($error_message !== false) {
            echo $error_message;
          } ?>

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