<h1>enter you login and password</h1>
<form name="form_login" action="<?php echo $_SERVER['SCRIPT_NAME'];?>" method='post' class="form-control">

		<div class="form-group">
				<div><label>Login: </label></div>
				<div><input type='text' name='login'></div>
		</div>

		<div class="form-group">
				<div><label>Password: </label></div>
				<div><input type='password' name='password'></div>
		</div>

		<div class="form-group">
				<input type='hidden' name="q" value='login'>
				<input type='submit' value='Login'>
		</div>
</form>

