<?php 
	session_start();
	include("config.php");
	
	if (!isset($_POST['messageFriendID']) || !isset($_SESSION['id'])  || (trim($_SESSION['id']) == '') || $_SESSION["login"] != "true") {
		header("location: index.php");
		exit();	
	}
	
	$userID = $_SESSION['id'];
	$friendID = $_POST['messageFriendID'];
	$message = $_POST['message'];
	
	if(isset($_POST["message"])){
		$query = "INSERT INTO messages (text, reciever_id, sender_id) VALUES ('$message', '$friendID', '$userID');";
		mysqli_query($database, $query) or die(mysqli_error($database));
		
		header("Location: messages.php");
	}
?>