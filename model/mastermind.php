<?php
require_once "something.php";

class MasterMind extends Games{
    protected $solution;
    protected $current;
    protected $board;
    public $colours = array("red","green","blue","yellow","orange","purple");

	public function __construct($userName) {
		parent::__construct($userName, "Mastermind");
        $this->createSolution();
        $this->initializeBoard();
        $this->hints = array();
    }

    public function get_board(){
        return $this->board;
    }

    public function get_current(){ //gets the current tile where a peg can be inserted to
        return $this->current;
    }

    public function createSolution(){ //creates the solution code upon starting the game
        for ($i=0;$i<=3;$i++) {
            $choice=random_int(0, 5);
            $this->solution[]=$this->colours[$choice];
        }
    }

    public function fill($color){ //fills the current tile with the user input if possible
        list($x, $y) = $this->current->get_coord();
        if($y < 4) { //in bounds
            $this->current->set_val($color);
            $this->board[$x][$y] = $this->current; //moves current (cursor) to next position
            $this->current = $this->board[$x][$y+1];
        } else {
            $this->history[0]="Row already full! Please submit or press reset";
        }
    }

    public function reset(){ //reset row 
        list($x,$y) = $this->current->get_coord();
        $this->current=$this->board[$x][0];
        for ($col=0;$col<=3;$col++){
            $this->board[$x][$col]->set_val("hole");
        }
    }

    public function check(){ //checks if inputs are correct, creates and sets hints
        $i=0; 
        list($x, $y) = $this->current->get_coord();
        $solcpy=$this->solution; //copy where matched colours can be removed
        $remaining = array();
        for ($col=0;$col<=3;$col++){ //loop to check right colour pegs in the right place
            if ($this->board[$x][$col]->get_val()==$this->solution[$col]){  
                $this->board[$x][$i+4]->set_val("black");
                $solcpy[$col]="taken"; //removing matching colour
                $i++;
            } else {
                $remaining[]=$this->board[$x][$col]; //array of incorrect pegs
            }        
        }
        $j=$i;
        for ($rcol=0;$rcol < count($remaining);$rcol++){ //loop to check if remaining pegs matches remaining colours in the solution
            if (in_array($remaining[$rcol]->get_val(), $solcpy)){ 
                $this->board[$x][$j+4]->set_val("white");
                $solcpy[array_search($remaining[$rcol]->get_val(),$solcpy)]="taken";
                $j++;
            }
        }
        if ($i == 4){ //all pegs match
            $this->state = "win";
        }
        else if ($x < 9){ //move to next row
            $this->current=$this->board[$x+1][0];   
        } else { //guess 10 has been made and is incorrect
            $this->state="lose";
            $this->history[0]="You lost!";
        }
    }

    public function getSolution(){ //returns solution set
        return $this->solution;
    }

    public function initializeBoard(){ //intializes board with all empty values
        for ($i = 0; $i<10;$i++){
            for ($j = 0;$j<8;$j++){
                $pos = new Position($i,$j);
                $this->board[$i][$j]=new Block("hole", $pos);
                if ($i==0 && $j==0) {
                    $this->current=$this->board[$i][$j];
                }
            }
        }
    }
}
?>