<!DOCTYPE html>
<html>

<?php
session_start();
include("config.php");

if (!isset($_SESSION['id']) || (trim($_SESSION['id']) == '') || $_SESSION["login"] != "true") {
    header("location: index.php");
    exit();
}

$id = $_SESSION['id'];
?>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Friends</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans">
    <link rel="stylesheet" href="assets/css/megamenu-dark.css">
    <link rel="stylesheet" href="assets/css/megamenu-dark1.css">
    <link rel="stylesheet" href="assets/css/Sidebar-Menu.css">
    <link rel="stylesheet" href="assets/css/Sidebar-Menu1.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body style="background-color:#5b77cc;">
<nav class="navbar navbar-light navbar-expand-md ">
    <div class="container-fluid"><a class="navbar-brand" href="friends.php"
                                    style="font-size:34px;color:rgba(255,255,255,0.9);"><strong>Friends</strong></a>
        <button class="navbar-toggler" data-toggle="collapse" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span
                    class="navbar-toggler-icon"></span></button>
        <div
                class="collapse navbar-collapse" id="navcol-1">
            <ul class="nav navbar-nav">
                <li class="nav-item" role="presentation"><a class="nav-link active" href="store.php"
                                                            style="margin-top:10px;color:rgba(255,255,255,0.9);">Store</a>
                </li>
                <li class="nav-item" role="presentation"><a class="nav-link" href="library.php"
                                                            style="margin-right:0px;margin-top:10px;color:rgba(255,255,255,0.9);">Library</a>
                </li>
                <li class="nav-item" role="presentation"><a class="nav-link" href="activity.php"
                                                            style="margin-top:10px;color:rgba(255,255,255,0.9);">Activity</a>
                </li>
            </ul>
            <button class="btn btn-success ml-auto" type="button" onclick="window.location.href='add_friend.php'">Add friend</button>
            <button class="btn btn-primary" type="button" onclick="window.location.href='messages.php'">Messages</button>
			<form action = 'logout.php'>
				<input class="btn btn-primary" type='submit' style="background-color:rgb(255,15,0);" value="Log Out">
			</form>
        </div>
    </div> 
</nav>

<?php 
function isOnline($user_id,$database)
{
	$query2 = "select * from online_users where user_id = '$user_id';";
	$result3 = mysqli_query($database, $query2) or die(mysqli_error($database));
	if($result3->num_rows > 0)
	{
		return true;
	}
	else
	{
		return false;
	}

}

?>

<div style="min-width:100%;">
    <div class="container" style="margin:0;">
        <div class="row">
            <?php
				$query = "SELECT user_id1 as friend_id, user_name as friend_name
									FROM friends f, users u
									WHERE user_id2='$id' AND user_id1=u.user_id
									UNION
									SELECT user_id2 as friend, user_name as friend_name
									FROM friends f, users u
									WHERE user_id1='$id' AND user_id2=u.user_id;";
				$result = mysqli_query($database, $query) or die(mysqli_error($database));
				$div = 0;
				$count = 0;

				while ($row = $result->fetch_assoc())
				 {
					if ($count == 0) 
					{
						echo '<div class="col-md-4" style="max-width:16%;background-color:#324fa7;height:500px;">';
					}
					$query2 = "SELECT game_name
					FROM plays p, users u, games g
					WHERE p.user_id = u.user_id AND g.game_id = p.game_id AND u.user_id = $row[friend_id] AND
					p.end_date IS NULL;";

					$result2 = mysqli_query($database, $query2) or die(mysqli_error($database));
					if ($result2->num_rows == 0)
					{
						$game_string = "Not Playing";
					} 
					else 
					{
						$row2 = $result2->fetch_assoc();
						$game_string = "In game: $row2[game_name]";
					}

					echo '<div class="dropdown" style="padding-top:30px;">';
					if(isOnline($row["friend_id"],$database))
					{
						#echo "<script>alert('online');</script>";
						if( $game_string != "Not Playing")
						{
							echo '<button class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false" type="button">' . $row["friend_name"] . '</button>';

							echo '<div class="dropdown-menu" role="menu"><h6 class="dropdown-header" role="presentation"><strong>'.$game_string.'</strong></h6>';

						}
						else
						{
							echo '<button class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false" type="button">' . $row["friend_name"] . '</button>';
							echo '<div class="dropdown-menu" role="menu">';

						}

							echo '<form method="post", action="message.php">
							<input name="messageFriendID" type="hidden" value = "'.$row["friend_id"].'">
							<input class="dropdown-item" role="presentation" type="submit" href="message.php" target="_blank" value="Message">
							</form>
							<form method="post" action="friends.php">
							<input name="unfriendID" type="hidden" value = "' . $row["friend_id"] . '">
							<input class="dropdown-item" role="presentation" type="submit" href="#" value = "Unfriend">
							</form>
							</div>
							</div>';
					}
					else
					{

						echo '<button class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-expanded="false" type="button">' . $row["friend_name"] . '</button>';
						echo '<div class="dropdown-menu" role="menu">';

						echo '<form method="post", action="message.php">
							<input name="messageFriendID" type="hidden" value = "'.$row["friend_id"].'">
							<input class="dropdown-item" role="presentation" type="submit" href="message.php" target="_blank" value="Message">
							</form>
							<form method="post" action="friends.php">
							<input name="unfriendID" type="hidden" value = "' . $row["friend_id"] . '">
							<input class="dropdown-item" role="presentation" type="submit" href="#" value = "Unfriend">
							</form>
							</div>
							</div>';


						#echo "<script>alert('not onlnie');</script>";
					}

					
						
						$count++;
						if ($count == 7) {
							$count = 0;
							echo '</div>';
						}
					}
					echo '</div>';
            ?>


        </div>
    </div>
</div>
<script src="assets/js/jquery.min.js"></script>
<script src="assets/bootstrap/js/bootstrap.min.js"></script>
<script src="assets/js/megamenu-dark.js"></script>
<script src="assets/js/Sidebar-Menu.js"></script>
</body>
</html>


<?php
if (isset($_POST["unfriendID"])) {
    $unfriend_id = $_POST["unfriendID"];
    $query = "delete from friends where (user_id1 = '$id' and user_id2 = '$unfriend_id') or (user_id1 = '$unfriend_id' and user_id2='$id');";
    mysqli_query($database, $query) or die(mysqli_error($database));
    echo "<script>window.location = window.location.href;</script>";
	echo "<script>alert('Unfriend Successful.');</script>";

}

?>



