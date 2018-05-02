<?php
include('connect.php');

	$user_sys  = 'user@chatbot.com';
	$resto_id = '1';

	//Get notif
	$notif = 0;
	$sql = "SELECT count(id) as notif ";
	$sql .= "FROM menu_order ";
	$sql .= "WHERE resto_id = '" . $resto_id . "' ";
	$sql .= "AND DATE(timestamp) = CURRENT_DATE ";
	$sql .= "AND status = '0' ";
	$sql .= "GROUP BY timestamp  ";
	$sql .= "ORDER BY timestamp ASC ";
	$statement = $db->prepare($sql);
	$statement->execute();

	$statement = $db->prepare($sql);
	$statement->execute();

	while($row=$statement->fetch(PDO::FETCH_ASSOC)){
		$notif =  $row['notif'];
	}

	$sql = "SELECT a.* FROM menu_order a ";
	$sql .= "WHERE DATE(a.timestamp) = CURRENT_DATE ";
	$sql .= "AND a.resto_id = '" . $resto_id . "' ";
	$sql .= "ORDER BY a.timestamp ASC ";

	$statement = $db->prepare($sql);
	$statement->execute();
	$someArray = [];
	while($row=$statement->fetch(PDO::FETCH_ASSOC)){
		//$details = getDetailOrders($row['id'], $row['resto_id']);
		$details = 'test';
	    array_push($someArray, [
	      'id'   	  => $row['id'],
	      'table_id'  => $row['table_id'],
	      'user_name' => $row['user_name'],
	      'status' 	  => $row['status'],
	      'details'   => $details,
	      'notif'     => $notif
	    ]);
	}


  // Convert the Array to a JSON String and echo it
  $someJSON = json_encode($someArray);
  echo $someJSON;






//==================================================================================
function getDetailOrders($id, $resto_id)
{
include('connect_loc.php');
	$hasil='';
	//Get new data
	$sql = "SELECT a.menu, b.name, a.quantity, a.description ";
	$sql .= "FROM menu_order_det a INNER JOIN restaurant_menu b ";
	$sql .= "ON a.menu = b.code AND b.restaurant_id = '" . $resto_id . "' ";
	$sql .= "WHERE a.id = '" . $id . "' ";
	$sql .= "ORDER BY a.timestamp ASC ";
	$statement = $db->prepare($sql);
	$statement->execute();

	while($row=$statement->fetch(PDO::FETCH_ASSOC)){
		$hasil .= "(" . $row['quantity'] . ") " . $row['menu'] . " | " . $row['name'] . " ==> " . $row['description'] . " \n";
	}
	return $hasil;
}

?>
