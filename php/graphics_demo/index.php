<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 transitional//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>Directory Navigation</title>
<meta name="description" content="" />
<meta name="keywords" content="" />
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<meta name="author" content="Peter Lavin" />
<meta http-equiv="Content-Language" content="EN" />
<meta name="copyright" content="copyright softcoded.com" />
<meta name="robots" content="follow, index" />
<meta name="abstract" content="" />
<!--local style elements here-->
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
<?php
/* final code for use with directoritems class after navigator
is created */
require 'PageNavigator.php';
require 'DirectoryItems.php';
//max per page
define("PERPAGE", 5);
//name of first parameter in query string
define("OFFSET", "offset");
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
$filearray = $di->getFileArraySlice($totaloffset, PERPAGE);
echo "<div style=\"text-align:center;\">";
echo "Click the file name to view full-sized version.<br />";
$path = "";
//specify size of thumbnail
$size = 100;	
//use SEPARATOR
foreach ($filearray as $key => $value){
	$path = "{$di->getDirectoryName()}/$key";	
	/*errors in getthumb or in class will result in broken links
	- error will not display*/
	echo "<img src=\"getthumb.php?path=$path&amp;size=$size\" ".
	  "style=\"border:1px solid black;margin-top:20px;\" ".
	  "alt=\"$value\" /><br />\n";
	echo "<a href=\"$path\" target=\"_blank\" >";
	echo "Title: $value</a> <br />\n";
}
echo "</div><br />";
$pagename = basename($_SERVER["PHP_SELF"]);
$totalcount = $di->getCount();
$numpages = ceil($totalcount/PERPAGE);
//create if needed
if($numpages > 1){
  //create navigator
  $nav = new PageNavigator($pagename, $totalcount, PERPAGE, $totaloffset);
	//is the default but make explicit
	$nav->setFirstParamName(OFFSET);
  echo $nav->getNavigator();
}
?>
</body>
</html>
