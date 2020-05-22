<h1>edit new term</h1>
<?php
$_vars["log"][] = array("message" => "term: ". $params["term"], "type" => "info");
if ( !empty( $params["term"] ) ) {
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
?>
<form name="form_term_edit" action="<?php echo $_SERVER['SCRIPT_NAME'];?>" method='post' class="form-control">
	<div>
		
		<div class="form-group">
<label>id: </label>
<input type='text' name='id' value='<?php echo $id?>' readonly>
		</div>
	
		<div class="form-group">
<label>*term title: </label>
<input type='text' name='title' value='<?php echo $title?>'>
		</div>

		<div class="form-group">
<button type="reset" class="btn btn-large btn-warning">reset form</button>			
			<input type='hidden' name="q" value='taxonomy/term-save'>
			<input type='submit' value='Save changes'>
		</div>

	</div>
</form>

<pre>
CREATE TABLE IF NOT EXISTS taxonomy_term_data (
	term_id INTEGER PRIMARY KEY AUTOINCREMENT CHECK (term_id>= 0), 
	voc_id INTEGER NOT NULL CHECK (voc_id>= 0) DEFAULT 0, 
	name VARCHAR(255) NOT NULL DEFAULT '', 
	parent_id INTEGER NOT NULL CHECK (parent_id>= 0) DEFAULT 0 
);
</pre>
