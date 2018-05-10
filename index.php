<?php
session_start();
include("config.php");

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
	$usernameOrEmail = mysqli_real_escape_string($database, $_POST["usernameOrEmail"]);
	$password = mysqli_real_escape_string($database, md5($_POST["password"]) );
	$sql_query = "select * from users where (user_name = '".$usernameOrEmail."' or email = '".$usernameOrEmail."') and password = '".$password."' ";
	$users = mysqli_query($database, $sql_query);
	if( mysqli_num_rows($users) == 1)
	{
		while( $read = mysqli_fetch_assoc($users))
		{
			#if($read["authority"]=="1"){ $_SESSION["authority"]="admin"; } else { $_SESSION["authority"]="user"; }
			$_SESSION["id"] = $read["user_id"];
		
		}
		echo "Login successful";
		$_SESSION["login"]="true";
		#header("Location: welcome.php");	
	} 
	else 
	{
		$_SESSION["login"]="false"; 
		 ?>
		<?php
		#header("Refresh:0");
		#echo "<script type='text/javascript'>alert('Login failed!')</script>";
		#header("Refresh:0");
		#header("Location: index.php");
		#echo "Login attempt failed. Invalid username or password. Page will be refreshed in 3 seconds"; ?>
		<html>

		<head>
			<meta charset="utf-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<title>login</title>
			<link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
			<link rel="stylesheet" href="assets/css/Login-Form-Dark.css">
			<link rel="stylesheet" href="assets/css/styles.css">
		</head>

		<body>
			<div class="login-dark">
				<form method="post">
					<h2 class="sr-only">Login Form</h2>
					<a class = "a"> Login failed! </a>
					<div class="illustration"><img src="assets/img/rsz_1mist_logo.png"></div>
					<div class="form-group"><input class="form-control" type="username" name="usernameOrEmail" placeholder="Username or Email" required autofocus></div>
					<div class="form-group"><input class="form-control" type="password" name="password" placeholder="Password" required></div>
					<div class="form-group"><button class="btn btn-primary btn-block" type="submit">Login</button>
					</div><a href="#" class="forgot">Forgot your username or password?</a>
					<a href="#" class="forgot">Not a user? Register!</a></form>
			</div>
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/bootstrap/js/bootstrap.min.js"></script>
		</body>

		</html> <?php	
	}
	
	exit();
	
}
?>

<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/Login-Form-Dark.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body>
    <div class="login-dark">
        <form method="post">
            <h2 class="sr-only">Login Form</h2>
            <div class="illustration"><img src="assets/img/rsz_1mist_logo.png"></div>
            <div class="form-group"><input class="form-control" type="username" name="usernameOrEmail" placeholder="Username or Email" required autofocus></div>
            <div class="form-group"><input class="form-control" type="password" name="password" placeholder="Password" required></div>
            <div class="form-group"><button class="btn btn-primary btn-block" type="submit">Login</button>
			</div><a href="#" class="forgot">Forgot your username or password?</a>
			<a href="#" class="forgot">Not a user? Register!</a></form>
			
    </div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>