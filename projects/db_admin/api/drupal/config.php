<?php
$config = array(
/*	
	'db'=>array(
		'dbType' => 'sqlite',
		'dbName' => 'mydb.sqlite',
		//'dsn' => 'sqlite:'.dirname(__FILE__).'/mydb.sqlite',
		'dsn' => 'sqlite:/home/www/sites/mydb/db/mydb.sqlite'
		//'dsn' => 'mysql:host=localhost;dbname=db1',
//		'dsn' => 'mysql:host=localhost',
//		'dbName' => 'db2',
//		'dbUser' => 'root',
//		'password' => 'master',
	),
*/
	'export'=>array(
		'filename' => 'export.xml',
		'file_path' => dirname(__FILE__)."/export.xml",
		"drupal_root"  => "/home/www/sites/mydb",
		"content_book"  => "notes",
		"tag_group" => "notes",
		'content_type' => '', //page, note, book, video, playlist
		"content_book" => 'notes',
		"tag_group" => 'tags',//library, alphabetical_voc
		"tag_name" => 'linux',//windows, config
		"type_export_content" => "nodes_all",
		"export_format" => "xml", //json, csv
		"list_drupal_cms_filepath" => "
/home/www/sites/mydb
/mnt/serv_d1/www/sites/music/cms/music_drupal
/mnt/serv_d1/www/sites/video/cms
/mnt/serv_d1/www/sites/lib/cms"
	)
);

$config["export"]["xml_template"] = '<?xml version="1.0" encoding="UTF-8"?>
<xroot>
	<schema>
		<xdata db_name="" db_type="" export_date="" export_time="">
			<content>
					<node id="" type="">
						<title></title>
						<created></created>
						<changed></changed>
						<body_value body_format=""></body_value>
					</node>
			</content>

			<content_links>
				<item content_id="" parent_id=""></item>
			</content_links>		

			<tag_groups>
				<item id="" name=""></item>
			</tag_groups>		

			<tag_links>
				<item content_id="" term_id=""></item>
			</tag_links>		

			<tag_list>
				<item id="" term_group_id="" parent_id="" name=""></item>
			</tag_list>		

		</xdata>
	</schema>

	<xdata db_name="{{dbName}}" db_type="{{dbType}}" export_date="{{exportDate}}" export_time="{{exportTime}}">
{{tag_groups}}
{{tag_list}}
{{tag_links}}
{{content_links}}
{{content}}
	</xdata>
</xroot>';

$config["export"]["tplContent"] = '<content>{{nodelist}}</content>';
$config["export"]["tplContentNode"] = '<node id="{{id}}" {{type}}>
	<title>{{title}}</title>
	<created>{{created}}</created>
	<changed>{{changed}}</changed>
	<body_value body_format="{{body_format}}">
{{body_value}}
	</body_value>
</node>';

$config["export"]["tplContentLinks"] = '<content_links>{{nodelist}}</content_links>';
$config["export"]["tplContentLink"] = '<item content_id="{{content_id}}" parent_id="{{parent_id}}"></item>';

$config["export"]["tplTagGroups"] = '<tag_groups>{{nodelist}}</tag_groups>';
$config["export"]["tplTagGroup"] = '<item id="{{id}}" name="{{name}}"></item>';

$config["export"]["tplTagList"] = '<tag_list>{{nodelist}}</tag_list>';
$config["export"]["tplTag"] = '<item id="{{id}}" term_group_id="{{term_group_id}}" parent_id="{{parent_id}}" name="{{name}}"></item>';

$config["export"]["tplTagLinks"] = '<tag_links>{{nodelist}}</tag_links>';
$config["export"]["tplTagLink"] = '<item content_id="{{content_id}}" term_id="{{term_id}}"></item>';

/*
<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0" 
xmlns:excerpt="http://wordpress.org/export/1.2/excerpt/" 
xmlns:content="http://purl.org/rss/1.0/modules/content/" 
xmlns:wfw="http://wellformedweb.org/CommentAPI/" 
xmlns:dc="http://purl.org/dc/elements/1.1/" 
xmlns:wp="http://wordpress.org/export/1.2/">
	<channel>
	<title></title>
	<link></link>
	<description/>
		<pubDate></pubDate>
		<language>ru</language>
		<wp:wxr_version>1.2</wp:wxr_version>
		<wp:base_site_url></wp:base_site_url>
		<wp:base_blog_url></wp:base_blog_url>
		<wp:author>
			<wp:author_login>drupal_export</wp:author_login>
					<wp:author_email></wp:author_email>
					<wp:author_display_name><![CDATA[Drupal_export]]></wp:author_display_name>
					<wp:author_first_name><![CDATA[]]></wp:author_first_name>
					<wp:author_last_name><![CDATA[]]></wp:author_last_name>
		</wp:author>

		<wp:category>
			<wp:term_id></wp:term_id>
			<wp:category_nicename></wp:category_nicename>
			<wp:category_parent></wp:category_parent>
			<wp:cat_name><![CDATA[" .$row->cat_name. "]]></wp:cat_name>
		</wp:category>

		<item>
			<title></title>
			<link></link>
			<pubDate></pubDate>
			<dc:creator>drupal_export</dc:creator>
			<guid isPermaLink="false"></guid>
			<description/>
			
			<content:encoded><![CDATA[ $body ]]></content:encoded>
			 
			<excerpt:encoded><![CDATA[]]></excerpt:encoded>
			<wp:post_id>" .$nid. "</wp:post_id>
			<wp:post_date>" .$created. "</wp:post_date>
			<wp:post_date_gmt>" .$changed. "</wp:post_date_gmt>
			<wp:comment_status>closed</wp:comment_status>
			<wp:ping_status>closed</wp:ping_status>
			<wp:post_name>" .$title. "</wp:post_name>
			<wp:status>publish</wp:status> // publish, draft, pending, private
			<wp:post_parent>" .$plid. "</wp:post_parent>
			<wp:menu_order>0</wp:menu_order>
			<wp:post_type>post</wp:post_type> //post, page, media
			<wp:post_password/>
			<wp:is_sticky>0</wp:is_sticky>

			<category domain="category" nicename="$category_name">
				<![CDATA[" .$category_name. "]]>
			</category>

		</item>

 */ 
 
$config["sql"]["nodes_all"] = "SELECT 
node.nid as id, 
node.title, 
node.type, 
node.created, 
node.changed,
field_data_body.body_value 
FROM node 
LEFT JOIN field_data_body ON field_data_body.entity_id=node.nid 
WHERE node.status=1
ORDER BY node.created;";

$config["sql"]["nodes_book"] = "SELECT 
-- book.mlid, book.nid, 
-- menu_links.plid, 
node.nid as id, node.type, node.status, node.created,  node.changed, node.title, 
field_data_body.body_value
FROM book 
LEFT JOIN menu_links ON menu_links.mlid=book.mlid 
LEFT JOIN node ON node.nid=book.nid
LEFT JOIN field_data_body ON field_data_body.entity_id=node.nid 
WHERE node.status=1 AND book.mlid in (
    SELECT menu_links.mlid FROM menu_links WHERE menu_links.menu_name IN (
        SELECT menu_name FROM menu_links WHERE link_title LIKE '{{content_book}}' AND module='book'
    ) ORDER BY menu_links.weight ASC
) ORDER BY menu_links.weight,title ASC;";

$config["sql"]["nodes_tag"] = "SELECT 
node.nid as id, node.type, node.status, node.created, node.changed, node.title, 
field_data_body.body_value
FROM node, field_data_body 
WHERE node.nid IN ( 
	SELECT taxonomy_index.nid FROM  taxonomy_index WHERE taxonomy_index.tid IN ( 
		SELECT  taxonomy_term_data.tid FROM  taxonomy_term_data WHERE taxonomy_term_data.name='{{tag_name}}'
	)
) AND node.status=1 AND field_data_body.entity_id=node.nid;";

$config["sql"]["nodes_type"] = "SELECT 
node.nid as id, 
node.title, 
node.type, 
node.created, 
node.changed,
field_data_body.body_value 
FROM node 
LEFT JOIN field_data_body ON field_data_body.entity_id=node.nid 
WHERE node.status=1 AND 
node.type='{{content_type}}' ORDER BY node.created;";


$config["sql"]["content_links"] = "SELECT 
book.nid as content_id, 
book.mlid,
menu_links.plid as parent_id_link 
FROM book, menu_links 
WHERE menu_links.mlid=book.mlid;";

$config["sql"]["content_links_book"] = "SELECT 
book.nid as content_id, 
book.mlid,
-- book.bid
menu_links.plid as parent_id_link 
FROM book, menu_links 
WHERE book.bid=(
    SELECT book.nid FROM book 
    WHERE book.mlid=(
        SELECT menu_links.mlid FROM menu_links WHERE link_title LIKE '{{content_book}}' AND module='book'
    )
) AND menu_links.mlid=book.mlid;
";

/*
$config["sql"]["content_links_type"] = "SELECT 
book.nid as content_id, 
book.mlid,
menu_links.plid as parent_id_link 
FROM book, menu_links, node 
WHERE menu_links.mlid=book.mlid AND
book.nid=node.nid AND
node.type='{{content_type}}';
";
*/

/*
SELECT 
book.nid as content_id, 
book.mlid,
menu_links.plid as parent_id_link 
FROM book, menu_links 
WHERE book.nid in (
	SELECT taxonomy_index.nid FROM  taxonomy_index WHERE taxonomy_index.tid IN ( 
		SELECT  taxonomy_term_data.tid FROM  taxonomy_term_data WHERE taxonomy_term_data.name='drupal'
	)
) AND menu_links.mlid=book.mlid;
*/

$config["sql"]["tag_groups"] = "SELECT 
vid as id, 
name
-- description
-- hierarchy 
FROM taxonomy_vocabulary;";

$config["sql"]["tag_list"] = "SELECT 
taxonomy_term_data.tid as id, 
taxonomy_term_data.vid as term_group_id,
name, 
taxonomy_term_hierarchy.parent as parent_id
FROM taxonomy_term_data, taxonomy_term_hierarchy 
WHERE taxonomy_term_hierarchy.tid=id
ORDER BY term_group_id;";

$config["sql"]["tag_links"] = "SELECT 
nid as content_id, 
tid as term_id
-- created
FROM taxonomy_index 
ORDER BY term_id;";

//$config["drupal_body_format"] = array(
	//"plain_text" => "Plain text",
	//"filtered_html" => "Filtered HTML",
	//"full_html" => "Full HTML",
	//"php_code" => "PHP code"
//);
 
return $config;
?>
