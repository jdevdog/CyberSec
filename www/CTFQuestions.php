<!DOCTYPE html>
<?php
	session_start();
	include "credentials.php";
	if(isset($_SESSION['user_id'])) {
		//echo "user id set. user is " . $_SESSION['user_id'];
	}
?>
<html>
	<head>
	<title>Welcome</title>
	<link rel="stylesheet" href="Question.css">
	</head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
/* Code from https://www.w3schools.com/howto/howto_css_modals.asp October 20th */
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

/* end of code from https://www.w3schools.com/howto/howto_css_modals.asp October 20th */
</style>
	<body>
	<div id="navi">
	<a class="link" href="index.php">Login</a><a class="link" href="CTFSco.php">Score</a><a class="link" href="CTFQuestions.php">Questions</a><a class="link" href="CTFAdmin.php">Admin</a>
	</div>
<?php
	include "credentials.php";
	if(isset($_SESSION['user_id'])) {
		//echo "user id set. user is " . $_SESSION['user_id'];
	

				// Create connection
			$conn = mysqli_connect($servername, $username, $password, $dbname);
				// Check connection
			if ($conn-> connect_error) {
		    	die("Connection failed: " . $conn-> connect_error);
			}
			$teamID = $_SESSION['user_id'];
			$sql = "SELECT score FROM teams where team_id =".$teamID;
			$result = mysqli_query($conn, $sql);
			echo "<div id = \"scoreboard\">";
			if ($result-> num_rows > 0) {
					while($row = $result-> fetch_assoc()) {
						echo "<h1 id = \"score\">Current Score: ".$row["score"]."</h1>";
					}
				}
			echo "</div>";
    		//close connection
			$conn-> close();
			}
?>
	<!--div id = "scoreboard">
		<h1 id = "score">Current Score: 150</h1>
	</div>-->
		<br>
		<?php
		include "credentials.php";

				// Create connection
			$conn = mysqli_connect($servername, $username, $password, $dbname);
				// Check connection
			if ($conn-> connect_error) {
		    	die("Connection failed: " . $conn-> connect_error);
			}
			$sql = "SELECT title, text, points, answer, max_attempts FROM questions order by points";
			$result = mysqli_query($conn, $sql);
			if ($result-> num_rows > 0) {
					while($row = $result-> fetch_assoc()) {
						echo "<div id=\"".$row["title"]."M"."\" class=\"modal\">";
						echo "<div class=\"modal-content\">";
    					echo "<span class=\"close\">&times;</span>";
    					echo "<p id=\"modalq\">".$row["text"]."</p>";
    					echo "<p id=\"tries\">"."Attempts Left: ".$row["max_attempts"]."</p>";
    					echo "<form action=\"\" method=\"post\">";
    					echo "<label>Answer:</label> <input type=\"text\" id=\"answer\" placeholder=\"Answer\" name=\"Answer\"><br><br>";
    					echo "<input type=\"submit\" value=\"Submit\" id=\"btn\">";
    					//echo "<input type=\"text\" id=\"answer\" placeholder=\"Answer\">";
    					//echo "<input type=\"button\" value=\"Submit\" id=\"btn\"  onclick=\"javascript:validate()\">";
    					echo "</form>";
    					echo "</div>";
    					echo "</div>";
    					}
    		}
    		//close connection
			$conn-> close();
?>
<!-- The Modal -->
<div id="myModal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
    <span class="close">&times;</span>
    <p id="modalq"></p>
    <p id="tries"></p>
    <input type="text" id="answer" placeholder="Answer">
    <input type="button" value="Submit" id="btn"  onclick="javascript:validate()">
  </div>
<script type="text/javascript">
function validate()
{
    if(   document.getElementById("answer").value == "Ethereal" )
    {
        alert( "Correct!" );
        document.getElementById("first").innerHTML = "<b>Team1: 200</b>"
        document.getElementById("second").innerHTML = "Team3: 190"
        document.getElementById("score").innerHTML = "Current Score: 200"
        document.getElementById("myBtn").disabled = true;
        document.getElementById("myBtn").innerHTML = "Completed";
        modal.style.display = "none";
    }
    else{
    	alert( "Wrong answer" );
    	if( document.getElementById("tries").innerHTML == "2" ){
    	document.getElementById("tries").innerHTML = "1";
    	}
    	else{
    	if( document.getElementById("tries").innerHTML == "1" ){
    	document.getElementById("tries").innerHTML = "0";
    	document.getElementById("myBtn").disabled = true;
        document.getElementById("myBtn").innerHTML = "Exceeded Attempts";
        modal.style.display = "none";
    	}
    	}

    }

    }
</script>
<!--start of code from https://stackoverflow.com/questions/4825295/javascript-onclick-to-get-the-id-of-the-clicked-button-->
<script type="text/javascript">
function showQ(currentID)
{
/* Code from https://www.w3schools.com/howto/howto_css_modals.asp October 20th */
// Get the modal

var modal = document.getElementById(currentID + "M");

// Get the button that opens the modal
var btn = document.getElementById(currentID);
// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal

modal.style.display = "block";


// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}
/* end of Code from https://www.w3schools.com/howto/howto_css_modals.asp October 20th */
}
</script>
<!--end of code from https://stackoverflow.com/questions/4825295/javascript-onclick-to-get-the-id-of-the-clicked-button-->
</div>
			<h2 id="q1">Questions</h2>
			<?php
				include "credentials.php";

				// Create connection
				$conn = mysqli_connect($servername, $username, $password, $dbname);
				// Check connection
				if ($conn-> connect_error) {
		    		die("Connection failed: " . $conn-> connect_error);
				}

				//fetching all teams and looping through the rows
				$sql = "SELECT title, text, points, answer FROM questions order by points";
				$result = mysqli_query($conn, $sql);
				$rowCount = 0;
				if ($result-> num_rows > 0) {
					while($row = $result-> fetch_assoc()) {
						if ($rowCount == 0){
							echo "<div class=\"row\">";
						}
						++$rowCount;
						echo "<div class=\"column odd1\" style=\"background-color:#04315a;\">";
						echo "<h2>".$row["title"]." (".$row["points"]."xp)</h2>";
						echo "<button class=\"qbtn\" id=\"".$row["title"]."\" onclick=\"javascript:showQ(this.id)\">Open Question</button>";
						echo "</div>";

						if ($rowCount == 3){
							echo "</div>";
							$rowCount = 0;
						}
				}
				}
				else {
					echo "<h1>No Questions Available<h1>";
				}

		//close connection
		$conn-> close();
	?>

<!--
			<div class="row">
  				<div class="column odd1" style="background-color:#04315a;">
    				<h2>Wireshark (50xp)</h2>
    				<button class="qbtn" id="myBtn">Open Question</button>
  				</div>
  			<div class="column odd2" style="background-color:#1d456a;">
    			<h2>Heartbleed (50xp)</h2>
    			<button class="qbtn" id="myBtn2" disabled>Completed</button>
  			</div>
  			<div class="column odd3" style="background-color:#365a7a;">
    			<h2>Format String (100xp)</h2>
    			<button class="qbtn" id="myBtn3" disabled>Exceeded Attempts</button>
			</div>
			<br>
			<div class="row">
  				<div class="column even1" style="background-color:#365a7a;">
    				<h2>Buffer Overflow (50xp)</h2>
    				<button class="qbtn" id="myBtn">Open Question</button>
  				</div>
  			<div class="column even2" style="background-color:#04315a;">
    			<h2>BLEH (50xp)</h2>
    			<button class="qbtn" id="myBtn2" disabled>Completed</button>
  			</div>
  			<div class="column even3" style="background-color:#1d456a;">
    			<h2>beh (100xp)</h2>
    			<button class="qbtn" id="myBtn3" disabled>Exceeded Attempts</button>
			</div> -->
</div>
<script>

</script>

	</body>
</html>
