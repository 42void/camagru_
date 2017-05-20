<?php
  session_start();
  require_once './config/database.php';
  require_once 'class.user.php';
  $user = new USER();
  $userID = $_SESSION['userSession'];
  $pictureID = $_POST['target3ID'];

  $user->delete($pictureID, $userID);
?>
