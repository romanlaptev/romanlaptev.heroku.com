<?php
class DB {
	
	private $dbTablesChecked = false;
	public $dbConnection = null;
	private	static $instance = null;
/*	
	public $infoSchema = array(
		//"content" => array(
			//"id" => "integer",
			////"category_id" => "integer",
			//"type_id" => "integer",
			//"title" => "string",
			//"body_value" => "string",
			//"created" => "DATETIME",
			//"changed" => "DATETIME"
			
		//),
		//"category" => array(
			//"id" => "integer",
			//"parent_id" => "integer",
			//"title" => "string"
		//),
		"taxonomy_groups" => array(
			"id" => "integer",
			"name" => "string"
		),
	);
*/
	private function __construct(){
		global $_vars;
		//$this->config = $params;
		$msg = "Object of class ".__CLASS__." was created.";
		$_vars["log"][] = array("message" => $msg, "type" => "info");
	}
	public static function getInstance() {
		if( is_null( self::$instance ) ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	private function dbConnect( $dsn ){
		global $_vars;
		try {
			$this->dbConnection = new PDO( $dsn );
			$this->dbConnection->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		} catch (Exception $e) {
			$_vars["log"][] = array("message" => $e, "type" => "error");
			return false;
		}	
	}//end dbConnect()
	
	private function db_connect( $dsn ){
		global $_vars;
		try {
			$connection = new PDO( $dsn );
			$connection->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
			return $connection;
		} catch (Exception $e) {
			$_vars["log"][] = array("message" => $e, "type" => "error");
			return false;
		}	
	}//end db_connect()


	public function runQuery( $connection, $sql_query ){
		global $_vars;

		if( !$connection ){
//echo _logWrap("wrong db connection");			
			//$dbType = $_vars["config"]["db"]["dbType"];
			$dsn = $_vars["config"]["db"]["dsn"];
			$connection = $this->db_connect( $dsn );
		}
//echo _logWrap($connection);
//return false;
		
		$result=false;
		try{
			$result  = $connection->query( $sql_query );
		} catch(Exception $e) {
//echo "Exception:". _logWrap($e);
		}
		
		if( !$result ){
			$msg =  "error, query: ".$sql_query;
			$_vars["log"][] = array("message" => $msg, "type" => "error");

			$msg =  "error info: ". _logWrap( $connection->errorInfo() );
			$_vars["log"][] = array("message" => $msg, "type" => "error");

			$arr = $connection->errorInfo();
			$desc = $arr[2];
			return array(
				"status" => false,
				"type" => "error",
				"description" => $desc
			);
		}

		if( !isset( $_vars["display_log"] ) ) {
			$_vars["display_log"] = true;
		}			
		if( $_vars["display_log"] == true ) {
			$msg =  "run query: <b>".$sql_query."</b>";
			$_vars["log"][] = array("message" => $msg, "type" => "success");
		}			

		$response = array(
			"status" => true,
			"type" => "success",
		);
		
		//$rows  = $result->fetchAll( PDO::FETCH_NUM );
		$rows  = $result->fetchAll( PDO::FETCH_ASSOC );
		//echo count( $rows );
		if( count( $rows ) > 0 ){
			$response["data"] = $rows;
		}

		return $response;
	}//end runQuery()



	public function initDb(){
		global $_vars;
//echo "TEST:<pre>";
//print_r($this->config);
//echo "</pre>";
		//$dbType = $this->config["dbType"];
		//$dsn = $this->config["dsn"];
		$dbType = $_vars["config"]["db"]["dbType"];
		$dsn = $_vars["config"]["db"]["dsn"];
		switch($dbType){

			case "sqlite":
if( $this->dbTablesChecked ){
	return true;
}			
				//$split_arr = explode( ":", $dsn);
				//$filePath = $split_arr[1];
				$filePath = str_replace("sqlite:", "", $dsn);

		 		if ( !file_exists( $filePath ) )	{
		 			$msg = "error, not found database: <b>".$filePath."</b>";
					$_vars["log"][] = array("message" => $msg, "type" => "error");
		 		}

				$perms = substr(sprintf('%o', fileperms( dirname($filePath) ) ), -4);
				if ( !is_writable( dirname($filePath) ) ){
					$msg = "Could not write to <b>" .dirname($filePath)."</b> (". $perms.") ";
					$_vars["log"][] = array("message" => $msg, "type" => "error");
					return false;
				}

				$this->dbConnect( $dsn );
				if( $this->dbConnection ){
					$this->dbTablesChecked = true;
					return $this->checkDbSchema();
				}
			break;

		}//end switch

		return false;
	}//end initDb()


	private function checkDbSchema(){
		global $_vars;
		
		if(!$_vars["db_schema"]){
			return true;//do not check database tables
		}
		$sql_query = $_vars["db_schema"]["SQLITE"];
		//$response = $this->runQuery( $this->dbConnection, $sql_query);
		//if( $response["status"] ){
			//return true;
		//}
		
		$this->dbConnection->setAttribute(PDO::ATTR_EMULATE_PREPARES, 0);
		try {
			
			$result  = $this->dbConnection->exec($sql_query);
			//$msg =  "result: ". $result;
			//$_vars["log"][] = array("message" => $msg, "type" => "info");
			$msg = "initialize database <b>".$_vars["config"]["db"]["dsn"]."</b>";
			$_vars["log"][] = array("message" => $msg, "type" => "success");
			return true;
			
		} catch(PDOException $e) {
			//echo $e->getMessage();
			//die();
			$msg = "error,  could not initialize database...";
			$msg .=  "error info: ". $e->getMessage();
			$_vars["log"][] = array("message" => $msg, "type" => "error");
			return false;
		}
		
	}//end checkDb()


	public function saveRecord($params){
//echo _logWrap($params);
		global $_vars;
		
		$p = array(
			"tableName" => false,
			"data" => array(),//array of field => value
			"query_condition" => false// "id=value", "title=value"...
		);

		//extend options object $p
		foreach( $params as $key=>$item ){
			$p[ $key ] = $item;
		}//next
//echo _logWrap($p);
	
		if( !$p["tableName"] ){
			$msg =  "error, wrong 'tableName'...";
			$_vars["log"][] = array("message" => $msg, "type" => "error");
			return false;
		}
		$tableName = $p["tableName"];
		
		if( empty( $p["data"]) ){
			$msg =  "error, empty data...";
			$_vars["log"][] = array("message" => $msg, "type" => "error");
			return false;
		}


		$response = $this->initDb();
		if( !$response ){
			return false;
		}
		
//-----------------------------	escape quotes
//https://www.php.net/manual/ru/pdo.quote.php
		foreach( $p["data"] as $field=>$value){
			$p["data"][$field] = $this->dbConnection->quote($value);
		}//next
//-----------------------------	
		
		//INSERT
		if( !$p["query_condition"] ) {
			$fields_string = "";
			$values_string = "";
			$n=0;
			foreach( $p["data"] as $field=>$value){
				if( $n === 0 ){
					$fields_string .= "`".$field."`";
					$values_string .= $value;
				} else {
					$fields_string .= ",`".$field."`";
					$values_string .= ",".$value;
				}
				$n++;
			}//next

			//$sql_query = "INSERT INTO `".$tableName."` (".$fields_string.") VALUES (".$values_string."); ";

//https://www.w3resource.com/sqlite/sqlite-insert-into.php
//http://www.mysql.ru/docs/man/REPLACE.html
//$sql_query = "INSERT OR REPLACE INTO `".$tableName."` (".$fields_string.") VALUES (".$values_string."); ";
$sql_query = "REPLACE INTO `".$tableName."` (".$fields_string.") VALUES (".$values_string."); ";
		}
		
		//UPDATE
		if( !empty( $p["query_condition"] ) ){
			
			$fields_string = "";
			$n=0;
			foreach( $p["data"] as $field=>$value){
				//if( !empty( $value) ){
				if( $value !== false || $value !== null ){
					if( $n === 0 ){
						$fields_string .= "`".$field."` = ".$value;
					} else {
						$fields_string .= ", `".$field."` = ".$value;
					}
					$n++;
				}
			}//next
			//$nameSk = $p["search_key"]["name"];
			//$valueSk = $p["search_key"]["value"];
			//$sql_query = "UPDATE `".$tableName."` SET ".$fields_string." WHERE ".$nameSk."='".$valueSk."'; ";
			
			$sql_query = "UPDATE `".$tableName."` SET ".$fields_string." WHERE ".$p["query_condition"]."; ";
		}
//echo _logWrap( $sql_query );
//return false;
		
		$response = $this->runQuery( $this->dbConnection, $sql_query);
		if( $response["status"] ){
			return true;
		}
		return false;
		
	}//end saveRecord()


	public function getRecords($params){
//echo _logWrap($params);
		global $_vars;
		
		$p = array(
			"tableName" => false,
			"fields" => array(),//array of field names
			//"search_keys" => false//WHERE search_key["name"] = search_key["value"]
			"query_condition" => false// "type__id=2,....."
		);
		//extend options object $p
		foreach( $params as $key=>$item ){
			$p[ $key ] = $item;
		}//next
//echo _logWrap($p);
	
		if( !$p["tableName"] ){
			$msg =  "error, wrong 'tableName'...";
			$_vars["log"][] = array("message" => $msg, "type" => "error");
			return false;
		}
		$tableName = $p["tableName"];

		if( !$p["fields"] ){
			$msg =  "error, empty array 'fields'...";
			$_vars["log"][] = array("message" => $msg, "type" => "error");
			return false;
		}

		//SELECT
		$fields_string = implode(', ', $p["fields"]);
		$sql_query = "SELECT ".$fields_string." FROM ".$tableName."; ";
		if( !empty( $p["query_condition"] ) ){
			$sql_query = "SELECT ".$fields_string." FROM ".$tableName." ".$p["query_condition"]."; ";
		}

//echo _logWrap( $sql_query );
//return false;

		$response = $this->initDb();
		if( !$response ){
			return false;
		}
		
		$response = $this->runQuery( $this->dbConnection, $sql_query);
//echo _logWrap( $response );
		//if( $response["status"] && count() ){
		if( !empty($response["data"]) ){
			return $response["data"];
		}
		return false;

	}//end getRecords()



	public function removeRecords($params){
//echo _logWrap($params);
		//global $_vars;
		$p = array(
			"tableName" => false,
			"query_condition" => false// "id=value"
		);
		//extend options object $p
		foreach( $params as $key=>$item ){
			$p[ $key ] = $item;
		}//next
//echo _logWrap($p);
	
		if( !$p["tableName"] ){
			$msg =  "error, wrong 'tableName'...";
			$_vars["log"][] = array("message" => $msg, "type" => "error");
			return false;
		}
		$tableName = $p["tableName"];


		//DELETE
		$sql_query = "DELETE FROM ".$tableName;
		
		if( !empty( $p["query_condition"] ) ){
			$sql_query = "DELETE FROM ".$tableName." WHERE ".$p["query_condition"]."; ";
		}
//echo _logWrap( $sql_query );
		
		$response = $this->initDb();
		if( !$response ){
			return false;
		}
		
		$response = $this->runQuery( $this->dbConnection, $sql_query);
//echo _logWrap( $response );
		if( $response["status"] ){
			return true;
		}
		return false;

	}//end removeRecords()
		
}//end class
?>
