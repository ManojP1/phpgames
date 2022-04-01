<?php
require_once "lib/lib.php";

class User {
    private $userName;
    private $pwdHash;
    public $errors = array();
    private $skill;
    private $color;
    private $gamesPref = array();
    private $dbconn;

	/*public function __construct($userName, $db) {
        $dbconn = $db;
        $this->userName = $userName;
        $query = "SELECT *, array_to_json(gamesPref) as prefArr  FROM appuser WHERE username=$1;";
        $result = pg_prepare($dbconn, "", $query);
        $result = pg_execute($dbconn, "", array($userName));
        if($row = pg_fetch_array($result, NULL, PGSQL_ASSOC)){
            $this->pwdHash = $row["pwdHash"];
            $this->skill = $row["skill"];
            $this->gamesPref = json_decode($row["prefArr"]);    
            $this->color = $row["color"];

        }           
    }*/

    public function __construct(){
    }

    
    public function login($user){ //returns a password given a username from the database
        global $dbconn;
        $query = "SELECT password FROM appuser WHERE userid=$1;";
		$result = pg_prepare($dbconn, "", $query);
		$result = pg_execute($dbconn, "", array($user));
		if($row = pg_fetch_array($result, NULL, PGSQL_ASSOC)){
            return $row['password'];
		} else{
			return "Invalid Username";
		}
    }

    public function isTaken($user){ //checks if given username is a duplicate
        global $dbconn;
        $query = "SELECT user FROM appuser WHERE userid=$1;";
        $result = pg_prepare($dbconn, "", $query);
		$result = pg_execute($dbconn, "", array($user));
		if($row = pg_fetch_array($result, NULL, PGSQL_ASSOC)){
            return true;
		} else{
			return false;
		}

    }
    public function register($user,$pwd,$skill, $gameprefs, $colour){ //registers a new user in in the database with the given user input
        global $dbconn;
		$gameprefq= '{'.implode(',',$gameprefs).'}'; //combines gameprefs array into string suitable for insertion into psql
		$phash = password_hash($pwd,PASSWORD_BCRYPT); //password_hash salts and hashes the given password for storage in the database
		$query = "INSERT INTO appuser (userid, password, skill, gameprefs, favcolour) values($1, $2, $3, $4, $5);";
		$result = pg_prepare($dbconn,"",$query);
		$result = pg_execute($dbconn,"", array($user, $phash, $skill, $gameprefq, $colour));
        //check if add success
        if($row = pg_fetch_array($result, NULL, PGSQL_ASSOC)){
        } else {
            $errors[]="invalid registration";
        }
    }

    public function updateProfile($user, $pwd, $skill, $gameprefs, $colour){ //updates existing user profile in database with new information
        global $dbconn;
        $gameprefq='{'.implode(',',$gameprefs).'}';
        $phash = password_hash($pwd, PASSWORD_BCRYPT);
        $query = "UPDATE appuser SET userid=$1, password=$2, skill=$3, gameprefs=$4, favcolour=$5 WHERE userid=$1;";
        $result = pg_prepare($dbconn,"",$query);
		$result = pg_execute($dbconn,"", array($user, $phash, $skill, $gameprefq, $colour));
    }
    
    public function getProfile($user){ //returns profile information of existing user for display in the user profile
        global $dbconn;
        $query = "SELECT *, array_to_json(gameprefs) AS gamepref FROM appuser WHERE userid=$1;";
		$result = pg_prepare($dbconn, "", $query);
		$result = pg_execute($dbconn, "", array($user));
		if($row = pg_fetch_array($result, NULL, PGSQL_ASSOC)){
			return $row;
        }
        
    }
}
?>