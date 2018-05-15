<?php
//ini_set('error_reporting', E_ALL );
//ini_set('display_errors', true );
//mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

session_start();
include("config.php");

$sender_id = $_SESSION["id"];
$game_id = $_GET["game_id"];
$receiver_id = $_GET["receiver_id"];

echo 'AAAA';

$sql_query = "INSERT INTO send_invitation (sender_id, reciever_id,game_id) VALUES ($sender_id, $receiver_id,$game_id );";
$result = mysqli_query($database, $sql_query) or die('Error in sending request');

mysqli_close($database);
?>
