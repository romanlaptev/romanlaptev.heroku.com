<?php
class App {
	
	public function __construct(){}

	public function exportForm($params){
		global $_vars;
		
		$p = array(
			"tpl_content_path" => false,
			"data" => array() 
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
		$p["data"]["filename"] = $_vars["config"]["export"]["filename"];
		
//----------------
		//$p["data"]["content_type"] = $_vars["config"]["export"]["content_type"];
		$content_type = $_vars["config"]["export"]["content_type"];
		$p["data"]["content_type_select"] = widget_content_type($content_type);
		
		$p["data"]["type"] = "xml";
//$_vars["log"][] = array("message" => $p["data"], "type" => "info");
		
		foreach( $p["data"] as $field=>$value){
			$p["content"] = str_replace( "{{".$field."}}", $value, $p["content"] );
		}//next
		
		return $p["content"];
	}//end exportForm()
	

	public function importForm($params){
		global $_vars;
		
		$p = array(
			"tpl_content_path" => false,
			"data" => array() 
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
		$p["data"]["filename"] = $_vars["config"]["export"]["filePath"];
		
		foreach( $p["data"] as $field=>$value){
			$p["content"] = str_replace( "{{".$field."}}", $value, $p["content"] );
		}//next
		
		return $p["content"];
	}//end importForm()


	public function getXMLcontent($params){
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
				$attributes = $this->getXMLattributes( $value1 );
//echo _logWrap( $attributes );
				if( !empty($attributes) ){
					$xmlData["attributes"] = $attributes;
				}

			foreach( $value1 as $item2 => $value2){
//echo _logWrap( $item2 );
//echo _logWrap( $value2 );
				$attr = $this->getXMLattributes($value2);
				$children = $this->getXMLchildren($value2);
				$xmlData["children"][] = array_merge( $attr, $children );
			}//next
		}//next

		return $xmlData;
	}//end getXMLcontent()


	//get attributes
	private function getXMLattributes($node){
		$data = array();
		foreach( $node->attributes() as $attr => $attr_value){
//$msg = $attr. ": ".$attr_value;
//echo _logWrap( $msg );
			$data[$attr] = (string)$attr_value;
		}//next
		return $data;
	}//end
	
	//get children nodes
	private function getXMLchildren($node){
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
	

	public function saveXMLnode( $params ){
		global $_vars;
		global $content;
		
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

		if( $update == 1){
			$_vars["import"]["numUpdated"]++;
		} else {
			$_vars["import"]["numCreated"]++;
		}
		
		$response = $content->save( $p["xmlNode"] );
		if( !$response ){
			$msg = "import: error, could not save content item ".$p["xmlNode"]["title"];
			$_vars["log"][] = array("message" => $msg, "type" => "error");
			return false;		
		} else {
			$msg = "import: save content item ".$p["xmlNode"]["title"];
			$_vars["log"][] = array("message" => $msg, "type" => "success");
			return true;
		}

	}//end saveXMLnode()


	public function saveXMLcontent_link( $params ){
		global $_vars;
		global $content_links;
		
		$p = array(
			"xmlNode" => null,
			//"dbNodes" => null
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
	[content_id] => 11
	[parent_id] => 10
	[parent_id_old] => 550
	[content_id_old] => 563)
*/
	
	//------------------ Update exists db node or create new db node
		$response = $content_links->save( $p["xmlNode"] );
		if( !$response ){
			$msg = "import: error, could not save content_link item ".$p["xmlNode"]["content_id"];
			$_vars["log"][] = array("message" => $msg, "type" => "error");
			return false;		
		} else {
			//$msg = "import: save content_link item ".$p["xmlNode"]["content_id"];
			//$_vars["log"][] = array("message" => $msg, "type" => "success");
			return true;
		}

	}//end saveXMLcontent_link()
	
}//end class
?>
