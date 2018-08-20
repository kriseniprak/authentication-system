<?php
session_start();

if(!isset($_SESSION['pid'])){
	header('location:login.php');
}

$connection=mysqli_connect('localhost', 'username', 'password', 'database');
$errors=array();

$control=mysqli_query($connection, "SELECT username, confirmed, recoveryprocess FROM users WHERE privateid='".$_SESSION['pid']."'");
while($row=mysqli_fetch_array($control,MYSQLI_ASSOC)){
	$username=$row['username'];
	$confirmed=$row['confirmed'];
	$recoveryprocess=$row['recoveryprocess'];
}

if($confirmed!=='1'){
	die("<html>
		    <head>
		       <title>Email confirm</title>
		       <link rel=\"stylesheet\" type=\"text/css\" href=\"css/style.css\">
	        </head>
	        <body>
		       <center>
			      <form>
			         <h1>You must confirm your email.</h1>
				  </form>
		       </center>
	        </body>
         </html>");
}

if($recoveryprocess=='1'){
	$update=mysqli_query($connection, "UPDATE users SET recoveryprocess='0', recoveryid='' WHERE privateid='".$_SESSION['pid']."'");
}

if(isset($_POST['submit'])){
	unset($_SESSION['pid']);
	header('location:login.php');
}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>System</title>
		<link rel="stylesheet" type="text/css" href="css/style.css">
	</head>
	<body>
		<center>
			<form method="POST">
				<h1>You are logged in!</h1>
				<input type="submit" name="submit" value="Logout">
			</form>
			<div class="info">
				<span>System created by <a href="https://github.com/squarebolt">Christian Carpineta</a></span>
			</div>
		</center>
	</body>
</html>