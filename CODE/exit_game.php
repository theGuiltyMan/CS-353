<?php
include("config.php");
if(isset($_POST["game_id"]))
{
	#var_dump($_POST);
	$game_id = $_POST["game_id"];
	$user_id =$_POST["user_id"];
	$query = "UPDATE plays
	SET end_date = NOW()
	WHERE user_id = '$user_id' AND game_id = '$game_id'AND end_date is NULL;";
	$result = mysqli_query($database, $query);
	if($result)
	{
		echo 1;
	}
	else{ echo 0;}
  
}


?>