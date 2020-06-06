<?php
class ContentLinks {

	private $tableName;

	public $infoSchema = array(
		"content_id" => "integer",
		"parent_id" => "integer"
	);
	
	public $templates = array(
		"content_links_input" => "<input name='parent_id' type='radio' value='{{content_id}}' data-parent-id='{{parent_id}}'>{{title}}<br/>",
		"content_links_checked" => "<input name='parent_id' type='radio' value='{{content_id}}' data-parent-id='{{parent_id}}' checked>{{title}}<br/>"
	);
	
	
	public function __construct(){
		$this->tableName = "content_links";
	}


	public function getList( $params=array() ){
		global $_vars;

		$p = array(
			//"tableName" => $this->tableName,
			"tableName" => "content_links, content",
			//"fields" => array_keys( $this->infoSchema ),
			"fields" => array(
				"content.title",
				"content_id", 
				"parent_id" 
			),
			"query_condition" => "WHERE content.id=content_links.content_id ORDER BY content_links.parent_id"
		);

		//extend options object $p
		foreach( $params as $key=>$item ){
			$p[ $key ] = $item;
		}//next

		$msg = "not found content links...";
		$msg_type = "warning";
		
		$db = DB::getInstance();
		$res = $db->getRecords($p);
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
	

	public function getHierarchyList($params){
		global $_vars;
		
		$p = array(
			//"child_id" => 0 //default value, no parent category
			"content_id" => 0 //default value, all groups without parents
		);
		
		//extend options object $p
		//foreach( $params as $key=>$item ){
			//$p[ $key ] = $item;
		//}//next

		//check input parameters object (only from array $p[key] )
		foreach( $p as $key=>$value ){
			if( !empty($params[ $key ]) ){
				$p[ $key ] = $params[ $key ];
			}
		}//next
		
		$data = false;
		
		//-------------- get node
		$db = DB::getInstance();
		$arg = array(
			"tableName" => "content",
			"fields" => array(
				"id", 
				"title", 
				"created",
				"changed",
				"body_value"
			),
			"query_condition" => "WHERE id=".$p["content_id"]
		);
		
		$res = $db->getRecords($arg);
	//echo _logWrap($data["node"]);
		if( !empty($res) ){
			$data["node"]	= $res[0];
		}
	
		//-------------- get children links
		$msg = "not found children content links, <b>getHierarchyList()</b>, content_id=".$p["content_id"];
		$msg_type = "warning";
		$db = DB::getInstance();
		$arg = array(
			"tableName" => "content_links, content",
			"fields" => array("content_id", "parent_id", "content.title" ),
			"query_condition" => "WHERE content_links.parent_id=".$p["content_id"]." AND content.id=content_links.content_id ORDER BY content.title"
		);
		
		$res = $db->getRecords($arg);
		if( !empty($res) ){
			if( $_vars["display_log"] == true ) {
				$msg = "found ".count($res)." records..";
				$msg_type = "success";
				$_vars["log"][] = array("message" => $msg, "type" => $msg_type);
			}	
			$data["children"]	= $res;
		}
		
		$_vars["log"][] = array("message" => $msg, "type" => $msg_type);
		return $data;
	}//end getHierarchyList()
	
	
	public function save( $params ){
//echo _logWrap($params);
		global $_vars;
		
		$p = array(
			"content_id" => null,
			"parent_id" => 0 //default parent category
		);
		
		//extend options object $p
		//foreach( $params as $key=>$item ){
			//$p[ $key ] = $item;
		//}//next

		//check input parameters object (only from array $p[key] )
		foreach( $p as $key=>$value ){
			if( !empty($params[ $key ]) ){
				$p[ $key ] = $params[ $key ];
			}
		}//next
		
		//remove not requred id (no need, when add category info)
		//if( !$p["content_id"] ){
			//unset( $p["content_id"] );
		//}
		
		$db = DB::getInstance();
		$arg = array(
			"tableName" => $this->tableName,
			"data" => $p
		);
		
		if( !empty( $p["id"] ) ) {
			$arg["query_condition"] = "id=".$p["id"];
		}
//echo _logWrap($arg);

		return $db->saveRecord($arg);
	}//end save()	
	
	
	
	public function get($params){
		global $_vars;

		$p = array(
			"tableName" => $this->tableName,
			"fields" => array_keys( $this->infoSchema ),
			"query_condition" => ""
		);
		
		//extend options object $p
		foreach( $params as $key=>$item ){
			$p[ $key ] = $item;
		}//next

		$msg = "not found content link...";
		$msg_type = "error";
		
		$db = DB::getInstance();
		$res = $db->getRecords($p);
		if( !empty($res) ){
			$msg = "found ".count($res)." records..";
			$msg_type = "success";
			$_vars["log"][] = array("message" => $msg, "type" => $msg_type);
			return $res;
		}
		
		$_vars["log"][] = array("message" => $msg, "type" => $msg_type);
		return false;
	}//end get()

		
	public function remove( $params ){
		global $_vars;
		$p = array(
			"content_id" => false
		);
		
		//extend options object $p
		//foreach( $params as $key=>$item ){
			//$p[ $key ] = $item;
		//}//next
		
		//check input parameters (only from array $p[key] )
		foreach( $p as $key=>$value ){
			if( !empty($params[ $key ]) ){
				$p[ $key ] = $params[ $key ];
			}
		}//next

		if( !$p["content_id"] ){
			return false;
		}
		
		$response = $this->fixChildContentLinks($p);// change parent_id of child nodes for content item to be deleted
		if( $response ){
			$msg = "change parent_id of child nodes for content item to be deleted.";
			$msg_type = "success";
		} else {
			$msg = "was not update content links for children nodes.";
			$msg_type = "warning";
		}
		$_vars["log"][] = array("message" => $msg, "type" => $msg_type);
		

		$db = DB::getInstance();
		$arg = array(
			"tableName" => $this->tableName,
			"query_condition" => "content_id=".$p["content_id"]//WHERE
		);
		
		return $db->removeRecords($arg);

	}//end remove()


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


	//----------------------- 
	// change parent_id of child nodes for content item to be deleted
	//----------------------- 
	private function fixChildContentLinks( $params ){
		//global $_vars;
		$p = array(
			"content_id" => false
		);
		
		//extend options object $p
		foreach( $params as $key=>$item ){
			$p[ $key ] = $item;
		}//next

	
		$sql_query = "UPDATE content_links SET parent_id=(";
		$sql_query .= "SELECT parent_id FROM content_links WHERE content_id=".$p["content_id"].") ";
		$sql_query .= "WHERE parent_id=".$p["content_id"].";";
//echo _logWrap($sql_query);

		$db = DB::getInstance();
		$response = $db->runQuery( $db->dbConnection, $sql_query);
//echo _logWrap( $response );
		if( $response["status"] ){
			return true;
		}
		return false;

	}//end fixChildContentLinks()
	
		
}//end class
?>
