<?php
// configuration
	include('connect.php');
// new data
	$id = $_POST['memi'];
	$a = $_POST['desc'];

// query
	$sql = "UPDATE restaurant_tables 
        	SET description=?
			WHERE id=?";
	$q = $db->prepare($sql);
	$q->execute(array($a,$id));
		header("location: table.php");
?>