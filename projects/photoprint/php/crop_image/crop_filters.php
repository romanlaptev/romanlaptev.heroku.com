<?php 
//https://www.php.net/manual/ru/refs.utilspec.image.php
//https://www.php.net/manual/ru/intro.image.php
//https://www.php.net/manual/ru/function.imagecreate.php
//http://www.php.su/imagecreate

//https://www.php.net/manual/ru/function.gd-info.php

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

//https://www.php.net/manual/ru/function.get-loaded-extensions.php
$loadedExt = get_loaded_extensions();
if ( !in_array("gd", $loadedExt ) ) {
	$msg = "<p>-- error, GD graphical module  is not in the list loaded extensions...</p>";
	echo $msg;
echo "loaded_extensions:<pre>";
print_r( $loadedExt );
echo "</pre>";
	exit;
}

if (!extension_loaded("gd") ) {
	
	if ( function_exists("dl") ){
		
		//https://www.php.net/manual/ru/function.dl.php
		if ( dl("gd.so") ) {//try dynamic load module
			//$msg = "<p>-- success, graphical module GD available...</p>";
			//echo $msg;
			runApp();
		} else {
			$msg = "<p>-- error, graphical module GD not loaded...</p>";
			$msg .= "<p>-- failed load gd.so..</p>";
			echo $msg;
			exit;
		}
		
	} else {
		$msg = "<p>-- error, graphical module GD not loaded...</p>";
		$msg .= "<p>-- failed load gd.so, not function dl()</p>";
		echo $msg;
		exit;
	}
	
} else {
	//$msg = "<p>-- success, graphical module GD available...</p>";
	//echo $msg;
	runApp();
}


function runApp(){
/*
//https://www.php.net/manual/ru/function.get-extension-funcs.php
echo "extension_funcs in module GD:<pre>";
print_r(get_extension_funcs("gd"));
echo "</pre>";

	if ( function_exists("gd_info") ){
echo "<pre>";
print_r( gd_info() );
echo "</pre>";
	} else {
$msg = "error, not support function gd_info(),  not  GD Support ...";
echo $msg;
		exit();
	}
*/	

	$src = "../../images/WP-14-1093.jpg";
	$filename = "test.jpg";
	$jpeg_quality = 90;
/*
	$src_image = imagecreatefromjpeg( $src );
	$src_x = 0;
	$src_y = 0;
	$src_w = imagesx($src_image);//1024;
	$src_h = imagesy($src_image);//768;
//echo 	$src_w;
//echo 	$src_h;
//exit;

	$dst_x = 0;
	$dst_y = 0;
	$dst_w = 500;
	$dst_h = 500;
	$dst_image = ImageCreateTrueColor( $dst_w, $dst_h );
	
	//https://www.php.net/manual/ru/function.imagecopyresampled.php
	imagecopyresampled( 
$dst_image, $src_image, 
$dst_x, $dst_y, 
$src_x, $src_y, 
$dst_w, $dst_h, 
$src_w , $src_h
);
*/

	$src_x = 488;
	$src_y = 222;
	$src_image = imagecreatefromjpeg( $src );

	$dst_x = 0;
	$dst_y = 0;
	$dst_w = 100;
	$dst_h = 100;
	$dst_image = imagecreatetruecolor( $dst_w, $dst_h );
	
	imagecopy(
$dst_image, $src_image, 
$dst_x, $dst_y, 
$src_x, $src_y, 
$dst_w, $dst_h 
);

	header ("Content-type: image/jpeg");
	//header("Content-Disposition: attachment; filename=".$filename.'');
	//header('Content-Transfer-Encoding: binary');
 	imagejpeg( $dst_image, null, $jpeg_quality );
	
	imagedestroy($dst_image);
	imagedestroy($src_image);

/*
//(PHP 5 >= 5.5.0, PHP 7)
//https://www.php.net/manual/ru/function.imagecrop.php
	$src_image = imagecreatefromjpeg( $src );
	$crop_width = imagesx($src_image);
	$crop_height = imagesy($src_image);
	$size = min($crop_width, $crop_height);
	
	$crop_image = imagecrop( $src_image, ['x' => 0, 'y' => 0, 'width' => $size, 'height' => $size]);
	if ($crop_image !== FALSE) {
		//imagejpeg( $crop_image, null, $jpeg_quality );
		imagejpeg( $crop_image, $filename, $jpeg_quality );
		imagedestroy($crop_image);
	}
*/
	
}//end runApp()
?>