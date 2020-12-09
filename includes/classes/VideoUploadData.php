<?php
class VideoUploadData {
    private $videoDataArray, $title, $description, $privacy, $category, $uploader;
    public function __construct(
        $videoDataArray, $title, $description, 
        $privacy, $category, $uploader) 
    {
        $this->videoDataArray = $videoDataArray;
        $this->title = $title;
        $this->description = $description;
        $this->privacy = $privacy;
        $this->category = $category;
        $this->uploader = $uploader;
    }
}
?>