<?php
session_start();
require_once 'class.user.php';
$reg_user = new USER();
// echo "<script>console.log($reg_user);</script>";
print("Bonjour ");
echo '<pre>';
print($_SESSION['userSession']);
echo '</pre>';
// echo '<pre>' . print_r($_SESSION, TRUE) . '</pre>';


// if ($reg_user->is_logged_in() != "") {
//   $reg_user->redirect('home.php');
// }

if (isset($_POST['btn-update'])) {
$uname = trim($_POST['txtuname']);
$email = trim($_POST['txtemail']);
}

$pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
$stmt = $pdo->prepare("UPDATE tbl_users SET userName = :uname, userEmail = :email WHERE userID = :session");
$stmt->bindparam(":uname", $uname);
$stmt->bindparam(":email", $email);
$stmt->bindparam(":session", $_SESSION['userSession']);
$stmt->execute();

?>
<!DOCTYPE html>
<html>

<head>
  <link rel="stylesheet" href="index.css" type="text/css">
  <title>My Account</title>
</head>

<body id="login">
  <div class="container">
    <?php if (isset($msg)) {
      echo $msg;
    }

    if (isset($_GET['passError'])) {
      ?>
      <div class='alert alert-success'>
        <strong><?php echo $_GET['passError'] ?></strong>
      </div>
    <?php
  }
  ?>

    <nav>
        <a class='btn' href="home.php">Back to home</a>
        <a class='btn' href="logout.php">Logout</a>
    </nav>
    <h1 class="title">Account settings</h1>

    <form class="form" method="post">
      <input class="input" type="text" placeholder="Username" name="txtuname" required />
      <input class="input" type="email" placeholder="Email address" name="txtemail" required />
      <div>
        <div class="signup-btn-container">
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