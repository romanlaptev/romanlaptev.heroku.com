<?php
$id = "";
$body_value = "";
$created = ""; 
$changed = ""; 
$html_content_links = "";

if ( !empty( $params["content_item"] ) ) {
echo "<h1>".$params["content_item"][0]["title"]."</h1>";

//echo _logWrap($params["content_item"]);
$_vars["log"][] = array("message" => $params["content_item"], "type" => "info");

	$id = $params["content_item"][0]["id"];

	if( isset($params["content_item"][0]["parent_id"]) ){
		//add widget content_links!!!!!!
		$html_content_links = "<div><p><b>content_links</b>.parent_id: ".$params["content_item"][0]["parent_id"]."</p></div>";
	}
	
	$body_value = $params["content_item"][0]["body_value"];
	
	$created = $params["content_item"][0]["created"];
	$changed = $params["content_item"][0]["changed"];
	if( is_numeric($params["content_item"][0]["created"]) ){
		$created =  date("d-M-Y H:i:s", $params["content_item"][0]["created"]);
		$changed = date("d-M-Y H:i:s", $params["content_item"][0]["changed"]);
	}
}
?>

<ul>
	<li><a href='?q=content/edit&id=<?php echo $id; ?>'>edit</a></li>
	<li><a href='?q=content/remove&id=<?php echo $id; ?>'>remove</a></li>
</ul>

<div class='body-value'>
<?php echo $body_value; ?>
</div>

<div>
<b>created:</b> <?php echo $created ?>, <b>changed:</b> <?php echo $changed; ?>
</div>

<?php echo $html_content_links; ?>
