<?php
$config = array(
	//'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR,
	'db'=>array(
		'dbType' => 'sqlite',
		'dbName' => 'notes.sqlite',
		'dsn' => 'sqlite:'.dirname(__FILE__).'/data/notes.sqlite',
		
		//'dsn' => 'mysql:host=localhost;dbname=db1',
//		'dsn' => 'mysql:host=localhost',
//		'dbName' => 'db2',
//		'dbUser' => 'root',
//		'password' => 'master',
	),
	'export'=>array(
		'filename' => 'export.xml',
		'filePath' => dirname(__FILE__)."/data/export_test.xml",
		//'filePath' => "/mnt/d2/temp/export.xml",
		'content_type' => ''//note, book, video....
	)
);

$config["content_types"] = array(
'page',
'article',
'note',
'book',
'video',
'music'
);		

$config["filter_formats"] = array(
	array(
		"format" => "plain_text",
		"name" => "Plain text",
	),
	array(
		"format" => "filtered_html",
		"name" => "Filtered HTML",
	),
	array(
		"format" => "full_html",
		"name" => "Full HTML",
	),
	array(
		"format" => "php_code",
		"name" => "PHP code",
	)
);
$config["default_filter_formats"] = "plain_text";

//============ XML templates
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


return $config;
?>
