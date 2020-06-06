<h3>Edit content item: {{title}}</h3>

<form name="form_edit_content" action="{{form-action}}" method='post' class="form-control">

		<div class="row">
			<div class="form-group pull-right">
<button type="reset" class="btn btn-large btn-warning">reset form</button>			
				<input type='hidden' name="q" value='content/save'>
				<input type='submit' value='Save changes'>
			</div>
		</div>
		
		<div class="row">
			<div class="pull-right">
<a href='?q=content/remove&id={{id}}'>remove content item</a>
			</div>
		</div>

		<div class="form-group">
<label>id: </label>
<input type='text' name='id' value='{{id}}' readonly>
		</div>
	
	<fieldset>
		<legend>*<b>title</b></legend>
<input type='text' name='title' value='{{title}}'>
	</fieldset>

<div class="row">	
	
	<div class="pull-left">	
		<fieldset>
			<legend><b>content type</b></legend>
{{content_type_select}}		
<!-- <input type='text' name='type_id' value='{{type_id}}' size='3'> -->
		</fieldset>
	</div>

	
	<div class="pull-left">	
		<fieldset>
			<legend><b>status</b></legend>
{{status_select}}
		</fieldset>
	</div>
	
	<div class="pull-left">	
		<fieldset><legend><b>body_format</b></legend>
{{body_format_select}}
		</fieldset>
	</div>
	
</div>

	<fieldset><legend>*<b>body_value</b></legend>
<textarea id="body-value" name="body_value" class="form-control" rows="20">{{body_value}}</textarea>
	</fieldset>
	
	<fieldset>
		<legend><b>content hierarchy, select parent content item</b></legend>
		
<!--
		<label>parent id:</label>
		<input type='text' name='parent_id_text' value='{{parent_id}}' size='3'>
<pre>
'top' string - define top level for this content node
clear any values from field - remove content link (NULL, no value)
</pre>

		<div class="form-item form-type-select">
			<label for="content-link-select">content link select</label>
				 <select id="content-link-select" name="parent_id_t1" class="form-select">
<option value="0">not selected</option>
<option value="top">create new content group</option>
<option value="69">notes</option>
<option value="67">- config</option>
<option value="66">-- apache</option>
<option value="68">--- APACHE, htaccess</option>
<option value="70">---- htaccess, albums_smarty</option>
				</select>
		</div>
-->
<!--
<div>
	<input name="parent_id_t2" type="radio" value="0">reset<br/>
	<input name="parent_id_t2" type="radio" value="top">create new content group<br/>
	<input name="parent_id_t2" type="radio" value="69">notes<br/>
	&nbsp;&nbsp;<input name="parent_id_t2" type="radio" value="67">config<br/>
	&nbsp;&nbsp;&nbsp;&nbsp;<input name="parent_id_t2" type="radio" value="66">apache<br/>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="parent_id_t2" type="radio" value="68">APACHE, htaccess<br/>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="parent_id_t2" type="radio" value="70">htaccess, albums_smarty<br/>
</div>
-->

<div>
	<input name="parent_id" type="radio" value="0">remove link<br/>
	<input name="parent_id" type="radio" value="top">set item as new content group (top level)<br/>
{{content_links}}
</div>

	</fieldset>


</form>
