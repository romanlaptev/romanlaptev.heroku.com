<!DOCTYPE html>
<html>
<head>
<title>Graphics Demo</title>
	<meta charset="utf-8">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<meta http-equiv="X-UA-Compatible" content="IE=Edge">
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">	

<style type="text/css">
		div.navigator{
			font-size:smaller;
			padding:5px;
			text-align:center;
		}
		div.totalpagesdisplay{
			padding-top:15px;
			font-size:smaller;
			text-align:center;
			font-style:italic;
		}
		.navigator a, span.inactive{
			padding : 0px 5px 2px 5px;
			margin-left:0px;
			border-top:1px solid #999999;
			border-left:1px solid #999999;
			border-right:1px solid #000000;
			border-bottom:1px solid #000000;
		}
		.navigator a:link, .navigator a:visited,
		    .navigator a:hover,.navigator a:active{
			color: #3300CC;
			background-color: #FAEBF7;
			text-decoration: none;
		}
		span.inactive{
			background-color :#EEEEEE;
			font-style:italic;
		}
</style>
</head>
<body>
	<div class='container'>
		<h3>Graphics demo, build thumbnails (use PHP GD)</h3>
<?php
error_reporting(E_ALL|E_STRICT);
ini_set('display_errors', 1);

require 'PageNavigator.php';
require 'DirectoryItems.php';


define("PERPAGE", 5);//max per page
define("OFFSET", "offset");//name of first parameter in query string

/*get query string - name should be same as first parameter name
passed to the page navigator class*/
$offset=@$_GET[OFFSET];

//check variable
if (!isset($offset)){
	$totaloffset=0;
}else{
	//clean variable here
	//then calc record offset
	$totaloffset = $offset * PERPAGE;
}
$di =& new DirectoryItems("graphics");
$di->imagesOnly();
$di->naturalCaseInsensitiveOrder();

//get portion of array
$filearray = $di->getFileArraySlice( $totaloffset, PERPAGE );
$path = "";
$size = 100;	//specify size of thumbnail

//use SEPARATOR
echo "<div class='row'>";
foreach ($filearray as $key => $value){
	$path = "{$di->getDirectoryName()}/$key";	
	echo "<div class='thumbnail col-xs-2 col-sm-2 col-md-2 col-lg-2'>";
	echo "<a href='$path' target='_blank'>";
	echo "<img src='getthumb.php?path=$path&amp;size=$size' alt='$value' /></a>";
	echo "<div class='img-caption text-center'>Title: $value</div>\n";
	echo "</div>";
}//next
echo "</div>";

$pagename = basename($_SERVER["PHP_SELF"]);
$totalcount = $di->getCount();
$numpages = ceil($totalcount/PERPAGE);

echo "<div class='row'>";
//create if needed
if($numpages > 1){
  //create navigator
  $nav = new PageNavigator($pagename, $totalcount, PERPAGE, $totaloffset);
	//is the default but make explicit
	$nav->setFirstParamName(OFFSET);
  echo $nav->getNavigator();
}
echo "</div>";
?>
	</div><!-- end container -->
</body>
</html>
