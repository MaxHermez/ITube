<?php

class VideoDetailsForm {

    public function __construct($con)
    {
        $this->con = $con;
    }

    // bootstrap form
    public function createUploadForm() 
    {
        $fileInput = $this->createFileInput();
        $titleInput = $this->createTitleInput();
        $descriptionInput = $this->createDescriptionInput();
        $privacyInput = $this->createPrivacyInput();
        $categoryInput = $this->createCategoriesInput();
        $uploadButton = $this->createUploadButton();
        return "<form action='processing.php' method='POST'>
                    $fileInput
                    $titleInput
                    $descriptionInput
                    $privacyInput
                    $categoryInput
                    $uploadButton
                </form>";
    }
    private function createFileInput() 
    {   return "<div class='form-group'>
                    <label for='exampleFormControlFile1'>Upload your video</label>
                    <input type='file' class='form-control-file' name='fileinput' id='exampleFormControlFile1' required>
                </div>";
    }
    private function createTitleInput() 
    {   return "<div class='form-group'>
                    <input class='form-control' type='text' placeholder='Video Title' name='titleInput'>
                </div>";
    }
    private function createDescriptionInput() 
    {   return "<div class='form-group'>
                    <textarea class='form-control' placeholder='A few words about the video' name='descriptionInput' style='resize: vertical;'></textarea>
                </div>";
    }
    private function createPrivacyInput() 
    {   return "<div class='form-group'>
                    <select class='form-control' placeholder='who can see this' name='privacyInput'>
                        <option value='0'>Private</option>
                        <option value='1'>Public</option>
                    </select>
                </div>";
    }
    private function createCategoriesInput() 
    {
        $query = $this->con->prepare("SELECT * FROM categories");
        $query->execute();
        $html = "<div class='form-group'>
                    <select class='form-control' name='categoryInput'>";
        while($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $name = $row["name"];
            $id = $row["id"];
            $html .= "<option value='$id'>$name</option>";
        }
        $html .= "</select></div>";
        return $html;
    }
    private function createUploadButton()
    {
        return "<button type='submit' class='btn btn-primary' name='uploadButton'>Upload</button>";
    }
}

?>