<?php
session_start();
include('connect.php');
$a = $_POST['email'];
$b = md5($_POST['pasw']);
$c = $_POST['name'];

$sql = "INSERT INTO tb_useradm VALUES (DEFAULT,?,?,?)";

$q = $db->prepare($sql);
$q->execute(array($a,$b,$c));
header("location: user.php");
?>