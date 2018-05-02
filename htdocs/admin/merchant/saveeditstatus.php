<?php
// configuration
	//include('connect.php');
	include('connect.php');
	
// new data
	$id = $_POST['orderID'];
	$a = $_POST['status'];

// query
	$sql = "UPDATE menu_order 
        	SET status=?
			WHERE id=?";
	$q = $db->prepare($sql);
	$q->execute(array($a,$id));
		header("location: monitor.php");
?>