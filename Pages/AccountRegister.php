<?php
ob_start();
require_once ('lib/PageTemplate.php');
require_once ('functions/auth.php');
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

$message = "";
$username = "";
$password = "";
$passwordAgain = "";
$name = "";
$street = "";
$postcode = "";
$city = "";





if (isset($_POST['create'])) {

    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $passwordAgain = $_POST['passwordAgain'] ?? '';
    $name = $_POST['name'] ?? '';
    $street = $_POST['street'] ?? '';
    $postcode = $_POST['postcode'] ?? '';
    $city = $_POST['city'] ?? '';
    $message = "Could not create account";
    if ($password !== $passwordAgain) {
        $message = "password not match";
    } else if (!$password || !$passwordAgain || !$username || !$name || !$street || !$postcode || !$city) {
        $message = "empty fields";
    } else {

        $v->field('username')->required()->email()->min_val(1)->max_len(100);
        /*   $v->field('name')->required()->min_len(1)->max_len(200);
          $v->field('street')->required()->min_len(1)->max_len(150);
          $v->field('postcode')->required()->numeric()->min_val(1)->max_len(20);
          $v->field('city')->required()->alpha()->min_len(1)->max_len(100); */
        if ($v->is_valid()) {
            $userId = auth();


            if ($userId) {
                $id = intval($userId);
                $dbContext->createIfNotExisting($name, $street, $postcode, $city, $id);
                $message = 'Thank you for your registration, check your email and verify your account';
            }


        }
    }








}
?>
<p>
<div class="row">

    <div class="row">
        <div class="col-md-12">
            <div class="newsletter">
                <p>User<strong>&nbsp;REGISTER</strong></p>
                <form method="POST">
                    <input class="input" type="email" name="username" placeholder="Enter Your Email">
                    <br />
                    <br />
                    <input class="input" type="password" name="password" placeholder="Enter Your Password">
                    <br />
                    <br />
                    <input class="input" type="password" name="passwordAgain" placeholder="Repeat Password">
                    <br />
                    <br />
                    <input class="input" type="text" name="name" placeholder="Enter Your Name">
                    <br />
                    <br />
                    <input class="input" name="street" placeholder="Enter Your Streetaddress">
                    <br />
                    <br />
                    <input class="input" type="postal" name="postcode" placeholder="Enter Your Postal code">
                    <br />
                    <br />
                    <input class="input" type="text" name="city" placeholder="Enter Your City">
                    <br />
                    <br />
                    <button class="newsletter-btn" type="submit" name="create"><i class="fa fa-envelope"></i>
                        Register</button>
                </form>


                <p><?php echo "$message"; ?></p>

            </div>
        </div>
    </div>


</div>


</p>