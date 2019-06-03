<?php
require_once 'class.user.php';
session_start();
$user = new USER();

if (!$user->is_logged_in()) {
  $user->redirect('index.php');
}

if (!empty($_FILES["userfile"]) && !$_FILES['userfile']['error'] && is_uploaded_file($_FILES['userfile']['tmp_name'])) {
  list($largeur, $hauteur, $type, $attr) = getimagesize($_FILES['userfile']['tmp_name']);
  if(filesize($_FILES['userfile']['tmp_name'])<1048576){
    $filepath = basename($_FILES['userfile']['name']);
    $extension = strtolower(pathinfo($filepath)['extension']);
    $authorizedExtensions = array("jpg", "png", "jpeg");
    $authorizedTypes = array("image/jpeg", "image/png");
    if (in_array($extension, $authorizedExtensions) && in_array(strtolower($_FILES["userfile"]["type"]), $authorizedTypes) && ($type === 2 || $type === 3)) {
      $uploadedFilePath = "./upload/gerard.png";
      imagepng(imagecreatefromstring(file_get_contents(($_FILES['userfile']['tmp_name']))), $uploadedFilePath);
      echo "<img width='100%' height='auto' src='$uploadedFilePath' />";
    } else {
      echo "Bad format, only .jpg .jpeg and .png accepted";
    }
  } else {
    echo "Only images under 1Mb are accepted for upload";
  }
} else {
  if ($_FILES['userfile']['error'] ==  1 || $_FILES['userfile']['error'] == 2)
    echo 'Only images under 1Mb are accepted for upload';
  else{
    echo "File couldn't be uploaded";
  }
}
?>
