<!DOCTYPE html>

<?php
//ini_set('error_reporting', E_ALL );
//ini_set('display_errors', true );
//mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

session_start();
include("config.php");

if (!isset($_SESSION['id']) || (trim($_SESSION['id']) == '') || $_SESSION["login"] != "true") {
    header("location: index.php");
    exit();
}
$id = $_SESSION['id'];
$game_id = $_GET['game_id'];


$query = "select game_id from plays where user_id = '$id' AND end_date IS NULL;";
$result = mysqli_query($database, $query) or die(mysqli_error($database));

if ($result->num_rows > 0) {
    $result = $result->fetch_assoc();
    $game_id = $result["game_id"];
    $query = "select game_name from games where game_id='$game_id';";
    $result = mysqli_query($database, $query) or die(mysqli_error($database));
    $game = $result->fetch_assoc();
    $game_name = $game["game_name"];


} else {
    $query = "select game_name from games where game_id='$game_id';";
    $result = mysqli_query($database, $query) or die(mysqli_error($database));
    $game = $result->fetch_assoc();
    $game_name = $game["game_name"];

    $query = "INSERT INTO plays (user_id, game_id) VALUES ('$id','$game_id');";
    $result = mysqli_query($database, $query) or die(mysqli_error($database));

}


?>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Play Page</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<script>
    function exitGame(user_id, game_id) {

        $.ajax({
            method: "POST",
            url: "exit_game.php",
            data: {user_id: user_id, game_id: game_id},
            success: function (result) {
                if (result == 1) {
                    window.location.href = "library.php";
                }
            }
        });
    }

</script>

<body style="background-color:#5b77cc;">
<nav class="navbar navbar-light navbar-expand-md" style="font-family:'Open Sans', sans-serif;/*color:#c4d0dc;*/">
    <div class="container-fluid"><a class="navbar-brand disabled" data-bs-hover-animate="pulse"
                                    style="color:#acacac;font-size:16px;">Store</a>
        <button class="navbar-toggler" data-toggle="collapse" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span
                    class="navbar-toggler-icon"></span></button>
        <div
                class="collapse navbar-collapse" id="navcol-1">
            <ul class="nav navbar-nav">
                <li class="nav-item" role="presentation"><a class="nav-link disabled" data-bs-hover-animate="pulse"
                                                            style="color:#acacac;">Friends</a></li>
                <li class="nav-item" role="presentation"><a class="nav-link disabled" data-bs-hover-animate="pulse"
                                                            style="color:#acacac;">Library</a></li>
            </ul>
            <button class="btn btn-primary disabled ml-auto" type="button"
                    style="background-color:rgba(99,166,67,0.31);color:rgb(255,255,255);">Messages
            </button>
        </div>
    </div>
</nav>

<div class="alert alert-success" role="alert">
    <h4 class="alert-heading">Playing <?php echo $game_name; ?></h4>
    <div class="row">
        <p>
            <button class="btn btn-warning ml-auto"
                    onclick="exitGame('<?php echo $id; ?>' , ' <?php echo $game_id ?>');"
                    type="button">Exit Game
            </button>
        </p>
        <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton"
                    data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                Invite Friend
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

                <?php
                $query = "SELECT DISTINCT f.friend_name, f.friend_id
FROM (SELECT user_id1 as friend_id, user_name as friend_name
								FROM friends f, users u
								WHERE user_id2=$id AND user_id1=u.user_id
								UNION
								SELECT user_id2 as friend, user_name as friend_name
								FROM friends f, users u
								WHERE user_id1=$id AND user_id2=u.user_id
) AS f, buy b
WHERE f.friend_id IN (SELECT user_id FROM buy WHERE game_id=$game_id);";
                $result = mysqli_query($database, $query) or die(mysqli_error($database));

                while ($friends = $result->fetch_assoc()) {
                    $friend_name = $friends[friend_name];
                    $friend_id = $friends[friend_id];
//            echo $friend_name;
//            echo $friend_id;
                    ?>


                    <a class="dropdown-item"
                       onclick="send_invitation('<?php echo $friend_id; ?>','<?php echo $game_id; ?>');">
                        <?php echo $friend_name; ?></a>
                    <?php
                }
                ?>
            </div>

        </div>
    </div>
</div>

<script src="assets/js/jquery.min.js"></script>
<script src="assets/bootstrap/js/bootstrap.min.js"></script>
<script src="assets/js/bs-animation.js"></script>
<script>
    function send_invitation(receiver_id, game_id) {
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        // alert("sender_id:"+sender_id+" receiver id:"+receiver_id+" game id:"+game_id);

        xmlhttp.open("GET", "send_game_invitation.php?receiver_id=" + receiver_id + "&game_id=" + game_id, true);
        xmlhttp.send();

        alert("Game invitation send");
    }
</script>
</body>

</html>
