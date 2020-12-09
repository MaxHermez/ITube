<?php 
require_once("includes/config.php"); 
require_once("includes/classes/FormSanitizer.php"); 
require_once("includes/classes/Constants.php"); 
require_once("includes/classes/Account.php"); 

$account = new Account($con);

if(isset($_POST["submitButton"])) {
    $firstName = FormSanitizer::sanitizeFormString($_POST["firstName"]);
    $lastname = FormSanitizer::sanitizeFormString($_POST["lastName"]);

    $username = FormSanitizer::sanitizeFormUsername($_POST["username"]);

    $email = FormSanitizer::sanitizeFormEmail($_POST["email"]);
    $emailc = FormSanitizer::sanitizeFormEmail($_POST["emailc"]);

    $password = FormSanitizer::sanitizeFormPassword($_POST["password"]);
    $passwordc = FormSanitizer::sanitizeFormPassword($_POST["passwordc"]);

    $account->register($firstName,$lastname,$username,$email,$emailc,$password,$passwordc);
    
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>ITube</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
    <script src="assets/js/commonActions.js"></script>
</head>
<body>
    <div class="signInContainer">
        <div class="column">
            <div class="header">
                <a class="logoContainer" href="index.php">
                    <img src="assets/images/icons/ITubeLogo.png" title="Home" alt="Site logo">
                </a>
                <hr>
                <h3>Sign Up</h3>
                <span>to continue to ITube</span>
            </div>
            <div class="loginForm">
                <form action="signUp.php" method="POST">
                    <?php echo $account->getError(Constants::$nameLength); ?>
                    <input type="text" name="firstName" placeholder="First Name" autocomplete="off" required>
                    <?php echo $account->getError(Constants::$nameLength); ?>
                    <input type="text" name="lastName" placeholder="Last Name" autocomplete="off" required>
                    <?php echo $account->getError(Constants::$usernameLength); ?>
                    <?php echo $account->getError(Constants::$usernameTaken); ?>
                    <input type="text" name="username" placeholder="Username" autocomplete="off" required>
                    
                    <input type="email" name="email" placeholder="Your Email" require>
                    <input type="email" name="emailc" placeholder="Confirm Email" autocomplete="off" required>

                    <input type="password" name="password" placeholder="New Password" autocomplete="off" required>
                    <input type="password" name="passwordc" placeholder="Confirm Password" autocomplete="off" required>
                    
                    <input type="submit" name="submitButton" value="Sign up">
                </form>
            </div>
            <a class="signInMessage" href="signIn.php">Already have an account? Sign in here!</a>
        </div>
    </div>
</body>
</html>