<?php
session_start();
include("config.php");

$moderator_id = $_SESSION["id"];
$user_id = $_GET["user_id"];
$discussion_id = $_GET["discussion_id"];


$sql_query = "INSERT INTO banned_users(banned_user_id, moderator_id, discussion_id) VALUES ($user_id, $moderator_id,$discussion_id);";
$result = mysqli_query($database, $sql_query) or die('Error in sending request');

mysqli_close($database);
?>