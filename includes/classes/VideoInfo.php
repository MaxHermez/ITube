<?php
require_once("includes/classes/VideoInfoControls.php"); 


class VideoInfo {
    private $video, $con, $loggedInUser;

    public function __construct($video, $con,$loggedInUser) {
        $this->video = $video;
        $this->con = $con;
        $this->loggedInUser = $loggedInUser;
    }

    public function create() {
        return $this->createPrimaryInfo() . $this->createSecondaryInfo();
    }
    private function createPrimaryInfo() {
        $title = $this->video->getTitle();
        $views = $this->video->getViews();

        $videoInfoControls = new VideoInfoControls($this->video, $this->loggedInUser);
        $controls = $videoInfoControls->create();

        return "<div class='videoInfo'>
                    <h1>$title</h1>
                    
                    <div class='bottomSection'>
                        <span class='viewCount'>$views views</span>
                        $controls
                    </div>
                </div>";
    }
    private function createSecondaryInfo() {
        
    }
}

?>