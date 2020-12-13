<?php
class ProfileData {
    private $con, $profileUser;
    public function __construct($con, $profileUsername) {
        $this->con = $con;
        $this->profileUser = new User($con, $profileUsername);
    }
    public function getProfileUserObj() {
        return $this->profileUser;
    }
    public function getProfileUsername() {
        return $this->profileUser->getUsername();
    }
    public function userExists() {
        $profileUsername = $this->getProfileUsername();
        $q = $this->con->prepare("SELECT * FROM users WHERE username=:un");
        $q->bindParam(":un", $profileUsername);
        $q->execute();
        return $q->rowCount() != 0;
    }
    public function getCoverPhoto() {
        return "assets/images/coverPhotos/defaultCover.jpg";
    }
    public function getProfileFullName() {
        return $this->profileUser->getName();
    }
    public function getProfilePic() {
        return $this->profileUser->getPicture();
    }
    public function getSubCount() {
        return $this->profileUser->getSubscriberCount();
    }
    public function getUserVideos() {
        $username = $this->getProfileUsername();
        $q = $this->con->prepare("SELECT * FROM videos WHERE uploadedBy=:uploadedBy ORDER BY uploadDate DESC");
        $q->bindParam(":uploadedBy", $username);
        $q->execute();
        $videos = array();
        while($row=$q->fetch(PDO::FETCH_ASSOC)) {
            $videos[] = new Video($this->con, $row, $username);
        }
        return $videos;
    }
    public function getAllUserDetails() {
        return array(
            "Name" => $this->getProfileFullName(),
            "Username" => $this->getProfileUsername(),
            "Subscribers" => $this->getSubCount(),
            "Total views" => $this->getProfileTotalViews(),
            "Sign up date" => $this->getProfileSignupDate(),
        );
    }
    public function getProfileTotalViews() {
        $username = $this->getProfileUsername();
        $q = $this->con->prepare("SELECT sum(views) FROM videos WHERE uploadedBy=:uploadedBy");
        $q->bindParam(":uploadedBy", $username);
        $q->execute();
        return $q->fetchColumn();
    }
    public function getProfileSignupDate() {
        $date = $this->profileUser->getSignUpDate();
        return Date("F j, Y",strtotime($date));
    }
    
}

?>