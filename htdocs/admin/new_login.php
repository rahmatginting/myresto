<?php 
	include ('connect.php');

	$a = $_POST['username'];
	$b = md5($_POST['password']);

	$sql = "SELECT user_idxx, user_emil, user_pasw, user_name FROM tb_useradm ";
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
		echo 1;
	}else{
		echo 0;
	}

?>
