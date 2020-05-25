<?php
return array(
	//'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR,
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
	'export'=>array(
		//'filename' => 'export.xml',
		'file_path' => dirname(__FILE__)."/export_test.xml",
		"drupalConstFile"  => dirname(__FILE__)."/../includes/bootstrap.inc",
		"content_book"  => "notes",
		"tag_group" => "notes",
		//'content_type' => 'note', //note, book, video....
		"export_format" => "xml" //json, csv
	)
);
?>
