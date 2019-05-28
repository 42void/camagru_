<?php
  require_once 'class.user.php';
  session_start();
  $user = new USER();
  $userID = $_SESSION['userSession'];
  $pictureID = $_POST['targetID'];

  $user->recordLikes($pictureID, $userID);
 ?>
