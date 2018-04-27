<?php
// configuration
	include('connect.php');
// new data
	$id = $_POST['memi'];
	$a = $_POST['name'];
	$b = $_POST['email'];
	$c = md5($_POST['pasw']);

// query
	$sql = "UPDATE tb_useradm 
        	SET user_name=?, user_emil=?, user_pasw=?
			WHERE user_idxx=?";
	$q = $db->prepare($sql);
	$q->execute(array($a,$b,$c,$id));
		header("location: user.php");
?>