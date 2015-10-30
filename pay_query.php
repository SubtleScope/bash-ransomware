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

$getTargetID = $_GET['unique_id'];
$timestamp = time();
$dateTimestamp = new DateTime("@$timestamp");

$timestamp = $dateTimestamp->format('Y-m-d H:i:s');

if (isset($_GET['unique_id']) && !empty($_GET['unique_id'])) {
   $sql = "SELECT timediff(exp_time, \"$timestamp\") as time_left from target_list where unique_id = \"$getTargetID\"";
   $result = $conn->query($sql);

   if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
         echo "<font size=\"5\">";
         echo "<b>$row[time_left]</b>";
         echo "</font>";
      }
   }
}

$conn->close();

?>
