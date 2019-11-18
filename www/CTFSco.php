<!DOCTYPE html> 
<html>
	<head>
	<title>Welcome Team1</title>
	<link rel="stylesheet" href="Score.css">
	</head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
/* The Modal (background) */
.modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  padding-top: 100px; /* Location of the box */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  /*background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content */
.modal-content {
  /*background-color: #fefefe;*/
  background-color: #04315a;
  color: #ffffff;
  margin: auto;
  padding: 20px;
  border: 1px solid #888;
  border-radius: 12px;
  width: 80%;
}

/* The Close Button */
.close {
  color: #aaaaaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: #000;
  text-decoration: none;
  cursor: pointer;
}

* {
  box-sizing: border-box;
}

/* Create three equal columns that floats next to each other */
.column {
  float: left;
  width: 33.33%;
  padding: 10px;
  /*height: 300px; /* Should be removed. Only for demonstration */
}

/* Clear floats after the columns */

.row:after {
  content: "";
  display: table;
  clear: both;
}
</style>
	<body>
	<div id="navi">
	<a class="link" href="index.html">Home</a><a class="link" href="CTFScore.html">Score</a><a class="link" href="CTFQuestions.html">Questions</a><a class="link" href="CTFAdmin.html">Admin</a>
	</div>
		<br>
		<table id="teams"class="table-responsive table-bordered">
		<tr>
			<th>Team</th> <!-- score -->
			<th>Score</th> <!-- name -->
		</tr>
	<?php
	
		// Create connection
		$conn = mysqli_connect($servername, $username, $password, $dbname);
		// Check connection
		if ($conn-> connect_error) {
		    die("Connection failed: " . $conn-> connect_error);
		}
				
		//fetching all teams and looping through the rows
		$sql = "SELECT score, name FROM teams order by score";
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
		<br>

	


	</body>
</html>
