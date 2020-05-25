<?php

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


function ImportForm(){
	global $_vars;
	return "<form method=post name='form_import' action='' class='form'>
		<fieldset>
<legend>Import parameters:</legend>
		<div class=class='form-group'>
<label>filename</label>
<input type='text' name='file_path' value='".$_vars["config"]["export"]["file_path"]."' size='60' class='form-control'/>
		</div>
		<input type=hidden name='action' value='import'/>
		<input type=submit value='Import' class='btn btn-large btn-primary'>
		</fieldset>
	</form>";
}//end ImportForm()

function exportForm(){
	global $_vars;
	return "<form method=post name='form_export' action='' class='form'>
		<fieldset>
<legend>Export parameters:</legend>

		<div class=class='form-group'>
<label>file path</label>
<input type='text' name='file_path' value='".$_vars["config"]["export"]["file_path"]."' size='60' class='form-control'/>
		</div>

		<div class=class='form-group'>
<label>sqlite path</label>
<input type='text' name='sqlite_path' value='".$_vars["config"]["db"]["dsn"]."' size='60' class='form-control'/>
<p>sqlite:/mnt/d2/temp/mydb.sqlite</p>
		</div>

		<div class=class='form-group'>
<label>Drupal content book</label>
<input type='text' name='content_book' value='".$_vars["config"]["export"]["content_book"]."' size='60' class='form-control'/>
<p>personal_info</p>
		</div>

		<div class=class='form-group'>
<label>Drupal tag group</label>
<input type='text' name='tag_group' value='".$_vars["config"]["export"]["tag_group"]."' size='60' class='form-control'/>
		</div>

<div class='form-group'>
	<b>export format</b>
	<label class='radio-inline'><input type='radio' name='export_format' checked='checked' value='xml'>XML</label>
	<label class='radio-inline'><input type='radio' name='export_format' value='wxr'>WXR ( WordPress eXtended Rss export/import )</label>
</div>

		<input type=hidden name='action' value='export'/>
		<input type=submit value='start export' class='btn btn-large btn-primary'>
		</fieldset>
	</form>";
}//end exportForm()


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
