<?php
session_start();
include('connect.php');
$a = $_POST['name'];
$b = $_POST['desc'];
$c = $_POST['address'];
$d = $_POST['longitude'];
$e = $_POST['latitude'];
$f = $_POST['telp'];
$g = $_POST['image'];

$sql = "INSERT INTO restaurants VALUES (DEFAULT,?,?,?,?,?,?,?)";

$q = $db->prepare($sql);
$q->execute(array($a,$b,$c,$d,$e,$f,$g));
header("location: merchant.php");
?>