<?php
include("config.php");
if (isset($_POST["id"]) && isset($_POST["money"]) )
{
	$id = $_POST["id"];
	$amount = $_POST["money"];
	$sql_query = "UPDATE users SET balance = balance + '$amount' WHERE user_id = '$id'; ";
	$result = mysqli_query($database, $sql_query);
	if($result)
	{
		echo "1";
	}
	else
	{
		echo "0";
	}
}
?>