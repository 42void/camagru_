<?php
  session_start();
  require_once './config/database.php';
  require_once 'class.user.php';
  $user = new USER();
  $pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
  $userID = $_SESSION['userSession'];
  $pictureID = $_POST['targetID2'];
  $comment = strip_tags($_POST['comment']);

  $user->recordComments($pictureID, $userID, $comment);

  $message= "
       Hi !

       You've got a new comment : \" ".$comment." \"

       Have a good day!
       ";
  $subject = "New comment";

echo $pictureID;
  $stmt = $pdo->prepare("SELECT userID FROM pictures WHERE pictureID=:pictureID LIMIT 1");
  $stmt->bindparam(":pictureID", $pictureID);
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  $userID = $row["userID"];

  $stmt2 = $pdo->prepare("SELECT userEmail FROM tbl_users WHERE userID=:userID LIMIT 1");
  $stmt2->bindparam(":userID", $userID);
  $stmt2->execute();
  $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
  $email = $row2["userEmail"];

  $user->send_mail($email,$message,$subject);
?>
