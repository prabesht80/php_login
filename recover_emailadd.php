<?php 

	
	session_start();
	//phpmailer 
	require 'PHPMailer/PHPMailerAutoload.php';

	//phpmailer setting
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
	$mail->Password = "xxxxxxx"; // SMTP password


	if(isset($_POST['submit'])){

		include 'conn.php';
		//value from form
		$email = mysqli_real_escape_string($conn, $_POST['email']);
		
		//if empty do not proceed
		if(empty($email)){
			print_r("enter your email address");
		}else{
			//check email 
			$emailquery = " SELECT * FROM registration WHERE email='$email'";
			//check if email already exist or not
			if($result = mysqli_query($conn,$emailquery)){
				$emailcount = mysqli_num_rows($result);
				if($emailcount){
						//get enter email token from db
						$getdata = mysqli_fetch_assoc($result);
						$name = $getdata['name'];
						$id = $getdata['id'];
						$token = $getdata['token'];


						//email to send
						$subject = "Recover password";
						$body = "Hi, $name click here to recover your password  http://localhost/usersignup/reset_pass.php?token=$token";//clickable token to mail
						$sender_email = "From: RecoverEmail@gmail.com";

						//Email setting
						$mail->isHTML(true);
						$mail->setFrom("thapaprabesh421@gmail.com", 'Welcome');
						$mail->addAddress($email);
						$mail->Subject = $subject;
						$mail->Body = $body;

						if($mail->send()){
							$_SESSION['msg'] = "check your email";
							header('location:checkmail_msg.php');
						}else{
							echo "failed to send email";
						}		
					
				}else{
					echo "sorry email isn't register";
				}
			}
		}	
	}


 ?>

 <!DOCTYPE html>
 <html>
 <head>
 	<title>recovery Email</title>
 	 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
 </head>
 <body>
 	<div class="container">
 		<div class="card-panel grey-text">
 			<h3>Enter your email address for password change</h3>
 			<form action="recover_emailadd.php" method="post">
 				Email:<input type="email" name="email">
 				<button class="btn waves-effect waves-light" type="submit" name="submit">Submit</button>
 			</form>
 		</div>
 	</div>
 
 </body>
 </html>