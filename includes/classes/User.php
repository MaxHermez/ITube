<?php

class User {
    private $con, $data;
    
    public function __construct($con, $username) {
        $this->con = $con;
        $query = $this->con->prepare("SELECT * FROM users WHERE username = :un");
        $query->bindParam(":un", $username);
        $query->execute();
        $this->data = $query->fetch(PDO::FETCH_ASSOC);
    }
    public static function isLoggedIn() {
        return isset($_SESSION["loggedIn"]) ? $_SESSION["loggedIn"] : "";
    }
    public function getUsername() {
        return $this->data["username"];
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
}

?>