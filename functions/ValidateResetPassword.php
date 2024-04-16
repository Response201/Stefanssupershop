<?php

require 'vendor/autoload.php';
require_once ('Models/Database.php');
$dbContext = new DBContext();

$message = '';

    try {
        $dbContext->getUsersDatabase()->getAuth()->canResetPasswordOrThrow($_GET['selector'], $_GET['token']);
        $selector=$_GET['selector'];
        $token= $_GET['token'];
        header("Location:/resetpassword?message=$message&selector=$selector&token=$token");
    } catch (Delight\Auth\AuthException $e) {
        $message = 'Cant reset your password';
        header("Location:/lostpassword?message=$message");

       

    }




?>