<!DOCTYPE html>
<?php
session_start();
include("config.php");

if (!isset($_SESSION['id']) || (trim($_SESSION['id']) == '') || $_SESSION["login"] != "true") {
    header("location: index.php");
    exit();
}

$id = $_SESSION['id'];
$comment_id = $_GET['comment_id'];
$game_id = $_GET['game_id'];
$discussion_id = $_GET['discussion_id'];
$post_comment_id =$_GET['post_comment_id'];


?>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reply</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/Contact-Form-Clean.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<script>
    function send(user_id,comment_id,discussion_id,game_id) 
    {
      var reply = document.getElementById("reply").value;
      var replied_id = document.getElementById("replied_id").value;

      $.ajax({
          method: "POST",
          url: "send_reply.php",
          data: { reply:reply , replied_id:replied_id, user_id: user_id},                                        
          success: function(result)
          {
            if(result==1)
            {
                window.location.replace("post.php?post_comment_id="+comment_id+"&discussion_id="+discussion_id+"&game_id="+game_id);
            }
        }
    });

      return false;
  }
</script>

<body style="background-color:#184660;">
    <div class="contact-clean" style="background-color:#184660;color:#184660;">
         <form onsubmit="return send(<?php echo $id; ?>,<?php echo $post_comment_id; ?>,<?php echo $discussion_id; ?>,<?php echo $game_id; ?>);" >
            <h2 class="text-center">Reply</h2>
            <p><b>Reply To:</b> 
			<?php 
				$query = "select text from comments where comment_id = '$comment_id';";
				$result = mysqli_query($database, $query) or die(mysqli_error($database));
				while($row = $result->fetch_assoc()){
					echo $row['text'];
				}
			?>
			</p>
			<?php echo '<input name="replied_id" id="replied_id" type="hidden" value = "'.$comment_id.'">';?>
            <div class="form-group"><textarea class="form-control" rows="14" name="reply" id="reply" placeholder="Reply"></textarea></div>
            <div class="form-group"><button class="btn btn-primary" type="submit" >send </button></div>
        </form>
    </div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>