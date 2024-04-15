<?php
ob_start();
require 'vendor/autoload.php';
require_once('Models/Database.php');
require_once('lib/PageTemplate.php');

# trick to execute 1st time, but not 2nd so you don't have an inf loop
if (!isset($TPL)) {
    $TPL = new PageTemplate();
    $TPL->PageTitle = "Login";
    $TPL->ContentBody = __FILE__;
    include "layout.php";
    exit;
}

$selector = $_GET['selector'] ?? false;
$message = '';
if( $selector ){

 
        $message = 'Email address has been verified';
        
  }



?>
<p>
<div class="row">

<div class="row">
                <div class="col-md-12">
                    <div class="newsletter">
                        <p>User<strong>&nbsp;LOGIN</strong></p>
 
                        <p> <?php echo "$message"; ?> </p>
                        <form>
                            <input class="input" type="email" placeholder="Enter Your Email">
                            <br/>
                            <br/>
                            <input class="input" type="password" placeholder="Enter Your Password">
                            <br/>
                            <br/>
                            <button class="newsletter-btn"><i class="fa fa-envelope"></i> Login</button>
                        </form>
                        <a href="">Lost password?</a>
                    </div>
                </div>
            </div>


</div>
    

</p>