<?php
	$postback=mt_rand();
	$_SESSION['postback']=$postback;
	// So I don't have to deal with uninitialized $_REQUEST['puzzle']
	$_REQUEST['puzzle']=!empty($_REQUEST['puzzle']) ? $_REQUEST['puzzle'] : '';
	$board = $_SESSION['puzzleGame']->get_board();
	$btnStyle = 'style="height:50px;width:50px;border:0.16em solid;color:blue;"'//the design for the block of puzzle
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>15 Puzzle</title>
		<meta http-equiv="cache-control" content="max-age=0" />
		<meta http-equiv="cache-control" content="no-cache" />
		<meta http-equiv="expires" content="0" />
		<meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
		<meta http-equiv="pragma" content="no-cache" />
	</head>
	<body>
		<header><h1>Welcome to the 15 Puzzle Game</h1></header>
		<?php include('navigation.php'); ?>
		<main>
		<form method="post" action="index.php">
		<input type="hidden" name="postback" value="<?= $postback ?>" />
			<table style="margin: 0 auto">
				<?php
				for ($y = 0; $y < 4; $y++){
					echo "<tr>";
					for ($x = 0; $x < 4; $x++){
						$block =  $board[$x][$y];
						echo '<td><button '.$btnStyle.' name="puzzle" value ="'.json_encode($block->get_coord());
						if($_SESSION['puzzleGame']->getState() =="win"){ //prevent changing solved puzzle 
							echo '" type="button';
							$_SESSION['puzzleGame']->history[-1] = "Please click Play Again to solve another puzzle.";
						}
						echo '">'.$block->get_val().'</button></td>';
					}
					echo "</tr>";
				}
				?>
			</table>
			</form>
			<?php
			if($_SESSION['puzzleGame']->getState() =="win"){ 
				echo '<form method="post">
				<input type="hidden" name="postback" value="'.$postback.'" />
						<input type="submit" style="height:30px;border:0.13em solid;color:green;" name="puzzle" value="Play Again" />
					</form>';
			} else { 
				echo '<form method="post">
				<input type="hidden" name="postback" value="'.$postback.'" />
					<input type="submit" style="height:30px;border:0.13em solid;color:red;" name="puzzle" value="Give Up" />
					</form>';
			}
			foreach($_SESSION['puzzleGame']->history as $key=>$value){
					echo("<br/> $value");
				}
			?>
		</main>
		<footer>
		<?php echo(view_errors($errors));?>
		</footer>
	</body>
</html>

