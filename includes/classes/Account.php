<?php
class Account {
    // used to validate and insert an Account's data
    private $con;
    private $errors = array();
    public function __construct($con) {
        $this->con = $con;
    }
    public function register($fn, $ln, $un, $em, $emc, $pw, $pwc) {
        $this->validateFirstName($fn);
    }
    private function validateFirstName($fn) {
        if(strlen($fn)>25 || strlen($fn) < 2) {
            array_push($this->errors, Constants::$nameLength);
        }
    }
    private function validateLastName($ln) {
        if(strlen($ln)>25 || strlen($ln) < 2) {
            array_push($this->errors, Constants::$nameLength);
        }
    }
    private function validateUsername($un) {
        if(strlen($un)>25 || strlen($un) < 2) {
            array_push($this->errors, Constants::$usernameLength);
            return;
        }
        $q = $this->con->prepare("SELECT username FROM users WHERE username=:un");
        $q->bindParam(":=un", $un);
        $q->execute();
        if($q->rowCount() != 0) {
            array_push($this->errors, Constants::$usernameTaken);
        }
    }
    public function getError($error) {
        if(in_array($error, $this->errors)) {
            return "<span class='errorMessage'>$error</span>";
        }
    }
}

?>