<form name="form_export" action="{{form-action}}" class="form-control" method="POST">
	<fieldset>
		<legend>Export parameters</legend>

		<div class="form-group">
<label><b>filename:</b></label>
<input type='text' name='filename' class='form-control' value='{{filename}}'>
		</div>
		
<!--
<input type='text' name='content_type' class='form-control' value=''>
-->
		<fieldset>
			<legend><b>content type</b></legend>
export: {{content_type_select}}
		</fieldset>
		
<!--
			<div class="form-group">
<label><b>category name:</b>
<input type="text" name="category_name" class="form-control" value="notes"><br>
		
<label><b>taxonomy group:</b></label>
<input type="text" name="taxonomy_group" class="form-control" value="notes"><br>
			</div>
-->
		
<!--
			<div class="form-group">
				<div>
	<label>db_path</label>
	<input class='form-control'	type='text' name='db_path' 	size='80' value='{{dbPath}}'/>
				</div>
<pre>
sqlite:/home/www/sites/music/cms/music_drupal/db/music.sqlite
sqlite:/mnt/d2/temp/music.sqlite
</pre>
			</div>
-->

		<div class="form-group">
			<ul>export format (filetype):
				<li><input type="radio" name="export_format" checked="checked" value=".xml">XML</li>
				<li><input type="radio" name="export_format" value=".json">JSON</li>
				<li><input type="radio" name="export_format" value=".wxr">WXR ( WordPress eXtended Rss export/import )</li>
			</ul>
		</div>


		<div class="form-group">
			<button type="reset" class="btn btn-large btn-warning">reset form</button>			
			<input type='hidden' name="q" value='export'>
			<input type='submit' value='start export'>
		</div>

	</fieldset>
</form>
