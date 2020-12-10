<?php
require_once("includes/header.php"); 
require_once("includes/classes/VideoPlayer.php"); 
require_once("includes/classes/VideoInfo.php"); 

if(!isset($_GET["id"])) {
    header('Location: 404.php');
    exit();
}

$video = new Video($con, $_GET["id"], $loggedInUser);
$video->incrementViews();
?>
<script src="assets/js/videoPlayerActions.js"></script>
<div class="watchLeftColumn">

<?php 
    $videoPlayer = new VideoPlayer($video);
    echo $videoPlayer->create(true);

    $videoInfo = new VideoInfo($video, $con, $loggedInUser);
    echo $videoInfo->create(true);

    // $videoInfo = new VideoInfo($con, $video, $loggedInUser);
    // echo $videoInfo->create(true);
?>

</div>

<div class="suggestions">

</div>


<?php require_once("includes/footer.php"); ?>
