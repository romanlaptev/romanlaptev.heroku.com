<?php

function PageHead(){
	return "<html>
<head>
	<meta charset='utf-8'>
	<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
	<meta name='viewport' content='width=device-width, initial-scale=1.0'/>
	<meta http-equiv='X-UA-Compatible' content='IE=Edge'>
	<link rel='stylesheet' type='text/css' href='inc/style.css'>
</head>
<body>
<div class='container padding-small'>
<div class='row'>
	<div class='pull-right padding-small'>
		<b>PHP version:</b>".phpversion().", <b>Drupal version:</b>".VERSION."
	</div>
</div>
";
}//end

function PageEnd(){
	return "</body></html>";
}//end


function ImportForm(){
	global $_vars;
	
	$upload_dir = dirname($_vars["config"]["export"]["file_path"]);
	$max_filesize = ini_get('upload_max_filesize'); 
	
	return "<h2>Import parameters</h2>
	<div class='panel'>
		<form method=post name='form_import' action='' class='form'>
		
			<div class='row'>
				<div class='pull-right'>
					<input type=hidden name='action' value='import'/>
					<input type=submit value='start import' class='btn btn-large btn-primary'>
				</div>
			</div>
			
			<fieldset><legend><b>Drupal root (absolute path to CMS)</b></legend>
<input type='text' name='drupal_root' value='".$_vars["config"]["export"]["drupal_root"]."' size='60' class='form-control'/>
<pre>".$_vars["config"]["export"]["list_drupal_cms_filepath"]."</pre>
			</fieldset>

			<fieldset>
				<div class=class='form-group'>
<b>filename</b>
<input type='text' name='file_path' value='".$_vars["config"]["export"]["file_path"]."' size='60' class='form-control'/>
				</div>
			</fieldset>
			
		</form>
	</div>
	
	<div class='panel'>
		<form method='post' enctype='multipart/form-data' action='' target=''>
		<fieldset><legend><b>upload import file on server</b></legend>
		
			<div class=class='form-group'>
				upload_dir:<input name='upload_dir' class='form-conrol' type='text' size='60' value='".$upload_dir."'>
<pre>
<small>
".$upload_dir."
/mnt/d2/temp
</small>
</pre>
			</div>
				
				<input name='upload_file' class='form-conrol' type='file' >
				<input type='hidden' name='action' value='upload_import_file'>
				<input type='submit' value='upload'>
			</fieldset>
		</form>
		<p><small>upload_max_filesize =".$max_filesize."</small></p>
	</div>";
}//end ImportForm()

function exportForm(){
	global $_vars;
	return "<form method=post name='form_export' action='' class='form'>
	
		<div class='row'>
			<div class='pull-right padding-small'>
				<input type=hidden name='action' value='export'/>
				<input type=submit value='start export' class='btn btn-large btn-primary'>
			</div>
		</div>
	
		<fieldset>
			<legend><b>Export parameters</b></legend>

		<div class=class='form-group'>
			<fieldset>
				<legend><b>Drupal root (absolute path to CMS)</b></legend>
<input type='text' name='drupal_root' value='".$_vars["config"]["export"]["drupal_root"]."' size='60' class='form-control'/>
<pre>".$_vars["config"]["export"]["list_drupal_cms_filepath"]."</pre>
			</fieldset>
		</div>

<br>
		<div class=class='form-group'>
			<fieldset>
				<legend><b>export content items (nodes)</b></legend>
<ul>
	<li><input type='radio' name='type_export_content' checked='checked' value='nodes_all'>all nodes</li>
	<li><input type='radio' name='type_export_content' value='nodes_book'>nodes of the book</li>
	<li><input type='radio' name='type_export_content' value='nodes_tag'>nodes by the tag</li>
	<li><input type='radio' name='type_export_content' value='nodes_type'>nodes by the type</li>
</ul>
				<div>
<b>Drupal content type</b>
<input type='text' name='content_type' value='".$_vars["config"]["export"]["content_type"]."' class='form-control'/>
<pre>
page
book
article
author
video
playlist
music
videoclip
</pre>
				</div>

			</fieldset>
		</div>

		<div class=class='form-group'>
			<fieldset>
				<legend><b>export content groups (content_links, book)</b></legend>
				<div>
<b>Drupal content book</b>
<input type='text' name='content_book' value='".$_vars["config"]["export"]["content_book"]."' size='60' class='form-control'/>
<pre>
notes
personal_info
hosting sites
библиотека
</pre>
				</div>

			</fieldset>
		</div>
		
		<div class=class='form-group'>
			<fieldset>
				<legend><b>export taxonomy (tags)</b></legend>
<b>tag_group(vocabulary):</b><input type='text' name='tag_group' value='".$_vars["config"]["export"]["tag_group"]."' class='form-control'/>
<pre>
notes, tags, library, alphabetical_voc, ......
</pre>
<b>tag_name(termin):</b><input type='text' name='tag_name' value='".$_vars["config"]["export"]["tag_name"]."' class='form-control'/>
<pre>
linux, windows, config, network, drupal, convert, .....
</pre>
			</fieldset>
		</div>
		
<br>
		<div class=class='form-group'>
<b>file path</b>
<input type='text' name='file_path' value='".$_vars["config"]["export"]["file_path"]."' size='60' class='form-control'/>
		</div>
		
<!--
		<div class=class='form-group'>
<b>sqlite path</b>
<input type='text' name='sqlite_path' value='_vars[config][db][dsn]' size='60' class='form-control'/>
<p>sqlite:/mnt/d2/temp/mydb.sqlite</p>
		</div>
-->
		
<div class='form-group'>
	<b>export format</b>
	<label class='radio-inline'><input type='radio' name='export_format' checked='checked' value='xml'>XML</label>
	<label class='radio-inline'><input type='radio' name='export_format' value='json'>JSON</label>
	<label class='radio-inline'><input type='radio' name='export_format' value='wxr'>WXR ( WordPress eXtended Rss export/import )</label>
</div>

		</fieldset>
		
	</form>";
}//end exportForm()


function loadDrupal(){
	global $_vars;

	$drupalRoot = $_vars["config"]["export"]["drupal_root"];
	define('DRUPAL_ROOT', $drupalRoot );
	$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
//echo _logWrap( DRUPAL_ROOT );

	chdir ($drupalRoot);
	//echo getcwd();
	//echo "<br>";

	// Bootstrap Drupal.
	$drupalConstFile = $drupalRoot."/includes/bootstrap.inc";
	if ( !file_exists( $drupalConstFile ) ){
		$msg = "error, not find Drupal constant file ".$drupalConstFile;
		echo $msg;
		return false;
	}
	require_once $drupalConstFile;
	drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
}//end loadDrupal()


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

	//if( gettype( $msg) !== "string"){
		//return false;
	//}

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
