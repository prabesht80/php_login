
<?php 
	
	session_start();
	include 'conn.php';

	if(isset($_POST['submit'])){
		if(isset($_GET['token'])){
			$token = $_GET['token'];

			$newpassword = mysqli_real_escape_string($conn, $_POST['pwd']);
			$cpassword = mysqli_real_escape_string($conn, $_POST['repwd']);

			$pass = password_hash($newpassword, PASSWORD_BCRYPT);
			$cpass = password_hash($cpassword, PASSWORD_BCRYPT);

			if($newpassword === $cpassword){
				$updatequery = "update registration set password = '$pass' where token='$token' ";
				$query = mysqli_query($conn, $updatequery);

				if($query){
					$_SESSION['msg'] = "change successful";
					header('location:signin.php');
				}else{
					$_SESSION['resetmsg'] = "sorry password is not change";
					header('location:passreser.php');
				}
			}else{
				$_SESSION['resetmsg'] = "Password doesn't match!";
			}
		}else{
			echo "tooken not found";
		}
	}


 ?>

<!DOCTYPE html>
 <html>
 <head>
 	<title>reset_pass</title>
 	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
 	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

 </head>
 <body>
 	<div class="container">
 		<div class="card-panel">
 			<h4>Reset Password</h4>
 			<p>
 				<?php
	 				if(isset($_SESSION['resetmsg'])){
	 					echo $_SESSION['resetmsg'];
	 				}else{
	 					echo $_SESSION['resetmsg'] = "";
	 				}
 			 	?>
 			</p>
 			<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
 				Password<i class="material-icons">lock</i>:<input type="text" name="pwd">
 				Retype password<i class="material-icons">lock</i>:<input type="text" name="repwd">
 				<button class="btn waves-effect waves-light" type="submit" name="submit">reset
				    <i class="material-icons right">send</i>
				</button>
 			</form>

 			<a href="signin.php" class="green-text">Back to login In page</a>
 			<a href="recover_emailadd.php" class="yellow-text">Go back</a>
 		</div>
 	</div>
 </body>
 </html>