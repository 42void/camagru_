<?php
if((!empty($_FILES["userfile"])) && ($_FILES['userfile']['error'] == 0)) {
  if($_FILES["userfile"]["size"] < 1048576){

    $uploaddir = './upload/';
    $filename = basename($_FILES['userfile']['name']);
    $ext = substr($filename, strrpos($filename, '.') + 1);
    if (($ext == "jpg") || ($ext == "png") || ($ext == "jpeg") && (($_FILES["userfile"]["type"] == "image/jpeg") || (($_FILES["userfile"]["type"] == "image/png")))){

      $uploadfile = $uploaddir . "gerard.png";
      imagepng(imagecreatefromstring(file_get_contents(($_FILES['userfile']['tmp_name']))), $uploadfile);
      echo "<img width='100%' height='auto' src='$uploadfile' />";

    }else{
      echo "Bad format";
    }
  }else {
     echo "Only images under 1Gb are accepted for upload";
  }
}else{
  if($_FILES['userfile']['error'] ==  1 || $_FILES['userfile']['error'] == 2)
    echo 'Error: only images under 1Gb are accepted for upload';
}
?>
