<?php
//Checks if the answer provided is correct. If not, it increments the number of attempts. If correct, if awards points to the team.
//If the number of attempts is >= the max attempts, the question is marked as inactive. If the answer was correct, the question is also marked as inactive.
function checkAnswer ($qaid, $teamid, $answer)
{
  include "credentials.php";
  $con = new mysqli($servername, $username, $password, $dbname);
  // Check connection
  if ($con-> connect_error)
  {
    die("Connection failed: " . $con-> connect_error);
  }

  $stmt = $con->prepare("SELECT attempts, max_attempts, score, points, answer FROM questionattempts
                         JOIN teams ON teams.team_id = questionattempts.team_id
                         JOIN questions ON questions.qa_id = questionattempts.qa_id
                         WHERE teams.team_id = ? AND questions.qa_id = ?");
  $stmt->bind_param('ii', $teamid, $qaid);
  $stmt->execute();
  $result = $stmt->get_result();
  $qa = $result->fetch_object();

  if($qa->answer == $answer)
  {
    $newScore = $qa->score + $qa->points;
    $stmt = $con->prepare("UPDATE teams SET score = ? WHERE team_id = ?");
    $stmt->bind_param('ii', $newScore, $teamid);
    $stmt->execute();

    $stmt = $con->prepare("UPDATE questionattempts SET active = false WHERE qa_id = ? AND team_id = ?");
    $stmt->bind_param('ii', $qaid, $teamid);
    $stmt->execute();
  }
  else
  {
    $newAttempts = $qa->attempts + 1;
    $stmt = $con->prepare("UPDATE questionattempts SET attempts = ? WHERE qa_id = ? AND team_id = ?");
    $stmt->bind_param('iii', $newAttempts, $qaid, $teamid);
    $stmt->execute();

    if($qa->attempts + 1 >= $qa->max_attempts)
    {
      $stmt = $con->prepare("UPDATE questionattempts SET active = false WHERE qa_id = ? AND team_id = ?");
      $stmt->bind_param('ii', $qaid, $teamid);
      $stmt->execute();
    }
  }
}


function checkActive($teamid, $qaid)
{
  include "credentials.php";
  $con = new mysqli($servername, $username, $password, $dbname);
  // Check connection
  if ($con-> connect_error)
  {
    die("Connection failed: " . $con-> connect_error);
  }

  $stmt = $con->prepare("SELECT active FROM questionattempts WHERE team_id = ? AND qa_id = ?");
  $stmt->bind_param('ii', $teamid, $qaid);
  $stmt->execute();
  $result=$stmt->get_result();
  $qa = $result->fetch_object();

  if($qa->active == true)
  {
    return true;
  }
  else
  {
    return false;
  }
}

function getAttempts($teamid, $qaid)
{
  include "credentials.php";
  $con = new mysqli($servername, $username, $password, $dbname);
  // Check connection
  if ($con-> connect_error)
  {
    die("Connection failed: " . $con-> connect_error);
  }

  $stmt = $con->prepare("SELECT attempts, max_attempts FROM questionattempts
                         JOIN teams ON teams.team_id = questionattempts.team_id
                         JOIN questions ON questions.qa_id = questionattempts.qa_id
                         WHERE teams.team_id = ? AND questions.qa_id = ?");
  $stmt->bind_param('ii', $teamid, $qaid);
  $stmt->execute();
  $result = $stmt->get_result();
  $qa = $result->fetch_object();

  return $qa->max_attempts - $qa->attempts;
}
?>
