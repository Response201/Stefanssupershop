<?php
ob_start();
require 'vendor/autoload.php';
require_once ('Models/Database.php');
require_once ('lib/PageTemplate.php');


$dbContext = new DBContext();
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';
$message = $_GET['message'] ?? '';






# trick to execute 1st time, but not 2nd so you don't have an inf loop
if (!isset($TPL)) {
    $TPL = new PageTemplate();
    $TPL->PageTitle = "Login";
    $TPL->ContentBody = __FILE__;
    include "layout.php";
    exit;
}

if (isset($_POST['login'])) {
    try {
        $dbContext->getUsersDatabase()->getAuth()->login($username, $password);
        header('Location: /');
        exit;
    } catch (Exception $e) {
        $message = "Could not login";
    }
}

?>
<p>
<div class="row">

    <div class="row">
        <div class="col-md-12">
            <div class="newsletter">
                <p>User<strong>&nbsp;LOGIN</strong></p>

                <p> <?php echo "$message"; ?> </p>
                <form method="POST">
                    <input class="input" type="email" name="username" placeholder="Enter Your Email">
                    <br />
                    <br />
                    <input class="input" type="password" name="password" placeholder="Enter Your Password">
                    <br />
                    <br />
                    <button class="newsletter-btn" type="submit" name="login"><i class="fa fa-envelope"></i>
                        Login</button>
                </form>
                <a href="/lostpassword">Lost password?</a>
            </div>
        </div>
    </div>


</div>


</p>