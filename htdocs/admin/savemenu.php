<?php
session_start();
include('connect.php');
$a = $_POST['resto'];
$b = $_POST['categ'];
$c = $_POST['code'];

$d = $_POST['name'];
$e = $_POST['desc'];
$f = $_POST['img'];

$lastRow = "select id+1 as last_id from restaurant_menu order by id desc limit 1";
$result = $db->prepare($lastRow);
$result->execute();

for($i=0; $row = $result->fetch(); $i++){
 $rowID=$row['last_id'];
}

//$sql = "INSERT INTO restaurant_menu VALUES (DEFAULT,?,?,?,?,?,?)";
$sql = "INSERT INTO restaurant_menu VALUES (" . $rowID . ",?,?,?,?,?,?)";

$q = $db->prepare($sql);
$q->execute(array($a,$b,$c,$d,$e,$f));
header("location: menu.php");
?>