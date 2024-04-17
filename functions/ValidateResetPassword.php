<?php
ob_start();
require 'vendor/autoload.php';
require_once ('Models/Database.php');

session_start();
$current_time = time();
$reset_time = $_SESSION['reset_password_session'];
/* defualt värde: 90000 (motsvarar 25 timmar), om en saktiv session saknas($reset_time) */
$time_difference = $current_time - $reset_time ?? 90000;
$hours_passed = $time_difference / (60 * 60);

setNewPasswordActivLink($hours_passed);
function setNewPasswordActivLink($hours_passed)
{
    $dbContext = new DBContext();
    $message = '';
    if ($hours_passed > 24) {
        try {
            $dbContext->getUsersDatabase()->getAuth()->canResetPasswordOrThrow($_GET['selector'], $_GET['token']);
            $selector = $_GET['selector'];
            $token = $_GET['token'];
            header("Location:/resetpassword?message=$message&selector=$selector&token=$token&time=$time_difference");
        } catch (Delight\Auth\AuthException $e) {
            $message = 'Cant reset your password';
            header("Location:/lostpassword?message=$message");
        }
    } else {
        $message = "The reset link has expired";
        header("Location:/lostpassword?message=$message");
        session_destroy();
    }

}
?>