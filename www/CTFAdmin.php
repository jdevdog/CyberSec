<!DOCTYPE html>
<?php
session_start();
 ?>
<html>
	<head>
	<title>Admin section</title>
	<link rel="stylesheet" href="admin.css">
	<?php
		include "credentials.php";
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
	<a class="link" href="index.php">Login</a><a class="link" href="CTFSco.php">Score</a><a class="link" href="CTFQuestions.php">Questions</a><a class="link" href="CTFAdmin.php">Admin</a>
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
<tr><th>Team</th><th></th>Scores</tr>
<?php
		include "credentials.php";
		// Create connection
		$conn = mysqli_connect($servername, $username, $password, $dbname);
		// Check connection
		if ($conn-> connect_error) {
		    die("Connection failed: " . $conn-> connect_error);
		}

		//fetching all teams and looping through the rows
		$sql = "SELECT score, name FROM teams order by score desc";
		$result = mysqli_query($conn, $sql);  							//$conn-> query($sql);
		if ($result-> num_rows > 0) {
			while($row = $result-> fetch_assoc()) {
				echo "<tr><td>".$row["name"]."</td><td>".$row["score"]."</td></tr>";
			}
		}
		else {
			echo "<h1>No Teams Available<h1>";
		}

		//close connection
		$conn-> close();
	?>
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
