<!DOCTYPE html>

<?php
session_start();
include("config.php");

if ( $_SESSION['authority'] != 'admin' || !isset($_SESSION['id']) || (trim($_SESSION['id']) == '') || $_SESSION["login"] != "true") {
    header("location: index.php");
    exit();
}
$id = $_SESSION['id'];
$discussion_id = $_GET['discussion_id'];

$query = "SELECT user_name, user_id FROM users u, banned_users b WHERE discussion_id = '$discussion_id' AND u.user_id = b.banned_user_id;";
$result = mysqli_query($database, $query) or die(mysqli_error($database));

?>

<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Banned Users Page</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body style="background-color:#5b77cc;">
    <nav class="navbar navbar-light navbar-expand-md" style="font-family:'Open Sans', sans-serif;/*color:#c4d0dc;*/">
        <div class="container-fluid"><a class="navbar-brand" href="store.php" data-bs-hover-animate="pulse" style="color:#acacac;font-size:16px;">Store</a><button class="navbar-toggler" data-toggle="collapse" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
            <div
                class="collapse navbar-collapse" id="navcol-1">
                <ul class="nav navbar-nav">
                    <li class="nav-item" role="presentation"><a class="nav-link active" href="friends.php" data-bs-hover-animate="pulse" style="color:#acacac;">Friends</a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link" href="library.php" data-bs-hover-animate="pulse" style="color:#acacac;">Library</a></li>
                </ul><button class="btn btn-primary ml-auto" type="button" onclick="window.location.href='messages.php'" style="background-color:rgba(99,166,67,0.31);color:rgb(255,255,255);">Messages</button></div>
            <form action = 'logout.php'>
                <input class="btn btn-primary" type='submit' style="background-color:rgb(255,15,0);" value="Log Out">
            </form>
        </div>
    </nav>
		
	<p style="color:rgb(255,255,255);padding:5px;">Banned Users</p>
		
    <div class="list-group">
	<?php
		while ($read = $result->fetch_assoc()){
		?>
			<a class="list-group-item list-group-item-action" style="background-color:#acacac;width:980px;margin:5px;padding:15px;">
				<span class="float-left" style="font-size:20px;color:#184660;"><?php echo $read['user_name']; ?></span>
				<form method = "post" action="unban.php?discussion_id=<?php echo $discussion_id ?>&banned_user_id=<?php echo $read["user_id"] ?>" >
					<input class="float-right btn btn-primary" type="submit" style="background-color:rgba(99,166,67,0.31);color:rgb(255,255,255);" value="Unban">
				</form>
			</a>
		<?php
		}
		?>
	</div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/bs-animation.js"></script>
</body>

</html>