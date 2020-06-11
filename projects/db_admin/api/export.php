﻿<?php
error_reporting(E_ALL|E_STRICT);
ini_set('display_errors', 1);

//echo "<pre>";
//print_r($_SERVER);
//print_r($_REQUEST);
//print_r($_FILES);
//echo "</pre>";

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
	//if(!empty($_REQUEST['export_format'])){
		//$_vars["config"]["export"]["filename"] .= $_REQUEST['export_format'];
	//}

	if(!empty($_REQUEST['content_type'])){
		$_vars["config"]["export"]["content_type"] = $_REQUEST['content_type'];
	}
	
	$res = exportProcess( $_vars["config"]["export"] );
	if( $res ){
//echo _logWrap( htmlspecialchars( $_vars["xml"]) );
		writeXML($_vars["xml"]);
/*		
	//$_vars["export"]["num_content_items"]	= 0;
	//$_vars["export"]["num_content_links"]	= 0;
	//$_vars["export"]["num_tag_groups"]	= 0;
	//$_vars["export"]["num_tag_list"]	= 0;
	//$_vars["export"]["num_tag_links"]	= 0;

		$msg = "Summary: ";
		$msg .= "<li>export content items: ".$_vars["export"]["num_content_items"]."</li>";
		$msg .= "<li>export content links: ".$_vars["export"]["num_content_links"]."</li>";
		$msg .= "<li>export tag groups: ".$_vars["export"]["num_tag_groups"]."</li>";
		$msg .= "<li>export tags: ".$_vars["export"]["num_tag_list"]."</li>";
		$msg .= "<li>export tag content links: ".$_vars["export"]["num_tag_links"]."</li>";
		
		$_vars["log"][] = array("message" => $msg, "type" => "info");
*/
	}
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
	
	$content = new Content();
	$content_links = new ContentLinks();
	$taxonomy = new Taxonomy();

	$res = exportProcess( $_vars["config"]["export"] );
//echo _logWrap($_vars["xml"]);
	if( $res ){
		$filePath = $_vars["config"]["export"]["filePath"];

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
	}
	$msg = "end of export...";
	$_vars["log"][] = array("message" => $msg, "type" => "success");
	
	//====================================== RUNTIME
	$runtime = round( microtime(true) - $_vars["timer"]["start"], 4);
	$msg = "export runtime, sec: ".$runtime;
	$_vars["log"][] = array("message" => $msg, "type" => "info");

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
function exportProcess( $params = array() ){
	global $_vars;
	global $content;
	global $content_links;
	global $taxonomy;
	
	$p = array(
		"type_export_content" => false,
		"content_type" => ""//by default, export content any types
	);
	
	//extend options object $p
	foreach( $params as $key=>$item ){
		$p[ $key ] = $item;
	}//next
	
//echo _logWrap($p);
	
/*
$_vars["sql"]["getContent"] = "SELECT id, title, created, changed, body_value
FROM content WHERE type_id=(
	SELECT content_type.id FROM content_type WHERE content_type.name='{{content_type}}'
);";

$_vars["sql"]["getContentLinks"] = "SELECT content_id, parent_id FROM content_links;";
$_vars["sql"]["getTaxonomyGroups"] = "SELECT id, name FROM taxonomy_groups;";
$_vars["sql"]["getTaxonomyIndex"] = "SELECT content_id, term_id FROM taxonomy_index;";
$_vars["sql"]["getTagList"] = "SELECT id, term_group_id, name, parent_id FROM taxonomy_term_data;";
*/

	$_vars["db_schema"] = false;//do not check database tables
	//$_vars["display_log"] = false;
	
//------------------------------- get exists DB nodes
	$arg = array(
		"tableName" => "content, content_type, filter_format",
		"fields" => array(
			"content.id", 
			"content.title", 
			"content.created",
			"content.changed",
			"content.body_value",
			"filter_format.format as body_format", 
			"content_type.name as type"
		),
		"query_condition" => "WHERE content.type_id=content_type.id 
AND filter_format.id=content.body_format ORDER BY content.title;"
	);
	
//$p["content_type"] = "video";
	if( !empty( $p["content_type"] ) ){
		$content_type = $p["content_type"];
		//$arg["query_condition"] = "LEFT JOIN content_type ON content_type.name='".$content_type."' AND content.type_id=content_type.id ORDER BY content.title";
		$arg["query_condition"] = "WHERE content_type.name='".$content_type."' 
AND content.type_id=content_type.id AND filter_format.id=content.body_format 
ORDER BY content.title;";
	}
	
	$_vars["dbData"]["content"] = $content->getListWithType($arg);
//echo _logWrap( $_vars["dbData"]["content"] == false );
//echo _logWrap( empty($_vars["dbData"]["content"]) );
//echo _logWrap( count($_vars["dbData"]["content"]) );
//echo _logWrap( $_vars["dbData"]["content"] );
//return false;

	if( !$_vars["dbData"]["content"] ){
		$msg = "export: warning, not found content items";
		if( !empty($content_type) ){
			$msg .= ", type: " . $content_type;
		}
		$_vars["log"][] = array("message" => $msg, "type" => "warning");
	}

	if( $_vars["dbData"]["content"] ){
		$msg = "export: found " . count( $_vars["dbData"]["content"] )." database nodes";
		$_vars["log"][] = array("message" => $msg, "type" => "success");
		
		//fix error, not unique created time
		for( $n1 = 0; $n1 < count($_vars["dbData"]["content"]); $n1++){
			$node = $_vars["dbData"]["content"][$n1];
			for( $n2 = $n1+1; $n2 < count($_vars["dbData"]["content"]); $n2++){
				$node2 = $_vars["dbData"]["content"][$n2];
//echo _logWrap( $node["created"] == $node2["created"] );
				if( $node["created"] == $node2["created"]){
					$_vars["dbData"]["content"][$n1]["created"] = time()+$n1;
				}
			}//next
		}//next
	}
//echo _logWrap( $_vars["dbData"]["content"] );
//return false;
	
	
//--------------------------
	$arg = array(
		"tableName" => "content_links",
		"fields" => array("content_id", "parent_id"),
		"query_condition" => null
	);
	$_vars["dbData"]["content_links"] = $content_links->getList($arg);

//--------------------------
	$_vars["dbData"]["tag_groups"] = $taxonomy->getGroupList();
	$_vars["dbData"]["tag_list"] = $taxonomy->getTagList();
	$_vars["dbData"]["tag_links"] = $taxonomy->getTagLinks();

	$_vars["xml"] = formXML( $_vars["dbData"] );
	if ( !empty($_vars["xml"]) ) {
		return true;
	}
	return false;
	
}//end exportProcess()					


function formXML( $xmlData ){
	global $_vars;
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
if( $field == "body_value" || $field == "title" ){
	$value = str_replace("&", "&amp;", $value);
	$value = str_replace("<", "&lt;", $value);
	$value = str_replace(">", "&gt;", $value);
//https://www.fileformat.info/info/unicode/char/0c0a/index.htm
	//$value = str_replace('', '', $value);
	$value = str_replace( chr(0x0C), '', $value);//remove Form Feed
}
//-----------				
if( $field == "type"){
	$content_type = "type='$value'";
	if( empty($value) ){
		$content_type = "";
	}
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
		$contentLinks = "";
		for( $n1 = 0; $n1 < count( $xmlData["content_links"] ); $n1++)	{
			$record = $xmlData["content_links"][$n1];
			$node = $_vars["config"]["export"]["tplContentLink"];
			foreach( $record as $field=>$value){
				$node = str_replace("{{".$field."}}", $value, $node);
			}//next
			$contentLinks .= "\n".$node;
		}//next
		$xml_content = str_replace("{{nodelist}}", $contentLinks."\n\t\t", $xml_content);
		$xml_content = "\t\t".$xml_content."\t\t";
	}
	$xml = str_replace("{{content_links}}", $xml_content, $xml);

//------------------ form tag groups
	$xml_content = "";
	if( !empty( $xmlData["tag_groups"] ) ){
		$xml_content = $_vars["config"]["export"]["tplTagGroups"];
		$tagGroups = "";
		for( $n1 = 0; $n1 < count( $xmlData["tag_groups"] ); $n1++)	{
			$record = $xmlData["tag_groups"][$n1];
			$node = $_vars["config"]["export"]["tplTagGroup"];
			foreach( $record as $field=>$value){
				$node = str_replace("{{".$field."}}", $value, $node);
			}//next
			$tagGroups .= "\n".$node;
		}//next
		$xml_content = str_replace("{{nodelist}}", $tagGroups."\n\t\t", $xml_content);
		$xml_content = "\t\t".$xml_content."\t\t";
	}
	$xml = str_replace("{{tag_groups}}", $xml_content, $xml);

//------------------ form tag list
	$xml_content = "";
	if( !empty( $xmlData["tag_list"] ) ){
		$tagList = "";
		$xml_content = $_vars["config"]["export"]["tplTagList"];
		for( $n1 = 0; $n1 < count( $xmlData["tag_list"] ); $n1++)	{
			$record = $xmlData["tag_list"][$n1];
			$node = $_vars["config"]["export"]["tplTag"];
			foreach( $record as $field=>$value){
				$node = str_replace("{{".$field."}}", $value, $node);
			}//next
			$tagList .= "\n".$node;
		}//next
		$xml_content = str_replace("{{nodelist}}", $tagList."\n\t\t", $xml_content);
		$xml_content = "\t\t".$xml_content."\t\t";
	}
	$xml = str_replace("{{tag_list}}", $xml_content, $xml);

//------------------ form tag links
	$xml_content = "";
	if( !empty( $xmlData["tag_links"] ) ){
		$tagLinks = "";
		$xml_content = $_vars["config"]["export"]["tplTagLinks"];
		for( $n1 = 0; $n1 < count( $xmlData["tag_links"] ); $n1++)	{
			$record = $xmlData["tag_links"][$n1];
			$node = $_vars["config"]["export"]["tplTagLink"];
			foreach( $record as $field=>$value){
				$node = str_replace("{{".$field."}}", $value, $node);
			}//next
			$tagLinks .= "\n".$node;
		}//next
		$xml_content = str_replace("{{nodelist}}", $tagLinks."\n\t\t", $xml_content);
		$xml_content = "\t\t".$xml_content."\t\t";
	}
	$xml = str_replace("{{tag_links}}", $xml_content, $xml);

//echo "<pre>";
//echo htmlspecialchars($xml);
//echo "</pre>";

	$db_name = $_vars["config"]["db"]["dbName"];
	$db_type = $_vars["config"]["db"]["dbType"];
	//$export_date = date(DATE_ATOM);
	$export_date = date('d-M-Y H:i:s');
	$export_time = time();
	
	$xml = str_replace( "{{dbName}}", $db_name, $xml );
	$xml = str_replace( "{{dbType}}", $db_type, $xml );
	$xml= str_replace( "{{exportDate}}", $export_date, $xml );
	$xml = str_replace( "{{exportTime}}", $export_time, $xml );

	return $xml;
}//end formXML()


function formTagList( $records ){
	global $_vars;
	
	$tagList = "";

	for( $n1 = 0; $n1 < count( $records ); $n1++)	{
		$record = $records[$n1];
//echo "<pre>";
//print_r($record);
//echo "</pre>";

		$tag = $_vars["tplTagListItem"];

		$tag = str_replace("{{tid}}", $record->tid, $tag);
		$tag = str_replace("{{vid}}", $record->vid, $tag);
		$tag = str_replace("{{group_name}}", $record->group_name, $tag);
		$tag = str_replace("{{name}}", $record->name, $tag);

		$tagList .= "\n\t".$tag;
	}//next

	$xmlList = str_replace("{{list}}", $tagList."\n", $_vars["tplTagList"]);
	
	return $xmlList;
}//end formTagList()


function writeXML($xml){
	global $_vars;

	$filename = $_vars["config"]["export"]["filename"];
	$filePath = $_vars["config"]["export"]["filePath"];
	
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
