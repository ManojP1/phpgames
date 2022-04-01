<?php
require_once "something.php";

class GuessGame extends Games {
	public $secretNumber = 5;
	public $history = array();

	public function __construct($userName) {
        parent::__construct($userName, "Guess Game");
		$this->secretNumber = rand(1,10);
	}
	
	public function makeGuess($guess){
		$this->numMoves++;
		if($guess>$this->secretNumber){
			$this->state="too high";
		} else if($guess<$this->secretNumber){
			$this->state="too low";
		} else {
			$this->state="correct";
		}
		$this->history[] = "Guess #$this->numMoves was $guess and was $this->state.";
	}
}
?>
