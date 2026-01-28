<?php

date_default_timezone_set("Asia/Kuala_Lumpur");
session_start();

$servername="localhost";
$username="root";
$password="";
$database="library";
 
$conn = new mysqli($servername,$username,$password,$database);
if($conn->connect_error){
	die ("Connection Failed" . $conn->connect_error);
}


?>

