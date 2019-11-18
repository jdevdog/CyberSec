<!DOCTYPE html> 
<html>
	<head>
	<title>Welcome Team1</title>
	<link rel="stylesheet" href="admin.css">
	<?php
                // Create connection
        $conn = mysqli_connect($servername, $username, $password, $dbname);
                // Check connection
        if ($conn-> connect_error) {
                die("Connection failed: " . $conn-> connect_error);
        }
		
        	if(isset($_POST['Invite'])) {
            	$myteam = mysqli_real_escape_string($conn, $_POST['team']);
            	$sql = "SELECT name FROM teams";
				$result = mysqli_query($conn, $sql);
            	$count = mysqli_num_rows($result) + 0;
				$sql = "INSERT INTO 'teams' ('team_id', 'score', 'name', 'password') VALUES (6, 0, 'test', 'password'";
				mysqli_query($conn, $sql);
				}
		
?>
	<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
	<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
	<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
	</head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<body>
		<div id="navi">
	<a class="link" href="index.html">Home</a><a class="link" href="CTFScore.html">Score</a><a class="link" href="CTFQuestions.html">Questions</a><a class="link" href="CTFAdmin.html">Admin</a>
	</div>
	<div id = "header">
		<h1 id = "welcome">Welcome, Admin</h1>
	</div>
	<h2>Schedule a CTF Event</h2>
	<input id="scheduler" type="text" name="datetimes" />
	<input id= "btn" type="button" value="Submit"  onclick="javascript:validate()">
	<h1 id="CTF"></h1>
<script type="text/javascript">
function validate()
{
    document.getElementById("CTF").innerHTML = "Capture the Flag: " + document.getElementById("scheduler").value;
}
</script>
	<form method="post">
	<h2>Invite <input type="text"  id="email" placeholder="Emails"> as <input type="text"  id="team" placeholder="Team Name">
	<input id="btn" type="submit" value="Invite"></h2>
	</form>



<table id="teams">
<tr><th>Team</th><th>Emails</th></tr>
<tr><td id="Team1">Starstrucks</td><td id="emails1">jvalentine@gmail.com; <br>credfield@gmail.com</td></tr>
<tr><td id="Team2">Rareware</td><td id="emails2">kameo@gmail.com; <br>dkong@gmail.com; <br>ylaylee@gmail.com</td></tr>
<tr><td id="Team3"></td><td id="emails3"></td></tr>
</table>

<script>
$(function() {
  $('input[name="datetimes"]').daterangepicker({
    timePicker: true,
    startDate: moment().startOf('hour'),
    endDate: moment().startOf('hour').add(32, 'hour'),
    locale: {
      format: 'M/DD hh:mm A'
    }
  });
});
</script>
	</body>
</html>
