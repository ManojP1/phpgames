<?php
require_once "something.php";
class GameStat {
    private $games = array();
    private $userName;
    private $gameNames = array('Guess Game','15 Puzzle','Peg Solitaire','Mastermind');//list of game names, add more after updating schema
    public function __construct($userName) {
        global $dbconn;
        foreach ($this->gameNames as $game){
            $this->games[$game] = array(0,0,0,0);
        }
        $this->userName = $userName;
        //query to pull the number of user wins and the total number of times the user played each game 
        $query = "SELECT gameName, COUNT(CASE WHEN outcome = 'win' THEN 1 END) as wins, COUNT(outcome) as total
                    FROM game WHERE username=$1 GROUP BY gameName;";
        $result = pg_prepare($dbconn, "", $query);
        $result = pg_execute($dbconn, "", array($userName));
        while($row = pg_fetch_array($result, NULL, PGSQL_ASSOC)){
            
            $this->games[$row["gamename"]][0] = $row["wins"];
            $this->games[$row["gamename"]][1] = $row["total"];
        } 
        //query to pull the number of all wins and the total number of times everyone played for each game
        $result = pg_query($dbconn, "SELECT gameName, COUNT(CASE WHEN outcome = 'win' THEN 1 END) as wins, COUNT(outcome) as total
        FROM game GROUP BY gameName;");
        while($row = pg_fetch_array($result, NULL, PGSQL_ASSOC)){
            $this->games[$row["gamename"]][2] = $row["wins"];
            $this->games[$row["gamename"]][3] = $row["total"];
        }
    }
    //return the array containing the games and their stats
    public function get_games(){
        return $this->games;
    }
}