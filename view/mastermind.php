<?php
	// So I don't have to deal with uninitialized $_REQUEST['guess']
	$postback=mt_rand();
	$_SESSION['postback']=$postback;
	$_REQUEST['mastermind']=!empty($_REQUEST['mastermind']) ? $_REQUEST['mastermind'] : '';
	$board = $_SESSION['mastermind']->get_board();
	$btnStyle = 'style="height:50px;width:50px;border:0.16em solid;color:blue;"';
	$buttonState = "";
	if ($_SESSION['mastermind']->getState()=="lose" || $_SESSION['mastermind']->getState()=="win"){
		$buttonState="disabled";
	}

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
		<title>Mastermind</title>
	</head>
	<body>
		<header><h1>Welcome to the Mastermind Game</h1></header>
		<?php include('navigation.php'); ?>
		<main>
		<div>
		<?php 
			foreach($_SESSION['mastermind']->getHistory() as $key=>$value){
				echo("<h3>$value</h3>");
			}
		?>
		<form method="post" action="index.php">
		<input type="hidden" name="postback" value="<?= $postback ?>" />
			<table style="margin: 0 auto">
				<tr>
				<?php
					foreach ($_SESSION['mastermind']->colours as $color){ //echoing input buttons
						echo '<td><button type="submit" name="pegChoice" value="'.$color.'"'.$buttonState.'/><img src="images/'.$color.'.png" width="50" height="50"/></td>';
					}?>
					<td><button type="submit" name="pegChoice" value="submit" style="height:60px;border:0.13em solid;color:blue;" <?php echo $buttonState?> />Submit</td>
					<td><button type="submit" name="pegChoice" value="reset" style="height:60px;border:0.13em solid;color:red;" <?php echo $buttonState?>/>Reset</td>
					<?php if ($_SESSION['mastermind']->getState()=="lose" || $_SESSION['mastermind']->getState()=="win") echo '<td><form method="post"><input type="submit" style="height:60px;border:0.13em solid;color:green;" name="pegChoice" value="Play Again" /></form></td>';
				?></tr>
			</table>
		</form>
		</div>
		<div style="display: flex">
			<div>
			<table border="border">
				<?php
				for ($x = 0; $x < 10; $x++){
					echo "<tr>";
					for ($y = 0; $y < 4; $y++){  //left table (coloured pegs)
						$block =  $board[$x][$y];
						echo '<td><img src="images/'.$block->get_val().'.png" width="50" height="50"/></td>';
					}
					echo "</tr>";
				}
				?>
			</table>
			</div><div>
			<table border="border">
				<?php
				for ($x = 0; $x < 10; $x++){
					echo '<tr style="background-color:grey">';
					for ($y = 4; $y < 8; $y++){ //right table (black/white pegs)
						$block = $board[$x][$y];
						echo '<td><img src="images/'.$block->get_val().'.png" width="50" height="50"/></td>';
					}
					echo "</tr>";
				}
				?>
			</table>
		<?php if ($_SESSION['mastermind']->getState()=="lose" || $_SESSION['mastermind']->getState()=="win"){ 
				echo '<table border="border" style="margin: 0 auto"><tr>'; //echos solution on game end
					for ($col=0;$col<=3;$col++){
						echo '<td><img src="images/'.$_SESSION['mastermind']->getSolution()[$col].'.png" width="50" height="50"></td>';
					} 
				echo "</tr></table>";
			  } 
		?>
			</div>
		</div>
		</main>
		<footer>
		<?php echo(view_errors($errors)); ?>
		</footer>
	</body>
</html>

