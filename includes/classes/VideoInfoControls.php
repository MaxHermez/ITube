<?php
require_once("includes/classes/ButtonMaker.php"); 
class VideoInfoControls {
    private $video, $loggedInUser;

    public function __construct($video,$loggedInUser) {
        $this->video = $video;
        $this->loggedInUser = $loggedInUser;
    }

    public function create() {
        $likeB = $this->createLikeButton();
        $dislikeB = $this->createDislikeButton();
        return "<div class='controls'>
                    $likeB
                    $dislikeB
                </div>";
    }
    private function createLikeButton() {
        $text = $this->video->getLikes();
        $videoId = $this->video->getId();
        $action = "likeVideo(this, $videoId)";
        $class = "likeButton";
        $imgSrc = "assets/images/icons/thumb-up.png";

        if($this->video->wasLikedBy()) {
            $imgSrc = "assets/images/icons/thumb-up-active.png";
        }

        return ButtonMaker::createButton($text, $imgSrc, $action, $class);
    }
    private function createDislikeButton() {
        $text = $this->video->getDislikes();
        $videoId = $this->video->getId();
        $action = "dislikeVideo(this, $videoId)";
        $class = "dislikeButton";
        $imgSrc = "assets/images/icons/thumb-down.png";

        if($this->video->wasDislikedBy()) {
            $imgSrc = "assets/images/icons/thumb-down-active.png";
        }

        return ButtonMaker::createButton($text, $imgSrc, $action, $class);
    }
}

?>