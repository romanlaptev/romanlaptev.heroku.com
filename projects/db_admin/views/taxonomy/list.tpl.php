<h1>Taxonomy: list term(tag) groups</h1>
<ul>
	<li class=""><a href='?q=term-group/create'>add new term group</a></li>
</ul>
<?php
$_vars["log"][] = array("message" => "term_groups: ", "type" => "info");
$_vars["log"][] = array("message" => $params["term_groups"], "type" => "info");

if ( !empty( $params["term_groups"] ) ) {
	
//widget table	
	$html = "<table border=1 cellspacing=3>{{rows}}</table>";
$tpl_head = "<tr class='text-center'>
	<td><b>id</b></td> 
	<td><b>name</b></td>
	<td><b>actions</b></td>
</tr>";
$tpl_record = "<tr>
	<td>{{id}}</td> 
	<td>{{name}}</td> 
	<td>
<a href='?q=term-group/list&id={{id}}'>[list]</a>
<a href='?q=term-group/edit&id={{id}}'>[edit]</a>
<a href='?q=term-group/remove&id={{id}}'>[remove]</a>
	</td>
</tr>";
	
	$html_rows = $tpl_head;
	for( $n = 0; $n < count( $params["term_groups"] ); $n++){
		$record = $params["term_groups"][$n];
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
<!--
<form name="form_term_groups" action="<?php echo $_SERVER['SCRIPT_NAME'];?>" method='post' class="form-control">
</form>
-->	
