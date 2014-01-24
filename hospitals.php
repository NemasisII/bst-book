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


$query 		= $_GET['query'];
$callback 	= $_GET['callback'];

$start  	= $_GET['start'];
$stop   	= $_GET['stop'];

$start  	= empty($start) ? 1 : $start;
$stop   	= empty($stop) ? 10 : $stop;

$start 		= ($start - 1) * $stop;

$sql_total 	= 'select count(*) as total from hospitals where name like "%' . $query . '%" or hospcode like "%' . $query . '"';

$st = $conn->prepare($sql_total);
$st->execute();
$rows_total = $st->fetch();

$sql_rows = 'select hospcode, hosptype, name from hospitals where name like "%' . $query . '%" or hospcode like "%' . $query . '" limit ' . $start . ', ' . $stop;

$st = $conn->prepare($sql_rows);
$st->execute();

$rows_result = $st->fetchAll();

$data = array();

foreach($rows_result as $r)
{
	$obj = new stdClass();
	$obj->code = $r['hospcode'];
	$obj->name = $r['name'] . ', ' . $r['hosptype'];
	
	$data[] = $obj;
}

$json = '{"ok": 1, "total": ' . $rows_total['total'] . ', "rows": ' . json_encode($data) . '}';

echo $callback . '(' . $json . ')';


