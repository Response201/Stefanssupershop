<?php
ob_start();
require_once ('lib/PageTemplate.php');
require_once ('functions/auth.php');
require_once ("Utils/Validator.php");
$dbContext = new DBContext();
$v = new Validator($_POST);
# trick to execute 1st time, but not 2nd so you don't have an inf loop
if (!isset($TPL)) {
    $TPL = new PageTemplate();
    $TPL->PageTitle = "Regsier";
    $TPL->ContentBody = __FILE__;
    include "layout.php";
    exit;

}

$message = $_GET['message'] ?? '';

if (isset($_POST['newPassword'])) {
    $password = $_POST['password'] ?? '';
    $passwordAgain = $_POST['passwordAgain'] ?? '';


    if (!$password || !$passwordAgain) {

        $message = 'Empty fields';
    }


    if ($password !== $passwordAgain) {

        $message = 'Passwords dont match';
    }

    if ($password === $passwordAgain) {
        $selector = $_GET['selector'];
        $token = $_GET['token'];


        try {
            $dbContext->getUsersDatabase()->getAuth()->resetPassword($selector, $token, $_POST['password']);
            $message = "Your password has been updated";
            $_SESSION['reset_selector'] = '';
             $_SESSION['reset_token'] = '';
            header("Location:/login?message=$message");

        } catch (\Delight\Auth\ResetDisabledException $e) {
            $message = 'Password reset is disabled';
            die('Password reset is disabled');
        }




    }
}



$time = $_GET['time'];





?>
<p>
<div class="row">

    <div class="row">
        <div class="col-md-12">
            <div class="newsletter">
                <p>Reset<strong>&nbsp;PASSWORD</strong></p>
                <form method="POST">
                    <input class="input" type="password" name="password" placeholder="New Password">
                    <br />
                    <br />
                    <input class="input" type="password" name="passwordAgain" placeholder="Repeat Password">

                    <br />
                    <br />

                    <button class="newsletter-btn" type="submit" name="newPassword"><i class="fa fa-envelope"></i>
                        Send</button>
                </form>

                <p><?php echo " $time"; ?></p>
                <p><?php echo " $message"; ?></p>

            </div>
        </div>
    </div>


</div>


</p>