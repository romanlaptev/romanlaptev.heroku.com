<?php
return array(
/*
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
*/
	'export'=>array(
		'filename' => 'export.xml',
		'filePath' => dirname(__FILE__)."/../data/export_test.xml",
		"drupalConstFile"  => dirname(__FILE__)."/../includes/bootstrap.inc"
		//'content_type' => ''//note, book, video....
	)
);
?>
