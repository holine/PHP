<?php
return array (
		'system' => array (
				'error_reporting' => E_ALL,
				'date.timezone' => 'Asia/Shanghai' 
		),
		'header' => array (
				'type' => array (
						'php' => 'text/html',
						'js' => 'text/javascript',
						'json' => 'text/javascript',
						'css' => 'text/css',
						'xml' => 'text/xml',
						'jpg' => 'image/jpg' 
				),
				'charset' => 'utf-8' 
		),
		'micro' => array (
				'module' => 'mod',
				'action' => 'act',
				'dir' => array (
						'module' => dirname ( __DIR__ ) . '/index/module',
						'model' => dirname ( __DIR__ ) . '/index/model',
						'template' => dirname ( __DIR__ ) . '/index/template',
				),
				'default' => array (
						'module' => 'index',
						'action' => 'home',
						'layout' => 'blank',
				) 
		) 
);