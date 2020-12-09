<?php require_once("includes/config.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>ITube</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
    <script src="assets/js/commonActions.js"></script>
</head>
<body>
    <div id="pageContainer">

        <div id="mastHeadContainer">
            <button class="navShowHide">
                <img src="assets/images/icons/menu.png" alt="">
            </button>

            <a class="logoContainer" href="index.php">
                <img src="assets/images/icons/ITubeLogo.png" title="ITube" alt="Site logo">
            </a>

            <div class="searchBarContainer">
                <form action="search.php" method="GET">
                    <input type="text" class="searchBar" name="term" placeholder="Search...">
                    <button class="searchButton" style="padding-bottom: 6px;">
                        <img src="assets/images/icons/search.png" title="seach" alt="search">
                    </button>
                </form>
            </div>

            <div class="userInfo">
                <a href="upload.php">
                    <img class="upload" src="assets/images/icons/upload.png" alt="">
                </a>
                <a href="#">
                    <img class="upload" src="assets/images/profilePictures/default.png" alt="">
                </a>
            </div>

        </div>
        <div id="sideNavContainer" style="display: none;">

        </div>
        <div id="mainSectionContainer">
            <div id="mainContentContainer">