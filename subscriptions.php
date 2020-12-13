<?php 
require_once("includes/header.php");

if(!User::isLoggedIn()) {
    require_once("includes/footer.php");
    echo "<script>notSignedIn();</script>";
    exit();
}

$subscriptionsProvider = new SubscriptionsProvider($con, $loggedInUser);
$videos = $subscriptionsProvider->getVideos();

$videoGrid = new VideoGrid($con, $loggedInUser);
?>
<div class="largeVideoGridContainer">
    <?php
    if(sizeof($videos)>0) {
        echo $videoGrid->createLarge($videos, "New from subscriptions", false);
    }
    else{
        echo "No videos to show";
    }
    ?>
</div>