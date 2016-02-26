<?php
  session_start();

  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "exchange";

  // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $bitcoinVal = 1;
  setcookie('bitcoins', $bitcoinVal);

  if (!isset($_SESSION)) {
     header("Location: index.php");
  } else {
     if (isset($_COOKIE['bitcoins'])) {
        $bitVal = $_COOKIE['bitcoins'];

        if ($bitVal > 5) {
           $transId = md5($bitVal) . md5("m4dh4tz");
 
           echo "<script>document.write(\"Your transaction id is: $transId\");</script>";
        }
     }
  }
?>

<html>
  <head>
    <title>M4dH4t'z Bitcoin Exchange</title>
  </head>
  <body bgcolor="lightblue">
    <center>
    <table style="padding-left: 1em; padding-right: 1em; background: white;border-radius:10px;-moz-border-radius:10px;-webkit-border-radius:10px;">
      <tr>
        <td align="center">
          <b><h3>Welcome to M4dH4t'z Bitcoin Exchange</h3></b>
        </td>
      </tr>
    </table>
    <br /><br />
    <table style="padding-left: 1em; padding-right: 1em; background: white;border-radius:10px;-moz-border-radius:10px;-webkit-border-radius:10px;">
      <tr>
        <td align="center">
          <b>Welcome, <?php echo $_SESSION['user']; ?>, You have <?php echo $bitVal; ?> Bitcoin</b>
        </td>
      </tr>
      <tr>
        <td align="center">
          <br /><br />
          In order to obtain a transaction id, you need at least 5 BTC. 
      <!--    <br />
          When you have enough, please submit the payment and receive a transaction id.
      -->
        </td>
      </tr>
    </table>
<!--    <br /><br />
    <form action="#">
    <table style="padding-left: 1em; padding-right: 1em; background: white;border-radius:10px;-moz-border-radius:10px;-webkit-border-radius:10px;">
      <tr>
        <td align="center">
          Click Submit when you have gotten enough Bitcoin to make a transaction.
          <br />
        </td>
      </tr>
      <tr>
        <td align="center">
          <input type="submit" value="Submit">
        </td>
      </tr>
    </table>
    </form>
-->
    </center>
  </body>
</html>
