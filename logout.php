<?php
  require_once 'class.user.php';
  session_start();
  $user = new USER();

  if(!$user->is_logged_in())
  {
   $user->redirect('index.php');
  }

  if($user->is_logged_in()!="")
  {
   $user->logout();
   $user->redirect('index.php');
  }
?>
