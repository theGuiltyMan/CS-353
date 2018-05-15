<?php

//ini_set('error_reporting', E_ALL);
//ini_set('display_errors', true);
//mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

session_start();
include("config.php");

if (!isset($_SESSION['id']) || (trim($_SESSION['id']) == '') || $_SESSION["login"] != "true") {
    header("location: index.php");
    exit();
}
$id = $_SESSION['id'];
$post_comment_id = $_GET['post_comment_id'];
$discussion_id = $_GET['discussion_id'];
$game_id = $_GET['game_id'];

$is_banned_query = "SELECT * FROM banned_users
WHERE banned_user_id=$id";

$result = mysqli_query($database, $is_banned_query) or die(mysqli_error($database));

$is_banned = true;

if ($result->num_rows) {
    $is_banned = false;
}

$is_admin_query = "SELECT * FROM moderates
WHERE user_id=$id AND discussion_id=$discussion_id;";

$result = mysqli_query($database, $is_admin_query) or die(mysqli_error($database));

$is_admin = false;

if ($result->num_rows) {
    $is_admin = true;
}


$query = " SELECT g.game_name, d.discussion_name, p.title
			FROM games g, discussions d, posts p
			WHERE d.game_id = g.game_id AND p.discussion_id = d.discussion_id AND
			g.game_id = '$game_id' AND p.discussion_id = '$discussion_id' AND 
			p.comment_id = '$post_comment_id'";
$result = mysqli_query($database, $query) or die(mysqli_error($database));
$where = $result->fetch_assoc();


?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans">
    <link rel="stylesheet" href="assets/css/styles.css">

    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/megamenu-dark.js"></script>
    <script src="assets/js/Sidebar-Menu.js"></script>
</head>

<body style="background-color:#184660;">
<nav class="navbar navbar-light navbar-expand-md" style="font-family:'Open Sans', sans-serif;/*color:#c4d0dc;*/">
    <div class="container-fluid"><a class="navbar-brand" href="store.php"
                                    style="color:#acacac;font-size:16px;">Store</a>
        <button class="navbar-toggler" data-toggle="collapse" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span
                    class="navbar-toggler-icon"></span></button>
        <div
                class="collapse navbar-collapse" id="navcol-1">
            <ul class="nav navbar-nav">
                <li class="nav-item" role="presentation"><a class="nav-link active" href="friends.php"
                                                            style="color:#acacac;">Friends</a></li>
                <li class="nav-item" role="presentation"><a class="nav-link" href="library.php" style="color:#acacac;">Library</a>
                </li>
            </ul>
            <button class="btn btn-primary ml-auto" onclick="window.location.href='messages.php'" type="button"
                    style="background-color:rgba(99,166,67,0.31);color:rgb(255,255,255);">Messages
            </button>
            <form action = 'logout.php'>
                <input class="btn btn-primary" type='submit' style="background-color:rgb(255,15,0);" value="Log Out">
            </form>
        </div>
    </div>
</nav>
<p style="color:rgb(255,255,255);padding:5px;"><a
            href="discussions.php?game_id=<?php echo $game_id; ?>"><?php echo $where["game_name"]; ?></a> &gt;<a
            href="posts.php?game_id=<?php echo $game_id; ?>&discussion_id=<?php echo $discussion_id; ?>"> <?php echo $where["discussion_name"]; ?>
        Issues </a>&gt; <?php echo $where["title"]; ?></p>
<div class="list-group float-left">

    <script>
        function ban_user(user_id,discussion_id) {
            if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else {
                // code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }

            xmlhttp.open("GET", "ban_user.php?user_id=" + user_id + "&discussion_id=" + discussion_id, true);
            xmlhttp.send();

            alert("User banned from discussion");
        }
        function getReplies(comment_id, percentage, date, user_name, text,user_id) {
            // console.log("Comment id: " + comment_id);
            var change = document.getElementById('result');
            var button = "";
            if (<?php echo $is_admin ? 'true' : 'false'; ?> && <?php echo $id;?> != user_id) {
                button = '<a class="list-group-item list-group-item-action active float-left" style="background-color:#acacac;width:980px;margin:5px;padding:15px;margin-left:' + percentage + 'px;max-width:50%;">'
                    + '<span class="float-left" style="font-size:20px;color:#184660;">' + text + '</span>' + '<span class="float-right" style="height:23px;margin:0px;padding:12px;font-size:12px;color:#184660;">At '
                    + date + ' by ' + user_name + '#' + comment_id +  '<button class="btn btn-danger" type="button" style="margin-top:-18px;margin-left:3px;" onclick="ban_user(\''+user_id+'\',\''+<?php echo $discussion_id; ?> + '\');">Ban </button>' +' <button onclick="window.location.href=' +
                    "'reply.php?comment_id=" + comment_id + "&discussion_id="+<?php echo $discussion_id; ?>+
                "&post_comment_id="+<?php echo $post_comment_id; ?>+
                "&game_id=" +<?php echo $game_id; ?> +"'" + '" class="btn btn-primary" type="button" style="margin-top:-18px;">Reply</button></span></a>';


            } else if (<?php echo $is_banned ? 'false' : 'true'; ?>){
                button = '<a class="list-group-item list-group-item-action active float-left" style="background-color:#acacac;width:980px;margin:5px;padding:15px;margin-left:' + percentage + 'px;max-width:50%;">'
                    + '<span class="float-left" style="font-size:20px;color:#184660;">' + text + '</span>' + '<span class="float-right" style="height:23px;margin:0px;padding:12px;font-size:12px;color:#184660;">At '
                    + date + ' by ' + user_name + '#' + comment_id + '</span></a>';
            }else{
                button = '<a class="list-group-item list-group-item-action active float-left" style="background-color:#acacac;width:980px;margin:5px;padding:15px;margin-left:' + percentage + 'px;max-width:50%;">'
                    + '<span class="float-left" style="font-size:20px;color:#184660;">' + text + '</span>' + '<span class="float-right" style="height:23px;margin:0px;padding:12px;font-size:12px;color:#184660;">At '
                    + date + ' by ' + user_name + '#' + comment_id + ' <button onclick="window.location.href=' +
                    "'reply.php?comment_id=" + comment_id + "&discussion_id="+<?php echo $discussion_id; ?>+
                "&post_comment_id="+<?php echo $post_comment_id; ?>+
                "&game_id=" +<?php echo $game_id; ?> +"'" + '" class="btn btn-primary" type="button" style="margin-top:-18px;">Reply</button></span></a>';
            }

            change.innerHTML = change.innerHTML + button;


            var data = {comment_id: comment_id, percentage: percentage, date: date, user_name: user_name, text: text};

            $.ajax({
                method: "POST",
                url: "post_get.php",
                dataType: "json",
                data: data,
                async: false,
                cache: false,
                success: function (result) {
                    if (result.count > 0) {
                        for (var i = 0; i < result.count; i++) {
                            getReplies(result.replies[i].comment_id, result.replies[i].percentage, result.replies[i].date, result.replies[i].user_name, result.replies[i].text, result.replies[i].user_id);
                        }

                    }

                },
                error: function (xhr, ajaxOptions, thrownError) {
                    //alert(xhr.status);
                    //alert(thrownError);
                }
            });


        }
    </script>

    <?php
    $query = "select comment_id, date, text, user_name,users.user_id from posts natural join comments natural join users where comment_id='$post_comment_id'";
    $result = mysqli_query($database, $query);
    $row = $result->fetch_assoc();

    echo "<div id='result'></div>";
    echo "<script>getReplies(" . $row['comment_id'] . "," . 0 . "," . '"' . $row['date'] . '"' . "," . '"' . $row['user_name'] . '"' . "," . '"' . $row['text'] . '"' . "," . '"' . $row['user_id'] . '"' . ");</script>";

    ?>
</div>

</body>

</html>