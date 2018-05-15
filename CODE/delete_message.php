<?php
session_start();
include("config.php");

$message_id = $_GET["message_id"];
$receiver_id = $_SESSION["id"];

$sql_query = "DELETE FROM messages WHERE message_id=$message_id;";

$result = mysqli_query($database, $sql_query)or die('Error in deleting');

