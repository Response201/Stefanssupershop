<?php
require 'vendor/autoload.php';
require_once ('Models/Database.php');
require_once ('Models/Database.php');
require_once ('functions/mailer.php');
function auth()
{
    $dbContext = new DbContext();
    if (isset($_POST['create'])) {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $existingUsers = $dbContext->getUserByUsername($username);
        if ($existingUsers) {
            return ;
        }
        try {
            $userId = $dbContext->getUsersDatabase()->getAuth()->register($username, $password, $username, function ($selector, $token) {
               
                $subject = "Registrering";
                $urlIn = 'http://localhost:8000/verify_email?selector=' . \urlencode($selector) . '&token=' . \urlencode($token);
                $body = "<body style=\"width:99%; height:99%; background-color:#E4E7ED; display:flex; flex-direction:column; justify-content:center; align-items:center\">
                <h1> Welcome to Stefans supershop </h1>
               <a href='$urlIn'> 
               <button style=\"width:fit-content; height:40px; background-color:#D10024; font-weight: 800; border-radius:20px; padding:0 10px; color:#E4E7ED; border:none;\">
               verify your account 
               </button>
               </a>
              </body>";
                mailer($selector, $token, $subject, $urlIn, $body);
            });
            return $userId;
        } catch (Exception $e) {
            return ;
        }
    }
}