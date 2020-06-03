<?php
error_reporting(E_ALL|E_STRICT);
ini_set('display_errors', 1);

//ini_set("session.gc_maxlifetime", 60 );//86400 sec
//ini_set("session.cookie_lifetime", 0);
//session_set_cookie_params(0);

session_start();

//echo "test:<pre>";
//print_r($_REQUEST);
//print_r($_SESSION);
//print_r ($_COOKIE);
//print_r($_SERVER);
//echo "</pre>";
//echo session_save_path();

require_once dirname(__FILE__)."/inc/functions.php";
require_once dirname(__FILE__)."/inc/db.php";
require_once dirname(__FILE__)."/inc/content.php";
require_once dirname(__FILE__)."/inc/content_links.php";
require_once dirname(__FILE__)."/inc/taxonomy.php";
require_once dirname(__FILE__)."/inc/app.php";

$_vars=array();
$_vars["config"] = require_once("config.php");
$_vars["db_schema"]["SQLITE"] = file_get_contents("data/db_schema_sqlite.sql" );
$_vars["display_log"] = true;

//$_vars["tpl"]["form_login"] = file_get_contents( "views/login.tpl.php" );
//$_vars["tpl"]["form_add-note"] = file_get_contents( "views/add-note.tpl.php" );
//$_vars["tpl"]["form_list-note"] = file_get_contents( "views/list-note.tpl.php" );

//echo _logWrap($_vars);
//exit();
$content = new Content();
$content_links = new ContentLinks();
$taxonomy = new Taxonomy();
$app = new App();
$views_params = array();

$_vars["log"][] = array("message" => $_REQUEST, "type" => "info");
$_vars["request"] = $_REQUEST;

if( !empty( $_vars["request"]["q"] ) ){
	switch ( $_vars["request"]["q"] ) {

//============================= LOGIN
		case "login-form":
			//$views_params["tpl_content"] = $_vars["tpl"]["form_login"];
			$views_params["tpl_content_filename"] = "views/login.tpl.php";
		break;
		case "login":
			$arg = array(
				"login" => $_REQUEST["login"],
				"password" => $_REQUEST["password"]
			);
			if ( verifyUser( $arg ) ) {
				$_SESSION['is_auth'] = true;
				$_SESSION['login'] = $_REQUEST["login"];
			}
		break;

		case "logout":
			//$views_params["is_auth"] = false;
			//$views_params["login"] = null;
			session_destroy();
			header("Location:".$_SERVER["SCRIPT_NAME"]);
		break;
		
//============================= CONTENT
		case "content/create":
			//$views_params["tpl_content_filename"] = "views/content/add.tpl.php";
			$arg = array();
			$arg["tpl_content_path"] = "views/content/add.tpl.php";
			$views_params["content"] = $content->addItem($arg);
		break;
		
		case "content/save":
			$response = $content->save( $_vars["request"] );
			if( !$response ){
$msg = "error,  could not save content item...";
$_vars["log"][] = array("message" => $msg, "type" => "error");
			} else {
$msg = "save content item...";
$_vars["log"][] = array("message" => $msg, "type" => "success");
			}
			//header("Location:".$_SERVER["SCRIPT_NAME"]);
		break;
		
		case "content/list":
			$views_params["content_list"] = $content->getListWithType();
			$views_params["tpl_content_filename"] = "views/content/list.tpl.php";
		break;

		case "content/view":
			$views_params["content_item"] = $content->getItem( $_vars["request"] );
			$views_params["tpl_content_filename"] = "views/content/view.tpl.php";
		break;

		case "content/edit":
			//$views_params["content_item"] = $content->getItem($_vars["request"]);
			////$views_params["tpl_content_filename"] = "views/content/edit.tpl.php";
			//$views_params["tpl_content"] = file_get_contents("views/content/edit.tpl.php");
////echo _logWrap($views_params["content_item"]);
//$_vars["log"][] = array("message" => $views_params["content_item"], "type" => "info");
			$arg = $_vars["request"];
			$arg["tpl_content_path"] = "views/content/edit.tpl.php";
			$views_params["content"] = $content->editItem($arg);
		break;

		case "content/remove":
			$msg =  "error removing content item, id: ".$_vars["request"]["id"];
			$msg_type = "warning";
				
			$response = $content->removeItem( $_vars["request"] );
			if( $response ){
				$msg =  "content item id: ".$_vars["request"]["id"]." was removed...";
				$msg_type = "success";
				//header("Location:".$_SERVER["SCRIPT_NAME"]);
			}
			$_vars["log"][] = array("message" => $msg, "type" => $msg_type);
		break;

		case "content/clear":
			$msg =  "error clear content.";
			$msg_type = "warning";
				
			$response = $content->clear();
			if( $response ){
				$msg =  "database table content was clear...";
				$msg_type = "success";
			}
			$_vars["log"][] = array("message" => $msg, "type" => $msg_type);
		break;
		
//============================= CONTENT LINKS
		case "content-links/list":
			$views_params["content_links"] = $content_links->getList();
			$views_params["hierarchy_list"] = $content_links->getHierarchyList($_vars["request"]);
			$views_params["tpl_content_filename"] = "views/content_links/list.tpl.php";
		break;
		
		case "content-links/remove":
			$msg =  "error remove content links info, content_id: ".$_vars["request"]["content_id"];
			$msg_type = "error";
			$response = $content_links->remove( $_vars["request"] );
			if( $response ){
				$msg = "remove content links info.";
				$msg_type = "success";
				$_vars["log"][] = array("message" => $msg, "type" => $msg_type);
			}
		break;

		case "content-links/clear":
			$msg =  "error clear content_links";
			$msg_type = "warning";
				
			$response = $content_links->clear();
			if( $response ){
				$msg =  "database table content_links was clear...";
				$msg_type = "success";
			}
			$_vars["log"][] = array("message" => $msg, "type" => $msg_type);
		break;
		
//============================= TAXONOMY
		case "taxonomy/list":
			$views_params["tag_groups"] = $taxonomy->getTagGroup();
			$views_params["tag_list"] = $taxonomy->getTagList();
			$views_params["tpl_content_filename"] = "views/taxonomy/list.tpl.php";
		break;
		
		case "tag-group/create":
			$views_params["tpl_content_filename"] = "views/taxonomy/term_group_create.tpl.php";
		break;
		
		case "tag-group/save":
			$response = $taxonomy->saveTermGroup( $_vars["request"] );
			if( !$response ){
$msg = "error,  could not save term group.";
$_vars["log"][] = array("message" => $msg, "type" => "error");
			} else {
$msg = "save new term group.";
$_vars["log"][] = array("message" => $msg, "type" => "success");
			}
		break;

		case "tag-group/list":
			$views_params["term_group"] = $taxonomy->getTagGroup( $_vars["request"] );
			$views_params["tpl_content_filename"] = "views/taxonomy/term_group_list.tpl.php";
		break;

		case "tag-group/edit":
			$views_params["term_group"] = $taxonomy->getTagGroup( $_vars["request"] );
			$views_params["tpl_content_filename"] = "views/taxonomy/term_group_edit.tpl.php";
		break;
		
		case "tag-group/remove":
			$msg =  "error removing term group, id: ".$_vars["request"]["id"];
			$msg_type = "warning";
				
			$response = $taxonomy->removeTermGroup( $_vars["request"] );
			if( $response ){
				$msg =  "ok, term group.id ".$_vars["request"]["id"]." was removed...";
				$msg_type = "success";
			}
			$_vars["log"][] = array("message" => $msg, "type" => $msg_type);
		break;
		
		case "taxonomy/term-create":
			$views_params["tpl_content_filename"] = "views/taxonomy/term_create.tpl.php";
		break;
		
		case "taxonomy/term-edit":
			$views_params["term"] = $taxonomy->getTerm( $_vars["request"] );
			$views_params["tpl_content_filename"] = "views/taxonomy/term_edit.tpl.php";
		break;

		case "taxonomy/term-save":
			$response = $taxonomy->saveTerm( $_vars["request"] );
			if( !$response ){
$msg = "error,  could not save term ".$_vars["request"]["term"];
$_vars["log"][] = array("message" => $msg, "type" => "error");
			} else {
$msg = "ok,  save new term ".$_vars["request"]["term"];
$_vars["log"][] = array("message" => $msg, "type" => "success");
			}
		break;
		
		case "taxonomy/term-remove":
			$msg =  "error removing tag, id: ".$_vars["request"]["id"];
			$msg_type = "warning";
				
			$response = $taxonomy->removeTerm( $_vars["request"] );
			if( $response ){
				$msg =  "ok, term.id: ".$_vars["request"]["id"]." was removed...";
				$msg_type = "success";
			}
			$_vars["log"][] = array("message" => $msg, "type" => $msg_type);
		break;
		
//======================================= NOTES
/*
		case "notes":
			$arg = array(
				"content_type" => "note"
			);
			$views_params["notes"] = $content->getList($arg);
			$views_params["hierarchy_list"] = $content_links->getHierarchyList($_vars["request"]);
			$views_params["tpl_content_filename"] = "views/notes.tpl.php";
		break;
*/		
//======================================= test API
		case "api":
			$views_params["tpl_content_filename"] = "views/test_api.tpl.php";
		break;

//======================================= IMPORT/EXPORT
		case "form-import":
			$arg = array();
			$arg["tpl_content_path"] = "views/import.tpl.php";
			$views_params["content"] = $app->importForm($arg);
		break;
		case "import":
			require_once dirname(__FILE__)."/api/import.php";
		break;

		case "form-export":
			$arg = array();
			$arg["tpl_content_path"] = "views/export.tpl.php";
			$views_params["content"] = $app->exportForm($arg);
		break;
		case "export":
			require_once dirname(__FILE__)."/api/export.php";
		break;

		//default:
		
	}// end switch
}

if( !isset( $_SESSION['is_auth'] ) ){
	$_SESSION['is_auth'] = false;
}
if( $_SESSION['is_auth'] ){
	$views_params["login"] = $_SESSION['login'];
	$views_params["is_auth"] = true;
}
render_tpl( "main", $views_params );

?>
