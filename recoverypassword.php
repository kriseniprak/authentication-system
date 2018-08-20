<?php
session_start();

if(isset($_SESSION['pid'])){
	header('location:index.php');
}

$connection=mysqli_connect('localhost', 'username', 'password', 'database');
$errors=array();
$h2='Enter the email address for the account.';
$formcontent="<label>Email:</label><br />
			  <input type=\"email\" name=\"email\"><br />";
$buttonname='submit';
$buttonvalue='Send';

if(isset($_POST['submit'])){
	$email=mysqli_real_escape_string($connection, $_POST['email']);
	
	if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
		$email_error='<span style="color:red;">Invalid email.</span><br />';
		array_push($errors, '1');
	}
	if(empty($email)){
		$email_error='<span style="color:red;">You did not enter your email.</span><br />';
		array_push($errors, '1');
	}
	if(count($errors)==0){
		$results=mysqli_query($connection, "SELECT privateid, recoveryprocess FROM users WHERE email='$email'");
		if(mysqli_num_rows($results)==1){
			while($row=mysqli_fetch_array($results,MYSQLI_ASSOC)){
				$privateid=$row['privateid'];
				$recoveryprocess=$row['recoveryprocess'];
			}
			if($recoveryprocess=='1'){
				die("<html>
		                <head>
		                   <title>Recovery password</title>
		                   <link rel=\"stylesheet\" type=\"text/css\" href=\"css/style.css\">
					       <script>
			                  window.setTimeout(function(){
				                 window.location.href = \"recoverypassword.php\";
				              }, 5000);
			               </script>
	                    </head>
	                    <body>
		                   <center>
			                  <form>
			                     <h1>You have already submitted a request.</h1>
							     <h2>You are about to be redirected ...</h2>
				              </form>
		                  </center>
	                    </body>
                     </html>");
			}
			point1:
			$calculate=((rand(10000000, 99999999)+rand(10000000, 99999999))*3)+($privateid*4);
			$ridcontrol=mysqli_query($connection, "SELECT privateid FROM users WHERE recoveryid='$calculate'");
			if(mysqli_num_rows($results)==1){
				goto point1;
			}
			$update=mysqli_query($connection, "UPDATE users SET recoveryprocess='1', recoveryid='$calculate' WHERE email='$email'");
			$message = "<html><body>Automatic message from System.<br />You have requested the password recovery on ".date('d/m/Y H:i:s')." via IP ".$_SERVER['REMOTE_ADDR'].".<br />If it was not you, do not consider this message, otherwise click <a href=\"http://www.changeme.com/recoverypassword.php?id=".$calculate."\">here</a>.</body></html>";
			$header = "MIME-Version: 1.0"."\r\n";
			$header .= "Content-type:text/html;charset=UTF-8"."\r\n";
			$header .= 'From: System'."\r\n";
			mail($email,"Recovery password",$message,$header);
			die("<html>
		            <head>
		               <title>Recovery password</title>
		               <link rel=\"stylesheet\" type=\"text/css\" href=\"css/style.css\">
					   <script>
			              window.setTimeout(function(){
				             window.location.href = \"login.php\";
				          }, 5000);
			           </script>
	                </head>
	                <body>
		               <center>
			              <form>
			                 <h1>An email has been sent to the address you entered.</h1>
							 <h2>You are about to be redirected ...</h2>
				          </form>
		               </center>
	                </body>
                 </html>");
		}else{
			$email_error='<span style="color:red;">Invalid account.</span><br />';
		}
	}
}

$results=mysqli_query($connection, "SELECT email, password, privateid FROM users WHERE recoveryid='".$_GET['id']."'");
if(mysqli_num_rows($results)==1){
	$h2='Enter the new password for your account.';
	$formcontent="<label>New password:</label><br />
		          <input type=\"password\" name=\"password\"><br />";
	$buttonname='changepassword';
	$buttonvalue='Change';
	while($row=mysqli_fetch_array($results,MYSQLI_ASSOC)){
		$email=$row['email'];
		$oldpassword=$row['password'];
		$privateid=$row['privateid'];
	}
}

if(isset($_POST['changepassword'])){
	$newpassword=mysqli_real_escape_string($connection, $_POST['password']);
	if(strlen($password)>20){
		$password_error='<span style="color:red;">Too long password.</span><br />';
		array_push($errors, '1');
	}
	if($newpassword==$oldpassword){
		$password_error='<span style="color:red;">You can not enter your old password.</span><br />';
		array_push($errors, '1');
	}
	if(empty($newpassword)){
		$password_error='<span style="color:red;">You did not enter your password.</span><br />';
		array_push($errors, '1');
	}
	$control=mysqli_query($connection, "SELECT privateid FROM users WHERE recoveryid='".$_GET['id']."'");
	if(mysqli_num_rows($control)!==1){
		header('location:recoverypassword.php');
	}
	if(count($errors)==0){
		$update=mysqli_query($connection, "UPDATE users SET password='$newpassword', recoveryprocess=0, recoveryid=NULL WHERE privateid='$privateid'");
		$message = "<html><body>Automatic message from System.<br />Password changed successfully! (<b>".date('d/m/Y H:i:s')."</b>, IP <b>".$_SERVER['REMOTE_ADDR']."</b>)</body></html>";
		$header = "MIME-Version: 1.0"."\r\n";
		$header .= "Content-type:text/html;charset=UTF-8"."\r\n";
		$header .= 'From: System'."\r\n";
		mail($email,"Recovery password",$message,$header);
		die("<html>
		        <head>
				   <title>Recovery password</title>
				   <link rel=\"stylesheet\" type=\"text/css\" href=\"css/style.css\">
				   <script>
				      window.setTimeout(function(){
					     window.location.href = \"login.php\";
				      }, 5000);
			       </script>
	            </head>
				<body>
				   <center>
				      <form>
					     <h1>Password changed successfully!</h1>
						 <h2>You are about to be redirected ...</h2>
				      </form>
		           </center>
	            </body>
            </html>");
	}
}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Recovery password</title>
		<link rel="stylesheet" type="text/css" href="css/style.css">
	</head>
	<body>
		<center>
			<form method="POST">
				<h1>Recovery password:</h1>
				<h2><?php echo $h2; ?></h2>
				<?php echo $formcontent; ?>
				<?php echo $email_error.$password_error; ?>
				<input type="submit" name="<?php echo $buttonname; ?>" value="<?php echo $buttonvalue; ?>">
				<div class="text">
					<a onclick="window.history.back();">Back</a>
				</div>
			</form>
			<div class="info">
				<span>System created by <a href="https://github.com/squarebolt">Christian Carpineta</a></span>
			</div>
		</center>
	</body>
</html>