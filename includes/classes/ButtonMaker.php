<?php

class ButtonMaker {

    public static $signInFunction = "notSignedIn()";

    public static function createUserLink($link) {
        return User::isLoggedIn() ? $link : ButtonMaker::$signInFunction;
    }

    public static function createButton($text, $imgSrc, $action, $class) {
        $image = ($imgSrc == null||$imgSrc=="") ? "" : "<img src='$imgSrc'>";

        $action = ButtonMaker::createUserLink($action);

        return "<button class=$class onclick='$action'>
            $image
            <span class='text'>$text</span>
        </button>";
    }
}

?>