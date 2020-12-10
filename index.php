<?php require_once("includes/header.php"); ?>

<?php
if(isset($_SESSION["loggedIn"])) {
    echo "user logged in: ", $loggedInUser->getName();
}
?>


<?php require_once("includes/footer.php"); ?>
