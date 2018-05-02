<?php
include('connect.php');


$lastRow = "select id+1 as last_id from restaurant_menu order by id desc limit 1";
$result = $db->prepare($lastRow);
$result->execute();

for($i=0; $row = $result->fetch(); $i++){
 $rowID=$row['last_id'];
}

echo $rowID;
?>
