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

    if ($password !== $passwordAgain) {
        $message = "password not match";
    } else if (!$password || !$passwordAgain || !$username || !$name || !$street || !$postcode || !$city) {
        $message = "empty fields";
    } else {

        $v->field('username')->required()->email()->min_val(1)->max_len(100);
        /*   $v->field('name')->required()->alpha()->min_val(1)->max_len(200);
          $v->field('street')->required()->alpha()->min_val(1)->max_len(150);
          $v->field('postcode')->required()->numeric()->min_val(1)->max_len(20);
          $v->field('city')->required()->alpha()->min_val(1)->max_len(100); */
        if ($v->is_valid()) {
            $message = auth();


            if ($message === 'Tack för din registerinbg, kolla mailet och verifiera ditt konto') {

                $dbContext->createIfNotExisting($username, $name, $street, $postcode, $city);


            }





        } else {
            $message = "Could not create account";
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
                    <input class="input" type="text" name="name" placeholder="Name">
                    <br />
                    <br />
                    <input class="input" name="street" placeholder="Street address">
                    <br />
                    <br />
                    <input class="input" type="postal" name="postcode" placeholder="Postal code">
                    <br />
                    <br />
                    <input class="input" type="text" name="city" placeholder="City">
                    <br />
                    <br />
                    <button class="newsletter-btn" type="submit" name="create"><i class="fa fa-envelope"></i>
                        Register</button>
                </form>


                <p><?php echo " $message"; ?></p>

            </div>
        </div>
    </div>


</div>


</p>