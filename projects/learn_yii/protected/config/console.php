<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'My Console Application',

	// preloading 'log' component
	'preload'=>array('log'),

	// application components
	'components'=>array(

		'db'=>array(
			'connectionString' => 'sqlite:'.dirname(__FILE__).'/../../data/learn.sqlite',
		),

		// uncomment the following to use a MySQL database
/*
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=learn',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => 'master',
			'charset' => 'utf8',
		),
*/
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
			),
		),
	),
);
