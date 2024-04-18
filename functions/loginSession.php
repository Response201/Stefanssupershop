<?php


function loginSession(){
    require 'vendor/autoload.php';
    require_once ('Models/Database.php');
    $dbContext = new DBContext();
       
        $timeStamp = time();
        $ip = $dbContext->getUsersDatabase()->getAuth()->getIpAddress();
        $id = $dbContext->getUsersDatabase()->getAuth()->getUserId();
        if ($timeStamp && $ip && $id) {
            $dbContext->createloginAttempts($id, $timeStamp, $ip);
       
    }
     
}
?>