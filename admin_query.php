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
               $sql1 = "UPDATE target_list SET time_expired=1 where unique_id = \"$_GET[unique_id]\"";
               $result1 = $conn->query($sql1);

               if ($result1->num_rows >= 0) {
                  echo "<font color=\"blue\">";
                  echo "Expired!";
                  echo "</font>";
               } else {
                  echo "<font color=\"green\">";
                  echo "$row[time_left]";
                  echo "</font>";
               }
            } else {
               echo "<font color=\"#006400\">";
               echo "$row[time_left]";
               echo "</font>";
            }
      }
   } else {
        echo "<br />Could not determine the time left!</br>";
   }  
}

$conn->close();

?>
