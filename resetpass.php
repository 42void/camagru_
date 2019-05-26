<?php
require_once 'class.user.php';
$user = new USER();

if(empty($_GET['id']) && empty($_GET['code'])){
   $user->redirect('index.php');
}

if(isset($_GET['id']) && isset($_GET['code'])){
   $id = base64_decode($_GET['id']);
   $code = $_GET['code'];
   $stmt = $user->runQuery("SELECT * FROM tbl_users WHERE userID=:uid AND tokenCode=:token");
   $stmt->bindparam(":uid", $id);
   $stmt->bindparam(":token", $code);
   $stmt->execute();

   $row = $stmt->fetch(PDO::FETCH_ASSOC);
   if($row){
    if(isset($_POST['btn-reset-pass'])){
     $pass = $_POST['pass'];
     $cpass = $_POST['confirm-pass'];



     if( strlen($pass) < 6 ){
     $msg = "<div class='alert alert-block'>
       <button class='close' data-dismiss='alert'>&times;</button>
       <strong>Sorry!</strong>  Yous password must be at least 6 characters long!
       </div>";
     }
     else if( strlen($pass) > 20 ){
     $msg = "<div class='alert alert-block'>
       <button class='close' data-dismiss='alert'>&times;</button>
       <strong>Sorry!</strong>  Your password must be less than 20 characters long!
       </div>";
     }
     else if( !preg_match("#[0-9]+#", $pass) ){
     $msg = "<div class='alert alert-block'>
       <button class='close' data-dismiss='alert'>&times;</button>
       <strong>Sorry!</strong>  Password must include at least one number!
       </div>";
     }
     else if( !preg_match("#[a-z]+#", $pass) ){
     $msg = "<div class='alert alert-block'>
       <button class='close' data-dismiss='alert'>&times;</button>
       <strong>Sorry!</strong>  Password must include at least one letter!
       </div>";
     }
     else if( !preg_match("#[A-Z]+#", $pass) ){
     $msg = "<div class='alert alert-block'>
       <button class='close' data-dismiss='alert'>&times;</button>
       <strong>Sorry!</strong>  Password must include at least one capital letter!
       </div>";
     }
     else if( !preg_match("#\W+#", $pass) ){
     $msg = "<div class='alert alert-block'>
       <button class='close' data-dismiss='alert'>&times;</button>
       <strong>Sorry!</strong>  Password must include at least one symbol!
       </div>";
     }
     else if($cpass!==$pass){
      $msg = "<div class='alert alert-block'>
        <button class='close' data-dismiss='alert'>&times;</button>
        <strong>Sorry!</strong>  Password Doesn't match.
        </div>";
     }

     else{
      $stmt = $user->runQuery("UPDATE tbl_users SET userPass=:upass WHERE userID=:uid");
      $stmt->bindparam(":upass", hash('whirlpool', $cpass));
      $stmt->bindparam(":uid", $row['userID']);
      $stmt->execute();



      $msg = "<div class='alert alert-success'>
        <button class='close' data-dismiss='alert'>&times;</button>
        Password Changed. You will be redirected in about 5 secs.
        </div>";
      header("refresh:5;index.php");
     }
    }
   }
   else{
    exit;
   }
}

?>
<!DOCTYPE html>
<html>
  <head>
    <title>Password Reset</title>
    <link rel="stylesheet" href="style.css" type="text/css">
    <link rel="stylesheet" href="home.css" type="text/css">
  </head>
  <nav>
    <a href="home.php">Back to home</a>
    <a href="logout.php">Logout</a>
  </nav>
  <body class="logoutDisplay" id="login">
    <div>
     <div>
   <strong>Hello !</strong>  <?php echo $rows['userName'] ?> You're here to reset your forgotten password
  </div>
        <form  method="post">
        <h3>Password Reset.</h3><hr />
        <?php
        if(isset($msg)){
          echo $msg;
        }
  ?>
        <input class="input" type="password"  placeholder="New Password" name="pass" required />
        <input class="input" type="password"  placeholder="Confirm New Password" name="confirm-pass" required />
      <hr />
        <button type="submit" class="generateNewPaswdButton" name="btn-reset-pass"><span class="loginButtonText">Reset your Password</span></button>

      </form>

    </div>
  </body>
</html>
