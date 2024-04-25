<?php
ob_start();
require_once ('lib/PageTemplate.php');
require_once ('functions/auth.php');
require_once ('functions/showError.php');
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
$usernameError = '';
$passwordError = '';
$nameError = '';
$streetError = '';
$postcodeError = '';
$cityError = '';
$message = "";



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
        $v->field('password')->required()->match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{6,}$/");
        $v->field('name')->required()->alpha([' '])->min_val(1)->max_len(100);
        $v->field('street')->required()->alpha_num([' '])->min_val(1)->max_len(150);
        $v->field('postcode')->required()->numeric([' ']);
        $v->field('city')->required()->alpha([' '])->min_len(2)->max_len(100);

        if ($v->is_valid()) {
            $userId = auth();
            if ($userId) {
                $id = intval($userId);
                $dbContext->createIfNotExisting($name, $street, $postcode, $city, $id);
                $message = 'Thank you for your registration, check your email and verify your account';
            }

        } else {
            $usernameError = $v->get_error_message('username') ?? '';
            $passwordError = $v->get_error_message('password') ?? '';
            $nameError = $v->get_error_message('name') ?? '';
            $streetError = $v->get_error_message('street') ?? '';
            $postcodeError = $v->get_error_message('postcode') ?? '';
            $cityError = $v->get_error_message('city') ?? '';
            $message = "Somthing went wrong ";



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
                    <?php showError($usernameError); ?>
                    <input class="input" type="password" name="password" placeholder="Enter Your Password">
                    <br />
                    <label class='input'
                        style="border:none; font-weight: 100; font-size: 10px; text-align: left;  ">minimum six
                        characters, at least one uppercase letter and one special character
                    </label>
                    <br />
                    <input class="input" type="password" name="passwordAgain" placeholder="Repeat Password">
                    <?php showError($passwordError); ?>
                    <input class="input" type="text" name="name" placeholder="Enter Your Name">
                    <?php showError($nameError); ?>
                    <input class="input" type="text" name="street" placeholder="Enter Your Streetaddress">
                    <?php showError($streetError); ?>
                    <input class="input" type="numric" name="postcode" placeholder="Enter Your Postal code">
                    <?php showError($postcodeError); ?>
                    <input class="input" type="text" name="city" placeholder="Enter Your City">
                    <?php showError($cityError); ?>
                    <button class="newsletter-btn" type="submit" name="create"><i class="fa fa-envelope "></i>
                        Register</button>
                </form>
                <p><?php echo "$message"; ?></p>
            </div>
        </div>
    </div>
</div>
</p>