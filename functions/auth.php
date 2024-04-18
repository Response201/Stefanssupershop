<?php
require 'vendor/autoload.php';
require_once ('Models/Database.php');
require_once ('Models/Database.php');
require_once('functions/mailer.php');
function auth()
{


    $dbContext = new DbContext();



    if (isset($_POST['create'])) {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';



        $existingUsers = $dbContext->getUserByUsername($username);

        if ($existingUsers) {

            return;

        }

        try {
            $userId = $dbContext->getUsersDatabase()->getAuth()->register($username, $password, $username, function ($selector, $token) {



$subject = "Registrering";
$urlIn ='http://localhost:8000/verify_email?selector=' . \urlencode($selector) . '&token=' . \urlencode($token);
$body =  "<i>Hej, klicka på <a href='$urlIn'>$urlIn</a></i> för att verifiera ditt konto";
                mailer($selector, $token, $subject, $urlIn, $body);



            });




            return $userId;

        } catch (Exception $e) {

            return;


        }




    }



}