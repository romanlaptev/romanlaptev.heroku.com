<?php
//header('Access-Control-Allow-Origin: *');
//error_reporting(E_ALL ^ E_DEPRECATED);
//error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
error_reporting(E_ALL|E_STRICT);
ini_set('display_errors', 1);

//echo "<pre>";
//print_r ($_SERVER);
//print_r ($_REQUEST);
//print_r($_FILES);
//echo "</pre>";

$_vars=array();
// $_vars["config"]["dbHost"] = "localhost";
// $_vars["config"]["dbUser"] = "root";
// $_vars["config"]["dbPassword"] = "master";
// $_vars["config"]["dbName"] = "db1";

//https://romanlaptev.herokuapp.com/
$_vars["config"]["dbHost"] = "ec2-184-73-189-190.compute-1.amazonaws.com";
$_vars["config"]["dbPort"] = "5432";
$_vars["config"]["dbUser"] = "aejvwysqgsboeb";
$_vars["config"]["dbPassword"] = "55b5c22131c1d612574edb5dea0b63433293d828ab1f77196f52eb0a849a577c";
$_vars["config"]["dbName"] = "d7c534mf7866o2";


$_vars["config"]["phpversion"] = phpversion();
$_vars["export"]["filename"] = "notes.xml";
$_vars["uploadPath"] = "upload";

$_vars["config"]["tableName"] = "notes";

$_vars["sql"]["createDB"] = "";
$_vars["sql"]["createTable"] = 'CREATE TABLE "public"."notes" (
	"id" integer NOT NULL,
	"author" character(20) NOT NULL,
	"title" character(255),
	"text_message" text,
	"client_date" date,
	"server_date" date,
	"ip" character(20),
	CONSTRAINT "notes_pkey" PRIMARY KEY (id)
) WITHOUT OIDS;';


$_vars["sql"]["removeTable"] = "";
$_vars["sql"]["insertNote"] = "";
$_vars["sql"]["insertAll"] = "";
$_vars["sql"]["insertValues"] = "";

$_vars["sql"]["updateNote"] = "";

$_vars["sql"]["showTables"] = "";
$_vars["sql"]["getNotes"] = "";
$_vars["sql"]["deleteNote"] = "";
$_vars["sql"]["clearNotes"] = "";

$_vars["log"] = array();
//========================================= connect to server
	$_vars["usePDO"] = 1;
	$_vars["link"] = connectDbPDO();
	$connection = $_vars["link"];
	$_vars["dbVersion"] = "";

	$query = "SELECT * FROM PG_SETTINGS WHERE name='server_version';";
	$result  = $connection->query( $query ) or die( $connection->errorInfo()[2] );
	$rows  = $result->fetchAll( PDO::FETCH_ASSOC );
echo "<pre>";	
print_r($rows);
echo "</pre>";	
	$_vars["dbInfo"][]["textMessage"] = "database server version: " . $rows[0]["setting"];
	
	unset ($_vars["link"]);
	
	if ( function_exists("json_encode") ){	//PHP 5 >= 5.2.0
		$json = json_encode($_vars["dbInfo"]);
		echo $json;
	} else {
$msg = "error, not support function json_encode(). incorrect PHP version - ".phpversion().", need PHP >= 5.2.0";
$_vars["log"][] = "{\"error_code\" : \"notSupportJSON\", \"message\" : \""+$msg+"\"}";
	}

	viewLog();
//=========================================== end


//output log in JSON format
function viewLog(){
	global $_vars;
	
	if( count( $_vars["log"] ) > 0){
		 $logStr = "[";
		for( $n = 0; $n < count( $_vars["log"] ); $n++){
			if( $n > 0){
				$logStr .= ", ";
			}
			$logStr .= $_vars["log"][$n];
		}
		$logStr .="]";
		// logStr = logStr.Replace("\\", "&#92;");//replace slash			
		//$logStr = str_replace("`", "&#39", $logStr);//replace apostrophe
		echo $logStr;
	}
}//end viewLog
	

function connectDbPDO(){
	global $_vars;
// echo "<pre>";
// print_r($_vars);
// echo "</pre>";

	$dbHost = $_vars["config"]["dbHost"];
	$dbPort = $_vars["config"]["dbPort"];
	$dbUser = $_vars["config"]["dbUser"];
	$dbPassword = $_vars["config"]["dbPassword"];
	$dbName = $_vars["config"]["dbName"];
	
	$dsn = "pgsql:dbname='{$dbName}'; host='{$dbHost}'; port='{$dbPort}'";
	try{
		$connection = new PDO( $dsn, $dbUser, $dbPassword );
//echo "Connect!";		
		
		return $connection;
	} catch( PDOException $exception ) {
		//echo $exception->getMessage();
		$_vars["log"][] = "{\"error_code\" : \"connectDBerror\", \"message\" : \"" . $exception->getMessage() . "\"}";
		viewLog();
		exit();
	}
}//end connectDbPDO()
?>