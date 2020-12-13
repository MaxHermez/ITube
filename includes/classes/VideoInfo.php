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
        // creates the video player and video info & interactions
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
        $desc = $this->video->getDescription();
        $uploadDate = $this->video->getUploadDate();
        $uploaderUsername = $this->video->getUploader();
        $profileButton = ButtonMaker::createUserProfileButton($this->con, $uploaderUsername);
        if($uploaderUsername == $this->loggedInUser->getUsername()) {
            $actionButton = ButtonMaker::createEditButton($this->video->getId());
        }
        else {
            $uploader = new User($this->con, $uploaderUsername);
            $actionButton = ButtonMaker::createSubscribeButton($this->con, $uploader, $this->loggedInUser);
        }
        return "<div class='secondaryInfo'>
                    <div class='topRow'>
                        $profileButton

                        <div class='uploadInfo'>
                            <span class='owner'>
                                <a href='profile.php?username=$uploaderUsername'>
                                    $uploaderUsername
                                </a>
                            </span>
                            <span class='date'>Published on $uploadDate</span>
                        </div>
                    $actionButton
                    </div>

                    <div class='descriptionContainer'>
                        $desc
                        </div>
                </div>";
    }
}

?>