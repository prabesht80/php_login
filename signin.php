<?php 

	session_start(); 
	
	include "conn.php";

	$email = $password = '';

	if(isset($_POST['submit'])){
		
		$email = mysqli_real_escape_string($conn, $_POST['email']);
		$password = mysqli_real_escape_string($conn, $_POST['pwd']);

		$email_search = " SELECT * FROM registration WHERE email='$email' and status='active' ";
		if($query = mysqli_query($conn, $email_search)){
			$email_count = mysqli_num_rows($query);

			if($email_count){
				$email_pass = mysqli_fetch_assoc($query);

				$database_pass = $email_pass['password']; //password in database
				$pass_decode = password_verify($password, $database_pass);//check entered pass and database pass ..match or not

				if($pass_decode){
					header('location: index.php');
				}else {
					echo "email and password doesn't match";
				}
			}else{
				echo "Invalid email!";
			}
		}	
	}

 ?>

 <!DOCTYPE html>
 <html>
 <head>
 	<title>signIN</title>
 	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
 	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
 </head>
 <body>
 	<div class="container">
 		<div class="card-panel">
 			<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
 				<div class="card-panel"><?php echo $_SESSION['msg']; ?></div>
 				Email ID<i class="material-icons">email</i>:<input type="email" name="email">
 				Password<i class="material-icons">lock</i>:<input type="password" name="pwd">
 				<button class="btn waves-effect waves-light" type="submit" name="submit">SignIn
				    <i class="material-icons right">send</i>
				</button>
 			</form>
 			<a href="signup.php" class="red-text">Don't have an account!</a>
 			<a href="recover_emailadd.php" class="blue-text">Forget password</a>
 		</div>
 	</div>
 </body>
 </html>