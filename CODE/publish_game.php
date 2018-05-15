<!DOCTYPE html>

<?php
session_start();
include("config.php");

//$_SESSION['developer'] == "false" ||
if ($_SESSION['developer'] == 'false' || !isset($_SESSION['id']) || (trim($_SESSION['id']) == '') || $_SESSION["login"] != "true") {
    header("location: index.php");
    exit();
}

$id = $_SESSION['id']; 
?>

<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>publish_game</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/Contact-Form-Clean.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
</head>

<body>
    <div class="contact-clean" style="background-color:#184660;">
        <form >
            <h2 class="text-center">Publish Game</h2>
			<div class="form-group"><input id="array" class="form-control" type="hidden" name="game_name"></div>
            <div class="form-group"><input id="game_name" class="form-control" type="text" name="game_name" placeholder="Game Name"></div>
            <div class="form-group"><input id="image_url" class="form-control is-invalid"  name="image_url" placeholder="Image URL">
			<input class="form-control is-invalid" id="price" name="price" placeholder="Price($)" inputmode="numeric" style="margin-top:16px;"></div>
            <div class="form-group"><textarea id="description" class="form-control" rows="14" name="message" placeholder="Description"></textarea></div>
            <div>
                <?php 
					$query = "select * from genres;";
					$result = mysqli_query($database, $query) or die(mysqli_error($database));
					
					while($row = $result->fetch_assoc()){
						echo '<div class="form-check"><input name="genre" value="'.$row['genre_id'].'" class="form-check-input" type="checkbox" id="formCheck-1"><label class="form-check-label" for="formCheck-1">'.$row['genre_name'].' (Age Restriction: '.$row['age_registriction'].')</label></div>';
					}
					
				?>

            </div>
            <div class="form-group"><button class="btn btn-primary" onclick="publish_game();" type="submit">send </button></div>
        </form>
    </div>

</body>

</html>

<script>
function publish_game(){
	var game_name = $('#game_name').val();
	var image_url = $('#image_url').val();
	var price = $('#price').val();
	var description = $('#description').val();
	var genres = Array();
	$("input:checkbox[name=genre]:checked").each(function(){
		genres.push($(this).val());
	});
	
	if(game_name == "" || image_url == "" || price == "" || description == "" || genres == ""){
		alert("Invalid entry");
		return;
	}
	
	var data = {game_name: game_name, image_url:image_url, price:price, description:description, genres:genres};
	alert(JSON.stringify(data));
	$.ajax({
		method: "POST",
		url: "publish.php",
		dataType: "JSON",
		async:false,
		cache: false, 
		data: data,
		success: function(result)
		{
			alert('Success');
		},
		error: function (xhr, ajaxOptions, thrownError)
		{
			alert(xhr.status);
			alert(thrownError);
		}
	});
	
//	var form = document.getElementById('myForm');
//	form.setAttribute("value", genresArray);
//	form.submit();
	
	//$.post('publish.php', {game_name:game_name, image_url:image_url, price:price, description:description, genres:genresArray});
}

</script>

