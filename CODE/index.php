<?php
session_start();
include("config.php");

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
	$usernameOrEmail = mysqli_real_escape_string($database, $_POST["usernameOrEmail"]);
	$password = mysqli_real_escape_string($database, md5($_POST["password"]) );
	$sql_query = "select * from users where (user_name = '".$usernameOrEmail."' or email = '".$usernameOrEmail."') and password = '".$password."' ";
	$users = mysqli_query($database, $sql_query);
	$check = mysqli_num_rows($users) == 1;
	if( $check)
	{
		while( $read = mysqli_fetch_assoc($users))
		{
			#if($read["authority"]=="1"){ $_SESSION["authority"]="admin"; } else { $_SESSION["authority"]="user"; }
			$_SESSION["id"] = $read["user_id"];
			$id = $read["user_id"];

			$sql_query = "select * from online_users where user_id = '$id';";
			$result = mysqli_query($database,$sql_query);

			if($result)
			{
				if(mysqli_num_rows($result) != 0)
				{
					$already = 1;
					echo "<script>alert('already');</script>";
				}
				else
				{
					$already = 0;
					echo "<script>alert('not already');</script>";
				}

			}
			else
			{
				echo "<script>alert('test etst');</script>";
			}



				$_SESSION["login"]="true";

				if($read["isAdmin"]==1){
					$_SESSION["authority"]="admin";
				}
				else{
					$_SESSION["authority"]="user";
				}

				if($read["isGameDev"]==1){
					$_SESSION["developer"]="true";
				}
				else{
					$_SESSION["developer"]="false";
				}

				header("Location: library.php");
	
			if (!$already)
			{
				$sql_query = "INSERT INTO online_users (user_id) VALUES ('$id'); ";
				$result = mysqli_query($database, $sql_query);

				if(!$result)
				{
					$_SESSION["login"]="false";
				}else
				{
					$_SESSION["login"]="true";

				if($read["isAdmin"]==1){
					$_SESSION["authority"]="admin";
				}
				else{
					$_SESSION["authority"]="user";
				}

				if($read["isGameDev"]==1){
					$_SESSION["developer"]="true";
				}
				else{
					$_SESSION["developer"]="false";
				}

				header("Location: library.php");
				}	
			}else
			{
				$_SESSION["login"]="true";

				if($read["isAdmin"]==1){
					$_SESSION["authority"]="admin";
				}
				else{
					$_SESSION["authority"]="user";
				}

				if($read["isGameDev"]==1){
					$_SESSION["developer"]="true";
				}
				else{
					$_SESSION["developer"]="false";
				}

				header("Location: library.php");
			}
			
		}

		#echo "Login successful";
	
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
					</div><a href="forget_password.php" class="forgot">Forgot your password?</a>
					<a href="registration.php" class="forgot">Not a user? Register!</a>
					</form>
			</div>
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/bootstrap/js/bootstrap.min.js"></script>
		</body>

		</html> <?php	
	}
	
	
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
            <?php if($_SERVER['REQUEST_METHOD'] == 'POST' && $_SESSION["login"] == "false")
            {?>
            	<a class = "a"> Login failed! </a>
            <?php }; ?>
            <div class="illustration"><img src="assets/img/rsz_1mist_logo.png"></div>
            <div class="form-group"><input class="form-control" type="username" name="usernameOrEmail" placeholder="Username or Email" required autofocus></div>
            <div class="form-group"><input class="form-control" type="password" name="password" placeholder="Password" required></div>
            <div class="form-group"><button class="btn btn-primary btn-block" type="submit">Login</button>
			</div><a href="forget_password.php" class="forgot">Forgot your password?</a>
			<a href="registration.php" class="forgot">Not a user? Register!</a></form>
			
    </div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>