<?php
	$postback=mt_rand();
	$_SESSION['postback']=$postback;
	// So I don't have to deal with uninitialized $_REQUEST['guess']
	$_REQUEST['guess']=!empty($_REQUEST['guess']) ? $_REQUEST['guess'] : '';
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
		<title>Guess Game</title>
	</head>
	<body>
		<header><h1>Welcome to GuessGame</h1></header>
		<?php include('navigation.php'); ?>
		<main>
		<?php if($_SESSION["guessGame"]->getState()!="correct"){ ?>
			<form method="post">
				<input type="hidden" name="postback" value="<?= $postback ?>" />
				<input type="text" name="guess" autofocus value="<?php echo($_REQUEST['guess']); ?>" /> 
				<input type="submit" name="submit" value="guess" />
			</form>
		<?php }
			echo(view_errors($errors));
			foreach($_SESSION['guessGame']->history as $key=>$value){
				echo("<br/> $value");
			}
		?>
		<form method="post">
			<input type="hidden" name="postback" value="<?= $postback ?>" />
			<input type="submit" name="submit" value="start again" />
		</form>
		</main>
		<footer></footer>
	</body>
</html>

