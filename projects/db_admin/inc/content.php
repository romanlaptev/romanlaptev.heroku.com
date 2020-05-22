<?php
class Content {
	
	public $test = "-Test-";
	private $tableName;
	
	public $infoSchema = array(
		"id" => "integer",
		"type_id" => "integer",
		"title" => "string",
		"body_value" => "string",
		"body_format" => "integer",
		"created" => "DATETIME",
		"changed" => "DATETIME"
	);

	public function __construct(){
		$this->tableName = "content";
		//$this->infoSchema = $db->infoSchema["content"];
	}

	public function save( $params ){
		global $_vars;

		$p = array(
			"id" => null,
			"type_id" => 1,//default content type "page"
			"title" => null,
			"body_value" => null,
      "created" => time(),
      "changed" => time(),
			"parent_id" => null//no content link by default
		);
		
		//extend options object $p
		foreach( $params as $key=>$item ){
			$p[ $key ] = $item;
		}//next
		
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
$msg =  "warning, empty <b>body</b>";
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
			$p["body_value"] = trim( $p["body_value"] );
			$p["body_value"] = htmlspecialchars( $p["body_value"] );
		}
//-----------------------

//echo _logWrap($p);

//INSERT INTO content(fields_string) VALUES(values_string);
//UPDATE content SET field1=value,field2=value WHERE id=request_id;
		
		//check input REQUEST parameters, select only from array $infoSchema[key]
		$data = array();
		foreach( $this->infoSchema as $key=>$value ){
			if( !empty($p[ $key ]) ){
				if( $key !== "id" ){
					$data[ $key ] = $p[ $key ];
				}
			}
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

		$msg = "error, not save content item";
		$msg_type = "error";
		
		//$db = new DB();
		$db = DB::getInstance();
		$res = $db->saveRecord($arg);
		if( $res ){
			//$msg = "save record";
			//$msg_type = "success";
			//$_vars["log"][] = array("message" => $msg, "type" => $msg_type);
			
//------------------------------ set content link, parent
			if( !empty($p["parent_id"]) ){
$msg2 = "error, not save content links info.";
$msg2_type = "error";
				$content_links = new ContentLinks();
				if( $content_links ){
					$arg = array(
						"content_id" => $p["id"], 
						"parent_id" => $p["parent_id"]
					);
//---------------------
	if( $p["parent_id"] == "top"){
		$arg["parent_id"] = 0;
	}
//---------------------					
					$save_res = $content_links->save( $arg );
					if( $save_res ){
$msg2 = "save content links info.";
$msg2_type = "success";
$_vars["log"][] = array("message" => $msg2, "type" => $msg2_type);
					}
				}
			} 
			
//$_vars["log"][] = array("message" => "parent_id:".$p["parent_id"] , "type" => "info");
//----------------------------- remove node content link

			if( !$p["id"] ){//skip, if new node
				return $res;
			}
			
			if( isset($p["parent_id"]) &&
						empty($p["parent_id"]) ){
				$content_links = new ContentLinks();
				if( $content_links ){
					$arg = array(
						"content_id" => $p["id"] 
					);
					$_res = $content_links->remove( $arg );
					if( $_res ){
$msg2 = "remove content links info.";
$msg2_type = "warning";
$_vars["log"][] = array("message" => $msg2, "type" => $msg2_type);
					}
				}
			}
			
			return $res;
		}
		$_vars["log"][] = array("message" => $msg, "type" => $msg_type);
		
	}//end save()


	public function getList( $params=array() ){
		global $_vars;

		$p = array(
			"tableName" => "content, content_type",
			//"fields" => array_keys( $db->infoSchema["content"] )
			"fields" => array(
				"content.id", 
				"content.title", 
				"content.created",
				"content_type.name as type"
			),
			"query_condition" => "WHERE content.type_id=content_type.id ORDER BY content_type.id"
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
			"fields" => array("id", "type_id", "title", "body_value", "created", "changed"),
			"query_condition" => "WHERE id=".$p["id"]
		);
		
		$res = $db->getRecords($arg);
		if( !empty($res) ){
			
			//try to get parent category
			$content_links = new ContentLinks();
			if( $content_links ){
				$arg = array(
					"fields" => array("parent_id"),
					"query_condition" => "WHERE content_id=".$p["id"]
				);
				$arr = $content_links->get( $arg );
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



	public function removeItem( $params ){
		//global $_vars;
		global $content_links;
		
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
		if( $response ){
			//remove content links info
			$msg2 = "error, not remove content links info.";
			$msg2_type = "error";
			
			if( $content_links ){
				$arg = array(
					"content_id" => $p["id"] 
				);
				$res = $content_links->remove( $arg );
				if( $res ){
					$msg2 = "remove content links info.";
					$msg2_type = "success";
					$_vars["log"][] = array("message" => $msg2, "type" => $msg2_type);
				}
			}
		}
		return $response;
	}//end removeItem()


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
		
//----------------
		if( !isset($p["data"][0]["parent_id"]) ){
			$p["data"][0]["parent_id"] = "";
		}
		
		$p["data"][0]["content_links"] = "";
		$arg = array(
			"item_parent_id" => $p["data"][0]["parent_id"]
		);
		$p["data"][0]["content_links"] = widget_content_links( $arg );

//----------------
//$_vars["log"][] = array("message" => $p["data"], "type" => "info");
		
		foreach( $p["data"][0] as $field=>$value){
			$p["content"] = str_replace( "{{".$field."}}", $value, $p["content"] );
		}//next
		
		return $p["content"];
	}//end editItem()


	public function addItem($params){
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

//----------------
		$p["data"]["content_links"] = widget_content_links();

//----------------
//$_vars["log"][] = array("message" => $p["data"], "type" => "info");
		
		foreach( $p["data"] as $field=>$value){
			$p["content"] = str_replace( "{{".$field."}}", $value, $p["content"] );
		}//next
		
		return $p["content"];
	}//end addItem()

}//end class
?>
