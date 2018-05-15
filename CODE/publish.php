<?php
session_start();
include("config.php");
header('Content-Type: application/json');

echo "<script>alert('Hello');</script>";

if ($_SESSION['developer'] == 'false' || !isset($_SESSION['id']) || (trim($_SESSION['id']) == '') || $_SESSION["login"] != "true") {
    header("location: index.php");
    exit();
}

$id = $_SESSION['id']; 
$game_name = $_POST['game_name'];
$image_url = $_POST['image_url'];
$price = $_POST['price'];
$description = $_POST[description];
$genre=$_POST['genre'];

$query = "insert into games(game_name, price, img_location, description, publisher_id) 
		values('$game_name', '$price', '$image_url', '$description', $id);";

$result = mysqli_query($database, $query) or die(mysqli_error($database));
$game_id = mysqli_insert_id($database);

$query = "insert into game_genres(game_id, genre_id) values";

if(count($genre) > 0){	
	$query = "insert into game_genres(game_id, genre_id) values";
	
	for($i = 0; $i < count($genre)-1; $i++){
		$query .= "('$game_id', '$genre[$i]'), ";
	}
	
	$query .= "('$game_id', '$genre[$count($genre)-1]');"; 
	$result = mysqli_query($database, $query) or die(mysqli_error($database));
}

header("Location: store.php");		
		//
?>