<h1>Notes, comments...</h1>
<?php
//echo _logWrap($params["category"]);
$_vars["log"][] = array("message" => $params["hierarchy_list"], "type" => "info");
$_vars["log"][] = array("message" => $params["notes"], "type" => "info");

if ( !empty($params["hierarchy_list"]) ) {
	$html = "<b>content hierarchy list</b>: <ul>{{list}}</ul>";
	$tpl_record = "<li><a href='?q=notes&content_id={{content_id}}'>{{title}}</a></li>";

	$html_rows = "";
	for( $n = 0; $n < count( $params["hierarchy_list"] ); $n++){
		$record = $params["hierarchy_list"][$n];
//echo _logWrap( $record );
		$html_record = $tpl_record;
		foreach( $record as $field=>$value ){
			$html_record = str_replace( "{{".$field."}}", $value,  $html_record);	
		}//next
		$html_rows .= $html_record;
	}//next
	$html = str_replace( "{{list}}", $html_rows,  $html);	
	echo $html;
}


if ( !empty($params["notes"]) ) {
	//Make widget!!!!!
	$html = "<table border=1 cellspacing=3>{{rows}}</table>";
$tpl_head = "<tr class='text-center'>
	<td></td> 
	<td><b>Title</b></td>
	<td><b>type</b></td>
	<td><b>actions</b></td>
</tr>";
$tpl_record = "<tr>
	<td>
<input type='checkbox' id='edit-nodes-{{id}}' name='nodes[]' value='id-{{id}}' class='form-checkbox'>
	</td>
	<td>{{title}}</td>
	<td>{{type}}</td>
	<td>
<a href='?q=content/view&id={{id}}'>[view]</a>
<a href='?q=content/edit&id={{id}}'>[edit]</a>
<a href='?q=content/remove&id={{id}}'>[remove]</a>
	</td>
</tr>";
	
	$html_rows = $tpl_head;
	for( $n = 0; $n < count( $params["notes"] ); $n++){
		$record = $params["notes"][$n];
//echo _logWrap( $record );
		$html_record = $tpl_record;
		foreach( $record as $field=>$value ){
			$html_record = str_replace( "{{".$field."}}", $value,  $html_record);	
		}//next
		$html_rows .= $html_record;
	}//next
	$html = str_replace( "{{rows}}", $html_rows,  $html);	
	echo $html;
}
?>
