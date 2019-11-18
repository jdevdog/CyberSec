<!DOCTYPE html> 
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
				<input type="text" name="username" id="username" class="log" placeholder="Username"><br><br>
				<input type="password" name="password" id="password" class="log" placeholder="Password"><br><br>
				<input type="submit" value="Login" id="btn">
			</form>
			<form action="./CTFQuestions.html">
				<input type="submit" value="Continue as Guest" id="btn2">
			</form>
<?php
		// Create connection
	$conn = mysqli_connect($servername, $username, $password, $dbname);
		// Check connection
	if ($conn-> connect_error) {
		die("Connection failed: " . $conn-> connect_error);
	}  			
	if($_SERVER["REQUEST_METHOD"] == "POST") {
            $myusername = mysqli_real_escape_string($conn, $_POST['username']);
            $mypassword = mysqli_real_escape_string($conn, $_POST['password']);
            $sql = "SELECT name FROM teams WHERE name = '$myusername' AND password = '$mypassword'";
			$result = mysqli_query($conn, $sql);
            $count = mysqli_num_rows($result);
            if($count == 1) {
                    header("location: CTFQuestions.html");
            } else {
                    $error = "Your login name or password is invalid";
            }
    }
?>
			<br>
			<br>
			</div>
	</body>
</html>
