<?php
session_start();
error_reporting(1);
include("config.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
$username = mysqli_real_escape_string($database, $_POST["username"]);
$email = mysqli_real_escape_string($database, $_POST["email"]);
$query = "select * from users where user_name = '".$username."' and email = '".$email."' ";
$users = mysqli_query($database, $query);

	if(mysqli_num_rows($users)==1)
	{
		while($read = mysqli_fetch_assoc($users)) {
			$id = $read["user_id"];
			$random_key = rand();
			if( $random_key ){
				$password = md5($random_key);
				$query = "UPDATE users SET password = '".$password."' WHERE user_id = '".$id."' ";
				$change = mysqli_query($database, $query);
			}
			echo "Password changed successfully. New password is $random_key ";
		}
	}

	else{
		echo "Could not change password";
	}
}?>



<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>forget_password</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/Login-Form-Dark.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body>
    <div class="login-dark" style="height:998px;">
        <form method="post">
            <h1 style="font-size:20px;height:20px;padding:13px;">Forgot your password?</h1>
            <div class="illustration"><img src="assets/img/rsz_1mist_logo.png"></div>
			<input class="form-control" type="username" name="username" placeholder="Username" required autofocus> <input class="form-control" type="email" name="email" placeholder="Email" required>
            <div class="form-group"><button class="btn btn-primary btn-block" type="submit">Change Password</button></div>
        </form>
    </div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>
	
	