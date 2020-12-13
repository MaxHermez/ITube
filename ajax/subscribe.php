<?php
require_once("../includes/config.php");
require_once("../includes/classes/User.php");

if(isset($_POST['userTo']) && isset($_POST['userFrom'])) {
    
    $userTo = $_POST['userTo'];
    $userFrom = $_POST['userFrom'];
    if($userFrom == "") {
        $sub = false;
        return;
    } 
    else {
        $watchingUser = new User($con, $userFrom);
        $sub = $watchingUser->isSubscribedTo($userTo);
    }
    if(!$sub) {
        // not subscribed
        $q = $con->prepare("INSERT INTO subscribers(userTo, userFrom) VALUES(:userTo, :userFrom)");
        $q->bindParam(":userTo", $userTo);
        $q->bindParam(":userFrom", $userFrom);
        $q->execute();
    }
    else {
        // already subscribed
        $q = $con->prepare("DELETE FROM subscribers WHERE userTo=:userTo AND userFrom=:userFrom");
        $q->bindParam(":userTo", $userTo);
        $q->bindParam(":userFrom", $userFrom);
        $q->execute();
    }
    $q = $con->prepare("SELECT * FROM subscribers WHERE userTo=:userTo");
    $q->bindParam(":userTo", $userTo);
    $q->execute();
    echo $q->rowCount();
}
else {
    echo "One or more parameters are not parsed to the subscribe.php file.";
}

?>