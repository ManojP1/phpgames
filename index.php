<?php
	ini_set('display_errors', 'On');
	require_once "lib/lib.php";
	require_once "model/something.php";
	require_once "model/pegGame.php";
	require_once "model/fifteenPuzzle.php";
	require_once "model/GuessGame.php";
	require_once "model/gameStat.php";
	require_once "model/mastermind.php";
	require_once "model/appUser.php";

	
	session_save_path("sess");
	session_start(); 

	$dbconn = db_connect();

	$errors=array();
	$view="";

	/* controller code */
	if(isset($_GET['page'])){ //handle the navigation links and update the url to remove query strings
		if($_GET['page'] != "register")
			$_SESSION['state'] = "unavailable";
		else $_SESSION['state'] = "register";
		$_REQUEST['choice'] = $_GET['page'];
		header("Location:index.php");
	}
	/* local actions, these are state transforms */
	if(!isset($_SESSION['state']) || ($_SESSION['state'] != "register" && !isset($_SESSION['user']))){
		$_SESSION['state']='login';
	}
	$_REQUEST['puzzle']=!empty($_REQUEST['puzzle']) ? $_REQUEST['puzzle'] : '';
	$_REQUEST['pegChoice']=!empty($_REQUEST['pegChoice']) ? $_REQUEST['pegChoice'] : '';
	$_REQUEST['pegGame']=!empty($_REQUEST['pegGame']) ? $_REQUEST['pegGame'] : '';

	switch($_SESSION['state']){
		case "mastermind":
			$view = "mastermind.php";
			if(isset($_REQUEST['postback']) && $_REQUEST['postback'] != $_SESSION['postback'])//to prevent resubmission/back buttons
				break;
			if (empty($_REQUEST['pegChoice'])) break;
			$_SESSION['mastermind']->setHistory("Choose a sequence of pegs.");
			if ($_REQUEST['pegChoice'] == "Play Again") {
				$_SESSION['mastermind'] = new MasterMind($_SESSION["user"]);
				break;
			}else if ($_REQUEST['pegChoice'] == "reset") {
				$_SESSION['mastermind']->reset();
				break;
			}else if ($_REQUEST['pegChoice'] == "submit") {
				if ($_SESSION['mastermind']->get_current()->get_coord()[1] < 4){
					$_SESSION['mastermind']->setHistory("Please fill the row before submitting.");
					break;
				}
				$_SESSION['mastermind']->check();
				if ($_SESSION['mastermind']->getState() == "win") {
					$_SESSION['mastermind']->endGame(); //write game log in db
					$_SESSION['mastermind']->setHistory("You Won!");
				} else if ($_SESSION['mastermind']->getState() == "lose") { 
					$_SESSION['mastermind']->endGame(); //write game log in db
					$_SESSION['mastermind']->setHistory("You Lost!");
				}
				break;
			}
			$_SESSION['mastermind']->fill($_REQUEST['pegChoice']);
			break;
		case "guessGame":
			// the view we display by default
			$view="guessGame.php";
			if(isset($_REQUEST['postback']) && $_REQUEST['postback'] != $_SESSION['postback'])
				break;
			// check if submit or not
			if(empty($_REQUEST['submit'])||($_REQUEST['submit']!="guess" && $_REQUEST['submit']!="start again"))
				break;
			if($_REQUEST['submit']=="start again"){
				if($_SESSION["guessGame"]->getState() == "correct")
					$_SESSION["guessGame"]->setState("win");
				$_SESSION["guessGame"]->endGame();
				// create a new game instance
				$_SESSION["guessGame"]=new GuessGame($_SESSION["user"]);
				break;
			}
			// validate and set errors
			if(!is_numeric($_REQUEST["guess"]))$errors[]="Guess must be numeric.";
			if(!empty($errors))break;

			// perform operation, switching state and view if necessary
			$_SESSION["guessGame"]->makeGuess($_REQUEST['guess']);
			$_REQUEST['guess']="";
			break;
		case "15 puzzle":
			$view="fifteenPuzzle.php";
			if(isset($_REQUEST['postback']) && $_REQUEST['postback'] != $_SESSION['postback'])
				break;
			if(empty($_REQUEST['puzzle'])){
				break;
			}else if ($_REQUEST['puzzle'] == "Play Again") {
				$_SESSION['puzzleGame'] = new PuzzleGame($_SESSION['user']);
			} else if ($_REQUEST['puzzle'] == "Give Up") {
				$_SESSION['puzzleGame']->setState("lose");
				$_SESSION['puzzleGame']->endGame();
				$_SESSION['puzzleGame'] = new PuzzleGame($_SESSION['user']);
			} else {
				$_SESSION['puzzleGame']->move(array($_REQUEST['puzzle'][1], $_REQUEST['puzzle'][3]));
				if($_SESSION['puzzleGame']->game_complete()){
					$_SESSION['puzzleGame']->setState("win");
					$_SESSION['puzzleGame']->endGame();
				}
			}
			break;
		case "pegs":
			$view="pegGame.php";
			if(isset($_REQUEST['postback']) && $_REQUEST['postback'] != $_SESSION['postback'])
				break;
			if (empty($_REQUEST['pegChoice'])){
				break;
			}else if ($_REQUEST['pegChoice']=="Back to Home") {
				$_SESSION['state']='unavailable';
				$view="unavailable.php";
				break;
			}else if ($_REQUEST['pegChoice'] == "Start Again") {
				$_SESSION['pegGame']->checkWin();
				$_SESSION['pegGame']->endGame();
				$_SESSION['pegGame']=new PegGame($_SESSION["user"]);
				$_SESSION['pegGame']->setState("peg1");
				break;
			}
			$pegchoice=preg_split("/[,]/", $_REQUEST['pegChoice']); //coordinates for a peg on the board
			$pegchoice[0]=(int)$pegchoice[0];
			$pegchoice[1]=(int)$pegchoice[1]; 
			if ($_SESSION['pegGame']->getState() == "peg1"){ //first peg selected
				if ($_SESSION['pegGame']->isEmpty($pegchoice[0],$pegchoice[1])) { //checking for valid first peg input
					$_SESSION['pegGame']->setHistory("Choose a valid source peg.");
					$_SESSION['pegGame']->setState("peg1");
					break;
				}
				$_SESSION['pegGame']->setSource($pegchoice);
				$_SESSION['pegGame']->setBoard($pegchoice[0],$pegchoice[1],2);
				$_SESSION['pegGame']->setState("peg2");
				$_SESSION['pegGame']->setHistory("Choose a destination.");
			} else if ($_SESSION['pegGame']->getState() == "peg2"){ //second peg selected
				$source=$_SESSION['pegGame']->getSource();
				if ($source[0]==$pegchoice[0] && $source[1]==$pegchoice[1]) { //if source peg was selected again, to deselect
					$_SESSION['pegGame']->setBoard($source[0],$source[1],1);
					$_SESSION['pegGame']->setHistory("Choose a source peg.");
					$_SESSION['pegGame']->setState("peg1");
				} elseif ($_SESSION['pegGame']->makeMove($source[0],$source[1],$pegchoice[0],$pegchoice[1])) { //attempt to make move
					if ($_SESSION['pegGame']->getState() == "win" || $_SESSION['pegGame']->getState() == "lose") {
						$_SESSION['pegGame']->setHistory("No moves left! Pegs remaining: ".$_SESSION['pegGame']->getNumPegs());
						break;
					}
					$_SESSION['pegGame']->setHistory("Choose another source peg.");
					$_SESSION['pegGame']->setState("peg1");
				} else { 																		//if move failed
					$_SESSION['pegGame']->setHistory("Choose a valid destination.");
				}
			}
			break;
		case "unavailable":
			$view="gameStat.php";
			if(isset($_REQUEST['postback']) && $_REQUEST['postback'] != $_SESSION['postback'])
				break;
			if(!isset($_REQUEST['choice']) || $_REQUEST['choice']=="Game Stats") {
				$_SESSION['gameStat'] =  new GameStat($_SESSION['user']);
				break;	
			} else if ($_REQUEST['choice'] == "15 Puzzle") {
				$_SESSION['puzzleGame'] = new PuzzleGame($_SESSION['user']);
				$_SESSION['state']="15 puzzle";
				$view="fifteenPuzzle.php";
			}else if($_REQUEST['choice']=="Peg Solitaire") {
				$_SESSION['pegGame']=new PegGame($_SESSION["user"]);
				$_SESSION['pegGame']->setState("peg1");
				$_SESSION['state']="pegs";
				$view="pegGame.php";
			} else if($_REQUEST['choice']=="User Profile"){
				$_SESSION['state']="profile";
				$view="profile.php";
				$user=new User();
				$_SESSION['profile'] = $user->getProfile($_SESSION['user']);
			} else if($_REQUEST['choice']=="Logout" ){
				$_SESSION['state']="login";
				$view="login.php";
				session_destroy();
			}else if ($_REQUEST['choice']=="Mastermind"){
				$_SESSION['mastermind']=new MasterMind($_SESSION["user"]);
				$_SESSION['state']="mastermind";
				$view="mastermind.php";

			}else if ($_REQUEST['choice']=="GuessGame"){
				$_SESSION['guessGame']=new GuessGame($_SESSION["user"]);
				$_SESSION['state']="guessGame";
				$view="guessGame.php";
			}
			break;
		case "register":
			$view="register.php";
			if(isset($_REQUEST['postback']) && $_REQUEST['postback'] != $_SESSION['postback'])
				break;
			if (empty($_REQUEST['submit']) || ($_REQUEST['submit']!="Submit Form" && $_REQUEST['submit']!="Back to Login")){
				break;
			}
			if ($_REQUEST['submit']=="Back to Login") {
				$_SESSION['state']='login';
				$view="login.php";
			} else {
				if(empty($_REQUEST['username']))$errors[]='Please enter a username';	
				if(empty($_REQUEST['password']))$errors[]='Please enter a password';
				if(empty($_REQUEST['password_confirm'])||$_REQUEST['password_confirm']!=$_REQUEST['password'])$errors[]="Passwords do not match";
				if(empty($_REQUEST['skill_level']))$errors[]='Please choose a skill level';
				$user=new User();
				if($user->isTaken($_REQUEST['username']))$errors[]="That username is already taken";
				if(!empty($errors))break;

				if(!$dbconn){
					$errors[]="Can't connect to db";
					break;				
				}
				$gameprefs=array();
				for ($i=1;$i<=4;$i++){
					if (!empty($_REQUEST['game_choice'.$i])){
						$gameprefs[]=$_REQUEST['game_choice'.$i];
					}
				}
				$user->register($_REQUEST['username'],$_REQUEST['password'],$_REQUEST['skill_level'],$gameprefs,$_REQUEST['colours']);
				$_SESSION['state']="login";
				$view="login.php";
			}
			break;
		case "profile":
			$view="profile.php";
			if(isset($_REQUEST['postback']) && $_REQUEST['postback'] != $_SESSION['postback'])
				break;
			if (empty($_REQUEST['submit']))
			break;
			if ($_REQUEST['submit']=="Back to Home") {
				$_SESSION['state']='unavailable';
				$view="unavailable.php";
				$break;
			} else {
				if(empty($_REQUEST['password']))
					$errors[]="Password must be entered";
				if(empty($_REQUEST['password_confirm']) || $_REQUEST['password_confirm']!= $_REQUEST['password'])
					$errors[]="Passwords do not match";
				if(empty($_REQUEST['skill_level']))$errors[]='Please choose a skill level';
				if(!empty($errors))break;
				if(!$dbconn){
					$errors[]="Can't connect to db";
					break;				
				}
				$user=new User();
				$gameprefs=array();
				for ($i=1;$i<=4;$i++){
					if (!empty($_REQUEST['game_choice'.$i])){
						$gameprefs[]=trim($_REQUEST['game_choice'.$i],'"');
					}
				}
				$user->updateProfile($_SESSION['user'],$_REQUEST['password'],$_REQUEST['skill_level'],$gameprefs,$_REQUEST['colours']);
				$_SESSION['profile'] = $user->getProfile($_SESSION['user']);
			}
			break;
		case "login":
			// the view we display by default
			$view="login.php";
			if(isset($_REQUEST['postback']) && $_REQUEST['postback'] != $_SESSION['postback'])
				break;
			// check if submit or not
			if(empty($_REQUEST['submit']) || ($_REQUEST['submit']!="login" && $_REQUEST['submit']!="register")){
				break;
			} else if ($_REQUEST['submit']=="register") {
				$_SESSION['state']='register';
				$view="register.php";
				break;
			}
			
			// validate and set errors
			if(empty($_REQUEST['user']))$errors[]='user is required';
			if(empty($_REQUEST['password']))$errors[]='password is required';
			$_REQUEST['game_choice'] = array ("stuff");
			if(!empty($errors))break;
			// perform operation, switching state and view if necessary
			if(!$dbconn){
				$errors[]="Can't connect to db";
				break;
			}
			$user=new User();
			$password=$user->login($_REQUEST['user'],$dbconn);
			if ($password == "Invalid Username"){
				$errors[]=$password;
			}
			
			if(!empty($errors))break;
			if(password_verify($_REQUEST['password'],$password)){
				$_SESSION['user']=$_REQUEST['user'];
            	$_SESSION['state']='unavailable';
				$view="gameStat.php";
				$_SESSION['gameStat'] =  new GameStat($_SESSION['user']);
			} else{
				$errors[]="Invalid Password";
			}
			
			break;
	}
	require_once "view/$view";
?>
