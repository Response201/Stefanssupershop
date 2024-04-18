<?php
require 'vendor/autoload.php';
require_once ('Models/Database.php');
$dbContext = new DBContext();
$message = '';
    try {
        $dbContext->getUsersDatabase()->getAuth()->confirmEmail($_GET['selector'], $_GET['token']);
        $message = 'Your account is now verified';
    } catch (Delight\Auth\AuthException $e) {
        $message = 'Something went wrong during verification';
    }
    header("Location:/login?message=$message");
?>