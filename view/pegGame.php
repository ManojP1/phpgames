<?php
	// So I don't have to deal with uninitialized $_REQUEST['guess']
	$_REQUEST['pegChoice']=!empty($_REQUEST['pegChoice']) ? $_REQUEST['pegChoice'] : '';
	$boardView=array("images/empty.png","images/peg.png","images/selected.jpg");
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Peg Solitaire</title>
	</head>
	<body>
	<header><h1>Welcome to Peg Solitaire Game</h1></header>
		<?php include('navigation.php'); ?>
		<main>
		<div style="text-align:center">
		<?php 
		if ($_SESSION['pegGame']->getState() == "win" || $_SESSION['pegGame']->getState() == "lose" ) {
		echo '<button type="submit" form="main" style="height:60px;border:0.15em solid;color:green;" name="pegChoice" value="Start Again">Start Game Again</button>';
		}
			foreach($_SESSION['pegGame']->getHistory() as $key=>$value){
				echo'<h3>'.$value.'</h3>';
			}
		?>
		<form method="post" id="main" action="index.php"> 
			<table style="margin: 0 auto">
            <?php //echoing main game board
            $k = 8;
            for ($i = 0; $i < 5; $i++) 
            { echo '<tr>';
                for ($j = 0; $j < $k; $j++) 
                    echo '<td></td>';
                $k--;
                for ($j = 0; $j <= $i; $j++) 
                    echo '<td><button type="submit" name="pegChoice" value="'.$i.','.$j.'"/> <img src="'.$boardView[$_SESSION['pegGame']->getBoard()[$i][$j]].'" width="50" height="50"/></td><td></td>';
                for ($j = 0; $j <= $k; $j++) 
                    echo '<td></td>';
                echo '</tr>';
            }?>
            </table>
		</form>
		</div> 
		</main>
		<footer>
		<?php echo(view_errors($errors)); ?>
		</footer>
	</body>
</html>

