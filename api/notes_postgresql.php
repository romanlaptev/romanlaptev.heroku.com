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

include("auth_postgresql.php");
//if( $_SERVER["SERVER_NAME"] !== "romanlaptev.herokuapp.com"){
	//$_vars["config"]["dbName"] = "notes";
//}

$_vars["config"]["phpversion"] = phpversion();
$_vars["export"]["filename"] = "notes.xml";
$_vars["uploadPath"] = "upload";

$_vars["config"]["tableName"] = "notes";

$_vars["sql"]["createDB"] = "";
$_vars["sql"]["createTable"] = 'CREATE TABLE IF NOT EXISTS "public"."notes" (
	"id" SERIAL,
	"author" character(20) NOT NULL,
	"title" character(255),
	"text_message" text,
	"client_date" date,
	"server_date" date,
	"ip" character(20),
	CONSTRAINT "notes_pkey" PRIMARY KEY (id)
) WITHOUT OIDS;';

$_vars["sql"]["removeTable"] = 'DROP TABLE \"'.$_vars["config"]["tableName"].'\"';

$_vars["sql"]["insertNote"] = "INSERT INTO notes (\"author\", \"title\", \"text_message\", \"client_date\", \"server_date\", \"ip\") 
VALUES (
'authorName', 
'title', 
'textMessage',
'12-12-12', 
'12-12-12', 
'ip'
);
";

$_vars["sql"]["insertAll"] = "INSERT INTO `".$_vars["config"]["tableName"]."` VALUES {{values}};";
$_vars["sql"]["insertValues"] = "(
NULL, 
'{{authorName}}', 
'{{title}}', 
'{{textMessage}}',
'{{client_date}}', 
'{{server_date}}', 
'{{ip}}'
)";

$_vars["sql"]["updateNote"] = "UPDATE ".$_vars["config"]["tableName"]." SET 
author = '{{authorName}}', 
title = '{{title}}', 
text_message = '{{textMessage}}',
client_date = '{{client_date}}', 
server_date = '{{server_date}}', 
ip = '{{ip}}' WHERE id={{id}}";

$_vars["sql"]["getNotes"] = 'SELECT id, author, title, text_message, client_date, server_date, ip FROM '.$_vars["config"]["tableName"].' ORDER BY "client_date" DESC';
$_vars["sql"]["deleteNote"] = 'DELETE FROM '.$_vars["config"]["tableName"].' WHERE "id"={{id}};';
$_vars["sql"]["clearNotes"] = "TRUNCATE TABLE ".$_vars["config"]["tableName"].";";

$_vars["log"] = array();

	$action = "";
	if( !empty($_REQUEST['action']) ){
		$action = $_REQUEST['action'];
	} else {
		$_vars["log"][] = "{\"error_code\" : \"noaction\", \"message\" : \"error, undefined var 'action'\"}";
	}
	
//========================================= connect to server
	$_vars["link"] = connectDbPDO();
	createTable();
	
	switch ($action){
		case "save_note":
			//saveNote();
		break;
		
		case "get_notes":
			$notes = getNotes();
//echo count($notes);	
//echo "<pre>";	
//print_r($notes);
//echo "</pre>";
			if( count($notes) > 0 ){
					if ( function_exists("json_encode") ){
						//PHP 5 >= 5.2.0
						$json = json_encode($notes);
						
						//restore formatting after json_encode
						$json = str_replace("&amp;gt;", "&gt;", $json);
						$json = str_replace("&amp;lt;", "&lt;", $json);
						$json = str_replace("&amp;quot;", "&quot;", $json);

						//$error = json_last_error();		
						echo $json;
					} else {
		//https://www.abeautifulsite.net/using-json-encode-and-json-decode-in-php4
		//http://www.epigroove.com/blog/how-to-use-json-in-php-4-or-php-51x
		//https://gist.github.com/jorgeatorres/1239453
//echo "error, not support function json_encode(). incorrect PHP version - ".$_vars["config"]["phpversion"].", need PHP >= 5.2.0";
$msg = "error, not support function json_encode(). incorrect PHP version - ".$_vars["config"]["phpversion"].", need PHP >= 5.2.0";
$_vars["log"][] = "{\"error_code\" : \"notSupportJSON\", \"message\" : \""+$msg+"\"}";
					}
			}

		break;

		case "delete_note":
			// if( !empty($_REQUEST['id']) ){
				// $id = $_REQUEST["id"];
				// deleteNote($id);
			// }
		break;

		case "edit_note":
			//updateNote();
		break;
		
		case "clear_notes":
			//clearNotes();
		break;
		
		case "remove_table":
			//removeTable();
		break;
		
		case "export_notes":
			//exportTable( $_vars["export"]["filename"] );
		break;
		
		case "upload":
			//uploadFile();
		break;
		
		case "import_notes":
			// $foldername = $_vars["uploadPath"];
			// chdir("../");
			// $fullPath = getcwd() . "/".$foldername;
			// importTable( $fullPath."/". $_vars["export"]["filename"]);
		break;
		
	}//end switch
	
	unset ($_vars["link"]);
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


//======================================== create table (CREATE TABLE IF NOT EXISTS)
function createTable(){
	global $_vars;
	
	$tableName = $_vars["config"]["tableName"];
	$query = $_vars["sql"]["createTable"];
	$msg_success = "$tableName created succesfully, ". "SQL: " . $query;
	
	$connection = $_vars["link"];
	try{
		$connection->query( $query );
//echo $msg_success;
	} catch( PDOException $exception ){
		print_r($connection->errorInfo(), true);
		echo $exception->getMessage();
		exit();
	}
	
}//end createTable()


function getNotes(){
	global $_vars;
	
	$messages = array();
	$query = $_vars["sql"]["getNotes"];
	
	$connection = $_vars["link"];
	$result  = $connection->query( $query ) or die( print_r($connection->errorInfo(), true) );
	$messages  = $result->fetchAll( PDO::FETCH_OBJ );
// echo count($messages);	
// echo "<pre>";	
// print_r($messages);
// echo "</pre>";	
	return $messages;
		
}//end getNotes()	

?>