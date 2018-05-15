<?php
session_start();
include("config.php");

$sender_id = $_GET["sender_id"];
$receiver_id = $_GET["receiver_id"];
$receiver_name = $_GET["receiver_name"];

$sql_query = "INSERT INTO friend_request (sender_id, reciever_id)
              VALUES ($sender_id,$receiver_id);
              ";

$result = mysqli_query($database, $sql_query)or die('Error in sending request');


mysqli_close($database);
?>