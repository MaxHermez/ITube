<?php 
require_once("includes/header.php");
require_once("includes/classes/VideoDetailsForm.php");
?>

<div class="column">
    <?php
    $formProvider = new VideoDetailsForm($con);
    echo $formProvider->createUploadForm();
    ?>
</div>

<?php require_once("includes/footer.php"); ?>
