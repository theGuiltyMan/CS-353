<?php 
	session_start();
	include("config.php");
	
	if ( !isset($_POST['message']) || !isset($_POST['title']) || !isset($_SESSION['id'])  || (trim($_SESSION['id']) == '') || $_SESSION["login"] != "true") {
		header("location: index.php");
		exit();	
	}
	
	$userID = $_SESSION['id'];
	$message = $_POST['message'];
	$title = $_POST['title'];
	$discussion_id = $_POST['discussion_id'];
	$game_id = $_GET['game_id'];

	
	$query = "INSERT INTO comments (text) VALUES ('$message');";
	mysqli_query($database, $query) or die(mysqli_error($database));
	$comment_id = mysqli_insert_id($database);
	$query = "insert into posts(user_id, title, comment_id, discussion_id) values('$userID', '$title', '$comment_id', '$discussion_id');";
	mysqli_query($database, $query) or die(mysqli_error($database));

	header("Location: posts.php?game_id=" . $game_id ."&discussion_id=". $discussion_id);
	
?>