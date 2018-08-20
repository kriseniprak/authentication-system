<?php
session_start();

if(isset($_SESSION['pid'])){
	header('location:index.php');
}

$connection=mysqli_connect('localhost', 'username', 'password', 'database');
$errors=array();

if(isset($_POST['submit'])){
	$email=mysqli_real_escape_string($connection, $_POST['email']);
	$username=mysqli_real_escape_string($connection, $_POST['username']);
	$password=mysqli_real_escape_string($connection, $_POST['password']);
	$confirmpassword=mysqli_real_escape_string($connection, $_POST['confirmpassword']);
	if(strlen($email)>30){
		$email_error='<span style="color:red;">Too long email.</span><br />';
		array_push($errors, '1');
	}
	if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
		$email_error='<span style="color:red;">Invalid email.</span><br />';
		array_push($errors, '1');
	}
	if(empty($email)){
		$email_error='<span style="color:red;">You did not enter your email.</span><br />';
		array_push($errors, '1');
	}
	if(strlen($username)>20){
		$username_error='<span style="color:red;">Too long username.</span><br />';
		array_push($errors, '1');
	}
	if(empty($username)){
		$username_error='<span style="color:red;">You did not enter your username.</span><br />';
		array_push($errors, '1');
	}
	if(strlen($password)>20){
		$password_error='<span style="color:red;">Too long password.</span><br />';
		array_push($errors, '1');
	}
	if(empty($password)){
		$password_error='<span style="color:red;">You did not enter your password.</span><br />';
		array_push($errors, '1');
	}
	if(empty($confirmpassword)){
		$passwordc_error='<span style="color:red;">You did not enter confirmation password.</span><br />';
		array_push($errors, '1');
	}
	if($password!==$confirmpassword){
		$passwordc_error='<span style="color:red;">Passwords doesn\'t match.</span><br />';
		array_push($errors, '1');
	}
	if(count($errors)==0){
		$results=mysqli_query($connection, 'SELECT email, password FROM users WHERE email='.$email.' AND password='.$password.'');
		if(mysqli_num_rows($results)==1){
			$email_error='<span style="color:red;">Email already registered.</span><br />';
	    }else{
			point1:
			$privateid=rand(10000000, 99999999);
			$myip=explode('.', $_SERVER['REMOTE_ADDR']);
			$privateid=$privateid + $myip[0]*3 + $myip[1]*3 + $myip[2]*3 + $myip[3]*3 + date('d');
			if($privateid>99999999){
				goto point1;
			}
			$pidcontrol=mysqli_query($connection, "SELECT id FROM users WHERE privateid='$privateid'");
			if(mysqli_num_rows($results)==1){
				goto point1;
			}
			$results=mysqli_query($connection, "INSERT INTO users (email, username, password, privateid, regip, regdate, logip, logdate) VALUES ('$email', '$username', '$password', '$privateid', '".$_SERVER['REMOTE_ADDR']."', '".date('d/m/Y H:i:s')."', '".$_SERVER['REMOTE_ADDR']."', '".date('d/m/Y H:i:s')."')");
			$message = "<html><body>Automatic message from System.<br />You registered on <b>".date('d/m/Y H:i:s')."</b> at System with the IP <b>".$_SERVER['REMOTE_ADDR']."</b>. To complete the registration click <a href=\"http://www.changeme.com/confirmemail.php?id=".(($privateid*20)+662885929347)."\">here</a>.</body></html>";
			$header = "MIME-Version: 1.0"."\r\n";
			$header .= "Content-type:text/html;charset=UTF-8"."\r\n";
			$header .= 'From: System'."\r\n";
			mail($email,"Confirm email",$message,$header);
			$_SESSION['pid']=$privateid;
			header('location:index.php');
		}
		
	}
}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Register page</title>
		<link rel="stylesheet" type="text/css" href="css/style.css">
	</head>
	<body>
		<center>
			<form method="POST">
				<h1>Register</h1>
				<label>Email:</label><br />
				<input type="email" name="email"><br />
				<?php echo $email_error; ?>
				<label>Username:</label><br />
				<input type="text" name="username"><br />
				<?php echo $email_error; ?>
				<label>Password:</label><br />
				<input type="password" name="password"><br />
				<?php echo $password_error; ?>
				<label>Confirm password:</label><br />
				<input type="password" name="confirmpassword"><br />
				<?php echo $passwordc_error; ?>
				<input type="submit" name="submit" value="Register">
				<div class="text">
					<a href="login.php">Already registered?</a>
				</div>
			</form>
			<div class="info">
				<span>System created by <a href="https://github.com/squarebolt">Christian Carpineta</a></span>
			</div>
		</center>
	</body>
</html>