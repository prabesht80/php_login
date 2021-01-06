<?php 
	
	session_start();
	//phpmailer 
	require 'PHPMailer/PHPMailerAutoload.php';

	$mail = new PHPMailer();
	$mail->isSMTP(true); // telling the class to use SMTP
	$mail->SMTPAuth = true;
	$mail->SMTPOptions = array(
	'ssl' => array(
	'verify_peer' => false,
	'verify_peer_name' => false,
	'allow_self_signed' => true
	)
	);
	$mail->SMTPSecure = 'tls';
	$mail->Host = 'tls://smtp.gmail.com';
	$mail->Port = 587;
	$mail->SMTPAuth = true;
	$mail->Username = "thapaprabesh421@gmail.com"; // SMTP username
	$mail->Password = "thapa124#"; // SMTP password

	$name = $phone = $email = $password = $cpassword = '';

	if(isset($_POST['submit'])){

		include 'conn.php';
		//value from form
		$name = mysqli_real_escape_string($conn, $_POST['username']);
		$phone = mysqli_real_escape_string($conn, $_POST['usernumber']);
		$email = mysqli_real_escape_string($conn, $_POST['email']);
		$password = mysqli_real_escape_string($conn, $_POST['pwd']);
		$cpassword = mysqli_real_escape_string($conn, $_POST['repwd']);

		//if empty do not proceed
		if(empty($name) || empty($phone) || empty($email) || empty($password) || empty($cpassword)){
			print_r("sorry cant proceed, some fields are empty!");
		}else{
			//password hashing
			$pass = password_hash($password, PASSWORD_BCRYPT);
			$cpass = password_hash($cpassword, PASSWORD_BCRYPT);

			$token = bin2hex(random_bytes(13));//generate random token

			//check email 
			$emailquery = " SELECT * FROM registration WHERE email='$email' ";
			//check if email already exist or not
			if($result = mysqli_query($conn,$emailquery)){
				$emailcount = mysqli_num_rows($result);
				if($emailcount>0){
					echo "sorry, email already exist";//if email address contain in any row
				}else {
					if($password == $cpassword){ //check if password are same
						//insert query
						$insertquery = "INSERT INTO registration(name,phone,email,password,cpassword,token,status) VALUES('$name', '$phone', '$email', '$pass', '$cpass', '$token', 'inactive') ";
						$query = mysqli_query($conn, $insertquery);

						if($query){
							//email to send
							$subject = "Email verification";
							$body = "Hi, $name. click here to verify your email  http://localhost/usersignup/status.php?token=$token";//clickable token to mail
							$sender_email = "From: verificationEmail@gmail.com";

							//Email setting
							$mail->isHTML(true);
							$mail->setFrom("thapaprabesh421@gmail.com", 'Welcome');
							$mail->addAddress($email);
							$mail->Subject = $subject;
							$mail->Body = $body;

							if($mail->send()){
								$_SESSION['msg'] = "check your email";
								header('location:signin.php');
							}else{
								echo "failed to send email";
							}
						}
					}else{
						echo "password doesn't match";
					}
				}
			}
		}	
	}

 ?>


 <!DOCTYPE html>
 <html>
 <head>
 	<title>signup</title>
 	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
 	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

 </head>
 <body>
 	<div class="container">
 		<div class="card-panel">
 			<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
 				Name<i class="material-icons">person</i>:<input type="text" name="username">
 				Phone<i class="material-icons">phone</i>:<input type="text" name="usernumber">
 				Email<i class="material-icons">email</i>:<input type="email" name="email">
 				Password<i class="material-icons">lock</i>:<input type="text" name="pwd">
 				Retype password<i class="material-icons">lock</i>:<input type="text" name="repwd">
 				<button class="btn waves-effect waves-light" type="submit" name="submit">Submit
				    <i class="material-icons right">send</i>
				</button>
 			</form>
 			<a href="signin.php" class="green-text">Back to login In page</a>
 		</div>
 	</div>


 
 </body>
 </html>