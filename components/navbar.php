<?php 
require_once ('Models/Database.php');
$dbContext = new DBContext();
$user = $dbContext->getUsersDatabase()->getAuth()->isLoggedIn();
$username = $dbContext->getUsersDatabase()->getAuth()->getUserId();
$name = $dbContext->getUser($username);

if ($user && $name) {
    echo " 
<li class=\"nav-item\"> 
<a  class=\"nav-link text-dark\" href=\"\" title=\"Manage\">
Hello $name->fullname!
</a>
</li>
<li class=\"nav-item\">
<a  class=\"nav-link text-dark\" href=\"/logout\" title=\"Manage\">
Logout
</a>
</li>";
} else {
    echo " 
        <li class=\"nav-item\">
        <a class=\"nav-link text-dark\" href=\"/register\">Register</a>
    </li>
    <li class=\"nav-item\">
        <a class=\"nav-link text-dark\" href=\"/login\">Login</a>
    </li>
        ";
}
?>