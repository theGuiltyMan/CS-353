<?php
session_start();
include("config.php");

$id = $_GET["id"];
$selected_name = $_GET["lib_name"];
$game_name = $_GET["game_name"];
$game_id = $_GET["game_id"];
echo $game_name ." ";
echo $id . " ";
echo $selected_name." ";
echo $game_id. " ";

$sql_query = "DELETE
FROM library
WHERE user_id='$id' AND game_id='$game_id' AND library_name='$selected_name'; ";

$result = mysqli_query($database, $sql_query)or die('Error in deleting');
#$row =  $result->fetch_assoc();
#echo $row["game_name"];

mysqli_close($database);
?>
