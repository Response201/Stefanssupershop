<?php
require 'vendor/autoload.php';
require_once ('Models/Database.php');
require_once ('functions/mailer.php');
function getResetPasswordToken()
{
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION['reset_password_session'] = time();
    $dbContext = new DbContext();
    $username = $_POST['username'] ?? '';
    $selector = $_SESSION['reset_selector'] ?? '';
    $token = $_SESSION['reset_token'] ?? '';
    if (!$selector && !$token) {
        try {
            $dbContext->getUsersDatabase()->getAuth()->forgotPassword($username, function ($selector, $token) {
                $_SESSION['reset_token'] = $token;
                $_SESSION['reset_selector'] = $selector;
            });
        } catch (Exception $e) {
            return "Something went wrong" . $e->getMessage();
        }
    }
    $subject = "Reset password";
    $url = 'http://localhost:8000/reset_password?selector=' . urlencode($_SESSION['reset_selector']) . '&token=' . urlencode($_SESSION['reset_token']);
    $body = "<i>Hej, klicka på <a href='$url'>$url</a></i> för att skapa ett nytt lösenord";
    mailer($selector, $token, $subject, $url, $body);
    return 'You have now received an email to reset your password';
}
