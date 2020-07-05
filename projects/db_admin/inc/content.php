<?php
class Content {
	
	private	static $instance = null;
	//public $test = "-Test-";
	private $tableName;
	
	public $infoSchema = array(
		"content" => array(
			"id" => "integer",
			"type_id" => "integer",
			"title" => "string",
			"body_value" => "string",
			"body_format" => "integer",
			"status" => "integer",
			"created" => "DATETIME",
			"changed" => "DATETIME"
		),		
		"content_type" => array(
			"id" => "integer",
			"name" => "string"
		),
		"filter_format" => array(
			"id" => "integer",
			"format" => "string",
			"name" => "string"
		)
	);
	
	private $defaultValues = array(
		"id" => null,
		"type_id" => 1,//default content type "page"
		"title" => null,
		"body_value" => null,
		"body_format" => 1, //default filter type "plain text"
		"status" => 1, //default status "publish"
		//"created" => time(),
		//"changed" => time(),//microtime(true),//uniqid()
		"parent_id" => null//no content link by default
	);

	public function __construct(){
		global $_vars;
$msg = "Object of class ".__CLASS__." was created.";
$_vars["log"][] = array("message" => $msg, "type" => "info");
		
		$this->tableName = "content";
		//$this->infoSchema = $db->infoSchema["content"];

		$this->defaultValues["created"] = time();
		$this->defaultValues["changed"] = time();//microtime(true),//uniqid()
	}
/*	
	public static function getInstance() {
		global $_vars;
$msg = "get instance ".__CLASS__;
$_vars["log"][] = array("message" => $msg, "type" => "info");
		if( is_null( self::$instance ) ) {
			self::$instance = new self;
		}
		return self::$instance;
	}
*/
	public function save( $params ){
		global $_vars;

		$p = $this->defaultValues;
		
		//extend options object $p
		//foreach( $params as $key=>$item ){
			//if( !empty($params[$key]) ){
				//$p[ $key ] = $item;
			//}
		//}//next
		
		//check parameters object (only from array $p[key] )
		foreach( $p as $key=>$value ){
			//if( !empty($params[ $key ]) ){
			if( isset($params[ $key ]) ){
				$p[ $key ] = $params[ $key ];
			}
		}//next
//echo _logWrap($p);

		//remove not requred id (no need, when add note)
		//if( !$p["id"] ){
			//unset( $p["id"] );
		//}

//echo "title: ". gettype($p["title"]);
		//if( gettype($p["title"]) !== "string" ){
//$msg =  "error form data: incorrect type for 'Title', type=".gettype($p["title"]);
//$_vars["log"][] = array("message" => $msg, "type" => "error");
			//return false;
		//}

		if( empty($p["title"]) ){
$msg =  "error, empty requred field: <b>title</b>";
$_vars["log"][] = array("message" => $msg, "type" => "error");
			return false;
		}
		
		if( empty($p["body_value"]) ){
$msg =  "warning, empty field: <b>body</b>";
$_vars["log"][] = array("message" => $msg, "type" => "warning");
			//return false;
		}

//-----------------------------	check form, filter values 
		//checkFormData( $p );
		//$p["title"] = trim( $p["title"] );
		//$p["title"] = htmlspecialchars( $p["title"] );
		//$p["title"] = str_replace("\"", "&quot;", $p["title"]);
		//$p["title"] = str_replace("'", "&apos;", $p["title"]);//replace apostrophe
		//$logStr = str_replace("`", "&#39", $logStr);//replace apostrophe
		$p["title"] = _filterFormInputValue( $p["title"] );
		
		if( !empty($p["body_value"]) ){
			$format = $p["body_format"];
			$p["body_value"] = $this->filterBody( $p["body_value"], $format );
		}
//-----------------------
//echo _logWrap($p);
//return false;

//INSERT INTO content(fields_string) VALUES(values_string);
//UPDATE content SET field1=value,field2=value WHERE id=request_id;
		
		//check input REQUEST parameters, select only from array $infoSchema[key]
		$data = array();
		foreach( $this->infoSchema["content"] as $key=>$value ){
			//if( !empty($p[ $key ]) ){
				if( $key !== "id" ){
					$data[ $key ] = $p[ $key ];
				}
			//}
		}//next
		
		//remove id from field list (no need, when add/update note)
		//unset( $data["id"]);
		
		$arg = array(
			"tableName" => $this->tableName,
			"data" => $data
		);
		if( !empty( $p["id"] ) ) {
			$arg["query_condition"] = "id=".$p["id"];
		}
//echo _logWrap($arg);
//return false;

		
		//$db = new DB();
		$db = DB::getInstance();
		$res = $db->saveRecord($arg);

		if( !$res["status"] ){
			$msg = "error, not save content item ".$p["title"];
			$msg_type = "error";
			$_vars["log"][] = array("message" => $msg, "type" => $msg_type);
			return false;
		}
		$msg = "save content item ".$p["title"];
		$msg_type = "success";
		$_vars["log"][] = array("message" => $msg, "type" => $msg_type);

		
//------------------------------ set content link, parent
//echo _logWrap( "parent_id: ".$p["parent_id"] );
//echo _logWrap( "Type parent_id: ".gettype($p["parent_id"]) );

		//if( empty($p["parent_id"]) ){
//echo _logWrap( "empty(not set) parent_id, return...");
			//return $res["status"];
		//}

//--------------------- get content_id after create new node
		if( !empty( $res["last_insert_id"] ) ) {
			$p["id"] = $res["last_insert_id"];
		}

//----------------------------- remove node content link
		if( $p["parent_id"] == "remove_link" ){
			//$_vars["content_links"] = new ContentLinks();
			if( $_vars["content_links"] ){
				$arg = array(
					"content_id" => $p["id"] 
				);
				$_res = $_vars["content_links"]->remove( $arg );
				if( $_res ){
$msg2 = "remove content links info.";
$msg2_type = "warning";
$_vars["log"][] = array("message" => $msg2, "type" => $msg2_type);
				}
			}
			return $res["status"];
		}
		
		//---------------------
		//if( !empty($p["parent_id"]) ){
		//if( isset($p["parent_id"]) ){
//echo _logWrap( "is set parent_id");
			$msg2 = "error, not save content links info.";
			$msg2_type = "error";
			//$_vars["content_links"] = new ContentLinks();
			if( $_vars["content_links"] ){
				
				$arg = array(
					"content_id" => $p["id"], 
					"parent_id" => $p["parent_id"]
				);
				$save_res = $_vars["content_links"]->save( $arg );
				if( $save_res["status"] ){
					$msg2 = "save content links info.";
					$msg2_type = "success";
					$_vars["log"][] = array("message" => $msg2, "type" => $msg2_type);
				}
			}
		//} 
			
//$_vars["log"][] = array("message" => "parent_id:".$p["parent_id"] , "type" => "info");
		return $res["status"];
	}//end save()


/*
	public function rpc_save( $request_data ){
		$jsonStr = "";
		for($n = 0; $n < count($request_data); $n++){
			
			$arg = array(
				"name" => $request_data[$n]["content_type"]
			);
			$request_data[$n]["content_type"] = $this->getContentTypeID($arg);

			$arg = array(
				"format" => $request_data[$n]["body_format"]
			);
			$request_data[$n]["body_format"] = $this->getFilterFormatID($arg);
			
			$eventType = "error";
			$message = "content item ".$request_data[$n]["title"]." NOT saved...";
			$response = $this->save( $request_data[$n] );
			if($response){
				$eventType = "success";
				$message = "content item ".$request_data[$n]["title"]." was saved...";
			}
			$jsonStr .= '{"eventType": "'.$eventType.'", "message": "'.$message.'"}';
			
		}//next
//echo _logWrap( count($request_data) );
//echo _logWrap( $request_data );

		$jsonStr = "[".$jsonStr."]";
		echo $jsonStr;
		exit();
	}//end rpc_save()
*/
	public function rpc_save( $params ){
		global $_vars;

		$p = array(
			"id" => null,
			"title" => null,
			"content_type" => "page",//default content type "page"
			"body_value" => null,
			"body_format" => "plain text",//default filter type "plain text"
			"parent_id" => null
		);
		
		//check parameters object (only from array $p[key] )
		foreach( $p as $key=>$value ){
			if( !empty($params[ $key ]) ){
				$p[ $key ] = $params[ $key ];
			}
		}//next
//echo _logWrap($p);

		if( !$p["title"] ){
			$eventType = "error";
			$msg = "error, empty required field 'title'...";
			$jsonStr = '{"eventType": "'.$eventType.'", "message": "'.$msg.'"}';
			echo $jsonStr;
			exit();
		}
		if( !$p["body_value"] ){
			$eventType = "error";
			$msg = "error, empty required field 'body_value'...";
			$jsonStr = '{"eventType": "'.$eventType.'", "message": "'.$msg.'"}';
			echo $jsonStr;
			exit();
		}

		$jsonStr = "";

		$arg = array(
			"name" => $p["content_type"]
		);
		
		$p["type_id"] = $this->getContentTypeID($arg);
		//unset( $p["content_type"] );
		
		$arg = array(
			"format" => $p["body_format"]
		);
		$p["body_format"] = $this->getFilterFormatID($arg);
			
		$eventType = "error";
		$msg = "content item ".$p["title"]." NOT saved...";
			
		$response = $this->save( $p );
		if($response){
			$eventType = "success";
			$msg = "content item '".$p["title"]."' was saved...";
		}
		
		$arg = array(
			"jsonStr" => '{"eventType": "'.$eventType.'", "message": "'.$msg.'"}'
		);
		$_vars["app"]->sendResponse($arg);
		exit();
		
	}//end rpc_save()


	private function filterBody( $body, $format){
		$body = trim( $body );
		
		if( $format == 3){//full HTML
			//$body = str_replace("&quot;", "\"", $body);
			//$body = str_replace("&amp;", "&", $body);
			//$body = str_replace("&lt;", "<", $body);
			//$body = str_replace("&gt;", ">", $body);
			
//https://www.fileformat.info/info/unicode/char/0c0a/index.htm
//$body = str_replace('', '', $body);//0C0A
$body = str_replace( chr(0x0C), '', $body);//remove Form Feed

			return $body;
		}
		
		if( $format == 4){//PHP code
			return $body;
		}
		
		//plain text
		$body = htmlspecialchars( $body );
		return  $body;
	}//end


	public function getListWithType( $params=array() ){
		$p = array(
			//"tableName" => "content",
			"tableName" => "content, content_type",
			"fields" => array(
				"content.id", 
				"content.title", 
				"content.created",
				"content_type.name as type"
			),
			//"query_condition" => "LEFT JOIN content_type ON content.type_id=content_type.id ORDER BY content_type.id"
			"query_condition" => "WHERE content.type_id=content_type.id ORDER BY content_type.id"
		);
		
		//extend options object $p
		foreach( $params as $key=>$item ){
			$p[ $key ] = $item;
		}//next
		
		return $this->getList($p);
	}//end getListWithType()
		
		
	public function getList( $params=array() ){
		global $_vars;
		$p = array(
			"tableName" => "content",
			"fields" => array_keys( $this->infoSchema["content"] )
		);
		
		//extend options object $p
		foreach( $params as $key=>$item ){
			$p[ $key ] = $item;
		}//next
		
		//$db = new DB();
		$db = DB::getInstance();
//echo _logWrap($p);
		
		$res = $db->getRecords($p);
		
		$msg = "not found content items.";
		$msg_type = "warning";
		if( !empty($res) ){
			if( $_vars["display_log"] == true ) {
				$msg = "found ".count($res)." records..";
				$msg_type = "success";
				$_vars["log"][] = array("message" => $msg, "type" => $msg_type);
			}			
			return $res;
		}
		
		$_vars["log"][] = array("message" => $msg, "type" => $msg_type);
		return false;
	}//end getList()

	public function rpc_list(){
		global $_vars;
		
		$contentArr = $this->getListWithType();
//echo _logWrap($contentArr);
		$arg = array(
			"jsonObj" => $contentArr
		);
		$_vars["app"]->sendResponse($arg);
		exit();
	}//end rpc_list()


	public function rpc_booklist(){
		global $_vars;
		
		$arg = array(
			//"tableName" => "content",
			"tableName" => "content, content_links",
			"fields" => array(
				"content.id", 
				"content.title", 
				"content.created",
				"content.changed"
			),
//"query_condition" => "LEFT JOIN content_links ON content_links.content_id=content.id WHERE content_links.parent_id=0"
"query_condition" => "WHERE content_links.content_id=content.id AND content_links.parent_id=0"
		);
		
		$contentArr = $this->getList($arg);
//echo _logWrap($contentArr);
//exit();
		$arg = array(
			"jsonObj" => $contentArr
		);
		$_vars["app"]->sendResponse($arg);
		exit();
	}//end rpc_booklist()
	
	
	public function getItem($params){
		global $_vars;

		$p = array(
			"id" => false,
			//"type_id" => false,
			//"title" => false
		);
		
		//extend options object $p
		foreach( $params as $key=>$item ){
			$p[ $key ] = $item;
		}//next

		if( !$p["id"] ){
			$msg = "error, invalid content item id...";
			$msg_type = "error";
			$_vars["log"][] = array("message" => $msg, "type" => $msg_type);
			return false;
		}
		
		$msg = "not found content item...";
		$msg_type = "error";
	
		$db = DB::getInstance();
		$arg = array(
			"tableName" => "content",
			//"fields" => array("id", "type_id", "title", "body_value", "created", "changed"),
			"fields" => array_keys( $this->infoSchema["content"] ),
			"query_condition" => "WHERE id=".$p["id"]
		);
		
		$res = $db->getRecords($arg);
		if( !empty($res) ){
			
			//try to get parent category
			//$_vars["content_links"] = new ContentLinks();
			if( $_vars["content_links"] ){
				$arg = array(
					"fields" => array("parent_id"),
					"query_condition" => "WHERE content_id=".$p["id"]
				);
				$arr = $_vars["content_links"]->get( $arg );
				if( $arr ){
					$res[0]["parent_id"] = $arr[0]["parent_id"];
				}
			}
			
			$msg = "found ".count($res)." records..";
			$msg_type = "success";
			$_vars["log"][] = array("message" => $msg, "type" => $msg_type);
			return $res;
		}
		
		$_vars["log"][] = array("message" => $msg, "type" => $msg_type);
		return false;
		
	}//end getItem()

	public function rpc_getItem($params){
		global $_vars;

		$p = array(
			"id" => false
		);
		
		//extend options object $p
		foreach( $params as $key=>$item ){
			$p[ $key ] = $item;
		}//next
//echo _logWrap($p);

		if( !$p["id"] ){
			$eventType = "error";
			$msg = "error, invalid content item id...";
			$jsonStr = '{"eventType": "'.$eventType.'", "message": "'.$msg.'"}';
			echo $jsonStr;
			exit();
		}

		$arr = $this->getItem( $p );
		
		$contentArr = $arr[0];//id is unique values!!! must be always one element return
		
		//get child pages
		//$_vars["content_links"] = new ContentLinks();
		if( $_vars["content_links"] ){
			$arg = array(
				"content_id" => $contentArr["id"] 
			);
			$res = $_vars["content_links"]->getHierarchyList($arg);
//echo _logWrap($res);
			if( isset($res) ){
				if( !empty($res["children"]) ){
					$contentArr["child_nodes"] = $res["children"];
				}
			}
		}
		
//echo _logWrap($contentArr);
		$arg = array(
			"jsonObj" => $contentArr
		);
		$_vars["app"]->sendResponse($arg);
		exit();
		
	}//end rpc_getItem()


	public function removeItem( $params ){
		global $_vars;
		//global $_vars["content_links"];
		
		$p = array(
			"id" => false
		);
		//extend options object $p
		foreach( $params as $key=>$item ){
			$p[ $key ] = $item;
		}//next

		if( !$p["id"] ){
			return false;
		}
		
		//$db = new DB();
		$db = DB::getInstance();
		$arg = array(
			"tableName" => $this->tableName,
			"query_condition" => "id=".$p["id"]//WHERE
		);
		
		$response = $db->removeRecords($arg);
//echo _logWrap($response);
//return false;
		if( $response ){
			
			//remove content links info
			if( $_vars["content_links"] ){
				$arg = array(
					"content_id" => $p["id"] 
				);
				$msg2 = "error, not remove content links info.";
				$msg2_type = "error";
				$res = $_vars["content_links"]->remove( $arg );
				if( $res ){
					$msg2 = "remove content links info.";
					$msg2_type = "success";
					$_vars["log"][] = array("message" => $msg2, "type" => $msg2_type);
				}
			}
		}
		return $response;
	}//end removeItem()
/*
	public function rpc_remove( $request_data ){
		$jsonStr = "";
		
		for($n = 0; $n < count($request_data); $n++){
			if( empty($request_data[$n]["id"]) ){
				continue;
			}
			$response = $this->removeItem( $request_data[$n] );

			$eventType = "error";
			$message = "content item ID: ".$request_data[$n]["id"]." NOT removed...";
			if($response){
				$eventType = "success";
				$message = "content item ID: ".$request_data[$n]["id"]." was removed...";
			}
			$jsonStr .= '{"eventType": "'.$eventType.'", "message": "'.$message.'"}';
		}//next
		
//echo _logWrap( count($request_data) );
//echo _logWrap( $request_data );
		//return false;
		$jsonStr = "[".$jsonStr."]";
		echo $jsonStr;
		exit();
	}//end rpc_remove()
*/

	public function rpc_removeItem($params){
		global $_vars;

		$p = array(
			"id" => false
		);
		
		//extend options object $p
		foreach( $params as $key=>$item ){
			$p[ $key ] = $item;
		}//next
		
//echo _logWrap($p);

		if( !$p["id"] ){
			$eventType = "error";
			$msg = "error, invalid content item id...";
			$jsonStr = '{"eventType": "'.$eventType.'", "message": "'.$msg.'"}';
			echo $jsonStr;
			exit();
		}

		$eventType = "error";
		$msg = "content item ID: ".$p["id"]." NOT removed...";
		
		$response = $this->removeItem( $p );
		if($response){
			$eventType = "success";
			$msg = "content item ID: ".$p["id"]." was removed...";
		}
		
		$arg = array(
			"jsonStr" => '{"eventType": "'.$eventType.'", "message": "'.$msg.'"}'
		);
		$_vars["app"]->sendResponse($arg);
		exit();
		
	}//end rpc_removeItem()


	public function editItem($params){
		global $_vars;

		$p = array(
			"id" => false,
			"tpl_content_path" => false
		);
		
		//extend options object $p
		foreach( $params as $key=>$item ){
			$p[ $key ] = $item;
		}//next

		if( !$p["id"] ){
			$msg = "error, invalid content item id...";
			$msg_type = "error";
			$_vars["log"][] = array("message" => $msg, "type" => $msg_type);
			return false;
		}
		
		if( !$p["tpl_content_path"] ){
			$msg = "error, empty 'tpl_content_path' value, wrong template...";
			$msg_type = "error";
			$_vars["log"][] = array("message" => $msg, "type" => $msg_type);
			return false;
		}
		
		$p["data"] = $this->getItem($params);
		if ( !$p["data"] ){
			return false;
		}
		
		$p["content"] = file_get_contents( $p["tpl_content_path"] );
		
		$p["data"][0]["form-action"] = $_SERVER['SCRIPT_NAME'];
//----------------
		//$p["data"][0]["html_type_options"] = "";
		$p["data"][0]["content_type_select"] = widget_type_id( $p["data"][0]["type_id"] );

		//$p["data"]["content_links"] = widget_content_links();
		
		if( !isset($p["data"][0]["parent_id"]) ){
			$p["data"][0]["parent_id"] = "";
		}
		
		$p["data"][0]["content_links"] = "";
		$arg = array(
			"item_parent_id" => $p["data"][0]["parent_id"]
		);
		$p["data"][0]["content_links"] = widget_content_links( $arg );
		$p["data"][0]["body_format_select"] = widget_body_format($p["data"][0]["body_format"]);
		$p["data"][0]["status_select"] = widget_status($p["data"][0]["status"]);

//----------------
//$_vars["log"][] = array("message" => $p, "type" => "info");
		
		foreach( $p["data"][0] as $field=>$value){
			$p["content"] = str_replace( "{{".$field."}}", $value, $p["content"] );
		}//next
		
		return $p["content"];
	}//end editItem()


	public function addItem( $params=array() ){
		global $_vars;

		$p = array(
			"tpl_content_path" => false,
			"data" => array() 
		);
		
		//extend options object $p
		foreach( $params as $key=>$item ){
			$p[ $key ] = $item;
		}//next

		if( !$p["tpl_content_path"] ){
			$msg = "error, empty 'tpl_content_path' value, wrong template...";
			$msg_type = "error";
			$_vars["log"][] = array("message" => $msg, "type" => $msg_type);
			return false;
		}
		
		if( file_exists( $p["tpl_content_path"] ) ){
			$p["content"] = file_get_contents( $p["tpl_content_path"] );
		} else {
			$msg = "error, not found filepath ".$p["tpl_content_path"].", template not load...";
			$msg_type = "error";
			$_vars["log"][] = array("message" => $msg, "type" => $msg_type);
			return false;
		}

		
		$p["data"]["form-action"] = $_SERVER['SCRIPT_NAME'];

//----------------
		//$p["data"]["html_type_options"] = "";
		//$p["data"]["html_type_options"] = widget_content_type();
		$p["data"]["content_type_select"] = widget_type_id();
		$p["data"]["content_links"] = widget_content_links();
		$p["data"]["body_format_select"] = widget_body_format();

//----------------
//$_vars["log"][] = array("message" => $p["data"], "type" => "info");
		
		foreach( $p["data"] as $field=>$value){
			$p["content"] = str_replace( "{{".$field."}}", $value, $p["content"] );
		}//next
		
		return $p["content"];
	}//end addItem()



	public function clear(){
		global $_vars;

		$sql_query = "DELETE FROM ".$this->tableName.";";
		
		$msg =  "error: database table <b>".$this->tableName."</b> was not cleaned";
		$msg_type = "warning";

		$db = DB::getInstance();
		$arg = array(
			"sql_query" => $sql_query,
			"query_type" => "exec"
		);
		$response = $db->runQuery($arg);
//echo _logWrap( $response );
				
		if( $response["status"] ){
			$msg =  "database table <b>". $this->tableName."</b> was cleared...";
			$msg_type = "success";
		}
		$_vars["log"][] = array("message" => $msg, "type" => $msg_type);
		
	}//end clear()


	public function setContentTypes(){
		global $_vars;
		
		$tableName = "content_type";
		$types = $_vars["config"]["content_types"];
		
		$sql_query_tpl = "INSERT INTO {{tableName}}(name) VALUES('{{type}}'); ";
		$sql_query = "";
		for( $n=0; $n<count($types); $n++){
			
			$query = $sql_query_tpl;
			$query = str_replace( "{{tableName}}", $tableName, $query);
			$query = str_replace( "{{type}}", $types[$n], $query );
			
			$sql_query .= $query;
		}//next
//echo _logWrap( $sql_query );
//return false;
		
		$db = DB::getInstance();
		$arg = array(
			"sql_query" => $sql_query,
			"query_type" => "exec"
		);
		$response = $db->runQuery($arg);
//echo _logWrap( $response );

		if( $response["status"] ){
			$msg = "content_type values added...";
			$_vars["log"][] = array("message" => $msg, "type" => "success");
		} else {
			$msg = "error: not add content_type values";
			$_vars["log"][] = array("message" => $msg, "type" => "error");
		}
		
	}//end setContentTypes()

	public function getContentTypeID( $params=array() ){
		global $_vars;
		
		$p = array(
			"name" => false
		);
		
		//extend options object $p
		foreach( $params as $key=>$item ){
			$p[ $key ] = $item;
		}//next

		if( !$p["name"] ){
			$msg = "error, empty type 'name' value...";
			$msg_type = "error";
			$_vars["log"][] = array("message" => $msg, "type" => $msg_type);
			return false;
		}
		
		$sql_query = "SELECT id FROM content_type WHERE name='".$p["name"]."';";
		$db = DB::getInstance();
		$arg = array(
			"sql_query" => $sql_query
		);
		$response = $db->runQuery($arg);
//echo _logWrap( $response );

		if( $response["status"] ){
			//$msg = "content_type values added...";
			//$_vars["log"][] = array("message" => $msg, "type" => "success");
			return $response["data"][0]["id"];
		} else {
			$msg = "warning: not found content_type ID";
			$_vars["log"][] = array("message" => $msg, "type" => "warning");
			return false;
		}
	}//end getContentTypeID()


	public function setFilterFormats(){
		global $_vars;
		
		$tableName = "filter_format";
		$formats = $_vars["config"]["filter_formats"];
		
		$sql_query_tpl = "INSERT INTO {{tableName}}(format, name) VALUES('{{format}}', '{{name}}'); ";
		$sql_query = "";
		for( $n=0; $n<count( $formats ); $n++){
			
			$query = $sql_query_tpl;
			$query = str_replace( "{{tableName}}", $tableName, $query);
			$query = str_replace( "{{format}}", $formats[$n]["format"], $query );
			$query = str_replace( "{{name}}", $formats[$n]["name"], $query );
			
			$sql_query .= $query;
		}//next
//echo _logWrap( $sql_query );
//return false;
		
		$db = DB::getInstance();
		$arg = array(
			"sql_query" => $sql_query,
			"query_type" => "exec"
		);
		$response = $db->runQuery($arg);
//echo _logWrap( $response );

		if( $response["status"] ){
			$msg = "filter_format values added...";
			$_vars["log"][] = array("message" => $msg, "type" => "success");
		} else {
			$msg = "error: not add filter_format values";
			$_vars["log"][] = array("message" => $msg, "type" => "error");
		}
		
	}//end setFilterFormats()

	public function getFilterFormatID( $params=array() ){
		global $_vars;
		
		$p = array(
			"format" => false
		);
		
		//extend options object $p
		foreach( $params as $key=>$item ){
			$p[ $key ] = $item;
		}//next

		if( !$p["format"] ){
			$msg = "error, empty format value...";
			$msg_type = "error";
			$_vars["log"][] = array("message" => $msg, "type" => $msg_type);
			return false;
		}
		
		$sql_query = "SELECT id FROM filter_format WHERE format='".$p["format"]."';";
		$db = DB::getInstance();
		$arg = array(
			"sql_query" => $sql_query
		);
		$response = $db->runQuery($arg);
//echo _logWrap( $response );

		if( $response["status"] ){
			return $response["data"][0]["id"];
		} else {
			$msg = "warning: not found content format ID";
			$_vars["log"][] = array("message" => $msg, "type" => "warning");
			return false;
		}
	}//end getFilterFormatID()

}//end class
?>
