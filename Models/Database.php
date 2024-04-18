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
    function getUser($userId)
    {
        $sql = "SELECT * FROM usersdata where userId=:userId";
        $prep = $this->pdo->prepare($sql);
        $prep->setFetchMode(PDO::FETCH_CLASS, 'Userdata');
        $prep->execute(['userId' => $userId]);
        return $prep->fetch();
    }
    function getUserByUsername($username)
    {
        $sql = "SELECT * FROM users where username=:username";
        $prep = $this->pdo->prepare($sql);
        $prep->execute(['username' => $username]);
        return $prep->fetch();
    }
    function createIfNotExisting($name, $street, $postcode, $city, $userId)
    {
        $existingUsersdata = $this->getUser($userId);
        if ($existingUsersdata) {
            return;
        } else {
            return $this->addUser($name, $street, $postcode, $city, $userId);
        }
    }
    function addUser($name, $street, $postcode, $city, $userId)
    {
        $postCode = intval($postcode);
        $id = intval($userId);
        $prep = $this->pdo->prepare('INSERT INTO usersdata ( fullname, street, postcode, city, userId) VALUES( :fullname, :street, :postcode, :city, :userId)');
        $prep->execute(['fullname' => $name, 'street' => $street, 'postcode' => $postCode, 'city' => $city, 'userId' => $id]);
        return $this->pdo->lastInsertId();
    }





    /* loggar alla lyckade inloggningar av användare */


    function getaddLoginSession($userId, $timeStamp)
    {

        $sql = "SELECT * FROM LoginSession where userId=:userId AND timeStamp=:timeStamp";
        $prep = $this->pdo->prepare($sql);
        $prep->execute(['userId' => $userId, 'timeStamp' => $timeStamp]);
        return $prep->fetch();


    }

    function addLoginSession($userId, $timeStamp, $ip)
    {

        $prep = $this->pdo->prepare('INSERT INTO LoginSession ( userId, timeStamp, ip) VALUES(:userId, :timeStamp, :ip)');
        $prep->execute(['userId' => $userId, 'timeStamp' => $timeStamp, 'ip' => $ip]);

    }



    function createloginAttempts($userId, $timeStamp, $ip)
    {

        $existingUsersdata = $this->getaddLoginSession($userId, $timeStamp);
        if ($existingUsersdata) {
            return;
        } else {
            return $this->addLoginSession($userId, $timeStamp, $ip);
        }
    }









    /* skapa databas */
    function initIfNotInitialized()
    {
        static $initialized = false;
        if ($initialized) {
            return;
        }
        $this->usersDatabase->setupUsers();
        $sql = 'CREATE TABLE IF NOT EXISTS `usersdata`(
            `fullname` varchar(200) NOT NULL,
            `street` varchar(150) NOT NULL,
            `postcode` varchar(20) NOT NULL,
            `city` varchar(100) NOT NULL,
            `userId` varchar(10) NOT NULL,
            PRIMARY KEY (`userId`)
        )';
        $this->pdo->exec($sql);



        $sql = 'CREATE TABLE IF NOT EXISTS `LoginSession`(
            `userId` varchar(10) NOT NULL,
            `ip` varchar(45) NOT NULL,
            `timeStamp` varchar(100)  NOT NULL,
            INDEX (`userId`),
            PRIMARY KEY ( `timeStamp`),
            FOREIGN KEY (`userId`) REFERENCES `usersdata`(`userId`)
        )';
        $this->pdo->exec($sql);




        $initialized = true;
    }
}
?>