<?php 
class LikedVideosProvider {
    private $con, $loggedInUser;
    public function __construct($con, $loggedInUser)
    {
        $this->con = $con;
        $this->loggedInUser = $loggedInUser;
    }
    public function getVideos() {
        $videos = array();
        $un = $this->loggedInUser->getUsername();
        $q = $this->con->prepare("SELECT videoId FROM likes WHERE username=:un AND commentId = 0
                                    ORDER BY id DESC");
        $q->bindParam(":un", $un);
        $q->execute();

        while($row = $q->fetch(PDO::FETCH_ASSOC)) {
            $videos[] = new Video($this->con, $row["videoId"], $this->loggedInUser);
        }
        return $videos;
    }
}




?>