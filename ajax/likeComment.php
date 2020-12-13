<?php 

require_once("../includes/config.php");
require_once("../includes/classes/Comment.php");
require_once("../includes/classes/User.php");

$username = $_SESSION["loggedIn"];
$loggedInUser = new User($con, $username);
$videoId = $_POST["videoId"];
$commentId = $_POST["commentId"];
$comment = new Comment($con, $commentId, $loggedInUser, $videoId);
echo $comment->like();

?>