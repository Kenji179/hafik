<?php
require_once 'vendor/autoload.php';

$dotenv = new \Dotenv\Dotenv(__DIR__);
$dotenv->load();

function getDbConnection()
{
	$host = getenv('DB_HOST');
	$username = getenv('DB_USERNAME');
	$password = getenv('DB_PASSWORD');
	$dbName = getenv('DB_NAME');
	
	try {
		$connection = new PDO("mysql:host=$host;dbname=$dbName;charset=utf8", $username, $password);
		$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch (\Exception $e) {
		file_put_contents('log.txt', $e->getMessage(), FILE_APPEND);
		file_put_contents('log.txt', $e->getFile() . ': ' . $e->getLine(), FILE_APPEND);
		file_put_contents('log.txt', $e->getTraceAsString(), FILE_APPEND);
	}
	
	return $connection;
}

