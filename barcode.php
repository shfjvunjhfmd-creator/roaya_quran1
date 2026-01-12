<?php
header("Content-Type: image/png");

$text = $_GET['text'] ?? '0000';

$width = 300;
$height = 80;

$image = imagecreate($width, $height);
$white = imagecolorallocate($image, 255, 255, 255);
$black = imagecolorallocate($image, 0, 0, 0);

imagestring($image, 5, 10, 30, $text, $black);

imagepng($image);
imagedestroy($image);
