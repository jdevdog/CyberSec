<!DOCTYPE html>

<?php
	session_start();
	include "credentials.php";
	if(isset($_SESSION['user_id'])) {
		//echo "user id set. user is " . $_SESSION['user_id'];
	}
	if(!empty($_POST)) {
		if(isset($_POST['submit'])) {
			// Create connection
			$con = mysqli_connect($servername, $username, $password, $dbname);
	// Check connection
			if ($con-> connect_error) {
				die("Connection failed: " . $con-> connect_error);
			}

			//prepare sql statement and fetch the question as an object
			$stmt = $con->prepare("SELECT * FROM questions WHERE qa_id = ?");
			$stmt->bind_param('i', $_POST['submit']);
			$stmt->execute();
			$result = $stmt->get_result();
			$question = $result->fetch_object();

			//fetch the user as an object
			$stmt = $con->prepare("SELECT * FROM teams WHERE team_id = ?");
			$stmt->bind_param('i', $_POST['submit']);
			$stmt->execute();
			$result = $stmt->get_result();
			$user = $result->fetch_object();

			//check if the submitted answer is correct
			if($question) {
				if($_POST['answer'] == $question->answer) //if the answer is correct
				{
					//calculate the user's score using the object created
					$totalscore = $user->score + $question->points;

					//use update to change the team's score to their new score
					$stmt = $con->prepare("UPDATE teams SET score = ? WHERE team_id = ?");
					$stmt->bind_param('ii',$totalscore, $_SESSION['user_id']);
					$stmt->execute();

					$message = "Your answer was correct!";
					echo "<script type='text/javascript'>alert('$message');</script>";
				}
				else //incorrect answer
				{
					//echo 'wrong answer';
					//check questions_attempted table to see if the team has made any previous attempts
					//(if an entry exists matching teamid and qa_id then they have made an attempt)
					$userid = $user->team_id;
					$qaid = $question->qa_id;
					$stmt = $con->prepare("SELECT * FROM questions_attempted WHERE team_id = ? AND qa_id = ?");
					$stmt->bind_param('ii',$userid, $qaid);
					$stmt->execute();
					$result = $stmt->get_result();

					if($result == false) //if this is their first attempt
					{
						//insert a new entry for the team and the question
						//echo 'inserting new attempt entry';
						$stmt = $con->prepare("INSERT INTO questions_attempted (team_id, qa_id, attempts) VALUES (?, ?, 1)");
						$stmt->bind_param('ii',$userid, $qaid);
						$stmt->execute();
					}
					else //if they've made previous attempts
					{
						//echo 'there exists an entry.';
						$attempts = $result->fetch_object();
						if($attempts->attempts < $question->max_attempts) {
								$stmt = $con->prepare("UPDATE questions_attempted SET attempts = attempts + 1 WHERE team_id = ? and qa_id = ?");
								$stmt->bind_param('ii',$userid, $qaid);
								$stmt->execute();
						}
						else {
							$message = "Maximum attempts exceeded.";
							echo "<script type='text/javascript'>alert('$message');</script>";
						}

					}
				}
			}
			else {
				echo 'ERROR: did not find question match';
			}
		}
	}
?>

<html>
<head>
	<title>Welcome</title>
	<link rel="stylesheet" href="./css/CTFQuestions.css">
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
			$con = mysqli_connect($servername, $username, $password, $dbname);
				// Check connection
			if ($con-> connect_error) {
		    	die("Connection failed: " . $con-> connect_error);
			}

			$teamID = $_SESSION['user_id'];
			$sql = "SELECT score FROM teams where team_id =".$teamID;
			$result = mysqli_query($con, $sql);
			echo "<div id = \"scoreboard\">";
			if ($result-> num_rows > 0) {
					while($row = $result-> fetch_assoc()) {
						echo "<h1 id = \"score\">Current Score: ".$row["score"]."</h1>";
					}
				}
			echo "</div>";
    		//close connection
			$con-> close();
			}
	?>
	<br>
		<?php
		include "credentials.php";

				// Create connection
			$con = mysqli_connect($servername, $username, $password, $dbname);
				// Check connection
			if ($con-> connect_error) {
		    	die("Connection failed: " . $con-> connect_error);
			}



			$sql = "SELECT title, QAText, points, answer, max_attempts FROM questions";
			$result = mysqli_query($con, $sql);
			$qnum = 0;
			if ($result-> num_rows > 0) {
					while($row = $result-> fetch_assoc()) {
						echo "<div id=\"".$row["title"]."M"."\" class=\"modal\">";
							echo "<div class=\"modal-content\">";
    							echo "<span class=\"close\">&times;</span>";
    							echo "<p id=\"modalq\">".$row["QAText"]."</p>";
    							echo "<p id=\"tries\">"."Attempts Left: ". $row['max_attempts'] . "</p>";
    							echo "<form action=\"\" method=\"post\">";
											//echo "<p> Sorry! You've attempted the question the maximum number of times! </p>";
											echo "<input type=\"text\" name=\"answer\" id=\"questionA\" placeholder=\"Answer\">";
											echo "<button type=\"Submit\" name=\"submit\" id=\"qaBtn\" value=\"".$qnum."\"> Submit </button>";
    							echo "</form>";
    						echo "</div>";
    					echo "</div>";
    					$qnum++;
    			}
    		}
    		//close connection
			$con-> close();
			?>
<!-- The Modal -->
	<div id="myModal" class="modal">
  <!-- Modal content -->
  <div class="modal-content">
    <span class="close">&times;</span>
    <p id="modalq"></p>
    <p id="tries"></p>
    <input type="text" id="answer" placeholder="Answer">
    <button type="submit" value="Submit" id="btn"  onclick="javascript:validate()">
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
				$con = mysqli_connect($servername, $username, $password, $dbname);
				// Check connection
				if ($con-> connect_error) {
		    		die("Connection failed: " . $con-> connect_error);
				}

				//fetching all teams and looping through the rows
				$sql = "SELECT title, QAtext, points, answer FROM questions";
				$result = mysqli_query($con, $sql);
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
		$con-> close();
		?>

</div>
</body>
</html>
