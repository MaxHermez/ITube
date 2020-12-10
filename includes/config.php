<?php
ob_start(); // makes the php return its output only after finishing running
session_start();
date_default_timezone_set("Asia/Beirut");

try {
    $con = new PDO("mysql:dbname=ITube;host=localhost", "root", "");
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
}
catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

?>