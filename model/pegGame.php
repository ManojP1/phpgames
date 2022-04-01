<?php
require_once "something.php";

class PegGame extends Games{
	public $numMoves;
	public $board;
	public $moves = array(array(0,-2),array(-2,-2),array(-2,0),array(0,2),array(2,2),array(2,0)); //array representing possible moves from a peg at index i,j
	public $source;
	public $dest;

	//For the board: 0=empty, 1=filled, 2=selected

	public function __construct($userName) {
		parent::__construct($userName, "Peg Solitaire");
		$this->source=array(0,0);
		$this->dest=array(0,0);
		$this->board=array(array(1), array(1,1),array(1,1,1), array(1,1,1,1), array(1,1,1,1,1));
		$this->randomizeBoard(); //random peg set blank
		$this->history[] = "Choose a source peg.";
    }
	
	public function randomizeBoard(){
		$rand1=array_rand($this->board);
		$rand2=array_rand($this->board[$rand1]); 
		$this->board[$rand1][$rand2]=0;
	}

	public function isEmpty($i,$j) { //check if peg is in bounds is empty
    	if (array_key_exists($i, $this->board)){
        	if (array_key_exists($j, $this->board[$i])) {
            	if ($this->board[$i][$j]==0) {
                	return true;
            	}
        	}
    	}
    
    		return false;
	}

	public function isFilled ($i,$j) { //check if peg is in bounds and filled
    	if (array_key_exists($i, $this->board)){
        	if (array_key_exists($j, $this->board[$i])) {
            	if ($this->board[$i][$j]==1){
                	return true;
				}    
        	}
    	}
    
    		return false;
	}	

	public function checkWin(){ //if 2 or less pegs left, the player wins, else they lose
		$this->state=($this->getNumPegs() < 3) ? "win":"lose";
	}
	public function makeMove($i,$j,$desti, $destj){ //moves the peg at (i,j) to (desti,destj) if possible, 
		$movei=$desti-$i;
		$movej=$destj-$j;
		if (abs($movei) > 2 || abs($movej) > 2) { //prevents cross board movements
			return false;
		}
		if (!is_int($i+$movei/2) || !is_int($j+$movej/2)){ //case where move is only one peg over
			return false;
		}
    	if ($this->board[$i][$j]==2 && $this->isEmpty($desti,$destj) && $this->isFilled($i+($movei/2),$j+($movej/2))) {
            $this->board[$i][$j]=0;
            $this->board[$desti][$destj]=1;
			$this->board[$i+$movei/2][$j+$movej/2]=0; //peg between source and destination peg
			if ($this->getMoves()==0){ //checks if the game is over and sets the state accordingly
				$this->checkWin();
			}
            return true;
        } else {
			return false;
        }
	}

	public function getMoves() { //counts the total number of possible moves for each peg on the board 
		$numMoves=0;
		for ($row=0;$row<=4;$row++) {
    		foreach ($this->board[$row] as $col => $value) {
        		foreach($this->moves as $key => $move) {
            		if ($this->board[$row][$col]==1 && $this->isEmpty($row+$move[0],$col+$move[1]) && $this->isFilled($row+($move[0]/2),$col+($move[1]/2))) {
                		$numMoves = $numMoves + 1;
                	}
        		}	
   			}	
		}
		return $numMoves;
	}

	public function getNumPegs() { //gets remaining pegs
		$numPegs=0;
		for ($row=0;$row<=4;$row++) {
    			foreach ($this->board[$row] as $col => $value) {
        			if ($this->board[$row][$col]==1 ){
            				$numPegs = $numPegs + 1;
        			}
    			}
		}
		return $numPegs;		
	}

	public function getBoard(){ 
		return $this->board;
	}

	public function setBoard($i,$j,$val){
		$this->board[$i][$j]=$val;		
	}
	public function setSource($peg){
		$this->source[0]=$peg[0];
		$this->source[1]=$peg[1];
	}

	public function getSource(){
		return $this->source;
	}

	
}
?>
