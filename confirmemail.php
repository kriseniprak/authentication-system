<?php
session_start();

if(!isset($_SESSION['pid'])){
	header('location:login.php');
}

$connection=mysqli_connect('localhost', 'username', 'password', 'database');
$errors=array();

$calculate=(($_GET['id']-662885929347)/20);

$control=mysqli_query($connection, "SELECT confirmed FROM users WHERE privateid='$calculate'");
if(mysqli_num_rows($control)==1){
	while($row=mysqli_fetch_array($control,MYSQLI_ASSOC)){
		$confirmed=$row['confirmed'];
	}
}else{
	header('location:index.php');
}

echo $confirmed;

if($confirmed!=='1'){
	$update=mysqli_query($connection, "UPDATE users SET confirmed='1' WHERE privateid='$calculate'");
	die("<html>
		    <head>
		       <title>Email confirm</title>
		       <link rel=\"stylesheet\" type=\"text/css\" href=\"css/style.css\">
			   <script>
			      window.setTimeout(function(){
				      window.location.href = \"index.php\";
				  }, 5000);
			   </script>
	        </head>
	        <body>
		       <center>
			      <form>
			         <h1>Email confirmed!</h1>
					 <h2>You are about to be redirected ...</h2>
				  </form>
		       </center>
	        </body>
         </html>");
} else {
	header('location:index.php');
}
?>