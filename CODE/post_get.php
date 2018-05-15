<?php
include("config.php");
header('Content-Type: application/json');
if(isset($_POST))
{
	#var_dump($_POST);
	$comment_id =$_POST["comment_id"];
	$query = "select comment_id, date, text, user_name,user_id from replies natural join comments natural join users where replied_id='$comment_id' order by date asc;";
	$result = mysqli_query($database, $query);
	$count = mysqli_num_rows($result);
	$percentage = $_POST["percentage"] + 50;
	$replies = array();
	while( $row = $result->fetch_assoc() )
	{
		$replies[] = array( "comment_id" => $row["comment_id"], "percentage" => $percentage, "date" => $row["date"], "user_name" => $row["user_name"], "text" => $row["text"], "user_id" => $row["user_id"] );
	}

	echo json_encode(array("count" => $count, "replies" => $replies));
  
}


?>