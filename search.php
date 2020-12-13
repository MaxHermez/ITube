<?php
include_once("includes/header.php");
include_once("includes/classes/SearchResultsMaker.php");
if(!isset($_GET['term']) || $_GET['term'] == "") {
    echo "No search term found.";
    exit();
}

$term = $_GET['term'];
if(!isset($_GET['orderBy']) || $_GET['orderBy'] == "views") {
    $orderBy = "views";
}
else {
    $orderBy = "uploadDate";
}
if(!isset($_GET['order']) || $_GET['order'] == "descending") {
    $order = "DESC";
}
else {
    $order = "ASC";
}

$searchResultsMaker = new SearchResultsMaker($con, $loggedInUser);
$videos = $searchResultsMaker->getVideos($term, $orderBy, $order);

$videoGrid = new VideoGrid($con, $loggedInUser);
?>
<div class='largeVideoGridContainer'>

    <?php
    if(sizeof($videos)>0){
        echo $videoGrid->createLarge($videos, sizeof($videos)." videos found", true);
    }
    else {
        echo "No results found";
    }

    ?>


</div>
<?php

include_once("includes/footer.php");

?>