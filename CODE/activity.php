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


<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity</title>
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
        <div class="container-fluid"><a class="navbar-brand" href="#" style="font-size:34px;color:rgba(255,255,255,0.9);">Activity</a><button class="navbar-toggler" data-toggle="collapse" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
            <div
                class="collapse navbar-collapse" id="navcol-1">
                <ul class="nav navbar-nav">
                    <li class="nav-item" role="presentation"><a class="nav-link" href="store.php" style="margin-top:10px;color:rgba(255,255,255,0.9);">Store</a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link" href="library.php" style="margin-right:0px;margin-top:10px;color:rgba(255,255,255,0.9);">Library</a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link" href="friends.php" style="margin-top:10px;color:rgba(255,255,255,0.9);">Friends</a></li>
                </ul>
                <a href="messages.php" class="btn btn-primary  ml-auto" role="button">Messages</a>
				<form action = 'logout.php'>
				<input class="btn btn-primary" type='submit' style="background-color:rgb(255,15,0);" value="Log Out">
			</form>
        </div>
    </nav>
<div style="min-width:100%;">
    <div class="container" style="margin:0;">
        <div class="row" ">
            <div class="col-md-4 offset-lg-0" style="margin:0 auto; float:none; max-width:80%; height:<?php echo $size_l; ?>px; min-width:50%;  background-color:#324fa7;">
                <h1 class="text-center" style="color:rgb(255,255,255);margin:0 auto;float:none;"><strong>Social
                Activity</strong></h1>
                <div id="division"></div>
                <?php
                $query = "SELECT user_id1 as f_id,u.user_name as f_name, user_id2 as ff_id, 
                u2.user_name as ff_name, DATEDIFF(NOW(), date) AS daysold 
                FROM friends f, (SELECT user_id1 as friend_id, user_name as friend_name
                FROM friends f, users u
                WHERE user_id2=$id AND user_id1=u.user_id
                UNION
                SELECT user_id2 as friend, user_name as friend_name
                FROM friends f, users u
                WHERE user_id1=$id AND user_id2=u.user_id
            ) AS ff, users u, users u2
            WHERE (f.user_id1 = friend_id OR f.user_id2 = friend_id) AND user_id1 != $id AND user_id2 != $id
            AND u.user_id = user_id1 AND u2.user_id = user_id2 AND date >= (DATE(NOW()) - INTERVAL 7 DAY)
            ORDER BY daysold ASC;";

            $result = mysqli_query($database, $query) or die(mysqli_error($database));
            $counter_l = 1;
            while ($social_activity = $result->fetch_assoc()) {
                $friend_string = "$social_activity[f_name] became friends with $social_activity[ff_name]";
                if ($social_activity[daysold] == 0) {
                    $days_old_string = "Today";
                } else {
                    $days_old_string = "$social_activity[daysold] days ago";
                }

                echo '
                <blockquote class="blockquote">
                <p class="text-white mb-0" style="padding-top:20px;font-size:15px;">' . $friend_string . '</p>
                <footer class="blockquote-footer float-right">' . $days_old_string . '</footer>
                </blockquote>';
                $counter_l = $counter_l +1;
            }
            $size_l = 130 * $counter_l;
            ?>
        </div>

        <div class="col-md-4 offset-lg-0" style="margin:0 auto;max-width:80%; float:none; height:<?php echo $size_r; ?>px;min-width:45%; background-color:#324fa7;">
            <h1 class="text-center" style="color:rgb(255,255,255);margin:0 auto;float:none;"><strong>Game
            Activity</strong></h1>
            <div id="division"></div>
                <?php
                $query = "SELECT user_name as f_name, game_name as g_name, TIMESTAMPDIFF(HOUR, p.start_date , p.end_date) AS hours_played,
DATEDIFF(NOW(), p.end_date) AS daysold
FROM (SELECT user_id1 as friend_id, user_name as friend_name
								FROM friends f, users u
								WHERE user_id2=$id AND user_id1=u.user_id
								UNION
								SELECT user_id2 as friend, user_name as friend_name
								FROM friends f, users u
								WHERE user_id1=$id AND user_id2=u.user_id
) AS f, users u, games g, plays p
WHERE friend_id = u.user_id AND p.user_id = u.user_id AND p.game_id = g.game_id
ORDER BY daysold ASC
;
";
                $result_2 = mysqli_query($database, $query) or die(mysqli_error($database));
                $counter_r = 1;
                while ($gaming_activity = $result_2->fetch_assoc()) {
                    if ($gaming_activity[hours_played] == 0){
                        $game_string = "$gaming_activity[f_name] played $gaming_activity[g_name] for less than one hour.";
                    } else if ($gaming_activity[hours_played] == 1){
                        $game_string = "$gaming_activity[f_name] played $gaming_activity[g_name] for $gaming_activity[hours_played] hour.";
                    }
                    else {
                        $game_string = "$gaming_activity[f_name] played $gaming_activity[g_name] for $gaming_activity[hours_played] hours.";
                    }
                    if ($gaming_activity[daysold] == 0) {
                        $days_old_string = "Today";
                    } else {
                        $days_old_string = "$gaming_activity[daysold] days ago";
                    }
                    echo '
                <blockquote class="blockquote">
                    <p class="text-white mb-0" style="padding-top:20px;font-size:15px;">'.$game_string.'</p>
                    <footer class="blockquote-footer float-right">'.$days_old_string.'</footer>
                </blockquote>'; 
                $counter_r= $counter_r +1;
                }
                $size_r = 130 * $counter_r;
                ?>
            </div>
        </div>
    </div>
</div>
<script src="assets/js/jquery.min.js"></script>
<script src="assets/bootstrap/js/bootstrap.min.js"></script>
<script src="assets/js/megamenu-dark.js"></script>
<script src="assets/js/Sidebar-Menu.js"></script>
</body>

</html>
