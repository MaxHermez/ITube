<?php 
require_once("../includes/config.php");
require_once("../includes/classes/Video.php");
require_once("../includes/classes/User.php");

$username = $_SESSION["loggedIn"];
$loggedInUser = new User($con, $username);
$videoId = $_POST["videoId"];
$video = new Video($con, $videoId, $loggedInUser);
echo $video->dislike();

?>