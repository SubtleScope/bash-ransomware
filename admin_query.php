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

$timestamp = time();
$dateTimestamp = new DateTime("@$timestamp");

$timestamp = $dateTimestamp->format('Y-m-d H:i:s');

if (isset($_GET['unique_id']) && !empty($_GET['unique_id'])) {
   $uniqueID = $_GET['unique_id'];

   $sql = "SELECT timediff(exp_time, \"$timestamp\") as time_left from target_list where unique_id = \"$uniqueID\"";
   $result = $conn->query($sql);

   if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
            if ($row['time_left'] < "00:00:01") {
               $sql1 = "UPDATE target_list SET time_expired=\"TRUE\" where unique_id = \"$targetID\"";
               $result1 = $conn->query($sql);

               if ($result->num_rows > 0) {
                  echo "Expired!";
               }
            } else {
               echo "$row[time_left]";
            }
      }
   } else {
        echo "<br />Could not determine the time left!</br>";
   }  
}

$conn->close();

?>
