<?php
// configuration
	include('connect.php');
// new data
	$id = $_POST['memi'];
	$a = $_POST['name'];
	$b = $_POST['desc'];
	$c = $_POST['address'];
	$d = $_POST['longitude'];
	$e = $_POST['latitude'];
	$f = $_POST['telp'];
	$g = $_POST['image'];

// query
	$sql = "UPDATE restaurants 
        	SET name=?, description=?, address=?, longitude=?, latitude=?, phone=?, image=?
			WHERE id=?";
	$q = $db->prepare($sql);
	$q->execute(array($a,$b,$c,$d,$e,$f,$g,$id));
		header("location: merchant.php");
?>