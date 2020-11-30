<?php 
define('JWT_SIGNATURE', 'asdoiashdoiajsdiojasoidj');

require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload

use JWTSignature\JWTSignature;

$jwt = new JWTSignature();

$hash = $jwt->encrypt('test', [
	'id'   => 'prod',
	'data' => [
		'content' => 'stuff',
		'date' => 'today',

	]
]);

print($hash);