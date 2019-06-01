<?php
  require_once 'class.user.php';
  session_start();
  $user = new USER();
  $userID = $_SESSION['userID'];
  $pictureID = htmlspecialchars($_POST['targetID2']);
  $comment = htmlspecialchars($_POST['comment']);

  $user->recordComments($pictureID, $userID, $comment);

  $message= "
       Hi !

       You've got a new comment : \" ".$comment." \"

       Have a good day!
       ";
  $subject = "New comment";

  echo $pictureID;
  $stmt = $user->runQuery("SELECT userID FROM pictures WHERE pictureID=:pictureID LIMIT 1");
  $stmt->bindparam(":pictureID", $pictureID);
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  $userID = $row["userID"];

  $stmt2 = $user->runQuery("SELECT userEmail FROM tbl_users WHERE userID=:userID LIMIT 1");
  $stmt2->bindparam(":userID", $userID);
  $stmt2->execute();
  $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
  $email = $row2["userEmail"];
  var_dump($email)
;  $user->send_mail($email,$message,$subject);
?>
