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

<div class="row">	
	
	<div class="pull-left">	
		<fieldset>
			<legend><b>content type</b></legend>
	{{content_type_select}}		
		</fieldset>
	</div>
	
	<div class="pull-left">	
		<fieldset>
			<legend><b>status</b></legend>
				<select name="status" class="form-select">
	<option value="0">not publush</option>
	<option value="1"selected="selected">publish</option>
				</select>
		</fieldset>
	</div>
	
	<div class="pull-left">	
		<fieldset>
			<legend><b>body_format</b></legend>
{{body_format_select}}
<!--			
				<select name="body_format" class="form-select">
	<option value="1" selected="selected">Plain text</option>
	<option value="2">Filtered HTML</option>
	<option value="3">Full HTML</option>
	<option value="4">PHP code</option>
				</select>
-->				
		</fieldset>
	</div>
	
</div>

	<fieldset>
		<legend>*<b>body_value</b></legend>
<textarea id="body-value" name="body_value" class="form-control" rows="20"></textarea>
	</fieldset>
	
	
	<fieldset>
		<legend><b>content hierarchy, select parent content item</b></legend>
		<div>
<input name="parent_id" type="radio" value="0">set item as new content group (book)<br/>
{{content_links}}
		</div>
	</fieldset>

</form>
