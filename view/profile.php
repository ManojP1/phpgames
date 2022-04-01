<?php
// So I don't have to deal with unset $_REQUEST['user'] when refilling the form
// You can also take a look at the new ?? operator in PHP7
$postback=mt_rand();
$_SESSION['postback']=$postback;        
$_REQUEST['username']=!empty($_REQUEST['username']) ? $_REQUEST['username'] : '';
$_REQUEST['password']=!empty($_REQUEST['password']) ? $_REQUEST['password'] : '';
$_REQUEST['skill_level']=!empty($_REQUEST['skill_level']) ? $_REQUEST['skill_level'] : '';
$_REQUEST['game_choice1']=!empty($_REQUEST['game_choice1']) ? $_REQUEST['game_choice1'] : '';
$_REQUEST['game_choice2']=!empty($_REQUEST['game_choice2']) ? $_REQUEST['game_choice2'] : '';
$_REQUEST['game_choice3']=!empty($_REQUEST['game_choice3']) ? $_REQUEST['game_choice3'] : '';
$_REQUEST['game_choice4']=!empty($_REQUEST['game_choice4']) ? $_REQUEST['game_choice4'] : '';
$_REQUEST['colours']=!empty($_REQUEST['colours']) ? $_REQUEST['colours'] : '';
//storing user profiles in easy to use variables, decoding gameprefs back into an array
$user=$_SESSION['profile']['userid'];
$skill=$_SESSION['profile']['skill'];
$gameprefs = json_decode($_SESSION['profile']['gamepref']);
$colour=$_SESSION['profile']['favcolour'];
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="cache-control" content="max-age=0" />
		<meta http-equiv="cache-control" content="no-cache" />
		<meta http-equiv="expires" content="0" />
		<meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
		<meta http-equiv="pragma" content="no-cache" />
		<link rel="stylesheet" type="text/css" href="style.css" />
		<title>Games - Register</title>
	</head>
	<body>
		<header><h1>User Profile</h1></header>
		<?php include('navigation.php'); ?>
		<main>
			<h1>Edit Profile</h1>
			<form method="post" action="index.php"> 
				<input type="hidden" name="postback" value="<?= $postback ?>" />
				<label>*Your Username: <input disabled type="text" name="username" value="<?php echo($_SESSION["user"]); ?>"/></label>
				<br><br>
				<label>*Reset Password: <input type="password" name="password" minlength = 8></textarea></label><br><br>
				<label>*Confirm Reset Password: <input type="password" name="password_confirm" minlength = 8/></label><br><br> 
				*Your Skill Level:<br>
				<label><input type="radio" name="skill_level" value="novice" <?php echo ($skill == 'novice') ? 'checked="checked"' : ''; ?> />Novice</label></br>
				<label><input type="radio" name="skill_level" value="intermediate" <?php echo ($skill == 'intermediate') ? 'checked="checked"' : ''; ?>/>Intermediate</label></br>
				<label><input type="radio" name="skill_level" value="expert" <?php echo ($skill == 'expert') ? 'checked="checked"' : ''; ?>/>Expert</label></br><br>
				Your Favourite Games:<br>
					<label><input type="checkbox" name="game_choice1" value="Guess Game" <?php echo (in_array("Guess Game",$gameprefs)) ? 'checked="checked"' : ''; ?> />Guess Game</label><br>
					<label><input type="checkbox" name="game_choice2" value="15 Puzzle" <?php echo (in_array("15 Puzzle",$gameprefs)) ? 'checked="checked"' : ''; ?>/>15 Puzzle</label><br>
					<label><input type="checkbox" name="game_choice3" value="Peg Solitaire" <?php  echo (in_array("Peg Solitaire",$gameprefs)) ? 'checked="checked"' : ''; ?>/>Peg Solitaire</label><br>
					<label><input type="checkbox" name="game_choice4" value="Mastermind" <?php echo (in_array("Mastermind",$gameprefs)) ? 'checked="checked"' : ''; ?>/>Mastermind</label><br><br>
				<label>Favourite Colour: <select id="colour" name="colours">
					<option <?php echo ($colour == NULL) ? 'selected="selected"' : ''; ?>>Select a Colour:</option>
					<option <?php echo ($colour == "White") ? 'selected="selected"' : ''; ?>>White</option>
					<option <?php echo ($colour == "Black") ? 'selected="selected"' : ''; ?>>Black</option>
					<option <?php echo ($colour == "Red") ? 'selected="selected"' : ''; ?>>Red</option>
					<option <?php echo ($colour == "Blue") ? 'selected="selected"' : ''; ?>>Blue</option>
					<option <?php echo ($colour == "Orange") ? 'selected="selected"' : ''; ?>>Orange</option>
				</select>
				</label><br><br>
				<input type="submit" name="submit" value="Apply Changes">
	    	</form>
			<div><?php echo(view_errors($errors)); ?></div>
		</main>
		<footer>
		</footer>
	</body>
</html>

