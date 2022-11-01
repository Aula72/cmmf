<?php 
namespace Cmmf;
include_once "constants.php";
date_default_timezone_set('Africa/Nairobi');
class Database{
    private $conn;
    private $servername = DBHOST;
    private $username = DBUSER;
    private $password = DBPASS;
    private $dbname = DBNAME;

    public function connect(){     
        try {
          $this->conn = new \PDO("mysql:host=".$this->servername.";dbname=".$this->dbname, $this->username, $this->password);
          // set the PDO error mode to exception
          $this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
//          echo "Connected successfully";
        } catch(\PDOException $e) {
          die(json_encode(["error"=>"Connection failed: " . $e->getMessage()]));
        }
        return $this->conn;
    }
}