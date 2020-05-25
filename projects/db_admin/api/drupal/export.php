<?php
// Добавлять в отчет все PHP ошибки
error_reporting(E_ALL|E_STRICT);
ini_set('display_errors', 1);
//echo "<pre>";
//print_r($_SERVER);
//print_r($_REQUEST);
//print_r($_FILES);
//echo "</pre>";

$_vars=array();
$_vars["config"] = require_once("config.php");
require_once "inc/functions.php";

//-------------------------------------------------------------------
$_vars["runType"] = "";
$sapi_type = php_sapi_name();
//if ( $sapi_type == 'apache2handler') {
	$_vars["runType"] = "web";
//}
if ( $sapi_type == "cli" ) { $_vars["runType"] = "console"; }
if ( $sapi_type == "cgi" ) { $_vars["runType"] = "console"; }

if (!empty($_REQUEST['run_type']) )	{
	$_vars["runType"] = $_REQUEST['run_type'];
}

//=================================== WEB run
if ( $_vars["runType"] == "web") {
//echo "<pre>";
//print_r($_SERVER);
//print_r($_REQUEST);
//print_r($_FILES);
//print_r($_vars);
//echo "</pre>";

//--------------------------------------- load Drupal
	$drupalConstFile = $_vars["config"]["export"]["drupalConstFile"];
	if ( !file_exists( $drupalConstFile ) ){
		$msg = "error, not find Drupal constant file ".$drupalConstFile;
		echo $msg;
	}
/*
	// Define default settings.
	chdir ("../");
	//echo getcwd();
	//echo "<br>";

	define('DRUPAL_ROOT', getcwd() );
	$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
	//echo _logWrap( DRUPAL_ROOT );
*/
	// Bootstrap Drupal.
	require_once $drupalConstFile;
	//drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);


	$_vars["form"] = "";
	if (empty($_REQUEST['action']))	{
		$_vars["form"] = exportForm();
	} else {
		$action = $_REQUEST['action'];
		switch ($action) {
			case "export":
				foreach( $_REQUEST as $param => $value){
					if( !empty($value) ){
						$_vars["config"]["export"][$param] = $value;
					}
				}//next
echo _logWrap( $_vars );
				//_exportProcess();
			break;
		}//end switch
	}//end elseif

	echo PageHead();
	echo $_vars["form"];
	echo PageEnd();
}


//==================================== CONSOLE run
if ( $_vars["runType"] == "console") {
//print_r($argv);
//$_SERVER["argv"]
	//_exportProcess();
}


//====================================== LOG
if ( !empty( $_vars[ "log" ] ) ) {
	for( $n = 0; $n < count( $_vars["log"] ); $n++){
	//for( $n = count( $_vars["log"] ) - 1; $n >= 0; $n--){
		$record = $_vars["log"][$n];
		echo _logWrap( $record["message"], $record["type"] );
	}//next
}


//====================
function exportProcess(){
	global $_vars;
/*
try{
				$db = new PDO($sqlite_path);
}catch(Exception $e){
	echo "exception: ",  $e->getMessage(), "\n";
echo "<pre>";
print_r($e);
echo "</pre>";
exit("<p>Error, could not open database!!!!!!!!!</p>");
}
*/
}//end exportProcess()


//------------------------- получить все термины таксономии
function get_taxonomy( $voc_title ){
	global $db;
	$sql = "
SELECT 
taxonomy_term_data.tid as term_id,
taxonomy_term_data.name as cat_name,
taxonomy_term_hierarchy.parent as category_parent
FROM taxonomy_term_data
LEFT JOIN taxonomy_term_hierarchy ON taxonomy_term_hierarchy.tid=taxonomy_term_data.tid
WHERE taxonomy_term_data.vid IN
(SELECT taxonomy_vocabulary.vid FROM taxonomy_vocabulary WHERE taxonomy_vocabulary.name='$voc_title')
ORDER BY category_parent, taxonomy_term_data.weight;
";
//echo "sql = ".$sql;
//echo "<hr>";
	$result = $db->query($sql);
	$result->setFetchMode(PDO::FETCH_OBJ);
	$fresult = $result->fetchAll();
	return $fresult;
}//end get_taxonomy()


//------------------------- получить связи терминов таксономии и нод
function get_taxonomy_index(){
	global $db;
	$sql = "SELECT nid,tid FROM taxonomy_index";
//echo "get_taxonomy_index, sql = ".$sql;
//echo "<hr>";
	$result = $db->query($sql);
	$result->setFetchMode(PDO::FETCH_OBJ);
	$fresult = $result->fetchAll();

	return $fresult;
}//end get_taxonomy_index()


function sort_termin_hierarchy(){
	global $book, $taxonomy_sort;
	foreach ( $book["taxonomy"] as $termin )
	{
		if ( $termin->category_parent == 0 )
		{
			$taxonomy_sort[] = $termin;
			$parent_id = $termin->term_id;
			$result = test_termin_hierarchy( $parent_id );
			if (!empty($result))
			{
				$taxonomy_sort[] = $result;
			}
		}
	}
	return $taxonomy_sort;
}//end sort_termin_hierarchy()

function test_termin_hierarchy( $parent_id ){
	global $book, $taxonomy_sort;
	foreach ( $book["taxonomy"] as $termin )
	{
		if ( $termin->category_parent == $parent_id )
		{
			$taxonomy_sort[] = $termin;
			$result = test_termin_hierarchy( $termin->term_id );
			if (!empty($result))
			{
				$taxonomy_sort[] = $result;
			}
		}
	}
}//end test_termin_hierarchy()


//---------------------------------------------
// получить menu_links.mlid всех страниц книги 
//---------------------------------------------
function get_content($book_title)
{
	global $db;

	$sql = "SELECT menu_links.mlid FROM menu_links WHERE menu_links.menu_name IN (SELECT menu_name FROM menu_links WHERE link_title LIKE '".$book_title."' AND module='book') ORDER BY weight ASC;";
//echo "sql = ".$sql;
//echo "<hr>";
	$result = $db->query($sql);
	//$result->setFetchMode(PDO::FETCH_ASSOC);
	$result->setFetchMode(PDO::FETCH_OBJ);

	$sql_mlid="";
	foreach ($result as $num => $row )
	{
		if ($num==0)
		{
			$sql_mlid .= $row->mlid;
		}
		else
		{
			$sql_mlid .= ", ".$row->mlid;
		}
	}
	if ( !empty($sql_mlid) )
	{
//echo "sql_mlid = ".$sql_mlid;
//echo "<br>";
		$pages=array();
		$pages = get_pages ($sql_mlid);
		return $pages;
	}

}//---------------------- end func

//-------------------------
// получить страницы книги
//-------------------------
function get_pages ($sql_mlid)
{
	global $db;
	$sql = "
SELECT 
book.mlid, 
book.nid, 
menu_links.plid, 
node.nid, 
node.title, 
node.created, 
node.changed, 
field_data_body.body_value, 
menu_links.weight, 
file_managed.filename
FROM book 
LEFT JOIN menu_links ON menu_links.mlid=book.mlid 
LEFT JOIN node ON node.nid=book.nid
LEFT JOIN file_usage ON file_usage.id=node.nid 
LEFT JOIN file_managed ON file_managed.fid=file_usage.fid
LEFT JOIN field_data_body ON field_data_body.entity_id=node.nid 
WHERE 
book.mlid in (".$sql_mlid.") AND node.status=1 ORDER BY menu_links.weight,title ASC
";
//echo "sql = ".$sql;
//echo "<hr>";
	$result = $db->query($sql);
	$result->setFetchMode(PDO::FETCH_OBJ);
	$fresult = $result->fetchAll();

	return $fresult;
}//---------------------------- end func


function write_xml($data)
{
	global $message;
	global $fs_path,$url_path, $filename;

	$xml="";
	$xml .= "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\n";
	$xml .="<book>\n";
	$xml .= "<nodes>\n";
	foreach ($data["nodes"] as $row )
	{

//print_r($row);
		$nid = $row->nid;
		$mlid = $row->mlid; 
		$plid = $row->plid; 
		$title = htmlspecialchars($row->title);
		$created = date('d-M-Y H:i:s', $row->created);
		$changed = date('d-M-Y H:i:s', $row->changed);
		$node_filename = $row->filename;
		$weight = $row->weight;

		$xml .=  "<node title=\"".$title."\" ";
		$xml .=  "nid=\"".$nid."\" ";
		$xml .=  "mlid=\"".$mlid."\" ";
		$xml .=  "plid=\"".$plid."\" ";
		$xml .=  "created=\"".$created."\" ";
		$xml .=  "changed=\"".$changed."\" ";
		$xml .=  "weight=\"".$weight."\"";
		$xml .=  ">\n";
		if (!empty($row->body_value))
		{
			$body = $row->body_value;
			$body = htmlspecialchars ($body);
//echo $body;
//echo "<br>";
			$xml .=  "<body_value>\n";
			$xml .=  $body."\n";
			$xml .=  "</body_value>\n";
		}

		if ($filename!="")
		{
			$xml .=  "<filename>\n";
			$xml .=  $node_filename."\n";
			$xml .=  "</filename>\n";
		}

		$xml .= "</node>\n";
	}//----------------------- end foreach
	$xml .= "</nodes>\n";
	$xml .="</book>\n";

//echo "<pre>";
//echo htmlspecialchars($xml);
//echo "</pre>";

	//----------------------------------- write xml file
	if ( !empty($xml) )
	{
		header('Content-Type:  application/xhtml+xml');
		header('Content-Disposition: attachment; filename='.$filename.'');
		header('Content-Transfer-Encoding: binary');
		header('Content-Length: '.strlen($xml));
		echo $xml;

/*
		$num_bytes = file_put_contents ($fs_path."/".$filename, $xml);
		if ($num_bytes > 0)
		{
$message .= "<span class='ok'>Write </span>".$num_bytes." bytes  in ".$filename;
$message .= "<br>";
$message .= "<a href='".$url_path."/".$filename."' target='_blank'>".$filename."</a>";
//echo "<pre>";
//readfile ($output_filename);
//echo "</pre>";

		}
		else
		{
$message .= getcwd();
$message .= "<br>";
$message .= "<span class='error'>Write error in xml/</span>".$filename;
$message .= "<br>";
		}
*/
	}
	//-----------------------------------

}//-------------------------- end func


function write_wxr($data)
{
	global $message;
	global $filename;
	global $voc_title;

	$xml="";
	$xml .= "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\n";
	$xml .= "<rss version=\"2.0\" 
xmlns:excerpt=\"http://wordpress.org/export/1.2/excerpt/\" 
xmlns:content=\"http://purl.org/rss/1.0/modules/content/\" 
xmlns:wfw=\"http://wellformedweb.org/CommentAPI/\" 
xmlns:dc=\"http://purl.org/dc/elements/1.1/\" 
xmlns:wp=\"http://wordpress.org/export/1.2/\">\n";
	$xml .="<channel>\n";
	$xml .="\t<title></title>\n";
	$xml .="\t<link></link>\n";
	$xml .="\t<description/>\n";
	$xml .="\t<pubDate>" .date('D, d M Y H:i:s O'). "</pubDate>\n";
	$xml .="\t <language>ru</language>\n";
	$xml .="\t <wp:wxr_version>1.2</wp:wxr_version>\n";
	$xml .="\t <wp:base_site_url></wp:base_site_url>\n";
	$xml .="\t <wp:base_blog_url></wp:base_blog_url>\n";
	$xml .="\t <wp:author>\n";
	$xml .="\t\t <wp:author_login>drupal_export</wp:author_login>\n";
	$xml .="\t\t  <wp:author_email></wp:author_email>\n";
	$xml .="\t\t  <wp:author_display_name><![CDATA[Drupal_export]]></wp:author_display_name>\n";
	$xml .="\t\t  <wp:author_first_name><![CDATA[]]></wp:author_first_name>\n";
	$xml .="\t\t  <wp:author_last_name><![CDATA[]]></wp:author_last_name>\n";
	$xml .="\t </wp:author>\n";

	foreach ($data["taxonomy"] as $row )
	{
		$xml .="\t <wp:category>\n";
		//$xml .="\t\t <wp:term_id>" .$row->term_id. "</wp:term_id>\n";
		$xml .="\t\t <wp:category_nicename>" .$row->cat_name. "</wp:category_nicename>\n";

		$category_parent_name = "";
		foreach ($data["taxonomy"] as $row2 )
		{
			if (  $row->category_parent == $row2->term_id)
			{
				$category_parent_name = $row2->cat_name;
			}
		}//----------------------- end foreach
		$xml .="\t\t <wp:category_parent>" .$category_parent_name. "</wp:category_parent>\n";

		$xml .="\t\t <wp:cat_name><![CDATA[" .$row->cat_name. "]]></wp:cat_name>\n";
		$xml .="\t </wp:category>\n";
	}//----------------------- end foreach

	foreach ($data["nodes"] as $row )
	{
		$nid = $row->nid;
		$mlid = $row->mlid; 
		$plid = $row->plid; 
		$title = htmlspecialchars($row->title);
		$created = date('Y-m-d H:i:s', $row->created);
		$pubdate = date('D, d M Y H:i:s O', $row->created);
		$changed = date('Y-m-d H:i:s', $row->changed);
		$node_filename = $row->filename;
		$weight = $row->weight;

		$xml .="\t <item>\n";
		$xml .="\t\t <title>" .$title. "</title>\n";
		$xml .="\t\t <link></link>\n";
		$xml .="\t\t <pubDate>" .$pubdate. "</pubDate>\n";
		$xml .="\t\t <dc:creator>drupal_export</dc:creator>\n";
		$xml .="\t\t <guid isPermaLink=\"false\"></guid>\n";
		$xml .="\t\t <description/>\n";
		if (!empty($row->body_value))
		{
			$body = $row->body_value;
			$body = htmlspecialchars ($body);
			$xml .=  "<content:encoded><![CDATA[\n";
			$xml .=  $body."\n";
			$xml .=  "]]></content:encoded>\n";
		}
		$xml .="\t\t <excerpt:encoded><![CDATA[]]></excerpt:encoded>\n";
		$xml .="\t\t <wp:post_id>" .$nid. "</wp:post_id>\n";
		$xml .="\t\t <wp:post_date>" .$created. "</wp:post_date>\n";
		$xml .="\t\t <wp:post_date_gmt>" .$changed. "</wp:post_date_gmt>\n";
		$xml .="\t\t <wp:comment_status>closed</wp:comment_status>\n";
		$xml .="\t\t <wp:ping_status>closed</wp:ping_status>\n";
		$xml .="\t\t <wp:post_name>" .$title. "</wp:post_name>\n";
		$xml .="\t\t <wp:status>publish</wp:status>\n"; // publish, draft, pending, private
		$xml .="\t\t <wp:post_parent>" .$plid. "</wp:post_parent>\n";
		$xml .="\t\t <wp:menu_order>0</wp:menu_order>\n";
		$xml .="\t\t <wp:post_type>post</wp:post_type>\n"; //post, page, media
		$xml .="\t\t <wp:post_password/>\n";
		$xml .="\t\t <wp:is_sticky>0</wp:is_sticky>\n";

		$category_name = "";
		foreach ($data["taxonomy_index"] as $row3 )
		{
			if (  $row->nid == $row3->nid)
			{
				$category_tid = $row3->tid;
				foreach ($data["taxonomy"] as $row2 )
				{
					if (  $row2->term_id == $category_tid )
					{
						$category_name = $row2->cat_name;
					}
				}//----------------------- end foreach
				$xml .="\t\t <category domain=\"category\" nicename=\"$category_name\"><![CDATA[" .$category_name. "]]></category>\n";
			}
		}//----------------------- end foreach

		$xml .="\t </item>\n";
	}//----------------------- end foreach

	$xml .="</channel>\n";
	$xml .="</rss>\n";

//echo "<pre>";
//echo htmlspecialchars($xml);
//echo "</pre>";

	//----------------------------------- write xml file
	if ( !empty($xml) )
	{
		header('Content-Type:  application/xhtml+xml');
		header('Content-Disposition: attachment; filename='.$filename.'');
		header('Content-Transfer-Encoding: binary');
		header('Content-Length: '.strlen($xml));
		echo $xml;
	}
	//-----------------------------------

}//end

?>
