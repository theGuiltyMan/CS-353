<?php
ini_set('error_reporting', E_ALL );
ini_set('display_errors', true );
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

session_start();
include("config.php");

$receiver_id = $_SESSION["id"];
$game_id = $_GET["game_id"];
$sender_id = $_GET["sender_id"];


$sql_query = "DELETE FROM send_invitation WHERE sender_id=$sender_id AND game_id=$game_id AND reciever_id=$receiver_id;";
$result = mysqli_query($database, $sql_query) or die('Error in sending request');

mysqli_close($database);
?>
