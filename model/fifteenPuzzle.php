<?php
require_once "something.php";
//Extends the position class to define what the next position means for a 15 puzzle
class PuzzlePosition extends Position {
    public function is_next_pos($pos){
        list($x, $y) = $this->get_coord();
        list($x1, $y1) = $pos->get_coord();
        return (abs($x - $x1) + abs($y - $y1)) == 1;
    }
}
//Creates a child puzzleGame class
class PuzzleGame extends Games{
    private $board;
	private $blank;
    //updates the parent class to fill in gameName for the games log
    public function __construct($userName) {
        parent::__construct($userName, "15 Puzzle");
        $num = 1;
        for ($y = 0; $y < 4; $y++){
            for ($x = 0; $x < 4; $x++){
                $pos = new PuzzlePosition($x, $y);
                if ($num > 15){
                    $this->blank = new Block(NULL, $pos);
                    $this->board[$x][$y] = $this->blank;
                } else {
                    $this->board[$x][$y] = new Block($num, $pos); //create a puzzle board
                }
                $num++;
            }
        }
        $this->randomizeBoard(30); //set number higher to increase difficulty
        if ($this->game_complete()) { //to make sure puzzle is not solved from get go
            $this->randomizeBoard(1);
        }
        $this->numMoves = 0;
        $this->history = array();
    }
    public function get_board(){
        return $this->board;
    }

    public function randomizeBoard($times){
        $a = 0;
        while ($a < $times){
            list($x, $y) = $this->blank->get_coord();
            //pick x or y to change
            if(random_int(0, 1)){
                $x += (2*random_int(0, 1) - 1); //add -1 or 1
            } else {
                $y += (2*random_int(0, 1) - 1);
            }
            $pos = new Position($x, $y);
            if ($pos->in_bounds(0, 3, 0, 3) && $this->move($this->board[$x][$y]->get_coord())){
                $a++;
            }
        }
    }
    //Takes a block coord to update the block if it is next to the blank block and updates history accordingly
    public function move($block_coord){
        $this->numMoves++;
        $x = intval($block_coord[0]);
        $y = intval($block_coord[1]);
        list($x1, $y1) = $this->blank->get_coord();
        $block = $this->board[$x][$y];
        if($this->blank->is_next_block($block)){
            $this->board[$x1][$y1] = $block;
            $this->board[$x][$y] = $this->blank;
            $this->blank->switch_block($block);
            $this->history[] = $this->numMoves.") Move ".$block->get_val()." Success.";
            return true;
        }

        $this->history[] = $this->numMoves.") Select a valid block to move.";
        return false;
    }
    //Check if puzzle is solved and update state
    public function game_complete(){
        for ($y = 3; $y > - 1; $y--){ //for optimization
            for ($x = 3; $x > -1; $x--){
                if (!$this->board[$x][$y]->is_match())
                    return false;
            }
        }
        $this->state = 'win';
        return true;
    }

}
?>
