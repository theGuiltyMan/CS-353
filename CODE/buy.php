<?php
include("config.php");
if (isset($_POST["bgn"]) )
{
	if($_POST["bgn"] == chk)
	{
		$desired_game_id = $_POST["d_game_id"];
		$sql_query = "SELECT game_id,game_name,price FROM games WHERE game_id = '$desired_game_id'; ";
		$result = mysqli_query($database, $sql_query)or die('Error In desired game');
		$game = $result->fetch_assoc();

		$id = $_POST["id"];
		$sql_query = "select balance, isGameDev from users where user_id = '$id' ";
		$result = mysqli_query($database, $sql_query)or die('Error In getting user');
		$user = $result->fetch_assoc();

		if($user["balance"] >= $game["price"] )
		{
			echo "1";
		}
		else
		{
			echo "0";
		}
	}
	else if( $_POST["bgn"] == by)
	{
		$desired_game_id = $_POST["d_game_id"];
		$id = $_POST["id"];
		$sql_query = "SELECT * FROM buy WHERE user_id = '$id' AND game_id = '$desired_game_id'; ";
		$result = mysqli_query($database, $sql_query)or die('Error in checking duplicate');
		if($result->num_rows > 0)
		{
			echo "2";
		}
		else
		{
			$sql_query = "SELECT price FROM games WHERE game_id = '$desired_game_id'; ";
			$result = mysqli_query($database, $sql_query)or die('Error in checking duplicate');
			$row = $result->fetch_assoc();
			$price = $row["price"];

			$sql_query = "INSERT INTO buy (user_id, game_id, price) VALUES ('$id','$desired_game_id','$price'); ";
			$result = mysqli_query($database, $sql_query)or die ("Error in inserting buy");
			if($result)
			{
				echo "1";
			}
			else{echo "dafuq";}
			
		}

		

	}
	


}
?>