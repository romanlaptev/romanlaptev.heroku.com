<?php
class App {
	private	static $instance = null;
	
	public function __construct(){
		global $_vars;
$msg = "Object of class ".__CLASS__." was created.";
$_vars["log"][] = array("message" => $msg, "type" => "info");
	}
	public static function getInstance() {
		global $_vars;
$msg = "get instance ".__CLASS__;
$_vars["log"][] = array("message" => $msg, "type" => "info");
		if( is_null( self::$instance ) ) {
			self::$instance = new self;
		}
		return self::$instance;
	}


	public function urlManager( $request = array() ){
		global $_vars;
		
		$content = Content::getInstance();
		$content_links = ContentLinks::getInstance();
		$taxonomy = Taxonomy::getInstance();
		
		if( !empty( $request["q"] ) ){
			switch ( $request["q"] ) {

		//============================= LOGIN
				case "login-form":
					//$_vars["views_params"]["tpl_content"] = $_vars["tpl"]["form_login"];
					$_vars["views_params"]["tpl_content_filename"] = "views/login.tpl.php";
				break;
				case "login":
					$arg = array(
						"login" => $request["login"],
						"password" => $request["password"]
					);
					if ( verifyUser( $arg ) ) {
						$_SESSION['is_auth'] = true;
						$_SESSION['login'] = $request["login"];
					}
				break;

				case "logout":
					//$_vars["views_params"]["is_auth"] = false;
					//$_vars["views_params"]["login"] = null;
					session_destroy();
					header("Location:".$_SERVER["SCRIPT_NAME"]);
				break;
				
		//============================= CONTENT
				case "content/create":
					//$_vars["views_params"]["tpl_content_filename"] = "views/content/add.tpl.php";
					$arg = array(
						"tpl_content_path" => "views/content/add.tpl.php"
					);
					$_vars["views_params"]["content"] = $content->addItem($arg);
				break;
				
				case "content/save":
					$content->save( $request );
					//header("Location:".$_SERVER["SCRIPT_NAME"]);
				break;
				
				case "content/rpc_save":
					$content->rpc_save( $request["request_data"] );
				break;
				
				case "content/list":
					$_vars["views_params"]["content_list"] = $content->getListWithType();
					$_vars["views_params"]["tpl_content_filename"] = "views/content/list.tpl.php";
				break;
				case "content/rpc_list":
					$content->rpc_list();
				break;

				case "content/view":
					$_vars["views_params"]["content_item"] = $content->getItem( $request );
					$_vars["views_params"]["tpl_content_filename"] = "views/content/view.tpl.php";
				break;

				case "content/edit":
					//$_vars["views_params"]["content_item"] = $content->getItem($request);
					////$_vars["views_params"]["tpl_content_filename"] = "views/content/edit.tpl.php";
					//$_vars["views_params"]["tpl_content"] = file_get_contents("views/content/edit.tpl.php");
		////echo _logWrap($_vars["views_params"]["content_item"]);
		//$_vars["log"][] = array("message" => $_vars["views_params"]["content_item"], "type" => "info");
					$arg = $request;
					$arg["tpl_content_path"] = "views/content/edit.tpl.php";
					$_vars["views_params"]["content"] = $content->editItem($arg);
				break;

				case "content/remove":
					$msg =  "error removing content item, id: ".$request["id"];
					$msg_type = "warning";
						
					$response = $content->removeItem( $request );
					if( $response ){
						$msg =  "content item id: ".$request["id"]." was removed...";
						$msg_type = "success";
						//header("Location:".$_SERVER["SCRIPT_NAME"]);
					}
					$_vars["log"][] = array("message" => $msg, "type" => $msg_type);
				break;
				case "content/rpc_remove":
					$content->rpc_remove( $request["request_data"] );
				break;

				case "content/clear":
					$content->clear();
				break;

				case "content/set_values":
					//$content->setContentTypes();
					$content->setFilterFormats();
				break;
				
		//============================= CONTENT LINKS
				case "content-links/list":
					$_vars["views_params"]["content_links"] = $content_links->getList();
					$_vars["views_params"]["hierarchy_list"] = $content_links->getHierarchyList($request);
					$_vars["views_params"]["tpl_content_filename"] = "views/content_links/list.tpl.php";
				break;
				
				case "content-links/remove":
					$msg =  "error remove content links info, content_id: ".$request["content_id"];
					$msg_type = "error";
					$response = $content_links->remove( $request );
					if( $response ){
						$msg = "remove content links info.";
						$msg_type = "success";
						$_vars["log"][] = array("message" => $msg, "type" => $msg_type);
					}
				break;

				case "content-links/clear":
					$content_links->clear();
				break;
				
		//============================= TAXONOMY
				case "taxonomy/list":
					$_vars["views_params"]["tag_groups"] = $taxonomy->getTagGroup();
					$_vars["views_params"]["tag_list"] = $taxonomy->getTagList();
					$_vars["views_params"]["tpl_content_filename"] = "views/taxonomy/list.tpl.php";
				break;
				
				case "tag-group/create":
					$_vars["views_params"]["tpl_content_filename"] = "views/taxonomy/term_group_create.tpl.php";
				break;
				
				case "tag-group/save":
					$response = $taxonomy->saveTermGroup( $request );
					if( !$response ){
		$msg = "error,  could not save term group.";
		$_vars["log"][] = array("message" => $msg, "type" => "error");
					} else {
		$msg = "save new term group.";
		$_vars["log"][] = array("message" => $msg, "type" => "success");
					}
				break;

				case "tag-group/list":
					$_vars["views_params"]["term_group"] = $taxonomy->getTagGroup( $request );
					$_vars["views_params"]["tpl_content_filename"] = "views/taxonomy/term_group_list.tpl.php";
				break;

				case "tag-group/edit":
					$_vars["views_params"]["term_group"] = $taxonomy->getTagGroup( $request );
					$_vars["views_params"]["tpl_content_filename"] = "views/taxonomy/term_group_edit.tpl.php";
				break;
				
				case "tag-group/remove":
					$msg =  "error removing term group, id: ".$request["id"];
					$msg_type = "warning";
						
					$response = $taxonomy->removeTermGroup( $request );
					if( $response ){
						$msg =  "term group.id ".$request["id"]." was removed...";
						$msg_type = "success";
					}
					$_vars["log"][] = array("message" => $msg, "type" => $msg_type);
				break;
				
				case "taxonomy/term-create":
					$_vars["views_params"]["tpl_content_filename"] = "views/taxonomy/term_create.tpl.php";
				break;
				
				case "taxonomy/term-edit":
					$_vars["views_params"]["term"] = $taxonomy->getTerm( $request );
					$_vars["views_params"]["tpl_content_filename"] = "views/taxonomy/term_edit.tpl.php";
				break;

				case "taxonomy/term-save":
					$response = $taxonomy->saveTerm( $request );
					if( !$response ){
		$msg = "error,  could not save term ".$request["term"];
		$_vars["log"][] = array("message" => $msg, "type" => "error");
					} else {
		$msg = "ok,  save new term ".$request["term"];
		$_vars["log"][] = array("message" => $msg, "type" => "success");
					}
				break;
				
				case "taxonomy/term-remove":
					$msg =  "error removing tag, id: ".$request["id"];
					$msg_type = "warning";
						
					$response = $taxonomy->removeTerm( $request );
					if( $response ){
						$msg =  "ok, term.id: ".$request["id"]." was removed...";
						$msg_type = "success";
					}
					$_vars["log"][] = array("message" => $msg, "type" => $msg_type);
				break;
				
		//======================================= IMPORT/EXPORT
				case "exchange":
					$_vars["views_params"]["tpl_content_filename"] = "views/exchange.tpl.php";
				break;
				
				case "form-import":
					$arg = array();
					$arg["tpl_content_path"] = "views/import.tpl.php";
					$_vars["views_params"]["content"] = $this->importForm($arg);
				break;
				case "import":
					require_once dirname(__FILE__)."/exchange/import.php";
				break;

				case "form-export":
					$arg = array();
					$arg["tpl_content_path"] = "views/export.tpl.php";
					$_vars["views_params"]["content"] = $this->exportForm($arg);
				break;
				case "export":
					require_once dirname(__FILE__)."/exchange/export.php";
				break;

				default:
					$_vars["views_params"]["tpl_content_filename"] = "views/home.tpl.php";
			}// end switch
		}


		//Remote Procedure Call, process remote request
		if( !empty( $request["rpc_request"] ) ){
			$response = $this->rpc_request_handler( $request["rpc_request"] );
		}

	}//end urlManager()


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
		$content_type = "";//export content any types
		//if(!empty($_vars["config"]["export"]["content_type"]) ){
			//$content_type = $_vars["config"]["export"]["content_type"];
		//}
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

			$attr = $this->getXMLattributes($value);
//echo _logWrap( "attr: " );
//echo _logWrap( $attr );
			if( !empty($attr) ){
				foreach( $attr as $key=>$value){
					$data[$key] = $value;
				}//next
			}
			
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
		if( !empty($p["xmlNode"]["created"]) ){
			$test = explode("-", $p["xmlNode"]["created"]);
		//echo count( $test );
		//echo "<br>";
		//echo count( $test ) > 1;
		//echo _logWrap( $test );
			if( count( $test ) > 1 ){
				$p["xmlNode"]["created"] = strtotime( $p["xmlNode"]["created"] );
		//echo $p["xmlNode"]["created"];
			}
		}	
		if( !empty($p["xmlNode"]["changed"]) ){
			$test = explode("-", $p["xmlNode"]["changed"]);
			if( count( $test ) > 1 ){
				$p["xmlNode"]["changed"] = strtotime( $p["xmlNode"]["changed"] );
			}
		}	

	//------------------ Update exists db node or create new db node
		$update = 0;
if( !empty($p["xmlNode"]["created"]) ){
		if( !empty($p["dbNodes"]) ){
			for( $n1 = 0; $n1 < count( $p["dbNodes"] ); $n1++){
				$dbNode = $p["dbNodes"][$n1];
//echo _logWrap( $dbNode["title"] );
//echo _logWrap( $p["xmlNode"]["title"] );
				if( $dbNode["created"]  ==  $p["xmlNode"]["created"] ){
					
					$dbTitle_hash = hash('ripemd160', $dbNode["title"]);
					$xmlTitle_hash = hash('ripemd160', $p["xmlNode"]["title"]);
					if( $dbTitle_hash ==  $xmlTitle_hash ){
					//if( strtoupper( $dbNode["title"] ) ==  strtoupper( $p["xmlNode"]["title"] ) ){
$msg = "import update: <small>". $dbNode["title"] ." = ". $p["xmlNode"]["title"]."</small>";
$_vars["log"][] = array("message" => $msg, "type" => "info");
					}
					
					$p["xmlNode"]["id"] = $dbNode["id"];
					$update = 1;
					break;
				} //else {
//$msg = "update warning:". $dbNode["title"] ." != ". $p["xmlNode"]["title"];
//echo _logWrap( $msg, "error" );
				//}
				
			}//next
		}
}	
/*
if( empty($p["xmlNode"]["created"]) ){
		if( !empty($p["dbNodes"]) ){
			for( $n1 = 0; $n1 < count( $p["dbNodes"] ); $n1++){
				$dbNode = $p["dbNodes"][$n1];
//echo _logWrap( $dbNode["title"] );
//echo _logWrap( $p["xmlNode"]["title"] );
				//if( strtoupper( $dbNode["title"] ) ==  strtoupper( $p["xmlNode"]["title"] ) ){
				
				$dbTitle_hash = hash('ripemd160', $dbNode["title"]);
				$xmlTitle_hash = hash('ripemd160', $p["xmlNode"]["title"]);
				if( $dbTitle_hash ==  $xmlTitle_hash ){
//$msg = "update:". $dbNode["title"] ." = ". $p["xmlNode"]["title"];
//echo _logWrap( $msg );
					$p["xmlNode"]["id"] = $dbNode["id"];
					$update = 1;
					break;
				}
				
			}//next
		}
}	
*/
		if( $update == 1){
			$_vars["import"]["numUpdated"]++;
		} else {
			$_vars["import"]["numCreated"]++;
		}
		
		$response = $content->save( $p["xmlNode"] );
		if( !$response ){
$msg = "import: error, not save content item ".$p["xmlNode"]["title"].", created: ".$p["xmlNode"]["created"];
$_vars["log"][] = array("message" => $msg, "type" => "error");
			return false;		
		} else {
$msg = "import: save content item ".$p["xmlNode"]["title"].", created: ".$p["xmlNode"]["created"];
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
		if( !$response["status"] ){
			$msg = "import: error, could not save content_link item ".$p["xmlNode"]["content_id"];
			$_vars["log"][] = array("message" => $msg, "type" => "error");
			return false;		
		} else {
			//$msg = "import: save content_link item ".$p["xmlNode"]["content_id"];
			//$_vars["log"][] = array("message" => $msg, "type" => "success");
			return true;
		}

	}//end saveXMLcontent_link()
	
	
//---------------------------- RPC, Remote Procedure Call
	public function rpc_request_handler( $rpc_request ){
		global $_vars;
//echo _logWrap( $rpc_request );
		//$p = array(
			//"format" => "json",//xml
			//"request_data" => array()
		//);
		
		////extend options object $p
		//foreach( $params as $key=>$item ){
			//if( !empty($params[$key]) ){
				//$p[ $key ] = $item;
			//}
		//}//next

		//http://php.net/manual/ru/function.json-encode.php
		//PHP 5 >= 5.2.0
		if ( !function_exists("json_decode") ){
$msg = "server error, not support function <b>json_decode(), required PHP >= 5.2.0, server PHP == ".phpversion();
			$msg_type = "error";
			$_vars["log"][] = array("message" => $msg, "type" => $msg_type);
			return false;
		}	
		//PHP 5 >= 5.3.0
		if ( !function_exists("json_last_error") ){ 
$msg = "server error, not support function <b>json_last_error()</b>, required PHP >= 5.3.0, server PHP == ".phpversion();
			$msg_type = "error";
			$_vars["log"][] = array("message" => $msg, "type" => $msg_type);
			return false;
		}	
		
		//https://www.php.net/manual/ru/function.json-decode.php
		$request_arr = json_decode($rpc_request, true);
		
		$msg_type = "error";
		switch ( json_last_error() ) {
			case JSON_ERROR_NONE:
				$msg_type = "success";
				$msg = "No errors";
if( empty($request_arr) ){
	$msg = "server warning, empty <b>RPC request</b>, no call remote procedure...";
	$msg_type = "warning";
	$_vars["log"][] = array("message" => $msg, "type" => $msg_type);
	return false;
}
if( empty($request_arr["action"]) ){
	$msg = "server warning, empty <b>RPC request action</b>, no call remote procedure...";
	$msg_type = "warning";
	$_vars["log"][] = array("message" => $msg, "type" => $msg_type);
	return false;
}
//echo _logWrap( $request_arr );
				$arg = array(
					"q" => $request_arr["action"]
				);
				if( !empty($request_arr["request_data"]) ){
					$arg["request_data"] = $request_arr["request_data"];
				}
				$this->urlManager($arg);
			break;
			
			case JSON_ERROR_DEPTH:
				$msg = "Maximum stack depth exceeded";
			break;
			case JSON_ERROR_STATE_MISMATCH:
				$msg = "Underflow or the modes mismatch";
			break;
			case JSON_ERROR_CTRL_CHAR:
				$msg = "Unexpected control character found";
			break;
			case JSON_ERROR_SYNTAX:
				$msg = "Syntax error, wrong formed JSON";
			break;
			case JSON_ERROR_UTF8:
				$msg = "Malformed UTF-8 characters, possibly incorrectly encoded";
			break;
			default:
				$msg = "Unknown error";
			break;
		}
		$_vars["log"][] = array("message" => $msg, "type" => $msg_type);
		
		return false;
		
	}//end rpc_request_handler()
	
}//end class
?>
