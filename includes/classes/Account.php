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
        $this->validateLastName($ln);
        $this->validateUsername($un);
        $this->validateEmail($em, $emc);
        $this->validatePasswords($pw, $pwc);
        if(empty($this->errors)) {
            return $this->insertUserDetails($fn, $ln, $un, $em, $pw);
        }
        else {
            return false;
        }
    }
    public function login($un, $pw) {
        $pw = hash("sha512", $pw);
        $q = $this->con->prepare("SELECT * FROM  users WHERE username=:un AND password=:pw");
        $q->bindParam(":un", $un);
        $q->bindParam(":pw", $pw);
        $q->execute();
        if($q->rowCount() == 1) {
            return true;
        }
        else {
            array_push($this->errors, Constants::$loginFailed);
            return false;
        }
    }
    public function insertUserDetails($fn, $ln, $un, $em, $pw) {
        $pw = hash("sha512", $pw);
        $profilePic = "assets/images/profilePictures/default.png";

        $q = $this->con->prepare("INSERT INTO users(firstName, lastName, username, email, password, profilePic)
                                    VALUES(:fn, :ln, :un, :em, :pw, :pic)");
        $q->bindParam(":fn", $fn);
        $q->bindParam(":ln", $ln);
        $q->bindParam(":un", $un);
        $q->bindParam(":em", $em);
        $q->bindParam(":pw", $pw);
        $q->bindParam(":pic", $profilePic);

        return $q->execute();
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
        if(strlen($un)>50 || strlen($un) < 2) {
            array_push($this->errors, Constants::$usernameLength);
            return;
        }
        $q = $this->con->prepare("SELECT username FROM users WHERE username=:un");
        $q->bindParam(":un", $un);
        $q->execute();
        if($q->rowCount() != 0) {
            array_push($this->errors, Constants::$usernameTaken);
        }
    }
    private function validateEmail($em,$emc) {
        if($em != $emc) {
            array_push($this->errors, Constants::$emailsMismatch);
        }
        if(!filter_var($em, FILTER_VALIDATE_EMAIL)) {
            array_push($this->errors, Constants::$emailInvalid);
        }
        if(strlen($em)>255 || strlen($em) < 8) {
            array_push($this->errors, Constants::$emailLength);
            return;
        }
        $q = $this->con->prepare("SELECT email FROM users WHERE email=:em");
        $q->bindParam(":em", $em);
        $q->execute();
        if($q->rowCount() != 0) {
            array_push($this->errors, Constants::$emailTaken);
        }
    }
    private function validateEmail2($em, $un) {
        if(!filter_var($em, FILTER_VALIDATE_EMAIL)) {
            array_push($this->errors, Constants::$emailInvalid);
        }
        if(strlen($em)>255 || strlen($em) < 8) {
            array_push($this->errors, Constants::$emailLength);
            return;
        }
        $q = $this->con->prepare("SELECT email FROM users WHERE email=:em AND username!=:un");
        $q->bindParam(":em", $em);
        $q->bindParam(":un", $un);
        $q->execute();
        if($q->rowCount() != 0) {
            array_push($this->errors, Constants::$emailTaken);
        }
    }
    private function verifyPassword($un, $pw) {
        $pw = hash("sha512", $pw);
        $q = $this->con->prepare("SELECT password FROM users WHERE username=:un");
        $q->bindParam(":un", $un);
        $q->execute();
        $data = $q->fetch(PDO::FETCH_ASSOC);
        $hash = $data["password"];
        if($pw == $hash) {
            return true;
        }
        else {
            array_push($this->errors, Constants::$passwordInvalid);
            return false;
        }
    }
    private function validatePasswords($pw,$pwc) {
        if($pw != $pwc) {
            array_push($this->errors, Constants::$passwordsMismatch);
        }
        if(!preg_match("/^(?=.*\d)(?=.*[A-Za-z])(?=.*[!@#$%^&*()])[0-9A-Za-z!@#$%^&*()]{8,25}$/", $pw)) {
            array_push($this->errors, Constants::$passwordCharacters);
        }
    }
    public function getError($error) {
        if(in_array($error, $this->errors)) {
            return "<span class='errorMessage'>$error</span>";
        }
    }
    public function updateDetails($fn, $ln, $em, $un, $pw) {
        $this->validateFirstName($fn);
        $this->validateLastName($ln);
        $this->validateEmail2($em, $un);
        $this->verifyPassword($un, $pw);
        if(empty($this->errors)) {
            $q = $this->con->prepare("UPDATE users SET firstName=:fn, lastName=:ln, email=:em WHERE username=:un");
            $q->bindParam(":fn", $fn);
            $q->bindParam(":ln", $ln);
            $q->bindParam(":em", $em);
            $q->bindParam(":un", $un);
            return $q->execute();
        }
        else {
            
            return false;
        }
    }
    public function getFirstError() {
        if(!empty($this->errors)) {
            return $this->errors[0];
        }
        else {
            return "";
        }
    }
    public function updatePassword($pw, $npw, $npwc, $un) {
        $this->verifyPassword($un, $pw);
        $this->validatePasswords($npw, $npwc);
        $npw = hash("sha512", $npw);
        if(empty($this->errors)) {
            $q = $this->con->prepare("UPDATE users SET password=:npw WHERE username=:un");
            $q->bindParam(":npw", $npw);
            $q->bindParam(":un", $un);
            return $q->execute();
        }
        else {
            
            return false;
        }
    }
}

?>