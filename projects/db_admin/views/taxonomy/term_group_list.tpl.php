<?php
$_vars["log"][] = array("message" => "term_group: ", "type" => "info");
$_vars["log"][] = array("message" => $params["term_group"], "type" => "info");

$group_name = "";
if ( !empty( $params["term_group"] ) ) {
	$group_name = "'".$params["term_group"][0]["name"]."'";
}

if ( !empty( $params["term_group"] ) ) {
	if ( !empty( $params["term_group"]["terms"] ) ) {
/*	
	$html = "<table border=1 cellspacing=3>{{rows}}</table>";
$tpl_head = "<tr class='text-center'>
	<td><b>id</b></td> 
	<td><b>parent id</b></td> 
	<td><b>title</b></td>
	<td><b>actions</b></td>
</tr>";
$tpl_record = "<tr>
	<td>{{id}}</td> 
	<td>{{parent_id}}</td> 
	<td>{{title}}</td>
	<td>
<a href='?q=category/edit&id={{id}}'>[edit]</a>
<a href='?q=category/remove&id={{id}}'>[remove]</a>
	</td>
</tr>";
	
	$html_rows = $tpl_head;
	for( $n = 0; $n < count( $params["term_groups"] ); $n++){
		$record = $params["category"][$n];
//echo _logWrap( $record );
		$html_record = $tpl_record;
		foreach( $record as $field=>$value ){
			$html_record = str_replace( "{{".$field."}}", $value,  $html_record);	
		}//next
		$html_rows .= $html_record;
	}//next
	$html = str_replace( "{{rows}}", $html_rows,  $html);	
	echo $html;
*/
	}
}
?>
<h1>Taxonomy: list tags of group <?php echo $group_name?></h1>
<ul>
<!--	
	<li class=""><a href='?q=term-group/edit'>term-group/edit</a></li>
	<li class=""><a href='?q=term-group/remove'>term-group/remove</a></li>
-->	
	<li class=""><a href='?q=taxonomy/term-create'>taxonomy/term-create</a></li>
	<li class=""><a href='?q=taxonomy/term-edit'>taxonomy/term-edit</a></li>
	<li class=""><a href='?q=taxonomy/term-remove'>taxonomy/term-remove</a></li>
</ul>
