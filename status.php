<?php 
		
	session_start();

	include 'conn.php';

	if(isset($_GET['token'])){

		$token = $_GET['token'];

		$updatequery = " UPDATE registration SET status='active' WHERE token='$token' ";

		$query = mysqli_query($conn, $updatequery);

		if($query){
			if(isset($_SESSION['msg'])){
				$_SESSION['msg'] = "account activated successfully";
				header('location:signin.php');
			}else{
				$_SESSION['msg'] = "sorry check your email to login";
				header('location:signin.php');
			}
		}else{
			$_SESSION['msg'] = "Account not activated";
			header('location:signup.php');
		}

	}

 ?>