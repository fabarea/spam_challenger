<?php

$EM_CONF[$_EXTKEY] = [
	'title' => 'Formhandler Spam Challenger',
	'description' => 'Additional Components for protecting a formhandler but without the need of a captcha.',
	'category' => 'plugin',
	'version' => '1.0.0-dev',
	'state' => 'stable',
	'author' => 'Fabien Udriot',
	'author_email' => 'fabien.udriot@ecodev.ch',
	'author_company' => 'Ecodev',
	'constraints' => [
		'depends' => [
			'php' => '5.2.0-0.0.0',
			'typo3' => '7.6.0-7.99.99',
		],
		'conflicts' => [
		],
		'suggests' => [
		],
	],
	'suggests' => [
	],
];

