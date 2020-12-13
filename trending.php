<?php 
require_once("includes/header.php");
require_once("includes/classes/TrendingProvider.php");
$trendingProvider = new TrendingProvider($con, $loggedInUser);
$videos = $trendingProvider->getVideos();

$videoGrid = new VideoGrid($con, $loggedInUser);
?>
<div class="largeVideoGridContainer">
    <?php
    if(sizeof($videos)>0) {
        echo $videoGrid->createLarge($videos, "Trending videos uploaded last week", false);
    }
    else{
        echo "No trending videos to show";
    }
    ?>
</div>