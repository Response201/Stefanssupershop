<?php


function loginSession()
{
    require 'vendor/autoload.php';
    require_once ('Models/Database.php');
    $dbContext = new DBContext();
    date_default_timezone_set('Europe/Stockholm');
    setlocale(LC_TIME, 'sv_SE');

    $timeStamp = date("Y-m-d H:i:s");
    $ip = $dbContext->getUsersDatabase()->getAuth()->getIpAddress();
    $id = $dbContext->getUsersDatabase()->getAuth()->getUserId();
    if ($timeStamp && $ip && $id) {
        $dbContext->createloginAttempts($id, $timeStamp, $ip);

    }

}
?>