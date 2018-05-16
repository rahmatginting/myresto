<?php
$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$parent_dir = dirname(dirname($_SERVER['SCRIPT_NAME'])) . '/';
$imgPath = "image/menu/";

$url = $protocol . $_SERVER['HTTP_HOST'] . $parent_dir . $imgPath;

//$menuID=$_FILES['file']['id'];
$menuID=$_POST['id'];

// file name
$filename = $_FILES['file']['name'];

// Location
$location = '../image/menu/'.$filename;

//Image URL to save into Database
$imgURL = $url.$filename;


// file extension
$file_extension = pathinfo($location, PATHINFO_EXTENSION);
$file_extension = strtolower($file_extension);

// Valid image extensions
$image_ext = array("jpg","png","jpeg","gif");

$response = 0;
if(in_array($file_extension,$image_ext)){
	// Upload file
	if(move_uploaded_file($_FILES['file']['tmp_name'],$location)){
		$response = $imgURL . "|" . $location . "|" . $menuID;
	}
}

include('connect.php');

// query
$sql = "UPDATE restaurant_menu 
    	SET picture=?
    	SET filename=?
		WHERE id=?";
$q = $db->prepare($sql);
$q->execute(array($imgURL,$filename,$menuID));

echo $response;
