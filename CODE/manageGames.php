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
    <title>Manage Games</title>
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
                    <li class="nav-item" role="presentation"><a class="nav-link" href="" style="margin-top:10px;color:rgba(255,255,255,0.3);">Friends</a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link" href="" style="margin-right:0px;margin-top:10px;color:rgba(255,255,255,0.3);">Library</a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link" href="" style="margin-top:10px;color:rgba(255,255,255,0.3);">Activity</a></li>
                </ul>
                <div class="dropdown ml-auto">
                <button class="btn btn-warning dropdown-toggle disabled" style="margin-right: 15px" disabled="disabled" type="button" data-toggle="dropdown">Funds: <?php echo $user["balance"] ?> $ <span class="caret"></span></button>
                <ul class="dropdown-menu">
                    <li><a href="#" >Add Funds</a></li>
                </ul>
                </div>
                <?php
                if($user["isGameDev"])
                {?>
                	<button class="btn btn-success" disabled="disabled">Manage Games</button> 
                <?php };
                ?>
                <button class="btn btn-primary" disabled="disabled">Messages</button> 
		<button class="btn btn-primary" disabled="disabled" style="background-color:rgb(255,15,0);">Log Out</button> 
	 </div>
    </nav>
    <div style="min-width:100%;">
    	<div class="container" >
    		<div class="jumbotron jumbotron-fluid" >
    			<div class="container">
    				<h1 class="display-4" style="text-align: center">Manage Games</h1>
    				<p class="lead" style="text-align: center">Manage your games with good mood.</p>
    				<div class="row">
                <div class="col">
                    <div class="row">
                        <div class="col-lg-3">
                            <h4 class="text-center">Select:</h4>
                        </div>
                        <div class="col-lg-4">
                        	<form method="POST">
                        		<select name="thename" class="form-control-sm" required="" autofocus="" onchange="this.form.submit()" >
                        				<?php
                        				if (isset($_POST["thename"]))
                        				{?>
                        					<option disabled selected value> <?php echo $_POST["thename"]; ?> </option>
                        				<?php }
                        				else
                        				{?>
                        					<option disabled selected value> -- select a game -- </option>
                        				<?php }; ?>
    
                        				<?php

                        				$sql_query = "select * from games where publisher_id = '$id'";
                        				$result = mysqli_query($database, $sql_query)or die('Error In getting lib names');


                        				while($row = $result->fetch_assoc())
                        					{?>
                        						<option value="<?php echo $row["game_name"]; ?>"><?php echo $row["game_name"]; ?></option>
                        						<?php }; ?>
                        		</select>
                        		<noscript><input type="submit" value="Submit"></noscript>
                        	</form>
                        </div>
                    </div>
                    <?php
                    if (isset($_POST["thename"]))
                    {
                    	$thename = $_POST["thename"];
                    }
                    ?>
                    <div class="row">
                    	<div class="col-lg-3">
                    		<h4 class="text-center">Name:</h4>
                    	</div>
                    	<div class="col-lg-4">
                    		<form action="manageGames.php?oldname=<?php echo $thename; ?>" method="POST">
                    			<input type="text" name="lib_name" class="form-control-sm" value="<?php echo $thename; ?>" id="fee">
                    			<input type="submit" name="ok_btn" class="btn btn-danger btn-sm" value="OK">
                    		</form>

                    		<?php
                    		if (isset($_POST["ok_btn"]))
                    		{
                                if(isset($_GET["oldname"]) && trim($_GET["oldname"]) == '' )
                                {
                                    echo "<script> alert('Select a game first'); </script>";
                                }
                                else
                                {
                                    $lib_name = $_POST["lib_name"];
                                    if(!isset($lib_name) || trim($lib_name) == '' )
                                    {
                                        echo "<script> alert('Game name can not be empty'); </script>";
                                    }
                                    else if($lib_name != $_GET["oldname"])
                                    {  
                                        $oldname = $_GET["oldname"];                                
                                        $sql_query = "UPDATE games
                                        SET game_name = '$lib_name'
                                        WHERE game_name = '$oldname'; ";
                                        $result = mysqli_query($database, $sql_query)or die('Error in changing game names');
                                        echo "<script>window.location.replace('manageGames.php');</script>";

                                    }
                                }
                            }

                        	 ?>

                        </div>
                    </div>
                    <div class="row">
                    	<div class="col-lg-3">
                    		<h4 class="text-center">Discount:</h4>
                    	</div>
                    	<div class="col-lg-4">
                    		<form method="POST">
                    			<input type="text" name="discount_amount" class="form-control-sm" placeholder="for %10 write 10" id="fee">
                    			<input type="submit" name="disco_btn" class="btn btn-danger btn-sm" value="OK">
                    			<input type="hidden" name="ss" value=<?php echo $_POST["thename"];?> >
                    		</form>

                    		<?php
                    		if (isset($_POST["disco_btn"]))
                    		{
                    			if(isset($_POST["ss"]) && trim($_POST["ss"]) != '' )
                    			{
                    				$disco = $_POST["discount_amount"];
                    				$game_name = $_POST["ss"];

                    				if(trim($disco) == '')
                    				{

                    					$sql_query = "select game_id from games where game_name = '$game_name' ";
                                        $result = mysqli_query($database, $sql_query)or die('Error in getting game id');
                                        $result = $result->fetch_assoc();
                                        $game_id = $result["game_id"];

                                        $sql_query = "call restore_price('$game_id');";
                                        $result = mysqli_query($database, $sql_query)or die('Error in deleting discoun');


                    					echo "<script>alert('delete discont')</script>";
                    					#echo "<script>window.location.replace('manageGames.php');</script>";
                    					
                    				}
                    				else
                    				{
                    					if(is_numeric($disco))
                    					{
                    						echo "<script>alert('".$game_name."')</script>";

                    						$disco = "0" . "." . $disco;
                    						$sql_query = "select game_id from games where game_name = '$game_name' ";
                    						$result = mysqli_query($database, $sql_query)or die('Error in getting game id');
                    						$result = $result->fetch_assoc();
                    						$game_id = $result["game_id"];

                    						echo "<script>alert('".$disco.",".$game_id."')</script>";

                    						$sql_query = "call activate_discount('$game_id','$disco');";
                    						$result = mysqli_query($database, $sql_query)or die(mysql_error());


                    						#echo "<script>window.location.replace('manageGames.php');</script>";

                    					}
                    					else
                    					{
                    						echo "<script>alert('Write numeric value')</script>";
                    					}
                    				}
                    			}
                    			else
                    			{
                    				echo "<script>alert('Select a game first')</script>";
                    			}
                    			
                            }

                        	 ?>

                        </div>
                    </div>
                    		<div class="row">
                                	<a href="publish_game.php" class="btn btn-success btn-lg" style="margin: 0 auto; float: none; margin-top: 40px; width: 400px !important;" role="button">Publish a game</a>
                            </div>

                            <div class="row">
                                	<a href="store.php" class="btn btn-primary btn-lg" style="margin: 0 auto; float: none; margin-top:5px; width: 400px !important;" role="button">Done</a>
                            </div>
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
</body>

</html>