<?php 
	include ('connect.php');
	
	$a = $_POST['username'];
	$b = md5($_POST['password']);

	/*
	$a = $_GET['username'];
	$b = md5($_GET['password']);
	echo $a;
	echo "</br>";
	echo $b;
	echo "</br>";
	*/

	//$sql = "SELECT user_idxx, user_resto, user_emil, user_pasw, user_name FROM tb_usrmerch ";
	//$sql .= " WHERE user_emil = ? AND user_pasw = ?";

	$sql = "SELECT a.user_idxx, a.user_resto, a.user_emil, a.user_pasw, a.user_name, b.name ";
	$sql .= "FROM tb_usrmerch a INNER JOIN restaurants b on a.user_resto = b.id ";
	$sql .= " WHERE user_emil = ? AND user_pasw = ?";

	//$sql = "SELECT user_idxx, user_emil, user_pasw, user_name FROM tb_useradm ";
	//$sql .= " WHERE user_emil = 'user@chatbot.com' ";

	$query = $db->prepare($sql);
	$query->execute(array($a,$b));
	$row = $query->fetch();

	if ($query->rowCount() > 0){
		session_start();
		$_SESSION['SESS_MEMBER_ID'] = $row['user_idxx'];
      	$_SESSION['userSession'] = $row['user_idxx'];
		$_SESSION['namaAlumni'] = $row['user_name'];
 		$_SESSION['emailAlumni']=$row['user_emil'];
 		$_SESSION['sys_restoID']=$row['user_resto'];
 		$_SESSION['sys_restoName']=$row['name'];
		echo 1; 
	}else{
		echo 0;
	}

?>
