<?php
  require_once 'class.user.php';
  session_start();
  $user = new USER();
  $userID = $_SESSION['userSession'];
  $pictureID = $_POST['target3ID'];

  $user->delete($pictureID, $userID);
?>
