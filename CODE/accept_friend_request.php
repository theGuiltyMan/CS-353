<?php
session_start();
include("config.php");

$sender_id = $_GET["sender_id"];
$receiver_id = $_SESSION["id"];

$sql_query1 = "INSERT INTO friends(user_id1, user_id2) VALUES ($sender_id,$receiver_id);";
$result = mysqli_query($database, $sql_query1)or die('Error in sending request');

$sql_query2 = "DELETE FROM friend_request WHERE sender_id = '".$sender_id."' AND reciever_id = '".$receiver_id."';";
$delete = mysqli_query($database, $sql_query2)or die('Error in sending request');
mysqli_close($database);
?>
