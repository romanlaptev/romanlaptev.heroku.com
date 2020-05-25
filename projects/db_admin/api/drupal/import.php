<?php
error_reporting(E_ALL|E_STRICT);
ini_set('display_errors', 1);

$_vars=array();
$_vars["config"] = require_once("config.php");

//--------------------------------------- load Drupal
$drupalConstFile = $_vars["config"]["export"]["drupalConstFile"];
if ( !file_exists( $drupalConstFile ) ){
	$msg = "error, not find Drupal constant file ".$drupalConstFile;
	echo $msg;
	exit();
}

// Define default settings.
chdir ("../");
//echo getcwd();
//echo "<br>";

define('DRUPAL_ROOT', getcwd() );
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
//echo _logWrap( DRUPAL_ROOT );

// Bootstrap Drupal.
require_once $drupalConstFile;
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);


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
		$_vars["form"] = viewForm();
	} else {
		$action = $_REQUEST['action'];
		switch ($action) {
			case "import":
				if( !empty($_REQUEST['filename']) ){
					$_vars["config"]["export"]["filePath"] = $_REQUEST['filename'];
				}
				_importProcess();
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
	_importProcess();
}


//====================================== LOG
if ( !empty( $_vars[ "log" ] ) ) {
	for( $n = 0; $n < count( $_vars["log"] ); $n++){
	//for( $n = count( $_vars["log"] ) - 1; $n >= 0; $n--){
		$record = $_vars["log"][$n];
		echo _logWrap( $record["message"], $record["type"] );
	}//next
}


//====================

function viewForm(){
	global $_vars;
	return "<form method=post name='form_import' action='' class='form'>
		<fieldset>
<legend>Import parameters:</legend>
		<div class=class='form-group'>
<label>filename</label>
<input type='text' name='filename' value='".$_vars["config"]["export"]["filePath"]."' size='60' class='form-control'/>
		</div>
		<input type=hidden name='action' value='import'/>
		<input type=submit value='Import' class='btn btn-large btn-primary'>
		</fieldset>
	</form>";
}//end viewForm()

function PageHead(){
	return "<html>
<head>
	<meta charset='utf-8'>
	<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
	<meta name='viewport' content='width=device-width, initial-scale=1.0'/>
	<meta http-equiv='X-UA-Compatible' content='IE=Edge'>
</head>
<body>
<div class='container'>";
}//end

function PageEnd(){
	return "
<p><b>PHP version:</b>".phpversion()."</p>
<p><b>Drupal version:</b>".VERSION."</p>	
</div>
</body>
</html>";
}//end


function _importProcess(){
	global $_vars;
	
	//--------------------------- check PHP-module SimpleXML
	$loadedExt = get_loaded_extensions();
	$module_name = "SimpleXML";
	$_vars["support"][$module_name] = check_module( $module_name, $loadedExt);
	if( !$_vars["support"][$module_name] ){
		$_vars["log"][] = array("message" => $loadedExt, "type" => "error");
		return false;
	}

	//--------------------------- load XML
	$xml_filepath = $_vars["config"]["export"]["filePath"];
	
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
	foreach( $_vars["xml"]->schema->xdata as $item => $value){
		foreach( $value as $ch_item => $ch_value){
//echo _logWrap( $ch_item );
//echo _logWrap( $ch_value );
			$arg = array( 
				"xml" => $_vars["xml"]->xdata->$ch_item, 
				"nodeName" => $ch_item 
			);
			$itemData = getXMLcontent( $arg );
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


	//import content info from XML nodes
	if( !empty( $_vars["xmlData"]["content"]["children"] ) ){
		_importContent();
	}

/*
	//import content links info from XML nodes
	if( !empty( $_vars["xmlData"]["content_links"]["children"] ) ){
		_importContentLinks();
		$msg = "Import ".$_vars["import"]["total"]." content links";
		//$msg .= ", created: " .$_vars["import"]["numCreated"];
		//$msg .= ", updated: " .$_vars["import"]["numUpdated"];
		$_vars["log"][] = array("message" => $msg, "type" => "success");
	}
*/

}//end _importProcess()



//-------------------------------
// import content info from XML nodes
//-------------------------------
function _importContent(){
	global $_vars;
/*
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
*/
	
//------------------------------- get exists DB nodes
	$sql_query = "SELECT nid, title, created FROM node;";
	$result = db_query($sql_query);
	$n1=0;
	foreach ($result as $row) {
		$_vars["dbData"]["content"][$n1] = $row;
		$n1++;
	}//next
	if( $_vars["dbData"]["content"] ){
		$msg = "import: found " . count( $_vars["dbData"]["content"] )." db nodes";
		$_vars["log"][] = array("message" => $msg, "type" => "success");
	} else {
		$msg = "import: warning, db nodes not found.";
		$_vars["log"][] = array("message" => $msg, "type" => "warning");
	}
//echo _logWrap( $_vars["dbData"] );
//return false;

//------------------------------- insert/update database nodes from XML nodes
//echo count($_vars["xmlData"]["content"]["children"]);
//echo _logWrap( $_vars["xmlData"] );
	$_vars["import"]["numUpdated"] = 0;
	$_vars["import"]["numCreated"] = 0;
	$_vars["import"]["total"] = 0;

//	for( $n1 = 0; $n1 < count($_vars["xmlData"]["content"]["children"]); $n1++){
		//$node = $_vars["xmlData"]["content"]["children"][$n1];
		$node = $_vars["xmlData"]["content"]["children"][0];
//echo _logWrap( $node );

//-------------------
//		if( !empty($node["type"]) ){
//			$key = $node["type"];
//			$node["type_id"] = $_vars["table_content_type"][$key];
//		}

//-------------------
		unset( $node["id"] );//do not save node old ID
		$arg = array(
			"xmlNode" => $node,
			"dbNodes" => $_vars["dbData"]["content"]
		);

		$response = saveXMLnode( $arg );
		if( $response){
			$_vars["import"]["total"]++;
		} else {
			$msg = "import warning: node " .$node["title"]. " not saved....";
			$_vars["log"][] = array("message" => $msg, "type" => "warning");
		}

//	}//next

	$msg = "Import ".$_vars["import"]["total"]." content items";
	$msg .= ", num created: " .$_vars["import"]["numCreated"];
	$msg .= ", num updated: " .$_vars["import"]["numUpdated"];
	$_vars["log"][] = array("message" => $msg, "type" => "success");
}//end _importContent()					


function getXMLcontent($params){
	global $_vars;
	
	$p = array(
		"xml" => null,
		//"schema" => false
		"nodeName" => ""
	);
	
	//extend options object $p
	foreach( $params as $key=>$item ){
		$p[ $key ] = $item;
	}//next

	//check input parameters object (only from array $p[key] )
	//foreach( $p as $key=>$value ){
		//if( !empty($params[ $key ]) ){
			//$p[ $key ] = $params[ $key ];
		//}
	//}//next
//echo _logWrap( $p );

	if( !$p["xml"] ){
		$msg = "import error, empty XML ".$p["nodeName"];
		$msg_type = "error";
		$_vars["log"][] = array("message" => $msg, "type" => $msg_type);
		return false;
	}
	
//echo _logWrap( $p["xml"]->node[0] );
/*
//---------------------- convert XML schema
	foreach( $p["schema"] as $item1 => $value1){
//echo _logWrap( $item1 );
		foreach( $value1 as $item2 => $value2){
//echo _logWrap( $item2 );
//echo _logWrap( $value2 );

			$p["schema_array"][$item2]=array(
				"attr" => $this->getXMLattributes($value2),
				"children" => $this->getXMLchildren($value2)
			);
			
		}//next
	}//next
echo _logWrap( $p );
*/

//---------------------- get XML values by schema
	$xmlData = array();
	foreach( $p["xml"] as $item1 => $value1){
//echo _logWrap( $item1 );
			$attributes = getXMLattributes( $value1 );
//echo _logWrap( $attributes );
			if( !empty($attributes) ){
				$xmlData["attributes"] = $attributes;
			}

		foreach( $value1 as $item2 => $value2){
//echo _logWrap( $item2 );
//echo _logWrap( $value2 );
			$attr = getXMLattributes($value2);
			$children = getXMLchildren($value2);
			$xmlData["children"][] = array_merge( $attr, $children );
		}//next
	}//next

	return $xmlData;
}//end getXMLcontent()


//get attributes
function getXMLattributes($node){
	$data = array();
	foreach( $node->attributes() as $attr => $attr_value){
//$msg = $attr. ": ".$attr_value;
//echo _logWrap( $msg );
		$data[$attr] = (string)$attr_value;
	}//next
	return $data;
}//end

//get children nodes
function getXMLchildren($node){
	$data = array();
	foreach( $node as $item => $value){
//$msg = $item. ": ".$value;
//echo _logWrap( $msg );
		$ch_node_value = (string)$value;
//$msg = $item. ": ".strlen($ch_hode_value);
//echo _logWrap( $msg );
		$data[$item] = $ch_node_value;
	}//next
	return $data;
}//end


function saveXMLnode( $params ){
	global $_vars;
	
	$p = array(
		"xmlNode" => null,
		"dbNodes" => null
	);
	
	//extend options object $p
	foreach( $params as $key=>$item ){
		$p[ $key ] = $item;
	}//next
//echo _logWrap( $p );

/*
xmlNode:
Array
(
+[id] => 550
[title] => video1
[created] => 1589594399
[changed] => 1589594399
[body_value] => parent
[type_id] => 7
)
*/

if( isset($p["xmlNode"]["noupdate"]) ){
	$msg = "import: warning, skip import ".$p["xmlNode"]["title"];
	$_vars["log"][] = array("message" => $msg, "type" => "warning");
	return false;
}

//-------------------check format 'created'
//echo _logWrap( $p["xmlNode"]["created"] );
//echo _logWrap( gettype( $p["xmlNode"]["created"] ) );

$test = explode("-", $p["xmlNode"]["created"]);
//echo count( $test );
//echo "<br>";
//echo count( $test ) > 1;
//echo _logWrap( $test );
if( count( $test ) > 1 ){
	$p["xmlNode"]["created"] = strtotime( $p["xmlNode"]["created"] );
//echo $p["xmlNode"]["created"];
}
$test = explode("-", $p["xmlNode"]["changed"]);
if( count( $test ) > 1 ){
	$p["xmlNode"]["changed"] = strtotime( $p["xmlNode"]["changed"] );
}

//------------------ Update exists db node or create new db node
	$update = 0;
/*
	if( !empty($p["dbNodes"]) ){
		for( $n1 = 0; $n1 < count( $p["dbNodes"] ); $n1++){
			$dbNode = $p["dbNodes"][$n1];
//echo _logWrap( $dbNode["title"] );
//echo _logWrap( $p["xmlNode"]["title"] );
			if( $dbNode["created"]  ==  $p["xmlNode"]["created"] ){
				//if( strtoupper( $dbNode["title"] ) ==  strtoupper( $p["xmlNode"]["title"] ) ){
//$msg = "update:". $dbNode["title"] ." = ". $p["xmlNode"]["title"];
//echo _logWrap( $msg );
					$p["xmlNode"]["id"] = $dbNode["id"];
					$update = 1;
					break;
				//}
			} //else {
//$msg = "update warning:". $dbNode["title"] ." != ". $p["xmlNode"]["title"];
//echo _logWrap( $msg, "error" );
			//}
			
		}//next
	}
*/
	if( $update == 1){
		$_vars["import"]["numUpdated"]++;
	} else {
		$_vars["import"]["numCreated"]++;
	}


	$xmlNode = $p["xmlNode"];

	$node = new stdClass();
	$node->nid = 1249;
	$node->uid = 1; // author id 
	$node->type = "page";
	$node->sticky = 0;//?
	$node->language = LANGUAGE_NONE;
	//$node->language = 'ru';
	$node->title = $xmlNode["title"];

	$body_text =  "test5 update";
	$node->body[ $node->language][0]['value'] = $body_text;
	$node->body[ $node->language][0]['summary'] = text_summary($body_text);
	$node->body[ $node->language][0]['format'] = 'filtered_html';

	$node->status = 1;     // public
	//$node->revision = 1;
	//$node->promote = ;

	$node->created = $xmlNode["created"];
	$node->changed = $xmlNode["changed"];

	//$node->path = "test1";
	//$node->log = "added $i node";
	//$node_terms = array();

  	// node_save() does not return a value. It instead populates the $node object. Thus to check if the save was successful, we check the nid.
	node_save($node);
	if( !empty($node->nid) ){
		$_vars["import"]["numCreated"]++;
		$msg =  "create new node, nid: " . $node->nid .", title: ".$node->title;
		$_vars["log"][] = array("message" => $msg, "type" => "success");
		return true;
	} else {
		$msg = "error, not created node, nid: ". $node->nid . ", title: ".$node->title;
		$_vars["log"][] = array("message" => $msg, "type" => "error");
		return false;		
	}
}//end saveXMLnode()



function _logWrap( $msg, $level = "info"){
	// check API type
	$sapi_type = php_sapi_name();
//echo "php_sapi_name: ". $sapi_type;
//echo "<br/>\n";
//echo "type: ". gettype( $msg);
//echo "<br/>\n";

//-------------
	//$runType = "";
	//if ($sapi_type == 'apache' ||
		//$sapi_type == 'apache2handler' || 
		//$sapi_type == 'cli-server'
	//) {
		$runType = "web";
	//}

	if ( $sapi_type == "cli" ) { $runType = "console"; }
	if ( $sapi_type == "cgi" ) { $runType = "console"; }

//-------------
	if( gettype( $msg) === "array" || 
		gettype( $msg) === "object"
	){
			if ( $runType == "web" ) {
				$out = "<pre>".print_r($msg,1)."</pre>";
				return $out;
			} else {
				$out = print_r($msg,1)."\n";
				return $out;
			}
	}

	if( gettype( $msg) !== "string"){
		return false;
	}

//-------------
	switch ($level) {
		case "info":
			if ( $runType == "web" ) {
				return "<div class='alert alert-info'>".$msg."</div>";
			}
			if ( $runType == "console" ) {
				return $msg."\n";
			}
		break;
		
		case "warning":
			if ( $runType == "web" ) {
				return "<div class='alert alert-warning'>".$msg. "</div>";
			}
			if ( $runType == "console" ) {
				return $msg."\n";
			}
		break;
		
		case "danger":
		case "error":
			if ( $runType == "web" ) {
				return "<div class='alert alert-danger'>".$msg. "</div>";
			}
			if ( $runType == "console" ) {
				return $msg."\n";
			}
		break;
		
		case "success":
			if ( $runType == "web" ) {
				return "<div class='alert alert-success'>".$msg. "</div>";
			}
			if ( $runType == "console" ) {
				return $msg."\n";
			}
		break;
		
		default:
			if ( $runType == "web" ) {
				return $msg. "<br/>";
			}
			if ( $runType == "console" ) {
				return $msg."\n";
			}
		break;
	}//end switch

}//end _logWrap()

function check_module( $module_name, $loadedExt){
	if ( !in_array( $module_name, $loadedExt ) ) {
		$msg = "error, PHP module <b>".$module_name."</b>  not loaded...";
		echo _logWrap($msg, "error");
		return false;
	} else {
		//$msg = "PHP module <b>".$module_name."</b> is available...";
		//echo _logWrap($msg, "success");
	//https://www.php.net/manual/ru/function.get-extension-funcs.php
	//echo "list of functions in module <b>".$module_name."</b>:<pre>";
	//print_r(get_extension_funcs( $module_name ));
	//echo "</pre>";
		return true;
	}
}//end check_module()

?>
