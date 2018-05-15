<!DOCTYPE html>
<html>
<?php
session_start();
include("config.php");
if (!isset($_POST['messageFriendID']) || !isset($_SESSION['id'])  || (trim($_SESSION['id']) == '') || $_SESSION["login"] != "true") {
		header("location: index.php");
		exit();	
}
else {
	$id = $_SESSION['id'];
	$friendID = $_POST['messageFriendID'];
	
	$query = "select count(*) as count
			  from friends 
			  where (user_id1 = '".$id."' and user_id2 = '".$friendID."' ) or (user_id1 = '".$friendID."' and user_id2 = '".$id."' );";
	$result = mysqli_query($database, $query) or die(mysqli_error($database));
	
	
	if( mysqli_num_rows($result) == 0){
		header("location: friends.php");
		exit();
	}

}

/*
									<form method="post", action="message.php">
									<input name="messageFriendID" type="hidden" value = "'.$row["friend_id"].'">
									<input class="dropdown-item" role="presentation" type="submit" href="message.php" target="_blank" value="Message">
									</form>
*/

?>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>messaging</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/Contact-Form-Clean.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body style="background-color:#184660;">
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
            <a href="messages.php" class="btn btn-primary  ml-auto" role="button">Messages</a>
            <form action = 'logout.php'>
                <input class="btn btn-primary" type='submit' style="background-color:rgb(255,15,0);" value="Log Out">
            </form>
        </div>
    </div>
</nav>
    <div class="align-items-center align-content-center"></div>
    <div class="contact-clean" style="background-color:#184660;">
        <form method="post" action="sendmessage.php">
            <h2 class="text-center"><?php 
				$query = "select user_name 
						  from users
						  where user_id = '".$friendID."';";
						  
				$result = mysqli_query($database, $query) or die(mysqli_error($database));
				$count = mysqli_num_rows($result);
				
				while($row = $result -> fetch_assoc()){
					echo "Message to ". $row['user_name']. " ";
				}
				
				
			?></h2>
			<input name="messageFriendID" type="hidden" <?php echo "value = '$friendID'";messageFriendID?>>
            <div class="form-group"><textarea class="form-control" rows="14" name="message" placeholder="Message"></textarea></div>
            <div class="form-group"><button class="btn btn-primary" type="submit">send </button></div>
        </form>
    </div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>