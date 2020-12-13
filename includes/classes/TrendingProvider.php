<?php 
class TrendingProvider {
    private $con, $loggedInUser;
    public function __construct($con, $loggedInUser)
    {
        $this->con = $con;
        $this->loggedInUser = $loggedInUser;
    }
    public function getVideos() {
        $videos = array();
        $q = $this->con->prepare("SELECT * FROM videos WHERE uploadDate >= now() - INTERVAL 7 DAY
                                    ORDER BY views DESC LIMIT 15"); // anything that's uploaded in the last 7 days, 15 most viewed
        $q->execute();

        while($row = $q->fetch(PDO::FETCH_ASSOC)) {
            $video = new Video($this->con, $row, $this->loggedInUser);
            array_push($videos, $video);
        }
        return $videos;
    }
}




?>