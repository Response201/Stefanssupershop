<?php
ob_start();
require_once ('lib/PageTemplate.php');
require_once ('functions/getResetPasswordToken.php');
require_once ("Utils/Validator.php");
$dbContext = new DBContext();
$v = new Validator($_POST);
# trick to execute 1st time, but not 2nd so you don't have an inf loop
if (!isset($TPL)) {
    $TPL = new PageTemplate();
    $TPL->PageTitle = "Regsier";
    $TPL->ContentBody = __FILE__;
    include "layout.php";
    exit;
}
$message = $_GET['message'] ?? '';

if (isset($_POST['lostPassword'])) {
   
    $message = getResetPasswordToken();
}
?>
<p>
<div class="row">
    <div class="row">
        <div class="col-md-12">
            <div class="newsletter">
                <p>Lost<strong>&nbsp;PASSWORD</strong></p>
                <form method="POST">
                    <input class="input" type="email" name="username" placeholder="Enter Your Email">
                    <br />
                    <br />
                    <button class="newsletter-btn" type="submit" name="lostPassword"><i class="fa fa-envelope"></i>
                        Send</button>
                </form>
                <p><?php echo "$message"; ?></p>
            </div>
        </div>
    </div>
</div>
</p>