<!DOCTYPE html>
<?php
session_start();

function gen_pass(int $length=16) : string {
    $pass = '';
    for ($i = 0; $i < $length; $i++) {
        $pass .= chr(random_int(65, 122));
    }

    return $pass;
}
?>
<html>
    <head>
    <title>Admin section</title>
    <link rel="stylesheet" href="./css/admin.css">
    </head>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <body>
        <div id="navi">
    <a class="link" href="index.php">Login</a><a class="link" href="CTFSco.php">Score</a><a class="link" href="CTFQuestions.php">Questions</a><a class="link" href="CTFAdmin.php">Admin</a>
    </div>
    <div id = "header">

<?php
if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != 1) {
    echo '<h1 id="welcome">You do not have permission to access this page.</h1>';
    echo '</div></body></html>';
    exit();
}

include "credentials.php";
// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if ($conn-> connect_error) {
    die("Connection failed: " . $conn-> connect_error);
}

if (isset($_POST['teamname'])) {
    $myteam = mysqli_real_escape_string($conn, $_POST['teamname']);
    $pass = gen_pass();
    $sql = "INSERT INTO teams (score, name, password) VALUES (0, '" . $myteam . "', '" . $pass . "');";
    $create_success = mysqli_query($conn, $sql);
}

if (isset($_GET['action']) && isset($_GET['id'])) {
    $myteam = mysqli_real_escape_string($conn, $_GET['id']);

    switch ($_GET['action']) {
    case 'remove':
        $sql = 'DELETE FROM teams WHERE team_id=' . $_GET['id'] . ';';
        if (mysqli_query($conn, $sql)) {
            $manage_message = 'Successfully removed team.';
        } else {
            $manage_message = 'Could not remove team. Was it already removed?';
        }
        break;

    case 'reset':
        $pass = gen_pass();
        $sql = 'UPDATE teams SET password=\'' . $pass . '\' WHERE team_id=' . $_GET['id'] . ';';
        if (mysqli_query($conn, $sql)) {
            $manage_message = 'Password changed to \'' . $pass . '\'.';
        }
        break;
    }
}
?>

        <h1 id = "welcome">Welcome, Admin</h1>
    </div>
    <h2>Add a Team</h2>
    <h1 id="CTF"></h1>
    <form method="post">
        <h2>Team Name <input type="text"  name="teamname" placeholder="Name">
        <input id="btn" type="submit" value="Create"></h2>
    </form>

<?php
if (isset($create_success)) {
    if ($create_success) {
        echo '<h2>Team added. Password (will not be displayed again): ' .
            $pass . '</h2>';
    } else {
        echo '<h2>Failed to add team. Ensure that the team does not already exist.</h2>';
    }
}
?>

<table id="teams">
<tr><th>Team</th><th>Scores</th><th>Actions</th></tr>
<?php
//fetching all teams and looping through the rows
$sql = "SELECT team_id, score, name FROM teams order by score desc";
$result = mysqli_query($conn, $sql);  							//$conn-> query($sql);
if ($result-> num_rows > 0) {
    while($row = $result-> fetch_assoc()) {
        echo "<tr><td>".$row["name"]."</td><td>".$row["score"]."</td>";
        echo "<td><a href=\"?action=remove&id=".$row["team_id"]."\">Remove</a></br>";
        echo "<a href=\"?action=reset&id=".$row["team_id"]."\">Reset Password</a></tr>";
    }
}
else {
    echo "<h1>No Teams Available<h1>";
}

if (isset($manage_message)) {
    echo "<h2>" . $manage_message . "</h2>";
}

?>
</table>

    </body>
</html>

<?php
$conn-> close();
?>
