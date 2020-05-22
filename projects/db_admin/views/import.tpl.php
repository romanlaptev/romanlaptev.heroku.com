<form name="form_import" action="{{form-action}}" class="form-control" method="POST">
	<fieldset>
		<legend>Import parameters</legend>

		<div class="form-group">
<label><b>import from this file:</b></label>
<input type='text' name='filename' class='form-control' value='{{filename}}' size='80'>
		</div>
		
		<div class="form-group">
			<ul>import format:
				<li><input type="radio" name="import_format" checked="checked" value=".xml">XML</li>
				<li><input type="radio" name="import_format" value=".json">JSON</li>
				<li><input type="radio" name="import_format" value=".wxr">WXR ( WordPress eXtended Rss export/import )</li>
			</ul>
		</div>


		<div class="form-group">
			<button type="reset" class="btn btn-large btn-warning">reset form</button>			
			<input type='hidden' name="q" value='import'>
			<input type='submit' value='start import'>
		</div>

	</fieldset>
</form>
