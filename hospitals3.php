<?php

$dsn = 'mysql:host=localhost; dbname=test';
$username = 'root';
$password = '';

try {
	$conn = new PDO( $dsn, $username, $password);
	$conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
} catch ( PDOException $e ) {
	echo 'Connection failed: ' . $e->getMessage();
}

$callback 	= $_GET['callback'];

$sql_total 	= 'select count(*) as total from hospitals';

$st = $conn->prepare($sql_total);
$st->execute();
$rows_total = $st->fetch();

$json = '{"ok": 1, "total": ' . $rows_total['total'] . '}';

echo $callback . '(' . $json . ')';


