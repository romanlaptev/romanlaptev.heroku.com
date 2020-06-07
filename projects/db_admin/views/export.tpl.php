<form name="form_export" action="{{form-action}}" class="form-control" method="POST">
	<fieldset>
		<legend>Export parameters</legend>

		
		<div class="row">
			<div class="form-group pull-right">
<button type="reset" class="btn btn-large btn-warning">reset form</button>			
<input type='hidden' name="q" value='export'>
<input type='submit' value='start export'>
			</div>
		</div>

		<div class="form-group">
<label><b>export filename:</b></label>
<input type='text' name='filename' class='form-control' value='{{filename}}'>
		</div>

		<fieldset>
			<legend><h3>content by type</h3></legend>
<b>content_type</b>: {{content_type_select}}
		</fieldset>
		
		<fieldset>
			<legend><h3>content by group</h3></legend>
<label><b>content group name:</b>
<input type="text" name="content_group" class="form-control" value=""><br>
notes, personal_info, hosting sites, библиотека
		</fieldset>
		
		<fieldset>
			<legend><h3>content by tag group</h3></legend>
<label><b>taxonomy(tag) group:</b></label>
<input type="text" name="taxonomy_group" class="form-control" value=""><br>
notes, tags, library, alphabetical_voc, ......
		</fieldset>

		<fieldset>
			<legend><h3>export format (filetype)</h3></legend>
			<ul>
<li><input type="radio" name="export_format" checked="checked" value=".xml">XML</li>
<li><input type="radio" name="export_format" value=".json">JSON</li>
<li><input type="radio" name="export_format" value=".wxr">WXR ( WordPress eXtended Rss export/import )</li>
			</ul>
		</fieldset>

	</fieldset>
</form>
