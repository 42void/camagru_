<?php
session_start();
$userID = $_SESSION['userSession'];

require_once './config/database.php';
require_once 'class.user.php';

$reg_user = new USER();

$dest = $_POST['image'];
$cat = $_POST['cat'];

$dest = str_replace('data:image/png;base64,', '', $dest);
$dest = str_replace(' ', '+', $dest);
$data_background = base64_decode($dest);

$src = imagecreatefrompng("./stackable_pics/$cat.png");
$dest=imagecreatefromstring($data_background);

imagealphablending($src, false);
imagesavealpha($src, true);

$src_width = imagesx($src);
$src_height = imagesy($src);
$dest_width = imagesx($dest);
$dest_height = imagesy($dest);

$dest_x = $dest_width - $src_width;
$dest_y =  $dest_height - $src_height;

imagecopyresized($dest, $src, 10, 10, 0, 0, $dest_width - 350, $dest_height - 200, $src_width, $src_height);

$ret = "./results/".uniqid().".png";
imagepng($dest, $ret);
echo $ret;

$reg_user->recordPicture($ret, $userID);

?>
