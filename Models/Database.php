<?php
require_once ('Models/Userdata.php');
require_once ('Models/UserDatabase.php');
class DBContext
{
    private $pdo;
    private $usersDatabase;
    function getUsersDatabase()
    {
        return $this->usersDatabase;
    }
    function __construct()
    {
        $host = $_ENV['host'];
        $db = $_ENV['db'];
        $user = $_ENV['user'];
        $pass = $_ENV['pass'];
        $dsn = "mysql:host=$host;dbname=$db";
        $this->pdo = new PDO($dsn, $user, $pass);
        $this->usersDatabase = new UserDatabase($this->pdo);
        $this->initIfNotInitialized();

    }



    function getUser($username)
    {
        $sql = "SELECT * FROM usersdata where username=:username";
        $prep = $this->pdo->prepare($sql);
        $prep->setFetchMode(PDO::FETCH_CLASS, 'Userdata');
        $prep->execute(['username' => $username]);
        return $prep->fetch();
    }

    function getUserId($username)
    {
        $sql = "SELECT * FROM users where username=:username";
        $prep = $this->pdo->prepare($sql);
        $prep->execute(['username' => $username]);
        return $prep->fetch();

    }


    function createIfNotExisting($username, $name, $street, $postcode, $city)
    {
        $existing = $this->getUser($username);
        $existingId = $this->getUserId($username);
        if ($existing && $existingId) {
            return;
        };

        return $this->addUser($username, $name, $street, $postcode, $city );
    }



    function addUser($username, $name, $street, $postcode, $city)
  
    {

$postC = intval( $postcode);

        $prep = $this->pdo->prepare('INSERT INTO usersdata (username, fullname, street, postcode, city) VALUES(:username, :fullname, :street, :postcode, :city)');
        $prep->execute(['username' => $username, 'fullname' => $name, 'street' => $street, 'postcode' => $postC , 'city' => $city]);
        return $this->pdo->lastInsertId();
    }


    /* skapa databas */
    function initIfNotInitialized()
    {
        static $initialized = false;
        if ($initialized)
            return;

        $this->usersDatabase->setupUsers();

        $sql = 'CREATE TABLE IF NOT EXISTS `usersdata` (
            `username` varchar(249)  NOT NULL,
            `fullname` varchar(200) NOT NULL,
            `street` varchar(150) NOT NULL,
            `postcode` varchar(20) NOT NULL,
            `city` varchar(100) NOT NULL,
            PRIMARY KEY (`username`)
           
        )';
        $this->pdo->exec($sql);

        $initialized = true;
    }
}
?>