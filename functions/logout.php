<?php
require_once ('Models/Database.php');
$dbContext = new DBContext();
$dbContext->getUsersDatabase()->getAuth()->logOut();
header('Location: /');
exit;
?>