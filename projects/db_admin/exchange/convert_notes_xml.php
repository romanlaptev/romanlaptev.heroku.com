<?php
require_once "../inc/functions.php";
require_once "../inc/db.php";
require_once "../inc/content.php";

$_vars=array();
$_vars["config"] = require_once("../config.php");

//--------------------------- input arguments
for( $n = 1; $n < count($argv); $n++){
//echo $argv[$n]. ", ".gettype($argv[$n]);
//echo "\n";
	$pos = strpos( $argv[$n], "--xml-file=");
//echo "pos: ".$pos. ", ".gettype($pos);
//echo "\n";
	if( $pos !== false ){
		$arr = explode( "=", $argv[$n]);
		$_vars["argv"]["input_file"] = $arr[1];
	}
}//next
if( !isset($_vars["argv"]["input_file"]) ){
	exit("error, required argument '--xml-file=file.xml' not defined\n");
}

//--------------------------- check PHP-module SimpleXML
$loadedExt = get_loaded_extensions();
$module_name = "SimpleXML";
$_vars["support"][$module_name] = check_module( $module_name, $loadedExt);
if( !$_vars["support"][$module_name] ){
	$_vars["log"][] = array("message" => $loadedExt, "type" => "error");
	exit;
}

//--------------------------- load XML
$_vars["xml"] = simplexml_load_file( $_vars["argv"]["input_file"] );
if ( !$_vars["xml"] ) {
	$msg = "Failed to open ".$_vars["argv"]["input_file"];
	$_vars["log"][] = array("message" => $msg, "type" => "error");
} else {
	$content = new Content();
	_import();
}

//====================================== LOG
if ( !empty( $_vars["log"] ) ) {
	for( $n = 0; $n < count( $_vars["log"] ); $n++){
	//for( $n = count( $_vars["log"] ) - 1; $n >= 0; $n--){
		$record = $_vars["log"][$n];
		echo _logWrap( $record["message"], $record["type"] );
	}//next
}


//======================================
function _import(){
	global $_vars;
	global $content;
	//global $content_links;

	$_vars["db_schema"] = false;//do not check database tables
	$_vars["display_log"] = false;
	
	$db = DB::getInstance();
//echo _logWrap($db);

//------------------------------- test save 
	//$arg = array(
		//"title" => "note_test5",
		//"body_value" => "1234555555555"
	//);
	//$response = $content->save( $arg );
	//if( !$response ){
		//$msg = "error,  could not save content item...";
		//$_vars["log"][] = array("message" => $msg, "type" => "error");
	//} else {
		//$msg = "ok,  save content item...";
		//$_vars["log"][] = array("message" => $msg, "type" => "success");
	//}

//------------------------------- get exists DB nodes
	$_vars["dbNodes"] = $content->getList();
	$msg = "Found " . count( $_vars["dbNodes"] )." database nodes";
	$_vars["log"][] = array("message" => $msg, "type" => "success");

	$_vars["numUpdatedNodes"] = 0;
	$_vars["numCreatedNodes"] = 0;

//------------------------------- import XML nodes
	$_vars["xmlNodes"] = array();
//echo _logWrap( $_vars["xml"]->note[0] );
	$num=0;
	
	//$node = $_vars["xml"]->note[2];
	foreach( $_vars["xml"]->note as $key=>$node ){
//echo _logWrap( $num.". ".$key.": ". (string)$node[@title] );
//echo _logWrap( $node->attributes() );

		$data = array();
		foreach( $node->attributes() as $attr => $attr_value){//get attributes
//$msg = $attr. ": ".$attr_value;
//echo _logWrap( $msg );
			$data[$attr] = (string)$attr_value;
		}//next

		foreach( $node as $item => $value){//get children nodes
//$msg = $item. ": ".$value;
//echo _logWrap( $msg );
			$ch_node_value = (string)$value;
//$msg = $item. ": ".strlen($ch_hode_value);
//echo _logWrap( $msg );
			$data[$item] = $ch_node_value;
		}//next
		
		$data["title"] = trim($data["title"]);
		
		unset( $data["author"] );
		unset( $data["ip"] );
		
		$data["created"] = $data["client_date"];
		unset( $data["client_date"] );
		unset( $data["server_date"] );
		
		$data["body_value"] = $data["text_message"];
		unset( $data["text_message"] );
		
		$data["type_id"] = 2;//note
//echo _logWrap( $data );

		_save_node($data);
		$num++;
		
	}//next
	
	$msg = "Import ".$num." content items...";
	$_vars["log"][] = array("message" => $msg, "type" => "success");
	
	$msg = "- created: " .$_vars["numCreatedNodes"];
	$_vars["log"][] = array("message" => $msg, "type" => "success");
	
	$msg = "- updated: " .$_vars["numUpdatedNodes"];
	$_vars["log"][] = array("message" => $msg, "type" => "success");

}//end _import()


function _save_node( $xmlNode ){
	global $_vars;
	global $content;
	
/*
xmlNode:
Array
(
    [title] => linux, date, установить системную дату/время                                                                                                                                                                                                                   
    [created] => 2020-03-31
    [body_value] => 
date -s &quot;2015-05-27 14:53:00&quot;

		
)
*/

//------------------ Update exists db node or create new db node
	$update = 0;
	for( $n1 = 0; $n1 < count( $_vars["dbNodes"] ); $n1++){
		$dbNode = $_vars["dbNodes"][$n1];
//echo _logWrap( $dbNode );
		if( strtoupper( $dbNode["title"] ) ==  strtoupper( $xmlNode["title"] ) ){
//print( "--test:". $dbNode->title . $xmlNode["title"]. "\n" );
			$update = 1;
			break;
		}
	}//next

	//if( !is_numeric($xmlNode["created"]) ){
		$xmlNode["created"] = strtotime( $xmlNode["created"] );
	//}

	if( $update == 1){
		$xmlNode["id"] = $dbNode["id"];
		$_vars["numUpdatedNodes"]++;
	} else {
		$_vars["numCreatedNodes"]++;
	}
    
	$response = $content->save( $xmlNode );
	if( !$response ){
		$msg = "error, could not save content item ".$xmlNode["title"];
		$_vars["log"][] = array("message" => $msg, "type" => "error");
		exit();		
	} else {
		$msg = "ok, save content item ".$xmlNode["title"];
		$_vars["log"][] = array("message" => $msg, "type" => "success");
	}

}//end _save_node()


function check_module( $module_name, $loadedExt){
	if ( !in_array( $module_name, $loadedExt ) ) {
		$msg = "error, module <b>".$module_name."</b>  not loaded...";
		echo $msg;
		echo "<br/>\n";
		return false;
	} else {
		$msg = "ok, module <b>".$module_name."</b> is available...";
		echo $msg;
		echo "<br/>\n";
	//https://www.php.net/manual/ru/function.get-extension-funcs.php
	//echo "list of functions in module <b>".$module_name."</b>:<pre>";
	//print_r(get_extension_funcs( $module_name ));
	//echo "</pre>";
		return true;
	}
}//end check_module()

?>
