<?php
session_start();
include("config.php");

if (!isset($_SESSION['id']) || (trim($_SESSION['id']) == '') || $_SESSION["login"] != "true") {
    header("location: index.php");
    exit();
}
$id = $_SESSION['id'];
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans">
    <link rel="stylesheet" href="assets/css/megamenu-dark.css">
    <link rel="stylesheet" href="assets/css/megamenu-dark1.css">
    <link rel="stylesheet" href="assets/css/Sidebar-Menu.css">
    <link rel="stylesheet" href="assets/css/Sidebar-Menu1.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/messages_css.css">

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
            <a href="messages.php" class="btn btn-primary  ml-auto" role="button">Messages</a>
            <form action='logout.php'>
                <input class="btn btn-primary" type='submit' style="background-color:rgb(255,15,0);" value="Log Out">
            </form>
        </div>
    </div>
</nav>
<div style="min-width:100%;">
    <div class="container" style="margin:0;">
        <div>
            <ul class="nav message-nav-tabs" id="tabs" style="color: white;">
                <li class="message-nav-item"><a id="tabs" class="message-nav-link active" role="tab" data-toggle="tab"
                                                href="#tab-1">Messages </a>
                </li>
                <li class="message-nav-item"><a id="tabs" class="message-nav-link" role="tab" data-toggle="tab"
                                                href="#tab-2">Game Invitations</a>
                </li>
                <li class="message-nav-item"><a id="tabs" class="message-nav-link" role="tab" data-toggle="tab"
                                                href="#tab-3">Friend Requests</a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" role="tabpanel" id="tab-1">
                    <div class="col-md-4 offset-lg-0"
                         style="margin:0 auto;max-width:100%;background-color:#324fa7;min-width:45%; height:<?php echo $size_req; ?>px; float:right;">
                        <h1 class="text-center" style="color:rgb(255,255,255);margin:0 auto;float:none;"><strong>Messages</strong>
                        </h1>
                        <div id="division">
                            <?php

                            $query = "SELECT user_id, user_name, text, date, message_id
                        FROM messages m, users u
                        WHERE u.user_id = m.sender_id AND m.reciever_id = $id;";

                            $result = mysqli_query($database, $query) or die(mysqli_error($database));
                            $counter_l = 1;
                            while ($messages = $result->fetch_assoc()) {

                                $message_string = $messages[text];
                                $message_id = $messages[message_id];
                                $sender_string = "Sended by: $messages[user_name] on $messages[date]";
                                ?>

                                <div class="row">
                                    <div class="column" style="max-width: 40%">
                                        <div class="row" style="margin: 10px">
                                            <div class="column" style="width: 50%;padding: 2px">
                                                <form method="post" action="message.php">
                                                    <input name="messageFriendID" type="hidden"
                                                           value="<?php echo $messages[user_id]; ?>">
                                                    <button class="btn btn-primary ml-auto btn-lg" type="submit"
                                                            style="margin: 0px;padding: 4px 33px 4px 4px;height:100%;width:100%;">
                                                        Reply
                                                    </button>
                                                </form>
                                            </div>
                                            <div class="column" style="width: 50%;padding: 2px">
                                                <button class="btn btn-danger ml-auto btn-lg"
                                                        style="margin: 0px;padding: 4px 33px 4px 4px;height:100%;width:100%;"
                                                        onclick="delete_message('<?php echo $message_id; ?>');">
                                                    Delete
                                                </button>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col" style="max-width:70%;">
                                        <blockquote class="blockquote">
                                            <p class="text-white mb-0"
                                               style="padding-top:20px;font-size:15px;"><?php echo $message_string; ?></p>
                                            <footer class="blockquote-footer float-right"><?php echo $sender_string; ?></footer>
                                        </blockquote>
                                    </div>
                                </div>

                                <?php
                                $counter_l = $counter_l + 1;
                            }
                            $size_l = 130 * $counter_l;
                            ?>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" role="tabpanel" id="tab-2">
                    <div class="col-md-4 offset-lg-0"
                         style="margin:0 auto;max-width:100%;background-color:#324fa7;min-width:45%; height:<?php echo $size_r; ?>px;">
                        <h1 class="text-center" style="color:rgb(255,255,255);margin:0 auto;float:none;"><strong>Game
                                Invitations</strong></h1>
                        <div id="division">
                            <?php
                            $query2 = "SELECT user_id, user_name, game_name, date, g.game_id
                    FROM send_invitation i, users u, games g
                    WHERE u.user_id = i.sender_id AND i.game_id = g.game_id AND i.reciever_id = $id;";
                            $result2 = mysqli_query($database, $query2) or die(mysqli_error($database));
                            $counter_r = 1;
                            while ($game_invitation = $result2->fetch_assoc()) {
                                $invitation_string = "$game_invitation[user_name] wants you to join: $game_invitation[game_name]";
                                $i_sender_string = "Sent on $game_invitation[date]";
                                $sender_id = $game_invitation[user_id];
                                $game_id = $game_invitation[game_id];


                                ?>
                                <div class="row">
                                    <div class="column" style="max-width: 40%">
                                        <div class="row" style="margin: 10px">
                                            <div class="column" style="width: 50%;padding: 2px">
                                                <a class="btn btn-primary  ml-auto btn-lg"
                                                   href="play.php?game_id=<?php echo $game_id; ?>"
                                                   onclick="join_game('<?php echo $sender_id; ?>','<?php echo $game_id; ?>');"
                                                   type="button">Join
                                                </a>
                                            </div>
                                            <div class="column" style="width: 50%;padding: 2px">
                                                <a class="btn btn-primary  ml-auto btn-lg"
                                                   onclick="decline_game_invitation('<?php echo $sender_id; ?>','<?php echo $game_id; ?>');"
                                                   type="button">Decline
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="column" style="max-width: 50%;min-width: 50%;margin: 15px">
                                        <blockquote class="blockquote">
                                            <p class="text-white mb-0"
                                               style="padding-top:20px;font-size:15px;"><?php echo $invitation_string; ?></p>
                                            <footer class="blockquote-footer float-right"><?php echo $i_sender_string; ?>
                                            </footer>
                                        </blockquote>
                                    </div>
                                </div>
                                <?php
                                $counter_r = $counter_r + 1;
                            }
                            $size_r = 130 * $counter_r;
                            ?>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" role="tabpanel" id="tab-3">
                    <div class="col-md-4 offset-lg-0"
                         style="margin:0 auto;max-width:100%;background-color:#324fa7;min-width:45%; height:<?php echo $size_req; ?>px; float:right;">
                        <h1 class="text-center" style="color:rgb(255,255,255);margin:0 auto;float:none;"><strong>Friend
                                Requests</strong></h1>
                        <div id="division">
                            <?php
                            $query3 = "SELECT user_name, sender_id, reciever_id, date
                    FROM friend_request f, users u
                    WHERE u.user_id = f.sender_id AND f.reciever_id = $id;";
                            $result3 = mysqli_query($database, $query3) or die(mysqli_error($database));
                            $counter_req = 1;
                            while ($friend_req = $result3->fetch_assoc()) {
                                $req_string = "$friend_req[user_name] wants to add you as friend";
                                $req_date_string = "Sent on $friend_req[date]";
                                ?>

                                <div class="row">
                                    <div class="column" style="max-width: 40%">
                                        <div class="row" style="margin: 10px">
                                            <div class="column" style="width: 50%;padding: 2px">
                                                <button class="btn btn-success  ml-auto btn-lg"

                                                        onclick="accept('<?php echo $friend_req["sender_id"]; ?>', '<?php echo $friend_req["user_name"] ?>');">
                                                    <span aria-hidden="true">Accept</span></button>
                                            </div>
                                            <div class="column" style="width: 50%;padding: 2px">
                                                <button class="btn btn-danger btn-lg"

                                                        onclick="reject('<?php echo $friend_req["sender_id"]; ?>', '<?php echo $friend_req["user_name"] ?>');">
                                                    <span aria-hidden="true">Reject</span></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="column" style="max-width: 50%;min-width: 50%;margin: 15px">
                                        <blockquote class="blockquote">
                                            <p class="text-white mb-0"
                                               style="padding-top:20px;font-size:15px;"><?php echo "$req_string" ?></p>

                                            <footer class="blockquote-footer float-right"><?php echo "$req_date_string" ?></footer>
                                        </blockquote>
                                    </div>
                                </div>

                                <?php
                                $counter_req = $counter_req + 1;
                            }
                            $size_req = 130 * $counter_req;
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/megamenu-dark.js"></script>
    <script src="assets/js/Sidebar-Menu.js"></script>
    <script>
        function delete_message(message_id) {
            if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else {
                // code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.open("GET", "delete_message.php?message_id=" + message_id, true);
            xmlhttp.send();

            alert("Message deleted");
            location.reload();
        }
    </script>
    <script>
        function join_game(sender_id, game_id) {
            if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else {
                // code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }

            xmlhttp.open("GET", "delete_game_invitation.php?sender_id=" + sender_id + "&game_id=" + game_id, true);
            xmlhttp.send();

            alert("Joining game...");
            return true;
        }

        function decline_game_invitation(sender_id, game_id) {
            if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else {
                // code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }

            xmlhttp.open("GET", "delete_game_invitation.php?sender_id=" + sender_id + "&game_id=" + game_id, true);
            xmlhttp.send();

            alert("Declined");

            location.reload();
        }
    </script>
    <script>
        function accept(sender_id, user_name) {
            if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else {
                // code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }

            xmlhttp.open("GET", "accept_friend_request.php?sender_id=" + sender_id, true);
            xmlhttp.send();

            alert("The friend request of " + user_name + " is accepted");
            location.reload();
        }
    </script>
    <script>

        function reject(sender_id, user_name) {
            if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else {
                // code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }

            xmlhttp.open("GET", "reject_friend_request.php?sender_id=" + sender_id, true);
            xmlhttp.send();

            alert("The friend request of " + user_name + " is rejected");
            location.reload();
        }
    </script>

</body>

</html>