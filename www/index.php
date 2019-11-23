<!DOCTYPE html>
<?php

	$session_start();

	if (!empty ( $_POST ))
	{
		if(isset( $_POST(['username']) && isset([$_POST['password']) )
		{
			$con = new mysqli($servername, $username, $password, $dbname);
				// Check connection
			if ($con-> connect_error)
			{
				die("Connection failed: " . $con-> connect_error);
			}

			echo $_POST['password'];
			echo $_POST['username'];
			$stmt = $con->prepare("SELECT * FROM teams WHERE name = ?");
			$stmt->bind_param('s', $_POST['username']);
			$result = $stmt->get_result();
			$user = $result->fetch_object();

			if( $_POST['password'] == $user->password ) )
			{
				$_SESSION(['user_id'] = $user->team_id;
				header("location: CRFQuestions.php");
			}
			else
			{
				$error = "Your login name or password is invalid";
			}
		}
	}
?>

<html>
	<head>
	<title>Capture the Flag</title>
	<link rel="stylesheet" href="CTF.css">
	</head>
	<body>
	<div id="page">
	<div class="header" id = "heading" style="padding-top: 10px">
			<h1 id="t1">Capture the Flag Login<br><h3 id="t2">Intro to Cybersecurity</h3></h1>
	</div>
		<br><br><br><br>
		<div id="login" style="padding-top: 20px; padding-left: 170px">
			<form action="" method="post">
				<input type="text" name="username" id="username" class="log" placeholder="Username" required><br><br>
				<input type="password" name="password" id="password" class="log" placeholder="Password" required><br><br>
				<input type="submit" value="Submit" id="btn">
			</form>
			<form action="./CTFQuestions.html">
				<input type="submit" value="Continue as Guest" id="btn2">
			</form>
			<br>
			<br>
			</div>
	</body>
</html>
