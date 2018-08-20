<?php
session_start();

if(isset($_SESSION['pid'])){
	header('location:index.php');
}

$connection=mysqli_connect('localhost', 'username', 'password', 'database');
$errors=array();

if(isset($_POST['submit'])){
	$username=mysqli_real_escape_string($connection, $_POST['username']);
	$password=mysqli_real_escape_string($connection, $_POST['password']);
	if(empty($username)){
		$username_error='<span style="color:red;">You did not enter your username.</span><br />';
		array_push($errors, '1');
	}
	if(empty($password)){
		$password_error='<span style="color:red;">You did not enter your password.</span><br />';
		array_push($errors, '1');
	}
	if(count($errors)==0){
		$results=mysqli_query($connection, "SELECT privateid FROM users WHERE username='$username' AND password='$password'");
		if(mysqli_num_rows($results)==1){
			while($row=mysqli_fetch_array($results,MYSQLI_ASSOC)){
				$_SESSION['pid']=$row['privateid'];
			}
			$update=mysqli_query($connection, "UPDATE users SET logip='".$_SERVER['REMOTE_ADDR']."', logdate='".date('d/m/Y H:i:s')."' WHERE privateid='".$_SESSION['pid']."'");
			header('location:index.php');
		}else{
			$username_error='<span style="color:red;">Invalid account.</span><br />';
		}
	}
}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Login page</title>
		<link rel="stylesheet" type="text/css" href="css/style.css">
	</head>
	<body>
		<center>
			<form method="POST">
				<h1>Login</h1>
				<label>Username:</label><br />
				<input type="text" name="username"><br />
				<?php echo $username_error; ?>
				<label>Password:</label><br />
				<input type="password" name="password"><br />
				<?php echo $password_error; ?>
				<input type="submit" name="submit" value="Login"><br />
				<div class="text">
					<a href="register.php">Not yet registered?</a><br />
					<a href="recoverypassword.php">Password lost?</a>
				</div>
			</form>
			<div class="info">
				<span>System created by <a href="https://github.com/squarebolt">Christian Carpineta</a></span>
			</div>
		</center>
	</body>
</html>