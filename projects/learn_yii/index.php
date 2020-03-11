<?php
error_reporting(E_ALL|E_STRICT);
ini_set('display_errors', 1);

//---------------------------
$moduleName = "sqlite3";
$loadedExt = get_loaded_extensions();
if ( !in_array( $moduleName, $loadedExt ) ) {
	$msg = "<p>-- error, $moduleName module  is not in the list of loaded extensions...</p>";
	echo $msg;
echo "loaded_extensions:<pre>";
print_r( $loadedExt );
echo "</pre>";
	//exit;
}
//echo extension_loaded($moduleName);
//exit;
if (!extension_loaded($moduleName) ) {
	
	if ( function_exists("dl") ){
		
		//https://www.php.net/manual/ru/function.dl.php
		if ( dl( $moduleName.".so" ) ) {//try dynamic load module
			runApp();
		} else {
			$msg = "<p>-- error, module $moduleName not loaded...</p>";
			$msg .= "<p>-- failed load $moduleName.so..</p>";
			echo $msg;
			exit;
		}
		
	} else {
		$msg = "<p>-- error, module $moduleName not loaded...</p>";
		$msg .= "<p>-- failed load $moduleName.so..</p>";
		echo $msg;
		exit;
	}
	
} else {
	//$msg = "<p>-- success, $moduleName available...</p>";
	//echo $msg;
	runApp();
}

//---------------------------
function runApp(){
	// change the following paths if necessary
	//$yii=dirname(__FILE__).'/../../var/www/php/yii/framework/yii.php';
	$yii='../../php/frameworks/yii/framework/yii.php';

	$config=dirname(__FILE__).'/protected/config/main.php';

	// remove the following lines when in production mode
	defined('YII_DEBUG') or define('YII_DEBUG',true);
	// specify how many levels of call stack should be shown in each log message
	defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

	require_once($yii);
	Yii::createWebApplication($config)->run();

//echo "Yii::app:<pre>";
//print_r( Yii::app() );
//echo "</pre>";

}//end runApp()


