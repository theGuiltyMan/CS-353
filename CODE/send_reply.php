<?php
include("config.php");

if(isset($_POST["user_id"]))
{

$id = $_POST['user_id'];
$replied_id = $_POST['replied_id'];
$message = $_POST['reply'];


$query = "INSERT INTO comments (text) VALUES ('$message');";
mysqli_query($database, $query) or die(mysqli_error($database));
$comment_id = mysqli_insert_id($database)or die("sex");

$query = "insert into replies(user_id, comment_id, replied_id) values('$id', '$comment_id', '$replied_id');";
mysqli_query($database, $query) or die(mysqli_error($database));

echo 1;
}



?>