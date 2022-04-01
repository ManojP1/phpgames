<?php
// So I don't have to deal with unset $_REQUEST['user'] when refilling the form
// You can also take a look at the new ?? operator in PHP7
$postback=mt_rand();
$_SESSION['postback']=$postback;
$_REQUEST['user']=!empty($_REQUEST['user']) ? $_REQUEST['user'] : '';
$_REQUEST['password']=!empty($_REQUEST['password']) ? $_REQUEST['password'] : '';
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
		<title>Games</title>
	</head>
	<body>
		<header><h1>Games</h1></header>
		<link rel="stylesheet" type="text/css" href="style.css" />
		<nav>
			<ul>
			<li> <a href="?page=register">Register</a>
			</ul>
		</nav>
		<main>
			<h1>Login</h1>
			<form action="index.php" method="post">
				<input type="hidden" name="postback" value="<?= $postback ?>" />
				<legend>Login</legend>
				<table>
					<!-- Trick below to re-fill the user form field -->
					<tr><th><label for="user">User</label></th><td><input type="text" name="user" value="<?php echo($_REQUEST['user']); ?>" /></td></tr>
					<tr><th><label for="password">Password</label></th><td> <input type="password" name="password" /></td></tr>
					<tr><th>&nbsp;</th><td><input type="submit" name="submit" value="login" /></td></tr>
					<tr><th>&nbsp;</th><td><?php echo(view_errors($errors)); ?></td></tr>
				</table>
			</form>
		</main>
		<footer>
		</footer>
	</body>
</html>

