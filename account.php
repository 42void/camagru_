<?php
session_start();
require_once 'class.user.php';
// echo "<script>console.log($reg_user);</script>";
// print("Bonjour");
// echo '<pre>';
// print($_SESSION['userSession']);
// echo '</pre>';
// echo '<pre>' . print_r($_SESSION, TRUE) . '</pre>';


// if ($reg_user->is_logged_in() != "") {
//   $reg_user->redirect('home.php');
// }

if (isset($_POST['btn-update'])) {
    $uname = trim($_POST['txtuname']);
    $email = trim($_POST['txtemail']);
    $pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $stmt = $pdo->prepare("UPDATE tbl_users SET userName = :uname, userEmail = :email WHERE userID = :session");
    $stmt->bindparam(":uname", $uname);
    $stmt->bindparam(":email", $email);
    $stmt->bindparam(":session", $_SESSION['userSession']);
    $ret = $stmt->execute();
}
?>
<!DOCTYPE html>
<html>

<head>
  <link rel="stylesheet" href="index.css" type="text/css">
  <title>My Account</title>
</head>

<body id="login">

    <script>

        function listener(event) {
            console.log("here")
            // event.preventDefault(); // prevent the browsers default behavior
            // console.log(event.type); // log the generated event

            console.log(event.type); // log the generated event
            // if(event.type !== 'invalid'){
            //     document.getElementById('msg').innerText = "Your credentials have been updated"
            // }
        }

        function update(){
            console.log("1",document.getElementById('email').addEventListener('invalid', listener))
            document.getElementById('username').addEventListener('invalid', listener)            
            // input.('invalid', listener);
            //  var saveButtonObject = event.target;
            //  var targetID = event.target.id;

            //  var postID = "targetID="+targetID;
            //  var ajx = new XMLHttpRequest();
            //  ajx.onreadystatechange = function () {
            //      if (ajx.readyState == 4 && ajx.status == 200) {
            //         location.reload();
            //      }
            //  };
            //  ajx.open("POST", "like.php", true);
            //  ajx.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            //  ajx.send(postID);
        }
    </script>
  <div class="container">
    

    <nav>
        <a class='btn' href="home.php">Back to home</a>
        <a class='btn' href="logout.php">Logout</a>
    </nav>
    <h1 class="title">Account settings</h1>

    <form class="form" method="post">
      <input class="input" id="username" type="text" placeholder="Username" name="txtuname" required />
      <input class="input" id="email" type="email" placeholder="Email address" name="txtemail" required />
      <div>
          <div id='msg'></div>
        <div class="signup-btn-container">
          <button type="submit" onclick="update()" class="btn" name="btn-update">
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