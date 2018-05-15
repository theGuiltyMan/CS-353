<!DOCTYPE html>

<?php
session_start();
include("config.php");

if (!isset($_SESSION['id']) || (trim($_SESSION['id']) == '') || $_SESSION["login"] != "true") {
header("location: index.php");
exit();
}
$id = $_SESSION['id'];
$game_id = $_GET['game_id'];
$discussion_id = $_GET['discussion_id'];

$query = "select game_name, discussion_name from games natural join discussions where game_id=".$game_id." and discussion_id=".$discussion_id.";";

$result = mysqli_query($database, $query) or die(mysqli_error($database));

while($row = $result->fetch_assoc()){
	$game_name = $row['game_name'];
	if($row['discussion_name'] == 'Technical')$discussion_name = "Technical Issues";
	else if($row['discussion_name'] == 'General')$discussion_name = "General Issues";
	else if($row['discussion_name'] == 'Gameplay')$discussion_name = "Gameplay Issues";
	else if($row['discussion_name'] == 'Guides')$discussion_name = "Guides & Tips";
}

?>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>discussiopage</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body style="background-color:#184660;">
    <nav class="navbar navbar-light navbar-expand-md" style="font-family:'Open Sans', sans-serif;/*color:#c4d0dc;*/">
        <div class="container-fluid"><a class="navbar-brand" href="store.php" data-bs-hover-animate="pulse" style="color:#acacac;font-size:16px;">Store</a><button class="navbar-toggler" data-toggle="collapse" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
            <div
            class="collapse navbar-collapse" id="navcol-1">
            <ul class="nav navbar-nav">
                <li class="nav-item" role="presentation"><a class="nav-link active" href="friends.php" data-bs-hover-animate="pulse" style="color:#acacac;">Friends</a></li>
                <li class="nav-item" role="presentation"><a class="nav-link" href="library.php" data-bs-hover-animate="pulse" style="color:#acacac;">Library</a></li>
            </ul>
				
				<button class="btn btn-primary ml-auto" <?php echo "onclick='window.location.href=\"newPost.php?discussion_id=".$discussion_id."&gameid=".$game_id."\"'"; ?> type="button" style="background-color:rgba(99,166,67,0.31);color:rgb(255,255,255);">New Post</button>
				<button class="btn btn-primary" onclick="window.location.href='messages.php'" type="button" style="margin:3px;background-color:rgba(0,123,255,0.38);">Messages</button>
			</div>
            <form action = 'banned_users.php' method="post">
                <input type="hidden" name="discussion_id" value="<?php echo $discussion_id;?>">
                <input class="btn btn-primary" type='submit' style="background-color:rgb(255,15,0);" value="Banned Users">
            </form>
            <form action = 'logout.php'>
                <input class="btn btn-primary" type='submit' style="background-color:rgb(255,15,0);" value="Log Out">
            </form>
        </div>
    </nav>
    <p style="color:rgb(255,255,255);padding:5px;"><a href="discussions.php?game_id=<?php echo $game_id; ?>"  ><?php echo $game_name; ?></a> &gt; <?php echo $discussion_name; ?> </p>
    <div class="list-group">
		<?php 
			$query = "select user_name, title, comment_id, date from users natural join ( select * from posts where discussion_id = '".$discussion_id."') t;";
			$result = mysqli_query($database, $query) or die(mysqli_error($database));
			
			while($row= $result->fetch_assoc()){
				echo '<a href="post.php?post_comment_id='.$row['comment_id'].'&discussion_id='.$discussion_id.'&game_id='.$game_id.'" class="list-group-item list-group-item-action" style="background-color:#acacac;width:980px;margin:5px;padding:15px;"><span class="float-left" style="font-size:20px;color:#184660;">'.$row["title"].'</span><span class="float-right" style="height:23px;margin:0px;padding:12px;font-size:12px;color:#184660;"> At '.$row['date'].' by '.$row['user_name'].'</span></a>';
				
			}
		?>
		
  </div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>