﻿<?php
error_reporting(E_ALL|E_STRICT);
ini_set('display_errors', 1);

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
	
	if(!empty($_REQUEST['filename'])){
		$_vars["config"]["export"]["filename"] = $_REQUEST['filename'];
	}
	//if(!empty($_REQUEST['import_format'])){
	//}
	//$_vars["db_schema"] = false;//do not check database tables
	$_vars["display_log"] = false;
	
	_importProcess();
}

//==================================== CONSOLE run
if ( $_vars["runType"] == "console") {
//print_r($argv);
//$_SERVER["argv"]
	$_vars["config"] = require_once("../config.php");
	
	require_once "../inc/functions.php";
	require_once "../inc/db.php";
	require_once "../inc/content.php";
	//require_once "../inc/content_links.php";
	//require_once "../inc/taxonomy.php";
	require_once "../inc/app.php";
	
	$content = new Content();
	//$content_links = new ContentLinks();
	//$taxonomy = new Taxonomy();
	$app = new App();

	$_vars["db_schema"] = false;//do not check database tables
	//$_vars["display_log"] = false;
	
	_importProcess();

	//====================================== LOG
	if ( !empty( $_vars["log"] ) ) {
		for( $n = 0; $n < count( $_vars["log"] ); $n++){
		//for( $n = count( $_vars["log"] ) - 1; $n >= 0; $n--){
			$record = $_vars["log"][$n];
			echo _logWrap( $record["message"], $record["type"] );
		}//next
	}
}


//====================
// FUNCTIONS
//====================
function _importProcess(){
	global $_vars;
	global $content;
	//global $content_links;
	//global $taxonomy;
	global $app;
	
	//--------------------------- check PHP-module SimpleXML
	$loadedExt = get_loaded_extensions();
	$module_name = "SimpleXML";
	$_vars["support"][$module_name] = check_module( $module_name, $loadedExt);
	if( !$_vars["support"][$module_name] ){
		$_vars["log"][] = array("message" => $loadedExt, "type" => "error");
		return false;
	}

	//--------------------------- load XML
	$xml_filepath = $_vars["config"]["export"]["filename"];
	
	if ( !file_exists($xml_filepath) ) {
		$msg = "error, ".$xml_filepath." not exists...";
		$_vars["log"][] = array("message" => $msg, "type" => "error");
		return false;
	}
		
	$_vars["xml"] = simplexml_load_file( $xml_filepath );
	if( !$_vars["xml"] ){
		$msg = "error, simplexml_load_file(), wrong file ".$xml_filepath;
		$_vars["log"][] = array("message" => $msg, "type" => "error");
		return false;
	}
	
	if( empty($_vars["xml"]->schema) ){
		$msg = "error, wrong XML structure schema ";
		$_vars["log"][] = array("message" => $msg, "type" => "error");
		return false;
	}
	
//echo _logWrap( $_vars["xml"]->schema );
//echo _logWrap( gettype( $_vars["xml"]->schema ) );
	
	//--------------------------- get XML values
/*	
	$item = "content";
	$arg = array(
		"xml" => $_vars["xml"]->xdata->$item,	
		//"schema" => $_vars["xml"]->schema->xdata->content	
		"nodeName" => $item 
	);
	$itemData = $app->getXMLcontent( $arg );
	if( !empty($itemData) ){
		$_vars["xmlData"][$item] = $itemData;
	}

	$item = "content_links";
	$arg = array( 
		"xml" => $_vars["xml"]->xdata->$item, 
		"nodeName" => $item 
	);
	$itemData = $app->getXMLcontent( $arg );
	if( !empty($itemData) ){
		$_vars["xmlData"][$item] = $itemData;
	}

	$item = "tag_groups";
	$arg = array( 
		"xml" => $_vars["xml"]->xdata->$item, 
		"nodeName" => $item 
	);
	$itemData = $app->getXMLcontent( $arg );
	if( !empty($itemData) ){
		$_vars["xmlData"][$item] = $itemData;
	}
	
	$item = "tag_links";
	$arg = array( 
		"xml" => $_vars["xml"]->xdata->$item, 
		"nodeName" => $item 
	);
	$itemData = $app->getXMLcontent( $arg );
	if( !empty($itemData) ){
		$_vars["xmlData"][$item] = $itemData;
	}

	$item = "tag_list";
	$arg = array( 
		"xml" => $_vars["xml"]->xdata->$item, 
		"nodeName" => $item 
	);
	$itemData = $app->getXMLcontent( $arg );
	if( !empty($itemData) ){
		$_vars["xmlData"][$item] = $itemData;
	}
*/
	foreach( $_vars["xml"]->schema->xdata as $item => $value){
		foreach( $value as $ch_item => $ch_value){
//echo _logWrap( $ch_item );
//echo _logWrap( $ch_value );
			$arg = array( 
				"xml" => $_vars["xml"]->xdata->$ch_item, 
				"nodeName" => $ch_item 
			);
			$itemData = $app->getXMLcontent( $arg );
			if( !empty($itemData) ){
				$_vars["xmlData"][$ch_item] = $itemData;
			}
		}//next
	}//next

//echo _logWrap( $_vars["xml"]->schema );
unset($_vars["xml"]);
//echo count($_vars["xmlData"]["content"]["children"]);
//echo _logWrap( $_vars["xmlData"] );
//return false;

//------------------------------- get content_type (for import 'type_id')
	$db = DB::getInstance();
	$arg = array(
		"tableName" => "content_type",
		"fields" => array("id","name")
	);
	$res = $db->getRecords($arg);
	if( !empty($res) ){
		for( $n=0; $n < count($res); $n++){//convert numeric array
			$record = $res[$n];
			$key = $record["name"];
			$value = $record["id"];
			$_vars["table_content_type"][$key] = $value;
		}//next
	}
//echo _logWrap( $_vars["table_content_type"] );
//return false;
	
//------------------------------- get exists DB nodes
	$arg = array(
		"fields" => array(
			"content.id", 
			"content.title", 
			"content.created"//,
			//"content.changed",
			//"content.body_value"
		)
	);
	
	$_vars["dbData"]["content"] = $content->getList($arg);
//echo _logWrap( $_vars );
	if( $_vars["dbData"]["content"] ){
		$msg = "import: found " . count( $_vars["dbData"]["content"] )." db nodes";
		$_vars["log"][] = array("message" => $msg, "type" => "success");
	} else {
		$msg = "import: warning, db nodes not found.";
		$_vars["log"][] = array("message" => $msg, "type" => "warning");
	}

//--------------------------
	//$_vars["xmlData"]["tag_groups"] = $taxonomy->getGroupList();
	//$_vars["xmlData"]["tag_list"] = $taxonomy->getTagList();
	//$_vars["xmlData"]["tag_links"] = $taxonomy->getTagLinks();

	//$_vars["xml"] = formXML( $_vars["xmlData"] );
	
//------------------------------- insert/update database nodes from XML nodes
//echo count($_vars["xmlData"]["content"]["children"]);
//echo _logWrap( $_vars["xmlData"] );
	$_vars["import"]["numUpdated"] = 0;
	$_vars["import"]["numCreated"] = 0;
	$_vars["import"]["total"] = 0;

	for( $n1 = 0; $n1 < count($_vars["xmlData"]["content"]["children"]); $n1++){
		$node = $_vars["xmlData"]["content"]["children"][$n1];
//echo _logWrap( $node["title"] );

//-------------------
		if( !empty($node["type"]) ){
			$key = $node["type"];
			$node["type_id"] = $_vars["table_content_type"][$key];
		}

//-------------------
		unset( $node["id"] );//do not save node old ID
		$arg = array(
			"xmlNode" => $node,
			"dbNodes" => $_vars["dbData"]["content"]
		);
		
		$response = $app->saveXMLnode( $arg );
		if( $response){
			$_vars["import"]["total"]++;
		} else {
			$msg = "import warning: node " .$node["title"]. " not saved....";
			$_vars["log"][] = array("message" => $msg, "type" => "warning");
		}
	}//next

	$msg = "Import ".$_vars["import"]["total"]." content items";
	$msg .= ", num created: " .$_vars["import"]["numCreated"];
	$msg .= ", num updated: " .$_vars["import"]["numUpdated"];
	$_vars["log"][] = array("message" => $msg, "type" => "success");

//------------------------------- import content links info from XML nodes
	$_vars["import"]["numUpdated"] = 0;
	$_vars["import"]["numCreated"] = 0;
	$_vars["import"]["total"] = 0;
	
	//get new ID from database nodes for next update content links
	$arg = array(
		"fields" => array(
			"content.id", 
			"content.title", 
			"content.created" 
		)
	);
	$_vars["dbData"]["content"] = $content->getList($arg);
	if( empty($_vars["dbData"]["content"]) ){
		$msg = "import error, database nodes not found";
		$_vars["log"][] = array("message" => $msg, "type" => "warning");
		return false;
	}
	
	//------ copy new ID to XML node["new_id"]
	for( $n1 = 0; $n1 < count( $_vars["dbData"]["content"] ); $n1++){
		$dbNode = $_vars["dbData"]["content"][$n1];
//echo _logWrap( $dbNode );
		for( $n2 = 0; $n2 < count( $_vars["xmlData"]["content"]["children"] ); $n2++){
			//$xmlNode = &$_vars["xmlData"]["content"]["children"][$n2];//as link!!!
			$xmlNode = $_vars["xmlData"]["content"]["children"][$n2];
			
			$testTitle = _filterFormInputValue( $xmlNode["title"] );
			//$testTitle = $xmlNode["title"];
			if( strtoupper( $dbNode["title"] ) ==  strtoupper( $testTitle ) ){
//$msg = "update:". $dbNode["title"] ." = ". $xmlNode["title"];
//echo _logWrap( $msg );
				if( $dbNode["created"]  ==  $xmlNode["created"] ){
					$xmlNode["new_id"] = $dbNode["id"];

					//----- build replacement table
					$key = $xmlNode["id"];
					$value = $dbNode["id"];
					$replacement_table[ $key ] = $value;
				}
			}
			$_vars["xmlData"]["content"]["children"][$n2] = $xmlNode;
		}//next
	}//next
//echo _logWrap( $_vars["xmlData"]["content"]["children"] );
//echo _logWrap( $replacement_table );
//return false;


	//------ replace old IDs into XML content_links (NEW content_id, NEW parent_id)
	for( $n1 = 0; $n1 < count( $_vars["xmlData"]["content_links"]["children"] ); $n1++){
		//$xmlLink = &$_vars["xmlData"]["content_links"]["children"][$n1];//as link!!!
		$xmlLink = $_vars["xmlData"]["content_links"]["children"][$n1];
/*		
		for( $n2 = 0; $n2 < count( $_vars["xmlData"]["content"]["children"] ); $n2++){
			$xmlNode = $_vars["xmlData"]["content"]["children"][$n2];
//echo _logWrap( $xmlLink["content_id"]. "==". $xmlNode["id"] );			
			if( $xmlLink["content_id"] == $xmlNode["id"] ){
				$xmlLink["content_id_old"] = $xmlLink["content_id"];//save old id
				$xmlLink["content_id"] = $xmlNode["new_id"];
			}
			if( $xmlLink["parent_id"] == $xmlNode["id"] ){
				$xmlLink["parent_id_old"] = $xmlLink["parent_id"];//save old id
				$xmlLink["parent_id"] = $xmlNode["new_id"];
			}
		}//next
		$_vars["xmlData"]["content_links"]["children"][$n1] = $xmlLink;
*/

/*
		foreach( $replacement_table as $key=>$value ){
			if( $key == $xmlLink["content_id"]){
				$xmlLink["content_id"] = $value;
			}
			if( $key == $xmlLink["parent_id"]){
				$xmlLink["parent_id"] = $value;
			}
		}//next
*/	
		//replace IDs
		$old_id = $xmlLink["content_id"];
		$new_id = $replacement_table[$old_id];
		$xmlLink["content_id"] = $new_id;
			
		$old_parent_id = $xmlLink["parent_id"];
		if( $old_parent_id > 0){
			$new_parent_id = $replacement_table[$old_parent_id];
			$xmlLink["parent_id"] = $new_parent_id;
		}

		//update
		$_vars["xmlData"]["content_links"]["children"][$n1] = $xmlLink;
	}//next
	
//echo _logWrap( $_vars["xmlData"]["content_links"]["children"] );
//return false;

//------------------------------- insert/update database content links
	//$_vars["import"]["numUpdated"] = 0;
	//$_vars["import"]["numCreated"] = 0;
	$_vars["import"]["total"] = 0;

	for( $n1 = 0; $n1 < count($_vars["xmlData"]["content_links"]["children"]); $n1++){
		$node = $_vars["xmlData"]["content_links"]["children"][$n1];
//echo _logWrap( $node["title"] );

		$arg = array(
			"xmlNode" => $node,
			//"dbNodes" => $_vars["dbData"]["content"]
		);
		$response = $app->saveXMLcontent_link( $arg );
		if( $response){
			$_vars["import"]["total"]++;
		} else {
			$msg = "import warning: node " .$node["content_id"]. " not saved....";
			$_vars["log"][] = array("message" => $msg, "type" => "warning");
		}
	}//next
	
	$msg = "Import ".$_vars["import"]["total"]." content links";
	//$msg .= ", created: " .$_vars["import"]["numCreated"];
	//$msg .= ", updated: " .$_vars["import"]["numUpdated"];
	$_vars["log"][] = array("message" => $msg, "type" => "success");

}//end _importProcess()					

?>
