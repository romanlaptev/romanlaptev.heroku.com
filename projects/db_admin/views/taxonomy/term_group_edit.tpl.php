<?php
$_vars["log"][] = array("message" => "term_group: ", "type" => "info");
$_vars["log"][] = array("message" => $params["term_group"], "type" => "info");

$group_name = "";
if ( !empty( $params["term_group"] ) ) {
	$group_name = $params["term_group"][0]["name"];
}
?>
<h1>Edit term group <?php echo $group_name?></h1>
<form name="form_term_group" action="<?php echo $_SERVER['SCRIPT_NAME'];?>" method='post' class="form-control">
	<div>
	
		<div class="form-group">
<label>*term group name: </label>
<input type='text' name='name' value='<?php echo $group_name?>'>
		</div>

		<div class="form-group">
<button type="reset" class="btn btn-large btn-warning">reset form</button>			
			<input type='hidden' name="q" value='term-group/save'>
			<input type='submit' value='Save changes'>
		</div>

	</div>
</form>

<ul>
	<li class=""><a href='?q=term-group/remove'>term-group/remove</a></li>
</ul>

<!--
<pre>
CREATE TABLE IF NOT EXISTS taxonomy_groups (
	id INTEGER PRIMARY KEY AUTOINCREMENT CHECK (id>= 0), 
	name VARCHAR(255) NOT NULL DEFAULT '' 
);
</pre>
-->
