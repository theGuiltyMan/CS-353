<?php
session_start();
include("config.php");

$sender_id = $_GET["sender_id"];
$receiver_id = $_SESSION["id"];

$sql_query = "DELETE FROM friend_request WHERE sender_id = '".$sender_id."' AND reciever_id = '".$receiver_id."';";
$delete = mysqli_query($database, $sql_query)or die('Error in sending request');

mysqli_close($database);
?>
