<?php
include('connect.php');

	// $user_sys  = $_POST['param01'];
	// $resto_id = $_POST['param02'];

	$user_sys  = 'sfdf';
	$resto_id = '1';

	//Get notification
	$notif = 0;
	$sql = "SELECT count(id) as notif ";
	$sql .= "FROM panggilan ";
	$sql .= "WHERE resto_id = '" . $resto_id . "' ";
	$sql .= "AND DATE(timestamp) = CURRENT_DATE ";
	$sql .= "AND status = 0 ";
	$sql .= "AND type = '01' ";
	$sql .= "ORDER BY timestamp ASC ";
	$statement = $db->prepare($sql);
	$statement->execute();

	while($row=$statement->fetch(PDO::FETCH_ASSOC)){
		$notif =  $row['notif'];
	}

	//Get data
	$sql = "SELECT a.* FROM panggilan a ";
	$sql .= "WHERE DATE(a.timestamp) = CURRENT_DATE ";
	$sql .= "AND a.resto_id = '" . $resto_id . "' ";
	$sql .= "AND a.type = '01' ";
	$sql .= "ORDER BY a.timestamp ASC ";

	$statement = $db->prepare($sql);
	$statement->execute();
	$someArray = [];
	while($row=$statement->fetch(PDO::FETCH_ASSOC)){
	    array_push($someArray, [
	      'id'   	  	=> $row['id'],
	      'table_id' 	=> $row['table_id'],
	      'user_name' 	=> $row['user_name'],
	      'status' 	  	=> $row['status'],
	      'description' => $row['description'],
	      'notif'     	=> $notif
	    ]);
	}


  // Convert the Array to a JSON String and echo it
  $someJSON = json_encode($someArray);
  echo $someJSON;

//==================================================================================

?>
