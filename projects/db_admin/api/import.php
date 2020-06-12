﻿<?php
error_reporting(E_ALL|E_STRICT);
ini_set('display_errors', 1);

$_vars["timer"]["start"] = microtime(true);

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
	
	importProcess();
}

//==================================== CONSOLE run
if ( $_vars["runType"] == "console") {
//print_r($argv);
//$_SERVER["argv"]
	$_vars["config"] = require_once("../config.php");
	
	require_once "../inc/functions.php";
	require_once "../inc/db.php";
	require_once "../inc/content.php";
	require_once "../inc/content_links.php";
	require_once "../inc/taxonomy.php";
	require_once "../inc/app.php";
	
	$content = new Content();
	$content_links = new ContentLinks();
	$taxonomy = new Taxonomy();
	$app = new App();

	$_vars["db_schema"] = false;//do not check database tables
	$_vars["display_log"] = false;
	
	importProcess();

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
function importProcess(){
	global $_vars;
	//global $content;
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

	$_vars["import_info"] = array();
	$_vars["import_info"]["xml_filepath"] = $xml_filepath;
	foreach( $_vars["xml"]->xdata->attributes() as $attr => $attr_value){
//$msg = $attr. ": ".$attr_value;
//echo _logWrap( $msg );
		$_vars["import_info"][$attr] = (string)$attr_value;
	}//next
//echo _logWrap( $_vars["import_info"] );
	
	//--------------------------- get XML values
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
//echo _logWrap( $_vars["xmlData"]["content"] );
//return false;

	$_vars["import"]["replacement_table"] = array();

	//import content info from XML nodes
	if( !empty( $_vars["xmlData"]["content"]["children"] ) ){
		importContent();
	}

	//import content links info from XML nodes
	if( !empty( $_vars["xmlData"]["content_links"]["children"] ) ){
		importContentLinks();
		$msg = "Import ".$_vars["import"]["total"]." content links";
		$_vars["log"][] = array("message" => $msg, "type" => "success");
	}

/*
	//import tag_groups info from XML nodes
	if( !empty( $_vars["xmlData"]["tag_groups"]["children"] ) ){
		//importTagGroups();
		$msg = "Import ".$_vars["import"]["total"]." tag groups";
		$msg .= ", num created: " .$_vars["import"]["numCreated"];
		$msg .= ", num updated: " .$_vars["import"]["numUpdated"];
		$_vars["log"][] = array("message" => $msg, "type" => "success");
	}
	if( !empty( $_vars["xmlData"]["tag_list"]["children"] ) ){
		//importTagList();
		$msg = "Import ".$_vars["import"]["total"]." tags (termins)";
		$msg .= ", num created: " .$_vars["import"]["numCreated"];
		$msg .= ", num updated: " .$_vars["import"]["numUpdated"];
		$_vars["log"][] = array("message" => $msg, "type" => "success");
	}
	if( !empty( $_vars["xmlData"]["tag_links"]["children"] ) ){
		//importTagLinks();
	}
//echo _logWrap( $_vars["import"]["replacement_table"] );
*/

	//--------------------------- get XML import info
//<xdata db_name="notes.sqlite" db_type="sqlite" export_date="23-May-2020 13:30:33" export_time="1590215433">
	$msg = "import info: <ul>";
	foreach( $_vars["import_info"] as $attr => $attr_value){
		$msg .= "<li><b>".$attr."</b> : ".$attr_value."</li>";
	}//next

	//RUNTIME
	$runtime_s = round( microtime(true) - $_vars["timer"]["start"], 2);
	$runtime_m = round( $runtime_s / 60, 2);
	$msg .= "<li><b>import runtime</b>: ".$runtime_s." sec, ".$runtime_m." min</li>";

	$msg .= "</ul>";
	$_vars["log"][] = array("message" => $msg, "type" => "info");
	
}//end importProcess()					


//-------------------------------
// import content info from XML nodes
//-------------------------------
function importContent(){
	global $_vars;
	global $content;
	//global $content_links;
	//global $taxonomy;
	global $app;

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

//------------------------------- get filter_format (for import 'body_format')
	$db = DB::getInstance();
	$arg = array(
		"tableName" => "filter_format",
		"fields" => array("id","format")
	);
	$res = $db->getRecords($arg);
	if( !empty($res) ){
		for( $n=0; $n < count($res); $n++){//convert numeric array
			$record = $res[$n];
			$key = $record["format"];
			$value = $record["id"];
			$_vars["table_filter_format"][$key] = $value;
		}//next
	}
//echo _logWrap( $_vars["table_filter_format"] );
//return false;
	
//------------------- get exists DB nodes for update if exists importing node
	$arg = array(
		"fields" => array(
			"content.id", 
			"content.title", 
			"content.created"//,
			//"content.changed",
			//"content.body_value"
		)
	);
	
	$_vars["dbData"]["content"] = $content->getListWithType($arg);
//echo _logWrap( $_vars["dbData"] );
//return false;
	if( $_vars["dbData"]["content"] ){
		$msg = "import: found " . count( $_vars["dbData"]["content"] )." db nodes";
		$_vars["log"][] = array("message" => $msg, "type" => "success");
	} //else {
		//$msg = "import: warning, db nodes not found.";
		//$_vars["log"][] = array("message" => $msg, "type" => "warning");
	//}
	
//------------------------------- insert/update database nodes from XML nodes
//echo count($_vars["xmlData"]["content"]["children"]);
//echo _logWrap( $_vars["xmlData"] );
	$_vars["import"]["numUpdated"] = 0;
	$_vars["import"]["numCreated"] = 0;
	$_vars["import"]["total"] = 0;

//------------------- fix error, not unique created time
	for( $n1 = 0; $n1 < count($_vars["xmlData"]["content"]["children"]); $n1++){
		$node = $_vars["xmlData"]["content"]["children"][$n1];
		if( empty( $node["created"] ) ){
			$_vars["xmlData"]["content"]["children"][$n1]["created"] = time()+$n1;
			$_vars["xmlData"]["content"]["children"][$n1]["changed"] = time()+$n1;
			continue;
		}	
		for( $n2 = $n1+1; $n2 < count($_vars["xmlData"]["content"]["children"]); $n2++){
			$node2 = $_vars["xmlData"]["content"]["children"][$n2];
//echo _logWrap( $node["created"].", ".$node2["created"] );
			if( $node["created"] == $node2["created"]){
				$new_time = time()+$n1;
				
				$_vars["xmlData"]["content"]["children"][$n1]["created"] = $new_time;
				$msg = "import:  <b>not unique field 'created' detected</b>, ";
				$msg .= " title: ".$node["title"].", created: ".$node["created"];
				$msg .= ", fix, new created: ".$new_time;
				$_vars["log"][] = array("message" => $msg, "type" => "warning");
			}
		}//next
	}//next


	for( $n1 = 0; $n1 < count($_vars["xmlData"]["content"]["children"]); $n1++){
		$node = $_vars["xmlData"]["content"]["children"][$n1];
//echo _logWrap( $node["title"] );

//-------------------
		if( !empty($node["type"]) ){
			$key = $node["type"];
			if( isset($_vars["table_content_type"][$key]) ){
				$node["type_id"] = $_vars["table_content_type"][$key];
			}
		}
//-------------------
		if( !empty($node["body_format"]) ){
			$key = $node["body_format"];
			if( isset($_vars["table_filter_format"][$key]) ){
				$node["body_format"] = $_vars["table_filter_format"][$key];
			}
		}
/*
		$key = $_vars["config"]["default_filter_formats"];
		$body_format = $_vars["table_filter_format"][$key];
		if( !empty($node["body_format"]) ){
			$key = $node["body_format"];
			if( isset($_vars["table_filter_format"][$key]) ){
				$body_format = $_vars["table_filter_format"][$key];
			}
		}
		$node["body_format"] = $body_format;
 */		
//-------------------
		//if( empty($node["created"]) ){//fix import error, empty created time
			//$node["created"] = time()+$n1;
			//$node["changed"] = time()+$n1;
		//}	
		
//-------------------
		unset( $node["id"] );//do not save node old ID
		$arg = array(
			"xmlNode" => $node,
			"dbNodes" => $_vars["dbData"]["content"]
		);
		
		$response = $app->saveXMLnode( $arg );
		if( $response){
			$_vars["import"]["total"]++;
			//sleep(1);//timeout before next saving (for form unique filed 'created')
		} else {
			$msg = "import warning: node " .$node["title"]. " not saved....";
			$_vars["log"][] = array("message" => $msg, "type" => "warning");
		}
	}//next

	$msg = "Import ".$_vars["import"]["total"]." content items";
	$msg .= ", num created: " .$_vars["import"]["numCreated"];
	$msg .= ", num updated: " .$_vars["import"]["numUpdated"];
	$_vars["log"][] = array("message" => $msg, "type" => "success");
	
}//end importContent()					


//-------------------------------
// import content links info from XML nodes
//-------------------------------
function importContentLinks(){
	global $_vars;
	global $content;
	//global $content_links;
	//global $taxonomy;
	global $app;
	
	//$_vars["import"]["numUpdated"] = 0;
	//$_vars["import"]["numCreated"] = 0;
	$_vars["import"]["total"] = 0;
	
	//get new ID from database nodes for next update content links
	$arg = array(
		"fields" => array(
			"content.id", 
			"content.title", 
			"content.created" 
		)
	);
	$_vars["dbData"]["content"] = $content->getListWithType($arg);
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
			
			//$testTitle = _filterFormInputValue( $xmlNode["title"] );
			//$testTitle = $xmlNode["title"];
			//if( strtoupper( $dbNode["title"] ) ==  strtoupper( $testTitle ) ){
//$msg = "update:". $dbNode["title"] ." = ". $xmlNode["title"];
//echo _logWrap( $msg );
				if( $dbNode["created"]  ==  $xmlNode["created"] ){
					$xmlNode["new_id"] = $dbNode["id"];

					//----- build replacement table IDs
					$key = $xmlNode["id"];
					$value = $dbNode["id"];
					$replacement_table[ $key ] = $value;
				}
			//}
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
		$old_id = $xmlLink["content_id"];
		if( isset($replacement_table[$old_id]) ){//replace IDs
			$new_id = $replacement_table[$old_id];
			$xmlLink["content_id"] = $new_id;
				
			$old_parent_id = $xmlLink["parent_id"];
			if( $old_parent_id > 0){
				$new_parent_id = $replacement_table[$old_parent_id];
				$xmlLink["parent_id"] = $new_parent_id;
			}

			//update
			$_vars["xmlData"]["content_links"]["children"][$n1] = $xmlLink;
		}
	}//next
	
//echo _logWrap( $_vars["xmlData"]["content_links"]["children"] );
//return false;

//------------------------------- insert/update database content links
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
	
}//end importContentLinks()


//-------------------------------
// import tag taxonomy info from XML nodes
//-------------------------------
function importTagGroups(){
	global $_vars;
	global $taxonomy;
	//global $app;
	
	
	$dataItemName = "tag_groups";
	$xmlData = $_vars["xmlData"][$dataItemName]["children"];
	if( empty($xmlData) ){
		return false;
	}
	
	$_vars["import"]["numUpdated"] = 0;
	$_vars["import"]["numCreated"] = 0;
	$_vars["import"]["total"] = 0;
	
	$replacement_table = &$_vars["import"]["replacement_table"];
	
//------------------------------- get exists DB nodes
	$dbData = $taxonomy->getTagGroup();
//echo _logWrap( count($dbData) );
//echo _logWrap( !empty($dbData) );
//echo _logWrap( $dbData );
//return false;

//------------------------------- insert/update 
	$_vars["import"]["total"] = 0;
	for( $n1 = 0; $n1 < count($xmlData); $n1++){
		$node = $xmlData[$n1];
//echo _logWrap( $node["name"] );

		unset( $node["id"] );//do not save node old ID

		//------------- add new ID, if node exists in database (for update query)
		$update = 0;
		
		if( !empty($dbData) ){
			for( $n2 = 0; $n2 < count($dbData); $n2++){
				$dbNode = $dbData[$n2];
				if( strtoupper( $dbNode["name"] ) ==  strtoupper( $node["name"] ) ){
//$msg = "update:". $dbNode["title"] ." = ". $p["xmlNode"]["title"];
//echo _logWrap( $msg );
					$node["id"] = $dbNode["id"];
					$update = 1;
					break;
				}
			}//next
			
		}
		
		if( $update == 1){
			$_vars["import"]["numUpdated"]++;
		} else {
			$_vars["import"]["numCreated"]++;
		}

		$response = $taxonomy->saveTermGroup( $node );
		if( $response["status"] ){
			
			//----- build replacement table IDs
			if( !empty($response["last_insert_id"]) ){
				$key = $xmlData[$n1]["id"];
				$replacement_table["taxonomy_groups"][ $key ] = $response["last_insert_id"];
			} else {
				$key = $xmlData[$n1]["id"];
				$replacement_table["taxonomy_groups"][ $key ] = $node["id"];
			}
			
			$_vars["import"]["total"]++;
//$msg = "save new term group.";
//$_vars["log"][] = array("message" => $msg, "type" => "success");
		} else {
$msg = "import error: could not save node...";
$_vars["log"][] = array("message" => $msg, "type" => "error");
		}
	}//next
	
}//end importTagGroups()


function importTagList(){
	global $_vars;
	global $taxonomy;
	//global $app;
	
	$_vars["import"]["numUpdated"] = 0;
	$_vars["import"]["numCreated"] = 0;
	$_vars["import"]["total"] = 0;
	
	$dataItemName = "tag_list";
	$xmlData = $_vars["xmlData"][$dataItemName]["children"];
	$_vars["import"]["total"] = 0;


//------------------------------- get exists DB nodes
	$dbData = $taxonomy->getTagList();
//echo _logWrap( count($dbData) );
//echo _logWrap( !empty($dbData) );
//echo _logWrap( $dbData );
//return false;
	
	$replacement_table = &$_vars["import"]["replacement_table"];
		
//------------------------------- insert/update 
	for( $n1 = 0; $n1 < count($xmlData); $n1++){
	//for( $n1 = 0; $n1 < 2; $n1++){
		$node = $xmlData[$n1];
//echo _logWrap( $node["name"] );

		unset( $node["id"] );//do not save node old ID
		
		//------------- add new ID, if node exists in database (for update query)
		$update = 0;
		
		if( !empty($dbData) ){
			for( $n2 = 0; $n2 < count($dbData); $n2++){
				$dbNode = $dbData[$n2];
				if( strtoupper( $dbNode["name"] ) ==  strtoupper( $node["name"] ) ){
//$msg = "update record: ". $dbNode["name"] ." = ". $node["name"];
//echo _logWrap( $msg );
					$node["id"] = $dbNode["id"];
					$update = 1;
					break;
				}
			}//next
			
		}
		
		if( $update == 1){
			$_vars["import"]["numUpdated"]++;
		} else {
			$_vars["import"]["numCreated"]++;
		}

		//update term_group_id
		$term_group_id_old = $xmlData[$n1]["term_group_id"];
		$term_group_id_new = $replacement_table["taxonomy_groups"][$term_group_id_old];
		$node["term_group_id"] = $term_group_id_new;
		
		$response = $taxonomy->saveTerm( $node );
//echo _logWrap($response);
		if( $response["status"] ){
			
			//----- build replacement table IDs
			if( !empty($response["last_insert_id"]) ){
				$key = $xmlData[$n1]["id"];
				$replacement_table["taxonomy_term_data"][ $key ] = $response["last_insert_id"];
				$xmlData[$n1]["new_id"] = $response["last_insert_id"];
			} else {
				$key = $xmlData[$n1]["id"];
				$replacement_table["taxonomy_term_data"][ $key ] = $node["id"];
				$xmlData[$n1]["new_id"] = $node["id"];
			}
			
			$_vars["import"]["total"]++;
//$msg = "save new term group.";
//$_vars["log"][] = array("message" => $msg, "type" => "success");
		} else {
$msg = "import error: could not save node...";
$_vars["log"][] = array("message" => $msg, "type" => "error");
		}
	}//next
	
//echo _logWrap( $xmlData );

//------------------------------- update parent_id
//echo _logWrap( $replacement_table );
	for( $n1 = 0; $n1 < count($xmlData); $n1++){
		$parent_id_old = $xmlData[$n1]["parent_id"];
		if( $parent_id_old == 0 ){
			continue;
		}
		$parent_id_new = $replacement_table["taxonomy_term_data"][$parent_id_old];
		
		$node = array(
			"id" => $xmlData[$n1]["new_id"],
			"parent_id" => $parent_id_new
		);
		$response = $taxonomy->saveTerm( $node );
		if( !$response["status"] ){
$msg = "import error: update taxonomy_term_data IDs";
$_vars["log"][] = array("message" => $msg, "type" => "error");
		}
	}//next
	
}//end importTagList()


function importTagLinks(){
	global $_vars;
	//global $taxonomy;
	//global $app;
	
	//$_vars["import"]["numUpdated"] = 0;
	//$_vars["import"]["numCreated"] = 0;
	$_vars["import"]["total"] = 0;
	
	$xmlData = $_vars["xmlData"]["tag_links"]["children"];

//------------------------------- insert/update database tag links
	$_vars["import"]["total"] = 0;
	//for( $n1 = 0; $n1 < count($xmlData); $n1++){
	//for( $n1 = 0; $n1 < 2; $n1++){
		//$node = $xmlData[$n1];
//echo _logWrap( $node );

		$arg = array(
			"tableName" => "taxonomy_index",
			//"data" => $xmlData
			"data" => array( $xmlData[0], $xmlData[1], $xmlData[2])
		);
		
		$db = DB::getInstance();
		$response = $db->saveRecords($arg);
		if( $response["status"] ){
			$_vars["import"]["total"] = count($xmlData);
		} else {
			$msg = "import error: XML node tag_links not saved...";
			$_vars["log"][] = array("message" => $msg, "type" => "error");
		}
		//$db->testPdo($arg);
	//}//next
 
}//end importTagLinks()

?>
