<h1>add new term</h1>
<form name="form_term_create" action="<?php echo $_SERVER['SCRIPT_NAME'];?>" method='post' class="form-control">
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
			<input type='hidden' name="q" value='taxonomy/term-create'>
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
