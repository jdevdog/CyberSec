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
        echo "<script type='text/javascript'>alert('$message');</script>";
			}
		}
    header("Location: " . $_SERVER['PHP_SELF']);
	}
?>

<html>
<head>
  <!--End of code from https://www.w3schools.com/css/css_table.asp -->
    <meta charset="utf-8">
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
    <link rel="stylesheet" type="text/css" href="./css/Questions.css">
    <link rel="stylesheet" type="text/css" href="../css/topnav.css">
</head>

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
              <a class="dropdown-item" href="./html/CyberFAQs.html"><h3>Cybersecurity FAQS</h3></a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="./index.php"><h3>Capture the Flag</h3></a>
            </div>
          </div>
        </div> <!-- img-fluid will scaled images to the size of their parent -->
        <div class="col-sm-6"><h1 id='headerTitle'>Cyber Security Website</h1></div>
        <div class="col-sm-3"></div>
        <div class="col-sm-2"><button type="button" class="btn btn-primary" id="login">Login</button></div>
      </div>
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
      echo "<div class='container' id='scoreboard'>";
      if ($result-> num_rows > 0) {
          while($row = $result-> fetch_assoc()) {
            echo "<h1>Your Team's Total Score: ".$row["score"]."</h1>";
          }
        }
      echo "</div>";
        //close connection
      $con-> close();
      }
  ?>
  <br>
<div class="body row">
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
         echo "<div class='col-sm-3'>";
         echo "<div class='container main'>";
         //MODAL TITLE UNOPENED
          echo "<h2>" . $row['title'] . "</h2>";
          //BUTTON TO OPEN THE MODAL
          echo "<button type='button' class='btn btn-primary' data-toggle='modal' data-target='#MyModal" . $qnum . "'> Open Question </button>";
          //THE MODAL
          echo "<div class='modal fade' id='MyModal" . $qnum . "'>";
            echo "<div class='modal-dialog'>";
              echo "<div class='modal-content'>";

              //MODAL HEADER
                echo "<div class='modal-header'>";
                  echo "<h4 class='modal-title'>" . $row["QAText"] . "</h4>";
                  echo "<button type='button' class='close' data-dismiss='modal'>&times;</button>";
                echo "</div>"; //CLOSE MODAL HEADER

                //MODAL BODY
                echo "<div class='modal-body'>";
                  echo "<form class='form-inline' action='".$_SERVER['PHP_SELF']."' method='post'>";
                    echo "<input type='text' name='answer' class='form-control' id='answerT' placeholder='Enter your answer here.'>";
                    echo "<button type='submit' name='submit' class='btn btn-primary' value='".$qnum."'> Submit </button>";
                  echo "</form>";//CLOSE FORM
                echo "</div>"; //CLOSE MODAL-BODY

                echo "<div class='modal-footer'>";
                  echo "<p id='tries'> Attempts Left: ".$row['max_attempts']."</p>";
                echo "</div>";//CLOSE FOOTER
               echo "</div>"; //CLOSE MODAL-CONTENT
              echo "</div>"; //CLOSE MODAL-DIALOG
             echo "</div>"; //CLOSE MODAL
            echo "</div>"; //CLOSE CONTAINER
            echo "</div>"; //CLOSE SM-COL-4
          $qnum++;
       }
     }

   $con-> close(); //CLOSE CONNECTION
   ?>
</div>
</body>

</html>
