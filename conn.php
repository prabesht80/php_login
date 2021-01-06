<?php 

//create connection
$conn = mysqli_connect("localhost", "root", "WxyXSmP1053", "signup");

//check connection
if(!$conn){
	die("connection failed: " . mysqli_connect_error());
}

 ?>