<?php
class Taxonomy {

	public function __construct(){}

	public function getGroupList(){
		global $_vars;
		
		$msg = "not found <b>term groups</b>";
		$msg_type = "warning";
		
		$db = DB::getInstance();
		$arg = array(
			"tableName" => "taxonomy_groups",
			//"fields" => array_keys( $db->infoSchema["content"] )
			"fields" => array("id", "name"),
			//"query_condition" => "WHERE type_id=2 ORDER BY title"
		);
		$res = $db->getRecords($arg);
		if( !empty($res) ){
			$msg = "found ".count($res)." records..";
			$msg_type = "success";
			$_vars["log"][] = array("message" => $msg, "type" => $msg_type);
			return $res;
		}
		$_vars["log"][] = array("message" => $msg, "type" => $msg_type);
		return false;
	}//end getGroupList()


	public function getTagList(){
		global $_vars;
		
		$msg = "not found <b>termins</b>";
		$msg_type = "warning";
		
		$db = DB::getInstance();
		$arg = array(
			"tableName" => "taxonomy_term_data",
			//"fields" => array_keys( $this->infoSchema["taxonomy_term_data"] )
			"fields" => array("id", "term_group_id", "name", "parent_id")
		);
		$res = $db->getRecords($arg);
		
		if( !empty($res) ){
			$msg = "found ".count($res)." records..";
			$msg_type = "success";
			$_vars["log"][] = array("message" => $msg, "type" => $msg_type);
			return $res;
		}
		$_vars["log"][] = array("message" => $msg, "type" => $msg_type);
		return false;
	}//end getTagList()


	public function getTagLinks(){
		global $_vars;
		
		$msg = "not found <b>tag links</b>";
		$msg_type = "warning";
		
		$db = DB::getInstance();
		$arg = array(
			"tableName" => "taxonomy_index",
			//"fields" => array_keys( $this->infoSchema["taxonomy_index"] )
			"fields" => array("content_id", "term_id"),
		);
		$res = $db->getRecords($arg);
		if( !empty($res) ){
			$msg = "found ".count($res)." records..";
			$msg_type = "success";
			$_vars["log"][] = array("message" => $msg, "type" => $msg_type);
			return $res;
		}
		$_vars["log"][] = array("message" => $msg, "type" => $msg_type);
		return false;
	}//end getTagLinks()

	public function saveTermGroup( $params ){
		$p = array(
			"id" => null,
			"name" => null
		);
		//check input parameters object (only from array $p[key] )
		$_search_keys = array();
		foreach( $p as $key=>$value ){
			if( !empty($params[ $key ]) ){
			//if( $params[ $key ] !== false ){
				$p[ $key ] = $params[ $key ];
			}
		}//next
		
		//remove not requred id (no need, where add group)
		if( !$p["id"] ){
			unset( $p["id"] );
		}
		
		if( empty($p["name"]) ){
$msg =  "error, empty requred field: term group <b>name</b>";
$_vars["log"][] = array("message" => $msg, "type" => "error");
			return false;
		}
		
//-----------------------------	check form, filter values 
		$p["name"] = _filterFormValue( $p["name"] );
//-----------------------

//echo _logWrap($p);
//return false;
		$db = DB::getInstance();
		$arg = array(
			"tableName" => "taxonomy_groups",
			"data" => $p
		);
		
		if( !empty( $p["id"] ) ) {
			$arg["query_condition"] = "id=".$p["id"];
		}
		
		return $db->saveRecord($arg);
	}//end saveTermGroup()


	
	public function getTermGroup( $params ){
//"tableName" => "taxonomy_groups",
//"tableName" => "taxonomy_term_data",
		global $_vars;
		
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

		$db = DB::getInstance();
		$arg = array(
			"tableName" => "taxonomy_groups",
			"fields" => array("id", "name"),
			"query_condition" => "WHERE id=".$p["id"]
		);
		
		$msg = "not found <b>term group </b> by id: ".$p["id"];
		$msg_type = "warning";

		$res = $db->getRecords($arg);
		
		if( !empty($res) ){
			$msg = "ok, found ".count($res)." records..";
			$msg_type = "success";
			$_vars["log"][] = array("message" => $msg, "type" => $msg_type);
			
			if( $params["q"] == "term-group/list"){
				$res["terms"] = $this->getTermGroupElements( $p["id"] );
			}
			
			return $res;
		}
		$_vars["log"][] = array("message" => $msg, "type" => $msg_type);
		return false;

	}//end getTermGroup()


	public function getTermGroupElements( $id ){
		
		if( !$id ){
			return false;
		}
		
		return false;
	}//end getTermGroupElements()

	
	
	public function removeTermGroup( $params ){
//"tableName" => "taxonomy_taxonomy_groups",
//"tableName" => "taxonomy_term_data",
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

		$db = DB::getInstance();
		$arg = array(
			"tableName" => "taxonomy_groups",
			"query_condition" => "id=".$p["id"]//WHERE
		);
		
		//removeChilrenElements();
		
		return $db->removeRecords($arg);
	}//end removeTermGroup()

	public function saveTerm( $params ){
//"tableName" => "taxonomy_term_data",
/*		
		global $_vars;

		$p = array(
			"id" => null,
			"category_id" => 0,//default category
			"type_id" => 2,//default content type "note"
			"title" => null,
			"body_value" => null
		);
		
		//check input parameters object (only from array $p[key] )
		$_search_keys = array();
		foreach( $p as $key=>$value ){
			if( !empty($params[ $key ]) ){
			//if( $params[ $key ] !== false ){
				$p[ $key ] = $params[ $key ];
			}
		}//next
		
		//remove not requred id (no need, where add note)
		if( !$p["id"] ){
			unset( $p["id"] );
		}

		if( empty($p["title"]) ){
$msg =  "error, empty requred <b>note title</b>";
$_vars["log"][] = array("message" => $msg, "type" => "error");
			return false;
		}
		
		if( empty($p["body_value"]) ){
$msg =  "error, empty requred <b>note text</b>";
$_vars["log"][] = array("message" => $msg, "type" => "error");
			return false;
		}

//-----------------------------	check form, filter values 
		//checkFormData( $p );
		$p["title"] = trim( $p["title"] );
		$p["title"] = htmlspecialchars( $p["title"] );
		$p["title"] = str_replace("\"", "&quot;", $p["title"]);
		$p["title"] = str_replace("'", "&apos;", $p["title"]);//replace apostrophe
		//$logStr = str_replace("`", "&#39", $logStr);//replace apostrophe
		
		$p["body_value"] = trim( $p["body_value"] );
		$p["body_value"] = htmlspecialchars( $p["body_value"] );
//$textMessage = addslashes( htmlspecialchars($_REQUEST["text_message"]) );
//$textbox=$vars['textbox']; 
//	$textbox = stripslashes($textbox);
//-----------------------

//echo _logWrap($p);
//return false;

//INSERT INTO content(fields_string) VALUES(values_string);
//UPDATE content SET field1=value,field2=value WHERE id=request_id;
		
		//$db = new DB();
		$db = DB::getInstance();
		$arg = array(
			"tableName" => "content",
			"data" => $p
		);
		
		if( !empty( $p["id"] ) ) {
			//$arg["search_key"] = array( "name" => "id", "value" => $p["id"] );
			$arg["query_condition"] = "id=".$p["id"];
		}
echo _logWrap($arg);
		
		return $db->saveRecord($arg);
*/
	}//end saveTerm()

	public function getTerm($params){
//"tableName" => "taxonomy_index",
//"tableName" => "content",
		
/*		
		global $_vars;

		$p = array(
			"id" => false,
			"category_id" => false,
			"type_id" => false,
			"title" => false
		);
		
		//form search keys
		//check input parameters object (only 'id' or 'title')
		$_search_keys = array();
		foreach( $p as $key=>$value ){
			if( !empty($params[ $key ]) ){
				$_search_keys[ $key ] = $params[ $key ];
			}
		}//next
//echo _logWrap($_search_keys);

		$msg = "not found note...";
		$msg_type = "error";
		
		if( empty($_search_keys) ){
			$_vars["log"][] = array("message" => $msg, "type" => $msg_type);
			return false;
		}
		
		$db = DB::getInstance();
//echo _logWrap( $db->dbSchema["node"] );
		$arg = array(
			"tableName" => "content",
			//"fields" => array("id", "category_id", "title"),
			"fields" => array_keys( $db->infoSchema["content"] ),
			"search_keys" => $_search_keys
		);
		$note = $db->getRecords($arg);
		if( !empty($note) ){
			$msg = "ok, found ".count($note)." note..";
			$msg_type = "success";
			$_vars["log"][] = array("message" => $msg, "type" => $msg_type);
			return $note;
		}
		
		$_vars["log"][] = array("message" => $msg, "type" => $msg_type);
		return false;
*/
	}//end getTerm()

	public function removeTerm( $params ){
//"tableName" => "taxonomy_term_data",
//"tableName" => "taxonomy_index",
/*		
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
			"tableName" => "content",
			"search_keys" => array(
				"id" => $p["id"]//,
				//"type" => "'note'"
			)
		);
		
		return $db->removeRecords($arg);
*/
	}//end removeTerm()
		


}//end class
?>
