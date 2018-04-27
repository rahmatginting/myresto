<?php
// configuration
	include('connect.php');
// new data
	$id = $_POST['memi'];
	$a = $_POST['name'];
	$b = $_POST['desc'];
	$c = $_POST['url'];

// query
	$sql = "UPDATE restaurant_menu 
        	SET name=?, description=?, picture=?
			WHERE id=?";
	$q = $db->prepare($sql);
	$q->execute(array($a,$b,$c,$id));
		header("location: menu.php");
?>