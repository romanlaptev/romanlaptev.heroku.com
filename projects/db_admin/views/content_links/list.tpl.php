<?php
//echo _logWrap($params);

$total = 0;
$html_table = "";

if ( isset($params["hierarchy_list"]) ) {
	
	if ( !empty($params["hierarchy_list"]["children"]) ) {
$_vars["log"][] = array("message" => $params["hierarchy_list"], "type" => "info");

$html = "<b>content hierarchy</b>: <ul>{{list}}</ul>";
$tpl_record = "<li><a href='?q=content-links/list&content_id={{content_id}}'>{{title}}</a></li>";

		$html_rows = "";
		for( $n = 0; $n < count( $params["hierarchy_list"]["children"] ); $n++){
			$record = $params["hierarchy_list"]["children"][$n];
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
	
	$node_title = "";	
	$body_value = "";	
	if ( !empty($params["hierarchy_list"]["node"]) ) {
		$node_title = $params["hierarchy_list"]["node"]["title"];	
		
		if ( !empty($params["hierarchy_list"]["node"]["body_value"]) ) {
			$body_value = $params["hierarchy_list"]["node"]["body_value"];	
		}
	}
	
}



if ( isset($params["content_links"]) ) {
	if ( !empty($params["content_links"]) ) {
$_vars["log"][] = array("message" => $params["content_links"][0], "type" => "info");
/*
		$html = "<table border=1 cellspacing=3>{{rows}}</table>";
	$tpl_head = "<tr class='text-center'>
		<td><b>title</b></td> 
		<td><b>content_id</b></td> 
		<td><b>parent_id</b></td> 
		<td><b>actions</b></td>
	</tr>";
	$tpl_record = "<tr>
		<td>{{title}}</td> 
		<td>{{content_id}}</td> 
		<td>{{parent_id}}</td> 
		<td>
	<a href='?q=content-links/remove&content_id={{content_id}}'>remove link</a>
		</td>
	</tr>";
		
		$html_rows = $tpl_head;
		for( $n = 0; $n < count( $params["content_links"] ); $n++){
			$record = $params["content_links"][$n];
	//echo _logWrap( $record );
			$html_record = $tpl_record;
			foreach( $record as $field=>$value ){
				$html_record = str_replace( "{{".$field."}}", $value,  $html_record);	
			}//next
			$html_rows .= $html_record;
		}//next
		$html = str_replace( "{{rows}}", $html_rows,  $html);	
		//echo $html;
		$html_table = $html;
*/
		$arg = array(
			"data" => $params["content_links"],
			"templates" => array(
				"tpl_head" => "<tr class='text-center'>
	{{field_names}}
	<td><b>actions</b></td>
	</tr>",
				"tpl_record" => "<tr>
	{{field_columns}}	
		<td>
<a href='?q=content-links/remove&content_id={{content_id}}'>remove link</a>
		</td>
	</tr>"
			) 
		);
		echo widget_table($arg);
		
		$total = count($params["content_links"]);
	}
}
?>
<h3><?php echo $node_title; ?></h3>
<?php echo $body_value; ?>	

<h3>num elements: <?php echo $total ?></h3>
<ul>
	<li class="inline-list"><a href='?q=content-links/clear'>clear table content_links</a></li>
</ul>

<form name="form_content_links" action="<?php echo $_SERVER['SCRIPT_NAME'];?>" method='post' class="form-control">
<?php echo $html_table; ?>	
</form>
