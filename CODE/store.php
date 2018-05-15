<?php
session_start();
include("config.php");

if (!isset($_SESSION['id']) || (trim($_SESSION['id']) == '') || $_SESSION["login"] != "true") {
    header("location: index.php");
    exit();
}

$id = $_SESSION['id'];
$sql_query = "select balance, isGameDev from users where user_id = '$id' ";
$result = mysqli_query($database, $sql_query)or die('Error In getting lib names');
$user = $result->fetch_assoc();


?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Store</title>
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
        <div class="container-fluid"><a class="navbar-brand" href="#" style="font-size:34px;color:rgba(255,255,255,0.9);">Store</a><button class="navbar-toggler" data-toggle="collapse" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
            <div
                class="collapse navbar-collapse" id="navcol-1">
                <ul class="nav navbar-nav">
                    <li class="nav-item" role="presentation"><a class="nav-link" href="friends.php" style="margin-top:10px;color:rgba(255,255,255,0.9);">Friends</a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link" href="library.php" style="margin-right:0px;margin-top:10px;color:rgba(255,255,255,0.9);">Library</a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link" href="activity.php" style="margin-top:10px;color:rgba(255,255,255,0.9);">Activity</a></li>
                </ul>
                <div class="dropdown ml-auto">
                <button class="btn btn-warning dropdown-toggle" style="margin-right: 15px" type="button" data-toggle="dropdown">Funds: <?php echo $user["balance"] ?> $ <span class="caret"></span></button>
                <ul class="dropdown-menu">
                    <li><a href="#" onclick="addFunds(<?php echo $id ?>);">Add Funds</a></li>
                </ul>
                </div>
                <?php
                if($user["isGameDev"])
                {?>
                    <a href="manageGames.php" class="btn btn-success" role="button">Manage Games</a>
                <?php };
                ?>
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

                    	$sql_query = "select genre_id, genre_name from genres;";
                    	$result = mysqli_query($database, $sql_query)or die('Error In getting lib names');

                		while($row = $result->fetch_assoc())
                		{
                			echo "<li><a href=\"store.php?sel_gen=". $row['genre_id'] ."\" style=\"color:rgb(255,255,255);font-size:18px;\">" . $row['genre_name'] . "</a></li>";
                		}

                    	?>

                    </ul>
                    <div id="left_menu_div" style="height:1px; border: 1px solid rgb(255,255,255); "></div>
                    <ul class="list-unstyled">
                        <li><a href="store.php?sel_gen=-1" style="color:rgb(255,255,255);font-size:18px;">Trending</a></li>
                        <li><a href="store.php?sel_gen=-2" style="color:rgb(255,255,255);font-size:18px;">New</a></li>
                        <li></li>
                    </ul>
                </div>
                <div class="col-md-8 col-lg-9 offset-lg-0" style="float:none;margin:0 auto;">

                
                		<?php
                		if(isset($_GET["sel_gen"]))
                		{
                			$sel_gen = $_GET["sel_gen"];
							if($sel_gen == -1){
								$sql_query = "	SELECT *
                                            FROM trending; ";
							}
							else if($sel_gen == -2){
								$sql_query = "	SELECT g.game_name, g.game_id, g.img_location, g.description, g.price, g.discount_amount
												FROM games g
												ORDER BY release_date DESC
												LIMIT 10; ";
							}
							else{
								
                			$sql_query = "	SELECT g.game_name, g.game_id, g.img_location, g.description,g.price,g.discount_amount
                                            FROM games g, game_genres gg
                                            WHERE gg.genre_id = '$sel_gen' AND gg.game_id = g.game_id; ";
							}
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
                                                <button class="btn btn-info btn-xs" role="button" onclick="before_buy('<?php echo $row[game_id]; ?>',
                                                                                                                     '<?php echo $row[game_name]; ?>',
                                                                                                                     '<?php echo $id; ?>'

                                                                                                                        );" >Buy</button>
                                                <button class= "btn btn-success btn-xs" role="button"><?php echo $row["price"]; ?>$</button>
                                                <?php
                                                if($row["discount_amount"] != 0)
                                                {?>
                                                    <button class= "btn btn-danger btn-xs" role="button">Discount: <?php echo $row["discount_amount"]*100; ?>%</button>
                                                <?php }; ?>
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
                                                    <button class="btn btn-info btn-xs" role="button" onclick="before_buy('<?php echo $row[game_id]; ?>',
                                                                                                                     '<?php echo $row[game_name]; ?>',
                                                                                                                     '<?php echo $id; ?>'

                                                                                                                        );" >Buy</button>
                                                    <button class= "btn btn-success btn-xs" role="button"><?php echo $row["price"]; ?>$</button>
                                                <?php
                                                if($row["discount_amount"] != 0)
                                                {?>
                                                    <button class= "btn btn-danger btn-xs" role="button">Discount: <?php echo $row["discount_amount"]*100; ?>%</button>
                                                <?php }; ?>
                								</div>  
                							</div>         

                						<?php }; ?>
										
										

                    				</div>
                                    <script>
                                        function before_buy(game_id,game_name,id) 
                                        {
                                          $.ajax({
                                              method: "POST",
                                              url: "buy.php",
                                              data: { d_game_id: game_id, bgn: "chk", id: "<?php echo $id; ?>"},                                        
                                              success: function(result)
                                              {
                                                if(result==1)
                                                {
                                                    buy(game_id,game_name,id);
                                                }
                                                else
                                                {
                                                    alert("You dont have enough money");
                                                }
                                               }
                                        });
                                        }
                                    </script>

                                    <script>
                                        function isNumeric(n) {
                                          return !isNaN(parseFloat(n)) && isFinite(n);
                                      }

                                        function addFunds(user_id)
                                        {
                                            var money = prompt("Please enter amount","");

                                            if (money != null && money != "")
                                            {
                                                if(isNumeric(money))
                                                {
                                                  $.ajax({
                                                      method: "POST",
                                                      url: "addFunds.php",
                                                      data: { id: user_id, money: money},                                        
                                                      success: function(result)
                                                      {
                                                        if(result)
                                                        {
                                                            alert(money +"$ has been added into your account");
                                                            location.reload();
                                                        }
                                                        else
                                                        {
                                                            alert(result);
                                                        }
                                                    }
                                                });
                                                }
                                                else
                                                {
                                                    alert("Write numeric value");
                                                }
                                            }
                                        }
                                    </script>

                                    <script>
                                        function buy(game_id,game_name,id) 
                                        {                     
                                            if (confirm("Are you sure you want to buy " + game_name))
                                            {
                                                $.ajax({
                                                  method: "POST",
                                                  url: "buy.php",
                                                  data: { d_game_id: game_id, bgn: "by", id: "<?php echo $id; ?>"},                                        
                                                  success: function(result)
                                                  {
                                                    if(result == 1)
                                                    {
                                                        alert(game_name + " has been bought");
                                                        location.reload();
                                                    }
                                                    else if(result == 2)
                                                    {
                                                        alert("You already have " + game_name);
                                                    }
                                                }
                                            });

                                            }
                                        }
                                    </script>
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