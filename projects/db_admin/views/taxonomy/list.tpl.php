<h1>Taxonomy</h1>
<ul>
	<li class=""><a href='?q=tag-group/create'>add new tag group</a></li>
</ul>
<?php
//$_vars["log"][] = array("message" => "tag_groups: ", "type" => "info");
//$_vars["log"][] = array("message" => $params["tag_groups"], "type" => "info");

//$_vars["log"][] = array("message" => "tag_list: ", "type" => "info");
//$_vars["log"][] = array("message" => $params["tag_list"], "type" => "info");

if ( !empty( $params["tag_groups"] ) ) {
	$arg = array(
		"data" => $params["tag_groups"],
		"templates" => array(
			"tpl_head" => "<tr class='text-center'>
{{field_names}}
<td><b>actions</b></td>
</tr>",
			"tpl_record" => "<tr>
{{field_columns}}	
	<td>
<a href='?q=tag-group/list&id={{id}}'>[list]</a>
<a href='?q=tag-group/edit&id={{id}}'>[edit]</a>
<a href='?q=tag-group/remove&id={{id}}'>[remove]</a>
	</td>
</tr>"
		) 
	);
	echo widget_table($arg);
}

if ( !empty( $params["tag_list"] ) ) {
	$arg = array(
		"data" => $params["tag_list"],
		"templates" => array(
			"table" => "<h3>list tag(termins) list</h3><table border=1 cellspacing=3>{{rows}}</table>",
			"tpl_head" => "<tr class='text-center'>
	<td><b>id</b></td> 
	<td><b>name</b></td>
	<td><b>term_group_id</b></td> 
	<td><b>parent_id</b></td> 
</tr>",
			"tpl_record" => "<tr>
	<td>{{id}}</td> 
	<td>{{name}}</td> 
	<td>{{term_group_id}}</td> 
	<td>{{parent_id}}</td> 
</tr>"
		) 
	);
	echo widget_table($arg);
}

?>
<!--
<form name="form_term_groups" action="<?php echo $_SERVER['SCRIPT_NAME'];?>" method='post' class="form-control">
</form>
-->	
