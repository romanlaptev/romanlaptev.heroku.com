<?php 
//error_reporting(E_ALL ^ E_DEPRECATED);
//error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
error_reporting(E_ALL|E_STRICT);
ini_set('display_errors', 1);

//echo "<pre>";
// print_r ($_SERVER);
// print_r ($_REQUEST);
// //print_r($_FILES);
//print_r( gd_info() );
//echo "</pre>";

try{
echo "<pre>";
print_r( gd_info() );
echo "</pre>";
} catch( Exception $e ) {
echo "error..";
echo $e->getMessage();
	exit();
}

//https://www.php.net/manual/ru/refs.utilspec.image.php
//https://www.php.net/manual/ru/intro.image.php
//https://www.php.net/manual/ru/function.imagecreate.php
//http://www.php.su/imagecreate

//https://www.php.net/manual/ru/function.gd-info.php
/*
header ("Content-type: image/png");

$im = @imagecreate (200, 200)or die ("Cannot Initialize new GD image stream");
$im2 = @imagecreate (200, 200)or die ("Cannot Initialize new GD image stream");

$background_color = imagecolorallocate ($im, 255, 255, 255);
$text_color = imagecolorallocate ($im, 233, 14, 91);
$color2 = imagecolorallocate ($im, 233, 14, 91);

imagestring ($im, 1, 5, 5,"Circle", $text_color);

$x0 = 100;
$y0 = 100;
$px = 60;
$py = 60;
for ($t = 0; $t < 360; $t++)
{
	$x = cos($t) * $px;
	$y = sin($t) * $py;
	$x = $x + $x0;
	$y = $y + $y0;
	imagesetpixel ($im, $x, $y, $color2);
}//next

imagepng ($im);
*/

/*
header("Content-Type: image/png");
$im = @imagecreate(110, 20) or die("Cannot Initialize new GD image stream");
$background_color = imagecolorallocate($im, 255, 255, 255);
$text_color = imagecolorallocate($im, 233, 14, 91);
imagestring($im, 1, 5, 5,  "A Simple Text String", $text_color);
imagepng($im);
//imagedestroy($im);
*/
?>