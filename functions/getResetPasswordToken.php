<?php
require 'vendor/autoload.php';
require_once ('Models/Database.php');


function getResetPasswordToken()
{


    $dbContext = new DbContext();

    $username = $_POST['username'] ?? '';


    try {
        $dbContext->getUsersDatabase()->getAuth()->forgotPassword($username, function ($selector, $token) {
            $smtphost = $_ENV['smtphost'] ?? '';
            $smtpport = $_ENV['smtpport'];
            $smtpusername = $_ENV['smtpusername'];
            $smtppassword = $_ENV['smtppassword'];
            $smtpsecure = $_ENV['smtpsecure'];


            $mail = new PHPMailer\PHPMailer\PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = $smtphost;
            $mail->SMTPAuth = true;
            $mail->Username = $smtpusername;
            $mail->Password = $smtppassword;
            $mail->SMTPSecure = $smtpsecure;
            $mail->Port = $smtpport;

            $mail->From = "hello@superdupershop.com";
            $mail->FromName = "Hello"; //To address and name 
            $mail->addAddress($_POST['username']); //Address to which recipient will reply 
            $mail->addReplyTo("noreply@superdupershop.com", "No-Reply"); //CC and BCC 
            $mail->isHTML(true);
            $mail->Subject = "Reset password";
            $url = 'http://localhost:8000/reset_password?selector=' . \urlencode($selector) . '&token=' . \urlencode($token);


            $mail->Body = "<i>Hej, klicka på <a href='$url'>$url</a></i> för att skapa ett nytt lösenord";
            $mail->send();

        });




        return 'You have now received an email to reset your password';

    } catch (Exception $e) {

        return "Something went wrong";


    }




}



