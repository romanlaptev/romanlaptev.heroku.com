<h1>taxonomy: add new term group</h1>
<?php
$default_name = "new_group";
?>
<form name="form_term_group_create" action="<?php echo $_SERVER['SCRIPT_NAME'];?>" method='post' class="form-control">
	<div>
	
		<div class="form-group">
<label>*term group name: </label>
<input type='text' name='name' value='<?php echo $default_name?>'>
		</div>

		<div class="form-group">
<button type="reset" class="btn btn-large btn-warning">reset form</button>			
			<input type='hidden' name="q" value='term-group/save'>
			<input type='submit' value='Save changes'>
		</div>

	</div>
</form>
<!--
<pre>
CREATE TABLE IF NOT EXISTS taxonomy_groups (
	id INTEGER PRIMARY KEY AUTOINCREMENT CHECK (id>= 0), 
	name VARCHAR(255) NOT NULL DEFAULT '' 
);
</pre>
-->
