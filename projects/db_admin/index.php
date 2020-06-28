<?php
header('Access-Control-Allow-Origin: *');
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
$_vars["content"] = new Content();
$_vars["content_links"] = new ContentLinks();
$_vars["taxonomy"] = new Taxonomy();
$_vars["app"] = new App();

$_vars["views_params"] = array();
$_vars["views_params"]["tpl_content_filename"] = "views/home.tpl.php";

$_vars["log"][] = array("message" => $_REQUEST, "type" => "info");
$_vars["request"] = $_REQUEST;

$_vars["app"]->urlManager( $_vars["request"] );


if( !isset( $_SESSION['is_auth'] ) ){
	$_SESSION['is_auth'] = false;
}
if( $_SESSION['is_auth'] ){
	$_vars["views_params"]["login"] = $_SESSION['login'];
	$_vars["views_params"]["is_auth"] = true;
}
render_tpl("page", $_vars["views_params"]);

?>
