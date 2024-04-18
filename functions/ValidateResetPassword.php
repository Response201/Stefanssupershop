<?php
ob_start();
require 'vendor/autoload.php';
require_once ('Models/Database.php');
session_start();
/* uträkning för att kontrollera att länken inte är äldre än 24 timmar */
$current_time = time();
$reset_time = $_SESSION['reset_password_session'];
$time_difference = $current_time - $reset_time;
$hours_passed = floor($time_difference / 3600);
setNewPasswordActivLink($hours_passed);
function setNewPasswordActivLink($hours_passed)
{
    $dbContext = new DBContext();
    $message = '';
    $selector = $_GET['selector'];
    $token = $_GET['token'];
    if ($hours_passed < 24) {
        try {
            $dbContext->getUsersDatabase()->getAuth()->canResetPasswordOrThrow($_GET['selector'], $_GET['token']);
            header("Location:/resetpassword?message=$message&selector=$selector&token=$token&time=$hours_passed");
        } catch (Delight\Auth\AuthException $e) {
            $message = 'Cant reset your password';
            header("Location:/lostpassword?message=$message");
        }
    } else {
        $message = "The reset link has expired $hours_passed";
        $selector = $_SESSION['reset_selector'];
        $token = $_SESSION['reset_token'];
        header("Location:/lostpassword?message=$message&selector=$selector&token=$token");
    }
}
?>