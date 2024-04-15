<?php
ob_start();
require 'vendor/autoload.php';
require_once ('Models/Database.php');

$dbContext = new DBContext();
$selector = $_GET['selector'] ?? '';
$message = '';






if ($selector) {
    try {
        $dbContext->getUsersDatabase()->getAuth()->confirmEmail($_GET['selector'], $_GET['token']);
        $message = 'Email address has been verified';

        header('Location:/login?selector=true');







    } catch (\Delight\Auth\InvalidSelectorTokenPairException $e) {
        $message = 'Något gick fel';
    } catch (\Delight\Auth\TokenExpiredException $e) {
        $message = 'Något gick fel';
    } catch (\Delight\Auth\UserAlreadyExistsException $e) {
        $message = 'Något gick fel';
    } catch (\Delight\Auth\TooManyRequestsException $e) {
        $message = 'Något gick fel';
    }


}
?>
<!DOCTYPE HTML>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Shop Homepage - Start Bootstrap Template</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap icons-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="/css/styles.css" rel="stylesheet" />
    <link href="/css/createUserForm.css" rel="stylesheet" />
</head>

<body>

    <article class="createUserFormContainer">
        <?php echo " $message"; ?>
    </article>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/activeBtns.js"></script>
</body>

</html>