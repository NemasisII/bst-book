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

$callback = $_GET['callback'];

$start = $_GET['start'];
$stop = $_GET['stop'];

$start = empty($start) ? 1 : $start;
$stop = empty($stop) ? 10 : $stop;

$limit = ((int) $stop - (int) $start) - 1;

$sql_rows = 'select hospcode, hosptype, name from hospitals limit ' . $start . ', ' . $limit;

$st = $conn->prepare($sql_rows);
$st->execute();

$rows_result = $st->fetchAll();

$data = array();

foreach($rows_result as $r)
{
  $obj = new stdClass();
  $obj->hospcode = $r['hospcode'];
  $obj->name = $r['name'];
  $obj->hosptype = $r['hosptype'];
  $data[] = $obj;
}

$json = '{"ok": 1, "rows": ' . json_encode($data) . '}';

echo $callback . '(' . $json . ')';
