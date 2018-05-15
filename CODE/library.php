<?php
session_start();
include("config.php");

if (!isset($_SESSION['id']) || (trim($_SESSION['id']) == '') || $_SESSION["login"] != "true") {
    header("location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>library</title>
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
        <div class="container-fluid"><a class="navbar-brand" href="#" style="font-size:34px;color:rgba(255,255,255,0.9);">Library</a><button class="navbar-toggler" data-toggle="collapse" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
            <div
                class="collapse navbar-collapse" id="navcol-1">
                <ul class="nav navbar-nav">
                    <li class="nav-item" role="presentation"><a class="nav-link" href="friends.php" style="margin-top:10px;color:rgba(255,255,255,0.9);">Friends</a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link" href="store.php" style="margin-right:0px;margin-top:10px;color:rgba(255,255,255,0.9);">Store</a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link" href="activity.php" style="margin-top:10px;color:rgba(255,255,255,0.9);">Activity</a></li>
                </ul>
                <a href="manageLibrary.php" class="btn btn-success ml-auto" role="button">Manage Library</a>
                <a href="messages.php" class="btn btn-primary" role="button">Messages</a>
		<form action = 'logout.php'>
				<input class="btn btn-primary" type='submit' style="background-color:rgb(255,15,0);" value="Log Out">
			</form>
	 </div>
    </nav>
    <div style="min-width:100%;">
        <div class="container" style="margin:0;">
            <div class="row" style="height:500px;">
                <div class="col-md-4" style="max-width:12%;background-color:#324fa7;">
                    <ul class="list-unstyled" style="width:130%;">

                    	<?php

                    	$id = $_SESSION['id'];

                    	$sql_query = "select library_name from library where user_id = '$id' group by library_name";
                    	$result = mysqli_query($database, $sql_query)or die('Error In getting lib names');

                		while($row = $result->fetch_assoc())
                		{
                			echo "<li><a href=\"library.php?sel_lib=". $row['library_name'] ."\" style=\"color:rgb(255,255,255);font-size:18px;\">" . $row['library_name'] . "</a></li>";
                		}

                    	?>



                    </ul>
                </div>
                <div class="col-md-8 col-lg-9 offset-lg-0" style="float:none;margin:0 auto;">

                
                		<?php
                		if(isset($_GET["sel_lib"]))
                		{
                			$sel_lib = $_GET["sel_lib"];
                			$sql_query = "	SELECT game_name, img_location, description, game_id
											FROM games_in_library
											WHERE user_id = '$id' AND library_name = '$sel_lib'
											ORDER BY game_name";
                    		$result = mysqli_query($database, $sql_query)or die('Error In getting lib games');

                    		if($result->num_rows > 0)
                    		{?>
                    			<div class="carousel slide" data-ride="carousel" id="carousel-1">
                    				<div class="carousel-inner" role="listbox" id="ina">

                    					<?php
                    						$row = $result->fetch_assoc();
                    					 ?>

                    					<div class="carousel-item active"><img class="w-100 d-block" src="<?php echo $row['img_location']; ?>" alt="Slide Image">
                    						<div class="carousel-caption">
                    							<h3><strong> <?php echo $row['game_name']; ?> </strong></h3>
                    							<p><?php echo $row['description']; ?></p>
                                                <a href="discussions.php?game_id=<?php echo $row['game_id']; ?>" class="btn btn-info btn-xs" role="button">Discussions</a>
                                                <a href="play.php?game_id=<?php echo $row['game_id']; ?>" class="btn btn-info btn-xs" role="button">Play</a>
                    						</div>
                    					</div>

                    					<?php
                    					while($row = $result->fetch_assoc())
                						{?>
                							<div class="carousel-item" id="actv"><img class="w-100 d-block" src="<?php echo $row['img_location']; ?>" alt="Slide Image" id="img">
                								<div class="carousel-caption">
                									<h3><strong><?php echo $row['game_name']; ?></strong></h3>
                									<p><?php echo $row['description']; ?></p>
                                                    <a href="discussions.php?game_id=<?php echo $row['game_id']; ?>" class="btn btn-info btn-xs" role="button">Discussions</a>
                                                <a href="play.php?game_id=<?php echo $row['game_id']; ?>" class="btn btn-info btn-xs" role="button">Play</a>
                								</div>  
                							</div>
                                            

                						<?php }; ?>

                    				</div>

                    				<div><a class="carousel-control-prev" href="#carousel-1" role="button" data-slide="prev" style="height:70%;"><span class="carousel-control-prev-icon"></span><span class="sr-only">Previous</span></a><a class="carousel-control-next" href="#carousel-1"
                    					role="button" data-slide="next" style="height:70%;"><span class="carousel-control-next-icon"></span><span class="sr-only">Next</span></a>
                    				</div>

                    					<ol class="carousel-indicators" style="height:130px;">
                    						<li data-target="#carousel-1" data-slide-to="0" class="active"></li>

                    				<?php 
                    					$result = mysqli_query($database, $sql_query)or die('Error In getting lib games');
                    					$row = $result->fetch_assoc();
                    					$count = 1;
                    					while($row = $result->fetch_assoc())
                						{?>
                							<li data-target="#carousel-1" data-slide-to="<?php echo $count;?>" class="active"></li>
                						<?php $count = $count +1; }; ?>
                					
                					</ol>
                				</div>

                    		
                    		<?php }; ?>

                		<?php }; ?>
                	
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