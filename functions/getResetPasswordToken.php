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
    $body = "<body style=\"width:99%; height:99%; background-color:#E4E7ED; display:flex; flex-direction:column; justify-content:center; align-items:center\">
    <h1> Create new password </h1>
   <a href='$url'> 
   <button style=\"width:fit-content; height:40px; background-color:#D10024; font-weight: 800; border-radius:20px; padding:0 10px; color:#E4E7ED; border:none; \">
   Click here!
   </button>
    </a>
  </body>";
    mailer($selector, $token, $subject, $url, $body);
    return 'You have now received an email to reset your password';
}
