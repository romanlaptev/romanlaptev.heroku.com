<?php
// Добавлять в отчет все PHP ошибки
error_reporting(E_ALL|E_STRICT);
ini_set('display_errors', 1);
//echo "<pre>";
//print_r($_SERVER);
//print_r($_REQUEST);
//print_r($_FILES);
//echo "</pre>";


$_vars=array();
$_vars["timer"]["start"] = microtime(true);

$_vars["config"] = require_once("config.php");
require_once "inc/functions.php";

//-------------------------------------------------------------------
$_vars["runType"] = "";
$sapi_type = php_sapi_name();
//if ( $sapi_type == 'apache2handler') {
	$_vars["runType"] = "web";
//}
if ( $sapi_type == "cli" ) { $_vars["runType"] = "console"; }
if ( $sapi_type == "cgi" ) { $_vars["runType"] = "console"; }

if (!empty($_REQUEST['run_type']) )	{
	$_vars["runType"] = $_REQUEST['run_type'];
}

//=================================== WEB run
if ( $_vars["runType"] == "web") {
//echo "<pre>";
//print_r($_SERVER);
//print_r($_REQUEST);
//print_r($_FILES);
//print_r($_vars);
//echo "</pre>";

	$_vars["form"] = "";
	if (empty($_REQUEST['action']))	{
		loadDrupal();
//echo _logWrap( $databases );
//echo _logWrap( $conf );
		
		$_vars["form"] = exportForm();
	} else {
		$action = $_REQUEST['action'];
		switch ($action) {
			case "export":
				foreach( $_REQUEST as $param => $value){
					if( !empty($value) ){
						$_vars["config"]["export"][$param] = $value;
					}
				}//next
//echo _logWrap( $_vars );
//echo _logWrap( $_vars["config"]["export"] );
//echo _logWrap( htmlspecialchars( $_vars["config"]["export"]["xml_template"]) );
//exit();

				loadDrupal();
				$res = exportProcess( $_vars["config"]["export"] );
				if( $res ){
//echo _logWrap( htmlspecialchars( $_vars["xml"]) );
					writeXML($_vars["xml"]);
					//$msg = "end of export...";
					//$_vars["log"][] = array("message" => $msg, "type" => "success");
				} 
				$msg = "export error";
				$_vars["log"][] = array("message" => $msg, "type" => "error");
				
			break;
		}//end switch
	}//end elseif

	echo PageHead();
	echo $_vars["form"];
	echo PageEnd();
}


//==================================== CONSOLE run
if ( $_vars["runType"] == "console") {
//print_r($argv);
//$_SERVER["argv"]

	loadDrupal();
	$res = exportProcess( $_vars["config"]["export"] );
//echo _logWrap($_vars["xml"]);
	if( !$res ){
		$msg = "export error";
		$_vars["log"][] = array("message" => $msg, "type" => "error");
		exit();
	}

	$filePath = $_vars["config"]["export"]["file_path"];
	if ( !file_exists( $filePath ) ){
		writeXML($_vars["xml"]);
	}
	
	if ( file_exists( $filePath ) ){
		$oldfile = $filePath;
		$newfile = $filePath."_";
		if ( !copy($oldfile, $newfile) ) {
$msg = "warning, unable copy $oldfile > $newfile";
$_vars["log"][] = array("message" => $msg, "type" => "success");
		} else {
$msg = "copy $oldfile (old version) > $newfile";
$_vars["log"][] = array("message" => $msg, "type" => "success");
		}
		writeXML($_vars["xml"]);
	}
	
	$msg = "end of export...";
	$_vars["log"][] = array("message" => $msg, "type" => "success");
}


//====================================== RUNTIME
$runtime = round( microtime(true) - $_vars["timer"]["start"], 4);
$msg = "export runtime, sec: ".$runtime;
$_vars["log"][] = array("message" => $msg, "type" => "info");


//====================================== LOG
if ( !empty( $_vars[ "log" ] ) ) {
	for( $n = 0; $n < count( $_vars["log"] ); $n++){
	//for( $n = count( $_vars["log"] ) - 1; $n >= 0; $n--){
		$record = $_vars["log"][$n];
		echo _logWrap( $record["message"], $record["type"] );
	}//next
}

//====================
function exportProcess($params){
	global $_vars;
	
	$p = array(
		"type_export_content" => false
	);
	
	//extend options object $p
	foreach( $params as $key=>$item ){
		$p[ $key ] = $item;
	}//next
//echo _logWrap($p);

	if( !$p["type_export_content"] ){
		$msg = "export error: not define 'type_export_content'...";
		$msg_type = "error";
		$_vars["log"][] = array("message" => $msg, "type" => $msg_type);
		return false;
	}

	//--------------------------- check PHP-module SimpleXML
	$loadedExt = get_loaded_extensions();
	$module_name = "SimpleXML";
	$_vars["support"][$module_name] = check_module( $module_name, $loadedExt);
	if( !$_vars["support"][$module_name] ){
		$_vars["log"][] = array("message" => $loadedExt, "type" => "error");
		return false;
	}

//------------------------------- get content ( node -> content )
	$type_export_content = $p["type_export_content"];
	$sql_query = $_vars["config"]["sql"][$type_export_content];
	
	//prepare sql query: replace parameters
	foreach( $p as $key=>$value ){
		$sql_query = str_replace("{{".$key."}}", $value, $sql_query);
	}//next
//echo _logWrap("SQL:".$sql_query);

	if( empty($sql_query) ){
		return false;
	}
	
	//$sql_query = "SELECT nid, title, created FROM node GROUP BY created;";
	$result = db_query($sql_query);
	foreach ($result as $row) {
		$_vars["dbData"]["content"][] = $row;
	}//next
	
//echo _logWrap( count( $_vars["dbData"]["content"] ) );
//echo _logWrap( $_vars["dbData"]["content"][0] );
//echo _logWrap( $_vars["dbData"]["content"] );
//return false;

//------------------------------- get content links ( book -> content_links )
	//if( $type_export_content == "nodes_all"){
		$sql_query = $_vars["config"]["sql"]["content_links"];
	//}
/*	
	if( $type_export_content == "nodes_book"){
		$sql_query = $_vars["config"]["sql"]["content_links_book"];
		//prepare sql query: replace parameters
		foreach( $p as $key=>$value ){
			$sql_query = str_replace("{{".$key."}}", $value, $sql_query);
		}//next
	}
*/
//echo _logWrap("SQL:".$sql_query);
	
	//if( $type_export_content == "nodes_tag"){	}
	//if( $type_export_content == "nodes_type"){	}

	
	$result = db_query($sql_query);
	foreach ($result as $row) {
		$_vars["dbData"]["content_links"][] = $row;
	}//next
	
	// change content links, add parent_id (parent_id_link == mlid )
	for( $n1 = 0; $n1 < count($_vars["dbData"]["content_links"]); $n1++ ){
		$mlid = $_vars["dbData"]["content_links"][$n1]->parent_id_link;
		
		$parent_id = 0;
		for( $n2 = 0; $n2 < count($_vars["dbData"]["content_links"]); $n2++ ){
			$record = $_vars["dbData"]["content_links"][$n2];
			if( $record->mlid == $mlid ){
				$parent_id = $record->content_id;
			}
		}//next
		$_vars["dbData"]["content_links"][$n1]->parent_id = $parent_id;
		
	}//next
	
//echo _logWrap( $_vars["dbData"]["content_links"] );
//return false;

	$_vars["xml"] = formXML( $_vars["dbData"] );
	if ( !empty($_vars["xml"]) ) {
		return true;
	}

	return false;
}//end exportProcess()


function formXML( $xmlData ){
	global $_vars;
	global $databases;//Drupal db config
	
	//$xml_tpl = $_vars["config"]["export"]["xml_template"];
//echo _logWrap( htmlspecialchars($xml_tpl) );

	//$xmlObj = simplexml_load_string( $xml_tpl );
	//$xmlSchema = $xmlObj->schema;
//echo _logWrap( $xmlSchema );
//return false;

	$xml = $_vars["config"]["export"]["xml_template"];

//------------------ form content node	
	$xml_content = "";
	if( !empty( $xmlData["content"] ) ){
		$xml_content = $_vars["config"]["export"]["tplContent"];
		$nodeList = "";

		for( $n1 = 0; $n1 < count( $xmlData["content"] ); $n1++)	{
			$record = $xmlData["content"][$n1];
			$node = $_vars["config"]["export"]["tplContentNode"];
			$content_type="";
			foreach( $record as $field=>$value){
//----------- filter
if( $field == "body_value"){
	if( !empty($value) ){
		$value = str_replace("&", "&amp;", $value);
		$value = str_replace("<", "&lt;", $value);
		$value = str_replace(">", "&gt;", $value);
	}
}
//-----------				
if( $field == "type"){
	$content_type = "type='$value'";
	continue;
}
				$node = str_replace("{{".$field."}}", $value, $node);
			}//next
			
			$node = str_replace("{{type}}", $content_type, $node);
			
			$nodeList .= "\n".$node;
		}//next

		$xml_content = str_replace("{{nodelist}}", $nodeList."\n\t\t", $xml_content);
		//$xml_content = str_replace("{{type}}", $_vars["config"]["export"]["content_type"], $xml_content);
		$xml_content = "\t\t".$xml_content."\t\t";
		
		//$_vars["export"]["num_content_items"] = $n1;
	}
	
	$xml = str_replace("{{content}}", $xml_content, $xml);

//------------------ form content links	
	$xml_content = "";
	if( !empty( $xmlData["content_links"] ) ){
		$xml_content = $_vars["config"]["export"]["tplContentLinks"];
		$nodeList = "";
		for( $n1 = 0; $n1 < count( $xmlData["content_links"] ); $n1++)	{
			$record = $xmlData["content_links"][$n1];
			$node = $_vars["config"]["export"]["tplContentLink"];
			foreach( $record as $field=>$value){
				$node = str_replace("{{".$field."}}", $value, $node);
			}//next
			$nodeList .= "\n".$node;
		}//next

		$xml_content = str_replace("{{nodelist}}", $nodeList."\n\t\t", $xml_content);
		$xml_content = "\t\t".$xml_content."\t\t";
	}
	$xml = str_replace("{{content_links}}", $xml_content, $xml);

	
//echo "<pre>";
//echo htmlspecialchars($xml);
//echo "</pre>";
//echo _logWrap( $databases );
	$db_name = $databases["default"]["default"]["database"];
	$db_type = $databases["default"]["default"]["driver"];
	$export_date = date('d-M-Y H:i:s');
	$export_time = time();
	
	$xml = str_replace( "{{dbName}}", $db_name, $xml );
	$xml = str_replace( "{{dbType}}", $db_type, $xml );
	$xml= str_replace( "{{exportDate}}", $export_date, $xml );
	$xml = str_replace( "{{exportTime}}", $export_time, $xml );

	return $xml;
}//end formXML()


function writeXML($xml){
	global $_vars;

	$filename = $_vars["config"]["export"]["filename"];
	$filePath = $_vars["config"]["export"]["file_path"];

	if( !empty($xml) ){
		if( $_vars["runType"] == "web") {
			header('Content-Type: application/xhtml+xml');
			header('Content-Disposition: attachment; filename='.$filename.'');
			header('Content-Transfer-Encoding: binary');
			//header('Content-Length: '.strlen($xml));
			echo $xml;
			exit();
		}

		if ( $_vars["runType"] == "console") {
			$num_bytes = file_put_contents ( $filePath, $xml);
			if ($num_bytes > 0){
$msg = "Write ".$num_bytes." bytes  in ".$filePath;
$_vars["log"][] = array("message" => $msg, "type" => "success");
			} else {
//_log( getcwd() );
$msg = "Write error in ".$filePath;
$_vars["log"][] = array("message" => $msg, "type" => "error");
			}
		}

	}

}//end writeXML()

?>
