<?php
  session_start();
  require_once './config/database.php';
  require_once 'class.user.php';
  $user = new USER();
  $userID = $_SESSION['userSession'];
  $pictureID = $_POST['targetID'];

  $user->recordLikes($pictureID, $userID);
 ?>
