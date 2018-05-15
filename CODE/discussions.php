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

$query = "SELECT * FROM discussions where game_id = '$game_id';";
$result = mysqli_query($database, $query) or die(mysqli_error($database));

while ($disc = $result->fetch_assoc()){
	if($disc['discussion_name'] == 'Technical')$technical = $disc['discussion_id'];
	else if($disc['discussion_name'] == 'General')$general = $disc['discussion_id'];
	else if($disc['discussion_name'] == 'Gameplay')$gameplay= $disc['discussion_id'];
	else if($disc['discussion_name'] == 'Guides')$guides = $disc['discussion_id'];
}

?>

<html>

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
                </ul><button class="btn btn-primary ml-auto" type="button" onclick="window.location.href='messages.php'" style="background-color:rgba(99,166,67,0.31);color:rgb(255,255,255);">Messages</button></div>
        </div>
    </nav>
    <?php
		$query = "select game_name from games where game_id='$game_id';";
		$result = mysqli_query($database, $query) or die(mysqli_error($database));
		while($name = $result->fetch_assoc())
			echo '<p style="color:rgb(255,255,255);padding:5px;">'.$name[game_name].'</p>';
		
	?>
    <div class="list-group">
		<a class="list-group-item list-group-item-action" <?php echo "href='posts.php?game_id=".$game_id."&discussion_id=".$technical."'"; ?> style="background-color:#acacac;width:980px;margin:5px;padding:15px;"><span class="float-left" style="font-size:20px;color:#184660;">Technical Issues</span></a>
		<a class="list-group-item list-group-item-action" <?php echo "href='posts.php?game_id=".$game_id."&discussion_id=".$general."'"; ?> style="background-color:#acacac;width:980px;margin:5px;padding:15px;"><span class="float-left" style="font-size:20px;color:#184660;">General Issues</span></a>
		<a class="list-group-item list-group-item-action" <?php echo "href='posts.php?game_id=".$game_id."&discussion_id=".$guides."'"; ?> style="background-color:#acacac;width:980px;margin:5px;padding:15px;"><span class="float-left" style="font-size:20px;color:#184660;">Guides & Tips</span></a>
		<a class="list-group-item list-group-item-action" <?php echo "href='posts.php?game_id=".$game_id."&discussion_id=".$gameplay."'"; ?> style="background-color:#acacac;width:980px;margin:5px;padding:15px;"><span class="float-left" style="font-size:20px;color:#184660;">Gameplay Issues</span></a>
	</div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/bs-animation.js"></script>
</body>

</html>