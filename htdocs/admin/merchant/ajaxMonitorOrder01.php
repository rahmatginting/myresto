<?php
include('connect.php');
//include('connect_loc.php');

	// $user_sys  = $_POST['param01'];
	// $resto_id = $_POST['param02'];

	$user_sys  = 'user@chatbot.com';
	$resto_id = '1';

/*
	$user_sys  = 'user@chatbot.com';
	$resto_id = '1';

	$user_sys  = $_POST['param01'];
	$resto_id = $_POST['param02'];

	$user_sys  = $_GET['param01'];
	$resto_id = $_GET['param02'];	
	
	//Clear yesterday data
	$sql = "DELETE FROM order_temp ";
	$sql .= "WHERE DATE(order_date) < CURRENT_DATE ";
	$sql .= "AND user_id = '" . $user_sys . "' ";
	$sql .= "AND type = '01' ";

	//Refresh Order List
	$sql = "INSERT INTO order_temp (user_id, order_id, order_date) ";
	$sql .= "SELECT '" . $user_sys . "' AS user_id, a.id FROM menu_order a LEFT JOIN order_temp b  ";
	$sql .= "ON a.id = b.order_id AND b.user_id = '" . $user_sys . "' ";
	$sql .= "WHERE b.order_id IS NULL ";
	$sql .= "AND a.resto_id = '" . $resto_id . "' ";
	$sql .= "AND DATE(a.timestamp) = CURRENT_DATE ";

	//Get new data
	$sql = "SELECT a.* FROM menu_order a INNER JOIN order_temp b ";
	$sql .= "ON a.id = b.order_id ";
	$sql .= "WHERE b.user_id = '" . $user_sys . "' ";
	$sql .= "AND DATE(a.timestamp) = CURRENT_DATE ";
	$sql .= "AND b.sent_status = '0' ";
	$sql .= "AND a.resto_id = '" . $resto_id . "' ";
	$sql .= "AND b.type = '01' ";

	$sql = "UPDATE order_temp SET ";
	$sql .= "sent_status = '1' ";
	$sql .= "WHERE user_id = '" . $user_sys . "' ";
	$sql .= "AND type = '01' ";
	
*/	


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
	$i=0;
	while($row=$statement->fetch(PDO::FETCH_ASSOC)){
		$i++;
		$details = getDetailOrders($row['id'], $row['resto_id']);
		//$details = 'test';
	    array_push($someArray, [
	      'id'   	  => $row['id'],
	      'table_id'  => $row['table_id'],
	      'user_name' => $row['user_name'],
	      'status' 	  => $row['status'],
	      'details'   => $details,
	      'notif'     => $notif
	    ]);
	}

    if($i>0) {
	  // Convert the Array to a JSON String and echo it
	  $someJSON = json_encode($someArray);
	  echo $someJSON;
    }else {
	  return false;    	
    }






//==================================================================================
function getDetailOrders($id, $resto_id)
{
include('connect.php');
//include('connect_loc.php');
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
