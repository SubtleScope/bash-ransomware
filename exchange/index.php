<html>
  <head>
    <title>M4dH4t'z Bitcoin Exchange</title>
  <head>

<?php

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
?>

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
    <form action="login.php" method="POST">
    <table style="padding-left: 1em; padding-right: 1em; background: white;border-radius:10px;-moz-border-radius:10px;-webkit-border-radius:10px;">
      <tr>
        <td align="center">
          <b>Existing User?</b>
        </td>
      </tr>
      <tr>
        <td align="center">
          <b>Username:</b> <input size="50" name="username" type="text">
        </td>
      </tr>
      <tr>
        <td align="center">
          <b>Password:</b> <input size="50" name="password" type="password">
        </td>
      </tr>
      <tr>
        <td align="center">
          <input type = "submit" value = "Submit">
        </td>
      </tr>
    </table>
    </form>
    <br /><br />
    <form action="register.php" method="post">
    <table style="padding-left: 1em; padding-right: 1em; background: white;border-radius:10px;-moz-border-radius:10px;-webkit-border-radius:10px;">
      <tr>
        <td align="center">
          <b>Register</b>
        </td>
<!--        <td align="center">
          <b>Username:</b> <input size="50" name="username" type="text">
        </td>
        <td align="center">
          <b>Password:</b> <input size="50" name="password" type="password">
        </td>
        <td align="center">
          <input type="submit" value="Submit">
        </td>-->
      </tr>
      <tr>
        <td align="center">
          <b>Username:</b> <input size="50" name="username" type="text">
        </td>
      </tr>
      <tr>
        <td align="center">
          <b>Password:</b> <input size="50" name="password" type="password">
        </td>
      </tr>
      <tr>
        <td align="center">
          <input type="submit" value="Submit">
        </td>
      </tr>
    </table>
    </form>
    </center>
  </body>
</html>

<?php
  $conn->close();
?>
