<?php

function render_tpl( $tpl_name, $params ) {
	global $_vars;
	require_once ("views/" .$tpl_name. ".tpl.php");
}//end


function verifyUser( $params ) {
	$result = db_find_user( $params );
//echo _logWrap( $result );

	if( $result["type"] === "error" ){
		$msg = $result["type"].", ".$result["description"];
		echo _logWrap( $msg, "error" );
		return false;
	}

	if( $result["type"] === "success" ){
		$msg = "user verification was successful";
		echo _logWrap( $msg, "success" );
		return true;
	}

	return false;
}//end verifyUser()



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
				return "<div class='alert alert-info'>".$out."</div>";
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


function _filterFormInputValue( $field ){
	$field = trim( $field );
	
	//$field = strip_tags($field);
//$textMessage = addslashes( htmlspecialchars($_REQUEST["text_message"]) );
//$textbox=$vars['textbox']; 
//	$textbox = stripslashes($textbox);
	
	$field = htmlspecialchars( $field );
	$field = str_replace("\"", "&quot;", $field );
	$field = str_replace("'", "&apos;", $field);//replace apostrophe
	return $field;
}//end


function widget_type_id( $type_id=0 ){

	$tpl_select =	'<select id="content-type-select" name="type_id" class="form-select">{{options}}</select>';
	$tpl_option = "<option value='{{id}}'>{{name}}</option>";
	$tpl_option_selected = "<option value='{{id}}' selected='selected'>{{name}}</option>";
	$html_options = "";
	
	$db = DB::getInstance();
	$arg = array(
		"tableName" => "content_type",
		"fields" => array("id", "name")
	);
	$options = $db->getRecords($arg);
//echo _logWrap($options);

	if( !empty($options) ){
		for( $n=0; $n < count($options); $n++ ){
			$html_option = $tpl_option;
			if( $options[$n]["id"] == $type_id ){
				$html_option = $tpl_option_selected;
			}
			$html_option = str_replace("{{id}}", $options[$n]["id"], $html_option );
			$html_option = str_replace("{{name}}", $options[$n]["name"], $html_option );
			$html_options .= $html_option;
		}//next
	}
	$html = str_replace("{{options}}", $html_options, $tpl_select );
	return $html;
}//end widget_type_id()

function widget_content_type( $content_type="" ){
	$tpl_select =	'<select id="content-type-select" name="content_type" class="form-select">
<option value="">any types</option>
{{options}}
</select>';
	$tpl_option = "<option value='{{name}}'>{{name}}</option>";
	$tpl_option_selected = "<option value='{{name}}' selected='selected'>{{name}}</option>";
	$html_options = "";
	
	$db = DB::getInstance();
	$arg = array(
		"tableName" => "content_type",
		"fields" => array("id", "name")
	);
	$options = $db->getRecords($arg);
//echo _logWrap($options);

	if( !empty($options) ){
		for( $n=0; $n < count($options); $n++ ){
			$html_option = $tpl_option;
			if( $options[$n]["name"] == $content_type ){
				$html_option = $tpl_option_selected;
			}
			$html_option = str_replace("{{name}}", $options[$n]["name"], $html_option );
			$html_options .= $html_option;
		}//next
	}
	$html = str_replace("{{options}}", $html_options, $tpl_select );
	return $html;
}//end widget_content_type()


function widget_body_format( $format_id=1 ){
	$tpl_select ='<select name="body_format" class="form-select">
{{options}}
</select>';
	$tpl_option = "<option value='{{id}}'>{{name}}</option>";
	$tpl_option_selected = "<option value='{{id}}' selected='selected'>{{name}}</option>";
	$html_options = "";
	
	$db = DB::getInstance();
	$arg = array(
		"tableName" => "filter_format",
		"fields" => array("id", "format", "name")
	);
	$options = $db->getRecords($arg);
//echo _logWrap($options);

	if( !empty($options) ){
		for( $n=0; $n < count($options); $n++ ){
			$html_option = $tpl_option;
			if( $options[$n]["id"] == $format_id ){
				$html_option = $tpl_option_selected;
			}
			$html_option = str_replace("{{id}}", $options[$n]["id"], $html_option );
			$html_option = str_replace("{{name}}", $options[$n]["name"], $html_option );
			$html_options .= $html_option;
		}//next
	}
	$html = str_replace("{{options}}", $html_options, $tpl_select );
	return $html;
}//end widget_body_format()


function widget_status( $status_id=0 ){
	
	$html = "<select name='status' class='form-select'>
<option value='0' selected='selected'>not publush</option>
<option value='1'>publish</option>
</select>";

	if( $status_id == 1){
		$html = "<select name='status' class='form-select'>
<option value='0'>not publush</option>
<option value='1' selected='selected'>publish</option>
</select>";
	}
	
	return $html;
}//end widget_status()

function widget_content_links( $params=null ){
	global $_vars;
	//global $content_links;

	$p = array(
		"item_parent_id" => false
	);
	
	//extend options object $p
	//foreach( $params as $key=>$item ){
		//$p[ $key ] = $item;
	//}//next

	//check input parameters, select only from array $p
	foreach( $p as $key=>$value ){
		if( !empty($params[ $key ]) ){
			$p[ $key ] = $params[ $key ];
		}
	}//next
//echo _logWrap($p);

	//if( empty($p["item_content_id"]) ){
//echo _logWrap("not found content link....");
	//}
	
	$html = "";
	$_vars["content_links_list"] = $_vars["content_links"]->getList();
//echo _logWrap($_vars["content_links_list"]);

	if( !$_vars["content_links_list"] ){
		return $html;
	}
	if( count( $_vars["content_links_list"] ) == 0 ){
		return $html;
	}
	
	$tpl_input = $_vars["content_links"]->templates["content_links_input"];
	$tpl_input_checked = $_vars["content_links"]->templates["content_links_checked"];
/*	
	for( $n = 0; $n < count($content_links_list); $n++ ){
		$html_input = $tpl_option;
		$content_link = $content_links_list[$n];
		foreach( $content_link as $field=>$value ){
			$html_input = str_replace("{{".$field."}}", $value, $html_input);
		}//next
		$html .= $html_input;
	}//next
*/
	for( $n = 0; $n < count( $_vars["content_links_list"] ); $n++ ){
		$content_link = $_vars["content_links_list"][$n];
		$parent_id = $content_link["parent_id"];
		$content_id = $content_link["content_id"];
		
		if( $parent_id == 0){
			$html_input = $tpl_input;
			if( $content_id == $p["item_parent_id"] ){
	//echo _logWrap($content_id.", ".$p["item_content_id"]);
	//echo $content_id == $p["item_content_id"];
				$html_input = $tpl_input_checked;
			}
			foreach( $content_link as $field=>$value ){
				$html_input = str_replace("{{".$field."}}", $value, $html_input);
			}//next
//echo _logWrap( htmlspecialchars( get_children_items($content_id, $tpl_option) ) );
			$html_input .= get_children_items($content_id, 1, $p["item_parent_id"]);
			$html .= $html_input;
		}
	}//next
//echo _logWrap( htmlspecialchars($html));
	
	return $html;
}//end widget_content_type()

function get_children_items( $contentID, $level, $item_parent_id ){
	global $_vars;
	//global $content_links;
	
	$tpl_input = $_vars["content_links"]->templates["content_links_input"];
	$tpl_input_checked = $_vars["content_links"]->templates["content_links_checked"];
	
	$html = "";
	//$html = $level."";
	$level++;
	for( $n = 0; $n < count( $_vars["content_links_list"] ); $n++ ){
		$content_link = $_vars["content_links_list"][$n];
		$parent_id = $content_link["parent_id"];
		$content_id = $content_link["content_id"];

		if( $parent_id == $contentID ){
			$html_input = str_repeat("&nbsp;", 2) ."|". str_repeat("-",$level) .$tpl_input;
			if( $content_id == $item_parent_id ){
				$html_input = str_repeat("&nbsp;", 2) ."|". str_repeat("-",$level) .$tpl_input_checked;
			}
			foreach( $content_link as $field=>$value ){
				$html_input = str_replace("{{".$field."}}", $value, $html_input);
			}//next
			$html_input .= get_children_items($content_id, $level, $item_parent_id);
			$html .= $html_input;
		}
	}//next
	
	return $html;
}//end get_children_items()


function widget_table( $params=array() ){

	$default_tpl = array(
		"table" => "<table border=1 cellspacing=3>{{head}}{{rows}}</table>",
		"tpl_head" => "<tr class='text-center'>{{field_names}}</tr>",
		"tpl_fieldNames" => "<td><b>{{fieldName}}</b></td>",
		"tpl_record" => "<tr>{{field_columns}}</tr>",
		"tpl_fieldColumns" => "<td>{{fieldValue}}</td>"
	) ;
	
	$p = array(
		"data" => array(),
		"templates" => $default_tpl
	);
	
	//extend options object $p
	foreach( $params as $key=>$item ){
		$p[ $key ] = $item;
	}//next
	
	$p["templates"] = array_merge( $default_tpl, $p["templates"]);
//echo _logWrap($p);
	
	if( count( $p["data"] ) == 0 ){
		return false;
	}
	$records = $p["data"];
	
	$html = $p["templates"]["table"];
	
	$html_head = $p["templates"]["tpl_head"];
	//form table column titles (field_names)
	$tpl_fieldNames = $p["templates"]["tpl_fieldNames"];
	$html_fieldNames = "";
	foreach( $records[0] as $fieldName => $value){
		$html_fieldNames .= str_replace("{{fieldName}}", $fieldName, $tpl_fieldNames);
	}//next
	$html_head = str_replace("{{field_names}}", $html_fieldNames, $html_head);

	$html_rows = "";
	for( $n = 0; $n < count( $records ); $n++){
		$record = $records[$n];
//echo _logWrap( $record );
		$html_record = $p["templates"]["tpl_record"];
		
		//form table column (field values)
		//{{fiels_columns}}
		$tpl_fieldColumns = $p["templates"]["tpl_fieldColumns"];
		$html_columns = "";
		foreach( $record as $field=>$value ){
			$html_columns .= str_replace( "{{fieldValue}}", $value, $tpl_fieldColumns);
		}//next
		
		//second replacing in table record (action id, other values...)
		$html_record = str_replace( "{{field_columns}}", $html_columns,  $html_record);	
		foreach( $record as $field=>$value ){
			$html_record = str_replace( "{{".$field."}}", $value, $html_record);
		}//next

		$html_rows .= $html_record;
	}//next

	$html = str_replace( "{{head}}", $html_head,  $html);
	$html = str_replace( "{{rows}}", $html_rows,  $html);
//echo _logWrap( htmlspecialchars($html));

	return $html;
}//end widget_table()

?>
