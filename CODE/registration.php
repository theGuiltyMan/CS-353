<?php
session_start();
error_reporting(1);
include("config.php");


$username = mysqli_real_escape_string($database, $_POST["username"]);
$email = mysqli_real_escape_string($database, $_POST["email"]);
$password = mysqli_real_escape_string($database, md5($_POST["password"]));
$repeat_password = mysqli_real_escape_string($database, md5($_POST["repeatPassword"]));
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	if( $password == $repeat_password )
	{
		$query = "INSERT INTO users (user_name, password, email, isAdmin, isGameDev) VALUES ('$username', '$password', '$email', false, false)" ;
		$insert = mysqli_query($database, $query);
		
		if($insert){
			echo "Registration successful! You will be directed to login page";
			header("Refresh: 3; index.php");
		}
		else{
			echo "Registration failed. Username or email not unique.";
		}
	}
	else{
		echo "Password and repeat password are not same";
	}
}?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>register</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/Login-Form-Dark.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body>
    <div class="login-dark" style="height:998px;">
        <form method="post">
            <h1 style="font-size:20px;height:20px;padding:13px;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Register!</h1>
            <div class="illustration"><img src="assets/img/rsz_1mist_logo.png"></div>
			<input class="form-control" type="email" name="email" placeholder="Email" required autofocus>
			<input class="form-control" type="username" name="username" placeholder="Username" required>
			<input class="form-control" type="password" name="password" placeholder="Password" required>
			<input class="form-control" type="password" name="repeatPassword" placeholder="Repeat Password" required>
            <div
                class="form-group"><button class="btn btn-primary btn-block" type="submit">Register</button></div>
    </form>
    </div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>