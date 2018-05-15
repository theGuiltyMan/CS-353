<?php
$user = "baris.eymur"; 
$pwd = "l5z0t1gd"; 
$host = "dijkstra.ug.bcc.bilkent.edu.tr"; 
$db = "baris_eymur";

$database = mysqli_connect($host,$user,$pwd,$db) or die("Cannot connect to the host!!!".mysqli_error()); 
mysqli_query($con,"SET NAMES UTF8");


?>