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
        $prep->execute(['userId' =>  $userId]);
        return $prep->fetch();
    }

    function getUserByUsername($username)
    {
       
        $sql = "SELECT * FROM users where username=:username";
        $prep = $this->pdo->prepare($sql);
        $prep->execute(['username' =>  $username]);
        return $prep->fetch();

    }


    function createIfNotExisting($name, $street, $postcode, $city, $userId)
    {

        $existingUsersdata = $this->getUser($userId);
      
        if ($existingUsersdata) {
            return;
        }else{

            return $this->addUser( $name, $street, $postcode, $city, $userId );
        }

       
    }



    function addUser( $name, $street, $postcode, $city,  $userId)
  
    {

$postCode = intval($postcode);
$id = intval($userId);
        $prep = $this->pdo->prepare('INSERT INTO usersdata ( fullname, street, postcode, city, userId) VALUES( :fullname, :street, :postcode, :city, :userId)');
        $prep->execute([ 'fullname' => $name, 'street' => $street, 'postcode' => $postCode , 'city' => $city, 'userId' => $id]);
        return $this->pdo->lastInsertId();
    }


    /* skapa databas */
    function initIfNotInitialized()
    {
        static $initialized = false;
        if ($initialized){
            return;
}
        $this->usersDatabase->setupUsers();

        $sql ='CREATE TABLE IF NOT EXISTS `usersdata`(
            `fullname` varchar(200) NOT NULL,
            `street` varchar(150) NOT NULL,
            `postcode` varchar(20) NOT NULL,
            `city` varchar(100) NOT NULL,
            `userId` varchar(10) NOT NULL,
            PRIMARY KEY (`userId`)
           
        )';
        $this->pdo->exec($sql);

        $initialized = true;
    }
}
 
?>