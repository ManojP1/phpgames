<?php
   $gameStat = $_SESSION['gameStat']->get_games();
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
		<title>Games</title>
	</head>
	<body>
		<header><h1>Welcome to the Games</h1></header>
        <?php include('navigation.php'); ?>
		<main>
            <h1>Game Statistics for <?php echo $_SESSION['user'] ?></h1>
        <table style="text-align:left">
        <?php
        foreach ($_SESSION['gameStat']->get_games() as $game=>$val){
            echo "<tr>";
            echo "<th>".$game."</th>";
            echo "</tr>";
            echo "<tr>";
            echo "<td>Wins:".$val[0]."</td>";
            echo "<td>Total Played:".$val[1]."</td>";
            echo "</tr>";
            echo "<tr>";
            echo "<td>Total Wins:".$val[2]."</td>";
            echo "<td>Total Games Played:".$val[3]."</td></tr>";
            echo "<tr>";
            echo "<td><br/></td>";
            echo "</tr>";
        }
        ?>
        </table>
        <?php echo(view_errors($errors));?>
        </main>
		<footer>
		</footer>
	</body>
</html>

