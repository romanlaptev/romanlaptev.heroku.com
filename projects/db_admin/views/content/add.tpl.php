<h1>Add new content item</h1>
<form name="form_add_content" action="{{form-action}}" method='post' class="form-control">

		<div class="row">
			<div class="form-group pull-right">
<button type="reset" class="btn btn-large btn-warning">reset form</button>			
				<input type='hidden' name="q" value='content/save'>
				<input type='submit' value='Save changes'>
			</div>
		</div>

	<fieldset>
		<legend>*<b>title</b></legend>
<input type='text' name='title' value='new content item'>
	</fieldset>

	<fieldset>
		<legend>*<b>body_value</b></legend>
<textarea id="body-value" name="body_value" class="form-control" rows="20"></textarea>
	</fieldset>
	
	<fieldset>
		<legend><b>content hierarchy, select parent content item</b></legend>
		<div>
<input name="parent_id" type="radio" value="top">set item as new content group (top level)<br/>
{{content_links}}
		</div>
	</fieldset>

	<fieldset>
		<legend><b>content type</b></legend>
{{content_type_select}}		
	</fieldset>

</form>
