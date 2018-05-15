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
    <title>manage lib</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans">
    <link rel="stylesheet" href="assets/css/megamenu-dark.css">
    <link rel="stylesheet" href="assets/css/megamenu-dark1.css">
    <link rel="stylesheet" href="assets/css/Sidebar-Menu.css">
    <link rel="stylesheet" href="assets/css/Sidebar-Menu1.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body style="background-color:#5b77cc;">
    <nav class="navbar navbar-light navbar-expand-md">
        <div class="container-fluid"><a class="navbar-brand" href="#" style="font-size:34px;color:rgba(255,255,255,0.9);">Library</a><button class="navbar-toggler" data-toggle="collapse" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
            <div
                class="collapse navbar-collapse" id="navcol-1">
                <ul class="nav navbar-nav">
                    <li class="nav-item" role="presentation"><a class="nav-link active" href="#" style="margin-top:10px;color:rgba(255,255,255,0.3);">Friends</a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link" href="#" style="margin-right:0px;margin-top:10px;color:rgba(255,255,255,0.3);">Store</a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link" href="#" style="margin-top:10px;color:rgba(255,255,255,0.3);">Activity</a></li>
                </ul>
                <button class="btn btn-success ml-auto" type="button" disabled="disabled">Manage Library</button><button class="btn btn-primary" type="button" disabled="disabled">Messages</button>
            </div>
            
                <button class="btn btn-primary" disabled="disabled" style="background-color:rgb(255,15,0);">Log Out</button> 
            
        </div>
    </nav>
    <div style="min-width:100%;">
        <div class="container">
            <div class="row" style="height:500px;">
                <div class="col">
                    <h3 class="text-center text-success"><strong><em>Manage Library</em></strong></h3>
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
                        					<option disabled selected value> -- select a library -- </option>
                        				<?php }; ?>
    
                        				<?php
                        				$id = $_SESSION['id'];

                        				$sql_query = "select library_name from library where user_id = '$id' group by library_name";
                        				$result = mysqli_query($database, $sql_query)or die('Error In getting lib names');

                        				$count = 0;
                        				while($row = $result->fetch_assoc())
                        					{?>
                        						<option value="<?php echo $row["library_name"]; ?>"><?php echo $row["library_name"]; ?></option>
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
                    		<form action="manageLibrary.php?oldname=<?php echo $thename; ?>" method="POST">
                    			<input type="text" name="lib_name" class="form-control-sm" value="<?php echo $thename; ?>" id="fee">
                    			<input type="submit" name="ok_btn" class="btn btn-danger btn-sm" value="OK">
                    		</form>

                    		<?php
                    		if (isset($_POST["ok_btn"]))
                    		{
                                if(isset($_GET["oldname"]) && trim($_GET["oldname"]) == '' )
                                {
                                    echo "<script> alert('Select a library first'); </script>";
                                }
                                else
                                {
                                    $lib_name = $_POST["lib_name"];
                                    if(!isset($lib_name) || trim($lib_name) == '' )
                                    {
                                        echo "<script> alert('Library name can not be empty'); </script>";
                                    }
                                    else if ($_GET["oldname"] == "My Games")
                                    {
                                        echo "<script> alert('You can not change your default Library'); </script>";

                                    }
                                    else if($lib_name != $_GET["oldname"])
                                    {  
                                        $oldname = $_GET["oldname"];                                
                                        $sql_query = "UPDATE library
                                        SET library_name = '$lib_name'
                                        WHERE library_name = '$oldname'; ";
                                        $result = mysqli_query($database, $sql_query)or die('Error in changing lib names');
                                        echo "<script>window.location.replace('manageLibrary.php');</script>";

                                    }
                                }
                            }

                        	 ?>

                        </div>
                    </div>
                                           
                    <div class="row">
                    	<div class="col-lg-3">
                    		<h4 class="text-center">Add a new library:</h4>
                    	</div>
                    	<div class="col-lg-4">
                    		<form method="POST">
                    			<input type="text" name="new_lib_name" class="form-control-sm">
                    			<input type="submit" name="lib_btn" class="btn btn-danger btn-sm" value="OK">
                    		</form>

                    		<?php
                    		if (isset($_POST["lib_btn"]))
                    		{
                    			$lib_name = $_POST["new_lib_name"];
                    			if(!isset($lib_name) || trim($lib_name) == '' )
                    			{
                    				echo "<script> alert('Library name can not be empty'); </script>";
                    			}
                    			else if ($lib_name == "My Games")
                    			{
                    				echo "<script> alert('You can not add library with default library '); </script>";
                    				
                    			}
                    			else 
                    			{  
                    				$id = $_SESSION["id"]; 
                    				$sql_query = "SELECT game_id FROM games_in_library WHERE user_id = '$id' AND library_name='My Games'; ";
                    				$result = mysqli_query($database, $sql_query);
                    				$row = $result->fetch_assoc();
                    				$game_id = $row["game_id"];


                    				$sql_query = "INSERT INTO library (user_id,library_name,game_id) VALUES ('$id','$lib_name','$game_id'); ";
                    				$result = mysqli_query($database, $sql_query);
                    				if($result)
	                        		{	
	                        			echo "<script> alert('". $lib_name ." has been created ". "')   </script>";
	                        			unset($_POST["lib_btn"]);
	                        			echo "<script>window.location = window.location.href;</script>";
	                        		}
	                        		else
	                        		{
	                        			echo "<script> alert('". $lib_name ." could not created". "')   </script>";
	                        		}
                    				
                    			}
                    			
                    		}

                    		?>

                    	</div>
                    </div>
                        


                    <div></div>
                    <div class="row">
                        <div class="col">
                            <ul class="list-group" id="ito">
                            	<?php
                            		if(isset($_POST["thename"]))
                            		{
                            			$id = $_SESSION["id"];
                            			$selected_name = $_POST["thename"];
                            			$sql_query = "SELECT game_name, game_id
													 FROM games_in_library
													 WHERE user_id = '$id' AND library_name = '$selected_name'; ";
                    					$result = mysqli_query($database, $sql_query)or die('Error in getting names');
                    					$counter = 0;
                    					$delee = 0;
                            			while($row = $result->fetch_assoc() )
                            			{
                            				if($selected_name == "My Games")
                            				{?>
                            				<li class="list-group-item float-left"><span><?php echo $row[game_name] ?></li>
                            					<?php }
                            				else{ ?>
                            					<li id="<?php echo $counter; ?>" class="list-group-item float-left">
                            					<span><?php echo $row[game_name]; ?>
	                            						<button class="close" onclick="del('<?php echo $row[game_name]; ?>',
	                            															'<?php echo $counter?>',
                                                                                            '<?php echo $id?>',
                                                                                            '<?php echo $row[game_id]?>',
	                            															'<?php echo $selected_name?>');">
	                            							<span aria-hidden="true">×</span>
	                            						</button> 
                            					</span>
                            					<?php 
                            					?>
                            				</li>
                            			<?php $counter = $counter +1; }
                            			 };


                            		};
                            	?>
                            </ul>
                        </div>
                    </div>
                    <script>
                    	function del(x,y,id,game_id,lib_name) {
                    		var elem = document.getElementById(y);
							elem.parentNode.removeChild(elem);

							if (window.XMLHttpRequest)
							{
					            // code for IE7+, Firefox, Chrome, Opera, Safari
					            xmlhttp = new XMLHttpRequest();
					        } else 
					        {
					            // code for IE6, IE5
					            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
					        }

					        xmlhttp.open("GET","delete_game.php?game_name="+x+"&lib_name="+lib_name+"&game_id="+game_id+"&id="+id,true);
					        xmlhttp.send();

                    		alert(x + " deleted");

                    		elem = document.getElementById("ito");
                    		child=(elem.firstElementChild||elem.firstChild);

                    		if(child instanceof Text)
                    		{
                    			
                    			window.location = window.location.href;
                    		}

                    }</script>
                    <div>
                        <div class="row">
                            <div class="col">
                                <h4 class="text-center">Add a game:</h4>
                                <form action="manageLibrary?libbname=<?php echo $selected_name; ?>" method="POST">
                                <input type="submit" name="sbt_btn" value= "Add" class="btn btn-success btn-block"></div>
                            <div class="col">
                            	<?php 

                            	if(isset($_POST["thename"]))
                            	{
                            		$id = $_SESSION["id"];
                            	$selected_name = $_POST["thename"];
                            	$sql_query = "SELECT game_name,game_id
                            	FROM games_in_library
                            	WHERE user_id = '$id' AND library_name = 'My Games' 
                            	AND (game_name) NOT IN (SELECT game_name
                            	FROM games_in_library
                            	WHERE user_id='$id' AND library_name ='$selected_name' ); ";
                        		$result = mysqli_query($database, $sql_query)or die('Error In getting lib names');
                        		$counter = 0;
                        		while($row = $result->fetch_assoc() )
                        			{  ?>

                        			<div class="form-check"><input class="form-check-input" type="radio" name="game" value="<?php echo $row['game_id']; ?>" id="formCheck-1"><label class="form-check-label" for="formCheck-1"> <?php echo $row["game_name"]; ?> </label></div>
                        			<?php $counter = $counter +1; }; 
                            	}
                            	

                            	?>
                                
                            </div>
                        </form>
                        </div>
                        <?php 
                        		if(isset($_POST["game"]))
                        		{
                        			$id = $_SESSION["id"];
	                            	$selected_name = $_GET["libbname"];
	                            	$selected_game_id = $_POST["game"];

	                            	$sql_query = "SELECT game_name FROM games WHERE game_id = '$selected_game_id'; ";
	                            	$result = mysqli_query($database, $sql_query)or die('Error In adding games');
	                            	$row = $result->fetch_assoc();
	                            	$game_name = $row["game_name"];

	                            	$sql_query = "INSERT INTO library (user_id, library_name, game_id) VALUES ('$id','$selected_name','$selected_game_id' ); ";
	                        		$result = mysqli_query($database, $sql_query);

	                        		if($result)
	                        		{	
	                        			echo "<script> alert('". $game_name ." has been added into your library ". $selected_name . "')   </script>";
	                        		}
                        		}
                       			
                        ?>
                        <div>
                            <div class="row">
                                <div class="col">
                                	<a href="library.php" class="btn btn-primary btn-block" role="button">Done</a>
                                </div>
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