<!DOCTYPE html>

<?php
session_start();
include("config.php");

if (!isset($_SESSION['id']) || (trim($_SESSION['id']) == '') || $_SESSION["login"] != "true") {
    header("location: index.php");
    exit();
}
$id = $_SESSION['id'];
$discussion_id = $_GET['discussion_id'];
$game_id = $_GET['gameid'];
?>

<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Post</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/Contact-Form-Clean.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body style="background-color:#184660;">
    <div class="contact-clean" style="background-color:#184660;">
        <form method="post" action="createPost.php?game_id= <?php echo $game_id; ?>">
            <h2 class="text-center">New Post</h2>
			<?php echo'<input name="discussion_id" type="hidden" value = "'.$discussion_id.'">';?>
            <div class="form-group"><input class="form-control" type="text" name="title" placeholder="Title"></div>
            <div class="form-group"><textarea class="form-control" rows="14" name="message" placeholder="Text"></textarea></div>
            <div class="form-group"><button class="btn btn-primary" type="submit">send </button></div>
        </form>
    </div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>