<?php
$config=[
	'url'=>'http://localhost/crawler',
	'db'=>[
		'servername'	=>	'127.0.0.1',
		'username'		=>	'root',
		'password'		=>	'',
		'name'			=>	'crawler'
	],
	'mail'=>[
		'host'			=>	'smtp.gmail.com',
		'username'		=>	'@gmail.com',
        'secure'        =>  'tls',
		'name'			=>	'My test email',
		'password'		=>	'',
		'port'			=>	'587',
		'prefix'		=>	''
	],
	'upload'=>[
		'dir'			=>	'upload',
		'allow'			=>	'jpeg,jpg,png,gif'
	],
	'data'=>[
		'news'			=>	'data/news/',
		'comments'		=>	'data/comments/',
	]
];