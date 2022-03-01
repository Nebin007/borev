<?php
include 'config/database.php';
class users{
    private $user_id;
    private $username;
    private $user_pass;
    private $user_email;
    private $register_date;
    private $name;
    private $surname;
    private $country;
    private $phone;
    private $ban = 0;
    private $suspension = 0;
    private $leveladmin = 0;
    private $user_level = 0;
    private $address;

    private $conn;
    private $stmt;
    
    function __construct(){
        $database = new database();
        $this->conn = $database->getConnection();
    }

    private function querymaker($qry){
        $this->stmt = $this->conn->prepare($qry);
        if($this->stmt->execute())
        {
            return true;
        }
        else{
            return false;
        }
    }
    private function existchecker($username,$email){
        $resarr = array();
        $this->querymaker("SELECT * FROM `users` WHERE username='".$username."'");
        $val = sizeof($this->stmt->fetchAll());
        array_push($resarr,$val);
        $this->querymaker("SELECT * FROM `users` WHERE user_email='".$email."'");
        $val = sizeof($this->stmt->fetchAll());
        array_push($resarr,$val);
        return $resarr;
    }

    public function registeruser($username,$email,$password,$name,$surname){
        $res = $this->existchecker($username,$email);
        if(array_sum($res)==0){
            $this->username = $username;
            $this->user_email = $email;
            $this->user_pass = $password;
            $this->name = $name;
            $this->surname = $surname;
            $qury = "INSERT INTO `users` VALUES(NULL,'".$this->username."','".$this->user_pass."','".$this->user_email."',
            CURRENT_TIMESTAMP,'".$this->name."','".$this->surname."',NULL,NULL,".$this->ban.",".$this->suspension.",".$this->leveladmin.",".$this->user_level.",NULL)";
            if($this->querymaker($qury)){
                echo("Successfully inserted");
            }
            else{
                echo("Problem occured");
            }
        }
        else if(array_sum($res)==2){
            echo("Username and Email already exists");
        }
        else{
            if($res[0]==1){
                echo("USername already exists");
                return false;
            }
            else if($res[1]==1){
                echo("Email already exists");
                return false;
            }
        }

    }

}
$usr = new users();
$usr->registeruser("aother","aother@yopmail","testman","kuthy","manam");

?>