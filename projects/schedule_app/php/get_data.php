<?php
//http://easy-code.ru/lesson/advanced-curl-php
error_reporting(E_ALL|E_STRICT);
ini_set('display_errors', 1);

//echo "<pre>";
//print_r($_REQUEST);
//print_r($_FILES);
//echo "</pre>";

$_vars=array();
$_vars["url"] = "";

if ( !empty($_REQUEST["request_url"]) )
{
	$_vars["url"] = $_REQUEST["request_url"];
} else {
	//echo "error, data_url not defined ";
	//echo "<br>";	
	//$_vars["log"][] = "{\"error_code\" : \"data_url not defined\", \"message\" : \"exception: \"}";
	exit();
}

if (function_exists("curl_init") ) 
{

	if( $curl = curl_init() ) 
	{
//echo "curl_init";
//echo "<br>";

		curl_setopt( $curl, CURLOPT_URL, $_vars["url"]); 
		//curl_setopt($curl, CURLOPT_CONNECTTIMEOUT ,5);
		//curl_setopt($curl, CURLOPT_TIMEOUT, 5);
	
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt( $curl, CURLOPT_HEADER, 0);
		
		$result = curl_exec( $curl );
		if ($result != FALSE) {
			header("Content-Type:  application/json");
			echo $result;
		} else {
			
			$curlError = curl_error($curl);
echo "curlError: ".$curlError;
echo "<br>";

			$curlErrorNo = curl_errno($curl);
echo "curlErrorNo: ".$curlErrorNo;
echo "<br>";
		
			$info = curl_getinfo($curl);
echo "curl_getinfo: <pre>";
print_r( curl_version() );
echo "</pre>";
			
		}

		curl_close( $curl );
	}
	else
	{
		echo "error, curl_init error...";
		echo "<br>";
		
		echo curl_error( $curl );
		echo "<br>";
		
		echo curl_errno( $curl );
		echo "<br>";
		
		$info = curl_getinfo($curl);
echo "curl_getinfo: <pre>";
print_r( curl_version() );
echo "</pre>";
	}

} else {
	//echo "error, no CURL support...";
	//echo "<br>";	
	$logMsg["eventType"] = "error";
	$logMsg["message"] = "error,  no <b>CURL</b> support... ";
	$jsonStr = json_encode($logMsg);
	echo $jsonStr;
}
?>
