<?php
session_start();
include('connect.php');
$a = $_POST['resto'];
$b = $_POST['code'];
$c = $_POST['desc'];

$lastRow = "select id+1 as last_id from restaurant_tables order by id desc limit 1";
$result = $db->prepare($lastRow);
$result->execute();

for($i=0; $row = $result->fetch(); $i++){
 $rowID=$row['last_id'];
}

//$sql = "INSERT INTO restaurant_menu VALUES (DEFAULT,?,?,?,?,?,?)";
$sql = "INSERT INTO restaurant_tables VALUES (" . $rowID . ",?,?,?)";

$q = $db->prepare($sql);
$q->execute(array($a,$b,$c));
header("location: table.php");
?>