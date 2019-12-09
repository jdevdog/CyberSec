<!DOCTYPE html>
<?php
session_start();
 ?>
<html>
	<head>
  	<title>Scoreboard</title>
  	<meta charset="utf-8">
  	<link rel="stylesheet" href="./css/Score.css">
    <!-- Bootstrap 4; Sets initial zoom level and sets the width to the screen width of the viewing device -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <!-- Popper JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <!-- Imports Google Font Open-Sans -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">
    <!-- General Stylesheet Link -->
    <link rel="stylesheet" type="text/css" href="../css/topnav.css">
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
	  <div class="container-fluid"> <!-- container-fluid is a full width container. it scales to the screen width -->
      <div class="row header"> <!-- each row can contain up to 12 columns. no matter what, all col must add up to 12 -->
        <div class="col-sm-1">
          <div class="dropdown">
            <button type="button" class="btn" data-toggle="dropdown">
              <img src='./include/images/burger-menu.jpg' class='img-fluid'>
            </button>
            <div class="dropdown-menu">
              <a class="dropdown-item" href="./html/MainPage.html"><h3>Home</h3></a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="./CTFSco.php"><h3>Scoreboard</h3></a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="./CTFQuestions.php"><h3>Questions</h3></a>
            </div>
          </div>
        </div> <!-- img-fluid will scaled images to the size of their parent -->
        <div class="col-sm-6"><h1 id='headerTitle'>Cyber Security: Scoreboard</h1></div>
        <div class="col-sm-3"></div>
        <div class="col-sm-2"><button type="button" class="btn btn-primary" id="login">Login</button></div>
      </div>
		<br>
		<table id="teams"class="table-responsive table-bordered">
		<tr>
			<th>Team</th> <!-- score -->
			<th>Score</th> <!-- name -->
		</tr>
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
		<br>
  </div>



	</body>
</html>
