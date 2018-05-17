<?php
$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
echo "protocol = " .$protocol ."</br></br>";

$parent_dir = dirname(dirname($_SERVER['SCRIPT_NAME'])) . '/';
echo "parent_dir = " .$parent_dir ."</br></br>";

$imgPath = "image/menu/";
echo "imgPath = " .$imgPath ."</br></br>";

$url = $protocol . $_SERVER['HTTP_HOST'] . $parent_dir . $imgPath;
echo "url = " .$url ."</br></br>";
?>