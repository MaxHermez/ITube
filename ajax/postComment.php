<?php
require_once("../includes/config.php");
require_once("../includes/classes/User.php");
require_once("../includes/classes/Comment.php");

if(isset($_POST['commentText']) && isset($_POST['postedBy'])&& isset($_POST['videoId'])) {
    $postedBy = ($_POST['postedBy']);
    $videoId = ($_POST['videoId']);
    $responseTo = isset($_POST['responseTo']) ? ($_POST['responseTo']) : 0;
    $commentText = ($_POST['commentText']);
    $loggedInUser = new User($con, $_SESSION["loggedIn"]);
    $q = $con->prepare("INSERT INTO comments(postedBy, videoId, responseTo, body)
                            VALUES(:postedBy, :videoId, :responseTo, :body)");
    $q->bindParam(":postedBy", $postedBy);
    $q->bindParam(":videoId", $videoId);
    $q->bindParam(":responseTo", $responseTo);
    $q->bindParam(":body", $commentText);
    $q->execute();

    
    $newComment = new Comment($con, $con->lastInsertId(), $loggedInUser, $videoId);
    echo $newComment->create();
}
else {
    echo "One or more parameters are not parsed to the subscribe.php file.";
}

?>