<?php
ini_set("display_errors", true);

$moduleName = "mysql";
$loadedExt = get_loaded_extensions();

echo extension_loaded($moduleName);
if ( !in_array( $moduleName, $loadedExt ) ) {
	$msg = "<p>-- error, $moduleName module  is not in the list of loaded extensions...</p>";
	echo $msg;
echo "loaded_extensions:<pre>";
print_r( $loadedExt );
echo "</pre>";
	exit;
}

	require_once dirname(__FILE__)."/config/main.php";
	require_once dirname(__FILE__)."/include/db.php";
	require_once dirname(__FILE__)."/include/functions.php";
	require_once dirname(__FILE__)."/controllers/" . $def_controller .".php";
?>
