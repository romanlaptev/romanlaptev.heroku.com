<h1>Add new content item</h1>
<form name="form_add_content" action="<?php echo $_SERVER['SCRIPT_NAME'];?>" method='post' class="form-control">
	
		<div class="row">
			<div class="form-group pull-right">
<button type="reset" class="btn btn-large btn-warning">reset form</button>			
				<input type='hidden' name="q" value='content/save'>
				<input type='submit' value='Save changes'>
			</div>
		</div>
	
		<div class="form-group">
<label>*title: </label>
<input type='text' name='title'>
		</div>
		
<fieldset>
	<legend><b>content hierarchy</b></legend>
	
	<label>parent id:</label>
	<input type='text' name='parent_id' value='' size='3'>
<pre>
'top' string - define top level for this content node
clear any values from field - remove content link (NULL, no value)
</pre>
	
</fieldset>
		
		<div class="form-group">
			<div class="form-item form-type-select">
				<label for="content-type-select">content type</label>
				 <select id="content-type-select" name="type_id" class="form-select">
<?php
//echo $html_type_options;
?>				 
<option value="1" selected="selected">page</option>
<option value="2">note</option>
<option value="3">book</option>
<option value="4">video</option>
<option value="5">music</option>
				
				</select>
<!--				
<input type='text' name='type_id' value='' size='3'>
-->
			</div>
		</div>
		
		<div class="form-group">
<div><label>*text: </label></div>
<div>
	<textarea id="body-value" name="body_value" class="form-control" rows="10"></textarea>
</div>
		</div>

</form>
