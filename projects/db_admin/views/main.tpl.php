<?php
//echo _logWrap($params);
?>
 <!DOCTYPE html>
<html>
<head>
	<title>Training database</title>
	<meta charset="utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="css/main.css" type="text/css">
</head>
<body>

<div class='header container'>
	<div class='wrapper'>
		<h1>Training database</h1>
	</div>

	<div id='block-user' class='panel'>
<?php
if ( !empty( $params["login"] ) ) {
	$html = "<h3>{{login}}</h3>";
	$html = str_replace( "{{login}}", $params["login"], $html );
	echo $html;
}
?>
	</div>

</div><!-- end header -->

<div class='menu container'>
	<div class='wrapper'>
		<ul class='pull-left'>
			<li><a href="<?php echo $_SERVER['SCRIPT_NAME']; ?>">Home</a></li>
		</ul>

		<ul class='pull-left menu-node'>
			<li><a href="?q=content/list">content</a></li>
			<li><a href="?q=content-links/list">content links</a></li>
			<li><a href="?q=taxonomy/list">tag taxonomy</a></li>
<li><a href="?q=exchange">data exchange</a></li>
<li><a href="data/notes.sqlite">download notes.sqlite</a></li>
<!-- <li><a href="?q=notes">Notes</a></li> -->

		</ul>

		<ul class='pull-right'>
			<li><a href='phpliteadmin/phpliteadmin.php' target="_blank">phpliteadmin</a></li>
<?php
	$html = "<li><a href='?q=login-form'>Log in</a></li>";
if ( !empty( $params["is_auth"] ) ) {
	$html = "<li><a href='?q=logout'>Log out</a></li>";
}
echo $html;
?>
		</ul>
	</div>
</div><!-- end menu -->

<div class='main container'>
		<div class='content'>
			<div class='wrapper'>
			  
				<div class='panel'>
<?php
if ( !empty( $params["content"] ) ) {
	echo $params["content"];
}
if ( !empty( $params["tpl_content_filename"] ) ) {
	require_once( $params["tpl_content_filename"] );
}
?>
				</div>
				
				<div class='panel log-panel'>
<?php
if ( !empty( $_vars["log"] ) ) {
	//for( $n = 0; $n < count( $_vars["log"] ); $n++){
	for( $n = count( $_vars["log"] ) - 1; $n >= 0; $n--){
		$record = $_vars["log"][$n];
		if( gettype($record["message"]) == "string"){
			$record["message"] = "<small>".$n.".</small> ".$record["message"];
		}
		echo _logWrap( $record["message"], $record["type"] );
	}//next
}
?>
				</div>
				
			</div>
		</div><!-- end content -->
</div><!-- end main -->

<div class='footer container'>
	<div class='wrapper'>
		<div class='panel'></div>
	</div>
</div><!-- end footer -->


</body>
</html>
