<?php
session_start();
$userID = $_SESSION['userSession'];

require_once './config/database.php';
require_once 'class.user.php';

$reg_user = new USER();

$cat = $_POST['cat'];

$src = imagecreatefrompng("./stackable_pics/$cat.png");
$dest= imagecreatefrompng("./uploads/gerard.png");

imagealphablending($src, false);
imagesavealpha($src, true);

$src_width = imagesx($src);
$src_height = imagesy($src);
$dest_width = imagesx($dest);
$dest_height = imagesy($dest);

$dest_x = $dest_width - $src_width;
$dest_y = $dest_height - $src_height;



if ($dest_width - 235 > 0 && $dest_height - 200 > 0){
  $dest_width = $dest_width-235;
  $dest_height = $dest_height-200;
}

imagecopyresized($dest, $src, 10, 10, 0, 0, $dest_width , $dest_height , $src_width, $src_height);

$ret = "./results/".uniqid().".png";
imagepng($dest, $ret);
echo $ret;

$reg_user->recordPicture($ret, $userID);


?>
