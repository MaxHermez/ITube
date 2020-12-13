<?php 
class SubscriptionsProvider {
    private $con, $loggedInUser;
    public function __construct($con, $loggedInUser)
    {
        $this->con = $con;
        $this->loggedInUser = $loggedInUser;
    }
    public function getVideos() {
        $videos = array();
        $subscriptions = $this->loggedInUser->getSubscriptions();

        if(sizeof($subscriptions)>0) {
            $condition = "";
            $i = 0;

            while($i <sizeof($subscriptions)) {
                if($i==0) {
                    $condition .= "WHERE uploadedBy=?";
                }
                else {
                    $condition .= " OR uploadedBy=?";
                }
                $i++;
            }
            $sqlVideos = "SELECT * FROM videos $condition ORDER BY uploadDate DESC";
            $q = $this->con->prepare($sqlVideos);
            $i = 1;
            foreach($subscriptions as $sub) {
                $subUsername = $sub->getUsername();
                $q->bindValue($i, $subUsername);
                $i++;
            }
            $q->execute();
            while($row = $q->fetch(PDO::FETCH_ASSOC)) {
                $video = new Video($this->con, $row, $this->loggedInUser);
                array_push($videos, $video);
            }
        }
        return $videos;
    }
}




?>