<!DOCTYPE html>

<?php
	session_start();
	include "credentials.php";
	if(isset($_SESSION['user_id'])) {
		//echo "user id set. user is " . $_SESSION['user_id'];
	}
	if(!empty($_POST) {
		if(isset($_POST['submit'])) {
			// Create connection
			$conn = mysqli_connect($servername, $username, $password, $dbname);
	// Check connection
			if ($conn-> connect_error) {
				die("Connection failed: " . $conn-> connect_error);
			}
			//prepare sql statement using submit Post variable
			$stmt = $con->prepare("SELECT * FROM questions WHERE qa_id = ?");
			$stmt->bind_param('i', $_POST['submit']);
			$stmt->execute();
			$result = $stmt->get_result();
			$question = $result->fetch_object();

			if($question) {
				if($_POST['answer'] == $question->answer)
				{
					echo 'user score = ' . $user->score;
					$stmt = $con->prepare("UPDATE teams SET score = ? WHERE team_id = ?");
					$stmt->bind_param('ii', $user->score + $question->points, $_SESSION['user_id']);
					$stmt->execute();
					echo 'user score after points = ' . $user->score;
				}
			}
			else {
				echo 'did not find question match';
			}
		}
	}
?>

<html>
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

<head>
	<title>Welcome</title>
	<link rel="stylesheet" href="Question.css">
</head>
<meta name="viewport" content="width=device-width, initial-scale=1">



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
	<br>
		<?php
		include "credentials.php";

				// Create connection
			$conn = mysqli_connect($servername, $username, $password, $dbname);
				// Check connection
			if ($conn-> connect_error) {
		    	die("Connection failed: " . $conn-> connect_error);
			}
			$sql = "SELECT title, QAText, points, answer, max_attempts FROM questions";
			$result = mysqli_query($conn, $sql);
			$qnum = 0;
			if ($result-> num_rows > 0) {
					while($row = $result-> fetch_assoc()) {
						echo "<div id=\"".$row["title"]."M"."\" class=\"modal\">";
							echo "<div class=\"modal-content\">";
    							echo "<span class=\"close\">&times;</span>";
    							echo "<p id=\"modalq\">".$row["QAText"]."</p>";
    							echo "<p id=\"tries\">"."Attempts Left: ".$row["max_attempts"]."</p>";
    							echo "<form action=\"\" method=\"post\">";
    								echo "<input type=\"text\" name=\"answer\" id=\"questionA\" placeholder=\"Answer\" style=\"width:30%;border-radius:12px;padding:14px;\" >";
    								echo "<input type=\"Submit\" name=\"submit\" id=\"qaBtn\" value=\"" . $qnum . "\" style=\"background-color:#872434;color:white;padding:14px 20px;margin:8px 0px;font-size:16px;border:none;border-radius:12px;cursor:pointer;\" >";
    							echo "</form>";
    						echo "</div>";
    					echo "</div>";
    					$qnum++;
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
				$sql = "SELECT title, QAtext, points, answer FROM questions";
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
</body>
</html>
