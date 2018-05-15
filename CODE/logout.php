<?php
session_start();
include("config.php");
	
	$id = $_SESSION["id"];

	$sql_query = "DELETE FROM online_users WHERE user_id = '$id';";
	$result = mysqli_query($database, $sql_query);

	if($result)
	{

	session_unset();
	session_destroy();
	header("Location: index.php");

	}

?>