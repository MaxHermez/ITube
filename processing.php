<?php 
require_once("includes/header.php");
require_once("includes/classes/VideoUploadData.php");
require_once("includes/classes/VideoProcessor.php");


if(!isset($_POST["uploadButton"])) {
    echo "No file sent to page.";
    exit();
}
// create the file upload data
$videoUploadData = new VideoUploadData(
    $_FILES['fileInput'],
    $_POST['titleInput'],
    $_POST['descriptionInput'],
    $_POST['privacyInput'],
    $_POST['categoryInput'],
    $loggedInUser->getUsername()
    );

$videoProcessor = new VideoProcessor($con);
$success = $videoProcessor->upload($videoUploadData);
if($success) {
    echo "Upload success!";
}
else { echo "Failed somewhere.";}
?>

<?php require_once("includes/footer.php"); ?>