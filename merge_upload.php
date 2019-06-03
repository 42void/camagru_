<?php
require_once 'class.user.php';
session_start();
$userID = $_SESSION['userID'];
$user = new USER();

$cat = $_POST['cat'];

$src = imagecreatefrompng("./cat_filters/$cat.png");
$dest= imagecreatefrompng("./upload/gerard.png");

imagealphablending($src, false);
imagesavealpha($src, true);

list($cat_width, $cat_height) = getimagesize("./cat_filters/$cat.png");
list($uploaded_width, $uploaded_height) = getimagesize("./upload/gerard.png");

$percent = 0.4;

imagecopyresized($dest, $src, 10, 10, 0, 0, $uploaded_width * $percent , $uploaded_width * $percent, $cat_width, $cat_height);

$ret = "./results/".uniqid().".png";
imagepng($dest, $ret);
echo $ret;

$user->recordPicture($ret, $userID);


?>
