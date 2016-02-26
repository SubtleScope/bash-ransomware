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

  $user = $_POST["username"];
  $passwd = $_POST["password"];

  if (isset($user) && isset($passwd)) {
    $user = $conn->real_escape_string($user);
    $pass = $conn->real_escape_string($passwd);

    $ret = FALSE;
    $pwd = md5(sha1(md5($user . $pass)));
   
    $sql = "SELECT username FROM users WHERE username = \"$user\" AND password = \"$pwd\"";

    $result = $conn->query($sql);
    $row = $result->fetch_array(MYSQLI_ASSOC);

    if ($row['username'] == $user) {
       session_start();

       $_SESSION['user'] = $user;
       $_SESSION['expireTime'] = time() + 600;

       header("Location: exchange.php");
    } else {
      echo "<script>alert('An error has occured, please try again');</script>";
      header("Location: index.php");
    }
  } else {
    header("Location: index.php");
  }
?>
