<?php
$protocol01 = isset($_SERVER["HTTPS"]) ? 'https' : 'http';
$protocol02 = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
echo "protocol01 = " .$protocol ."</br></br>";
echo "protocol02 = " .$protocol ."</br></br>";

$parent_dir = dirname(dirname($_SERVER['SCRIPT_NAME'])) . '/';
echo "parent_dir = " .$parent_dir ."</br></br>";

$imgPath = "image/menu/";
echo "imgPath = " .$imgPath ."</br></br>";

$url = $protocol . $_SERVER['HTTP_HOST'] . $parent_dir . $imgPath;
echo "url = " .$url ."</br></br>";
?>
