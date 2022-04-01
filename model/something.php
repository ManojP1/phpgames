<?php
class Games {
	public $history = array();
    protected $userName, $numMoves, $gameName;
	protected $state = "";
	
	public function __construct($userName, $gameName) {
		$this->userName = $userName;
		$this->gameName = $gameName;
	}
	public function getState(){
		return $this->state;
	}
	public function setState($state){
		$this->state = $state;
	}
	public function getNumMoves(){
		return $this->numMoves;
	}
	public function getHistory(){
		return $this->history;
	}
	public function setHistory($val){
		$this->history[0] = $val;
	}
	public function endGame(){
		global $dbconn, $errors;
		if($this->state != 'win' && $this->state != 'lose'){
			$this->state = 'lose';
		}
		if(!in_array($this->gameName, array('Guess Game','15 Puzzle','Peg Solitaire','Mastermind'))){ //update schema constraints to add new games
			$errors[]="Invalid game name. Your play history and stats will not be updated.";
			return;
		}

		$query = "INSERT into game (gameName, userName, outcome, intVal) values($1, $2, $3, $4);";
        $result = pg_prepare($dbconn, "", $query);
        $result = pg_execute($dbconn, "", array($this->gameName, $this->userName, $this->state, $this->numMoves));
        //check if add success
        if(!$result){
            $errors[]="Failed to add game log in database. Your play history and stats will not be updated.";
        }
	}
}
class Position //represents a single position on a game board, as coordinates
{
	private $x, $y;
	public function __construct($row, $col) {
		$this->x =$row;
		$this->y = $col;
	}
	public function get_coord(){
		return array($this->x, $this->y);
	}
	public function eq($pos1){
		return $this->x == $pos1->x && $this->y == $pos1->y;
	}
	public function in_bounds($top, $bot, $left, $right){
		return $this->x >= $left && $this->x <= $right && $this->y >= $top && $this->y <= $bot;
	}
	public function __toString() {
		return "($this->x,$this->y)";
	}
}
class Block { //represents a single tile on the game board with its content and position
	private $ind; 
	private $val;
	protected $cur_pos;
	private $org_pos;

	public function __construct($val, $position){
		$this->val = $val;
		$this->cur_pos = $position;
		$this->org_pos = $position;
	}

	public function get_val(){
		return $this->val;
	}

	public function set_val($val){
		$this->val = $val;
	}

	public function get_pos(){
		return $this->cur_pos;
	}
	public function get_coord(){
		return $this->cur_pos->get_coord();
	}
	public function is_match(){
		return $this->cur_pos->eq($this->org_pos);
	}
	public function is_blank(){
		return $this->val == NULL;
	}
	public function get_blank(){
		return $this->blank;
	}
	public function is_next_block($block){
		return $this->cur_pos->is_next_pos($block->cur_pos);
	}
	public function switch_block($block){
		$pos1 = $this->cur_pos;
		$pos2 = $block->cur_pos;
		$this->cur_pos = $pos2;
		$block->cur_pos = $pos1;
	}
	public function __toString() {
		return "B:{$this->val} Pos:{$this->cur_pos}<br>";
	}
}
?>

