<?php
session_start();
error_reporting(1);
include("config.php");

$id = $_SESSION['id'];
$username = mysqli_real_escape_string($database, $_POST["search"]);
#echo "$username";


?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>add_friend</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans">
    <link rel="stylesheet" href="assets/css/16-scrollbar-styles.css">
    <link rel="stylesheet" href="assets/css/16-scrollbar-styles1.css">
    <link rel="stylesheet" href="assets/css/megamenu-dark.css">
    <link rel="stylesheet" href="assets/css/megamenu-dark1.css">
    <link rel="stylesheet" href="assets/css/Pretty-Search-Form.css">
    <link rel="stylesheet" href="assets/css/Sidebar-Menu.css">
    <link rel="stylesheet" href="assets/css/Sidebar-Menu1.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body style="background-color:#5b77cc;">
<nav class="navbar navbar-light navbar-expand-md">
    <div class="container-fluid"><a class="navbar-brand" href="friends.php"
                                    style="font-size:34px;color:rgba(255,255,255,0.9);"><strong>Friends</strong></a>
        <button class="navbar-toggler" data-toggle="collapse" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span
                    class="navbar-toggler-icon"></span></button>
        <div
                class="collapse navbar-collapse" id="navcol-1">
            <ul class="nav navbar-nav">
                <li class="nav-item" role="presentation"><a class="nav-link active" href="#"
                                                            style="margin-top:10px;color:rgba(255,255,255,0.9);">Store</a>
                </li>
                <li class="nav-item" role="presentation"><a class="nav-link" href="library.php"
                                                            style="margin-right:0px;margin-top:10px;color:rgba(255,255,255,0.9);">Library</a>
                </li>
                <li class="nav-item" role="presentation"><a class="nav-link" href="activity.php"
                                                            style="margin-top:10px;color:rgba(255,255,255,0.9);">Activity</a>
                </li>
            </ul>
            <button class="btn btn-success ml-auto" type="button" style="background-color:rgb(0,123,255);">Messages
            </button>
						<form action = 'logout.php'>
				<input class="btn btn-primary" type='submit' style="background-color:rgb(255,15,0);" value="Log Out">
			</form>
        </div>
    </div>
</nav>
<form method="POST">
    <div class="input-group">
        <div class="input-group-prepend"></div>
        <input class="form-control" type="text" name="search" placeholder="Enter a username">
        <div class="input-group-append"><input type="submit" name="searchButton" class="btn btn-light" value="search"
                                               style="background-color:rgb(180,121,215)" onclick="this.form.submit()">
        </div>
    </div>
</form>
<div style="min-width:100%;"></div>
<ul class="list-group">
    <?php
    if (isset($_POST["searchButton"])) {
        $query = "SELECT * FROM users WHERE user_name LIKE '%$username%' AND user_id != $id AND user_name NOT IN (SELECT  user_name
								FROM friends f, users u
								WHERE user_id2=$id AND user_id1=u.user_id
								UNION
								SELECT  user_name 
								FROM friends f, users u
								WHERE user_id1=$id AND user_id2=u.user_id);";

        $users = mysqli_query($database, $query);
        #echo "inside";
        if (mysqli_num_rows($users) != 0) {
            #echo "ifin icinde";
            $counter = 0;
            while ($read = mysqli_fetch_assoc($users)) {
                //echo '<li class="list-group-item float-left"><span>' . $read["user_name"] . ' </li>';
                //echo '<li id="'.$counter.'" class="list-group-item float-left">';
                ?>
                <li id="<?php echo $counter; ?>" class="list-group-item float-left">
                    <span><?php echo $read["user_name"]; ?>
                        <button class="close" onclick="send_friend_request('<?php echo $id; ?>',
                                '<?php echo $read["user_id"]?>',
                                '<?php echo $read["user_name"]?>');">
                                <span aria-hidden="true">Add Friend</span>
                            </button>
                    </span>
                    <?php
                    ?>
                </li>

                <?php
                #$sender_id = $_SESSION["id"];
                #$receiver_id = $read["user_id"];
                #$query = "INSERT INTO friend_request(user_name, reciever_id) VALUES ('$sender_id', '$receiver_id')";
                #$friend_request = mysqli_query($database, $query);
                $counter = $counter + 1;
            }
        } else {
            ?>
    <li id="<?php echo $counter; ?>" class="list-group-item float-left">
            <span>No such non-friend user exists.</span>
    </li>
                <?php
        }
    }
    ?>
</ul>
<script src="assets/js/jquery.min.js"></script>
<script src="assets/bootstrap/js/bootstrap.min.js"></script>
<script src="assets/js/megamenu-dark.js"></script>
<script src="assets/js/Sidebar-Menu.js"></script>
<script>
    function send_friend_request(sender_id,receiver_id,receiver_name){

        if (window.XMLHttpRequest)
        {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else
        {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.open("GET","send_friend_request.php?sender_id="+sender_id+"&receiver_id="+receiver_id+"&receiver_name="+receiver_name,true);
        xmlhttp.send();

        alert("Friend request sent to " + receiver_name );

    }
</script>
</body>

</html>