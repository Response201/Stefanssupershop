<?php
require 'vendor/autoload.php';
require_once ('Models/Database.php');


function auth()
{


    $dbContext = new DbContext();



    if (isset($_POST['create'])) {
        $username = $_POST['username']?? '';
        $password = $_POST['password']?? ''; 

    try{
        $userId = $dbContext->getUsersDatabase()->getAuth()->register($username, $password, $username, function ($selector, $token) {
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
$mail->Password =  $smtppassword;
$mail->SMTPSecure =  $smtpsecure;
$mail->Port = $smtpport;

            $mail->From = "hello@superdupershop.com"; 
            $mail->FromName = "Hello"; //To address and name 
            $mail->addAddress($_POST['username']); //Address to which recipient will reply 
            $mail->addReplyTo("noreply@superdupershop.com", "No-Reply"); //CC and BCC 
            $mail->isHTML(true); 
            $mail->Subject = "Registrering"; 
            $url = 'http://localhost:8000/verify_email?selector=' . \urlencode($selector) . '&token=' . \urlencode($token);   
            
 
            $mail->Body = "<i>Hej, klicka på <a href='$url'>$url</a></i> för att verifiera ditt konto";
            $mail->send();

        });

         
       
       
        return 'Tack för din registerinbg, kolla mailet och verifiera ditt konto';
     
}
    catch(Exception $e){
     
        return "Error";
      
        
    }




}



 }