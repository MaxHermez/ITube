<?php

class User {
    private $con, $data;
    
    public function __construct($con, $username) {
        if(!$username == "") {
            $this->con = $con;
            $query = $this->con->prepare("SELECT * FROM users WHERE username = :un");
            $query->bindParam(":un", $username);
            $query->execute();
            $this->data = $query->fetch(PDO::FETCH_ASSOC);
        }
        else {
            $this->data = $username;
        }
    }
    public static function isLoggedIn() {
        return isset($_SESSION["loggedIn"]) ? $_SESSION["loggedIn"] : "";
    }
    public function getUsername() {
        return (!$this->data == "") ? $this->data["username"] : null;
    }
    public function getName() {
        return $this->data["firstName"] . " " . $this->data["lastName"];
    }
    public function getFirstName() {
        return $this->data["firstName"];
    }
    public function getLastName() {
        return $this->data["lastName"];
    }
    public function getEmail() {
        return $this->data["email"];
    }
    public function getPicture() {
        return $this->data["profilePic"];
    }
    public function getSignUpDate() {
        return $this->data["signUpDate"];
    }
    public function isSubscribedTo($user) {
        $userFrom = $this->getUsername();
        $q = $this->con->prepare("SELECT * FROM subscribers WHERE userTo=:user AND userFrom=:userFrom");
        $q->bindParam(":user", $user);
        $q->bindParam(":userFrom", $userFrom);
        $q->execute();
        return $q->rowCount() > 0;
    }
    public function getSubscriberCount() {
        $user = $this->getUsername();
        $q = $this->con->prepare("SELECT COUNT(*) as 'count' FROM subscribers WHERE userTo=:user");
        $q->bindParam(":user", $user);
        $q->execute();
        $data = $q->fetch(PDO::FETCH_ASSOC);
        return $data["count"];
    }
    public function getSubscriptions() {
        if(!User::isLoggedIn()) {
            return array();
        }
        $q = $this->con->prepare("SELECT userTo FROM subscribers WHERE userFrom=:userFrom");
        $username = $this->getUsername();
        $q->bindParam(":userFrom", $username);
        $q->execute();
        $subs = array();
        while($row = $q->fetch(PDO::FETCH_ASSOC)) {
            $user = new User($this->con, $row["userTo"]);
            array_push($subs, $user);
        }
        return $subs;
    }
}

?>