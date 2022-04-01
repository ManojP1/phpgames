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
			<title>Games - Register</title>
			<link rel="stylesheet" type="text/css" href="style.css" />
        </head>
        <body>
		<header><h1>Registration</h1></header>
		<nav>
			<ul>
			<li> <a href="?page=login">Login</a>
			</ul>
		</nav>
		<main>
			<h1>Register an Account</h1>
			<form method="post" action="index.php">
				<input type="hidden" name="postback" value="<?= $postback ?>" />
				<label>*Username: <input type="text" name="username" value="<?php echo($_REQUEST["username"]); ?>" minlength="1" maxlength="10"/></label><br><br>
				<label>*Password:<input type="password" name="password"  minlength = 8></textarea></label><br><br>
				<label>*Confirm Password: <input type="password" name="password_confirm" minlength = 8/></label><br><br> 
				*Choose your Skill Level:<br>
					<label><input type="radio" name="skill_level" value="novice" <?php echo ($_REQUEST['skill_level'] == 'novice') ? 'checked="checked"' : ''; ?>/>Novice</label></br>
					<label><input type="radio" name="skill_level" value="intermediate" <?php echo ($_REQUEST['skill_level'] == 'intermediate') ? 'checked="checked"' : ''; ?>/>Intermediate</label></br>
					<label><input type="radio" name="skill_level" value="expert" <?php echo ($_REQUEST['skill_level'] == 'expert') ? 'checked="checked"' : ''; ?>/>Expert</label></br><br>
				Choose your Favourite Games:<br>
					<label><input type="checkbox" name="game_choice1" value="Guess Game" <?php echo ($_REQUEST['game_choice1'] == "Guess Game") ? 'checked="checked"' : ''; ?> />Guess Game</label><br>
					<label><input type="checkbox" name="game_choice2" value="15 Puzzle" <?php echo ($_REQUEST['game_choice2'] == "15 Puzzle") ? 'checked="checked"' : ''; ?>/>15 Puzzle</label><br>
					<label><input type="checkbox" name="game_choice3" value="Peg Solitaire" <?php echo ($_REQUEST['game_choice3'] == "Peg Solitaire") ? 'checked="checked"' : ''; ?>/>Peg Solitaire</label><br>
					<label><input type="checkbox" name="game_choice4" value="Mastermind" <?php echo ($_REQUEST['game_choice4'] == "Mastermind") ? 'checked="checked"' : ''; ?>/>Mastermind</label><br><br>
				<label>Colour:
					<select id="colour" name="colours">
						<option value=NULL <?php echo ($_REQUEST['colours'] == NULL) ? 'selected="selected"' : ''; ?>>Select a Colour:</option>
						<option value="White" <?php echo ($_REQUEST['colours'] == "White") ? 'selected="selected"' : ''; ?>>White</option>
						<option value="Black" <?php echo ($_REQUEST['colours'] == "Black") ? 'selected="selected"' : ''; ?>>Black</option>
						<option value="Red" <?php echo ($_REQUEST['colours'] == "Red") ? 'selected="selected"' : ''; ?>>Red</option>
						<option value="Blue" <?php echo ($_REQUEST['colours'] == "Blue") ? 'selected="selected"' : ''; ?>>Blue</option>
						<option value="Orange" <?php echo ($_REQUEST['colours'] == "Orange") ? 'selected="selected"' : ''; ?>>Orange</option>
					</select>
				</label><br><br>
				<input type="submit" name="submit" value="Submit Form">
	    	</form>
			</main>
			<footer><?php echo(view_errors($errors)); ?></footer>
        </body>
</html>

