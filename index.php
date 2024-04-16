<?php
// globala initieringar !
require_once (dirname(__FILE__) . "/Utils/Router.php");
require_once ("vendor/autoload.php");



$router = new Router();


$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();



$router->addRoute('/', function () {
    require __DIR__ . '/Pages/index.php';
});



$router->addRoute('/register', function () {
    require __DIR__ . '/Pages/AccountRegister.php';
});


$router->addRoute('/verify_email', function () {
    require __DIR__ . '/functions/verify.php';
});

$router->addRoute('/login', function () {
    require __DIR__ . '/Pages/AccountLogin.php';
});

$router->addRoute('/logout', function () {
    require __DIR__ . '/functions/logout.php';
});





$router->dispatch();
?>