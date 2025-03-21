<?php 

require_once("../includes/config.php");
require_once("../includes/classes/Comment.php");
require_once("../includes/classes/User.php");


if(User::isLoggedIn()) {
    $username = $_SESSION["loggedIn"];
    $loggedInUser = new User($con, $username);
}
else{$loggedInUser = new User($con, "");}

$videoId = $_POST["videoId"];
$commentId = $_POST["commentId"];
$comment = new Comment($con, $commentId, $loggedInUser, $videoId);
echo $comment->getReplies();

?>