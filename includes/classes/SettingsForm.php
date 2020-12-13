<?php

class SettingsForm {
    // bootstrap form
    public function createUserDetailsForm($firstname, $lastname, $email) 
    {
        $firstNameInput = $this->createFirstNameInput($firstname);
        $lastNameInput = $this->createLastNameInput($lastname);
        $emailInput = $this->createEmailInput($email);
        $passwordInput = $this->createPasswordInput("password", "Enter your password");
        $saveButton = $this->createSaveDetailsButton();

        return "<form action='settings.php' method='POST' enctype='multipart/form-data'>
                    <span class='title'>User Details:</span>
                    $firstNameInput
                    $lastNameInput
                    $emailInput
                    $passwordInput
                    $saveButton
                </form>";
    }
    public function createPasswordsForm() 
    {
        $oldPasswordInput = $this->createPasswordInput("oldPassword", "Old password");
        $newPasswordInput = $this->createPasswordInput("newPassword", "New password");
        $newPasswordCInput = $this->createPasswordInput("newPasswordc", "Confirm password");
        $saveButton = $this->createSavePasswordButton();

        return "<form action='settings.php' method='POST' enctype='multipart/form-data'>
                    <span class='title'>Update Password:</span>
                    $oldPasswordInput
                    $newPasswordInput
                    $newPasswordCInput
                    $saveButton
                </form>";
    }
    private function createFirstNameInput($value) {
        if($value==null) $value = "";
        return "<div class='form-group'>
                    <input class='form-control' type='text' placeholder='First name' value='$value' name='firstName'>
                </div>";
    }
    private function createLastNameInput($value) {
        if($value==null) $value = "";
        return "<div class='form-group'>
                    <input class='form-control' type='text' placeholder='First name' value='$value' name='lastName'>
                </div>";
    }
    private function createEmailInput($value) {
        if($value==null) $value = "";
        return "<div class='form-group'>
                    <input class='form-control' type='text' placeholder='Email' value='$value' name='email'>
                </div>";
    }
    private function createPasswordInput($name, $placeholder) {
        if($name==null) $value = "";
        return "<div class='form-group'>
                    <input class='form-control' type='password' placeholder='$placeholder' name='$name'>
                </div>";
    }
    private function createSaveDetailsButton()
    {
        return "<button type='submit' class='btn btn-primary' name='saveDetailsButton'>Save</button>";
    }
    private function createSavePasswordButton()
    {
        return "<button type='submit' class='btn btn-primary' name='savePasswordButton'>Save</button>";
    }
}

?>