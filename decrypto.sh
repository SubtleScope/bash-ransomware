#!/bin/bash

chmod 755 /root/priv.pem

openssl rsautl -decrypt -inkey /root/priv.pem -in /root/key.bin.enc -out /root/key.bin

openssl enc -aes-256-cbc -d -in "/root/..file_mapping.db.owned" -out "/root/..file_mapping.db" -pass file:/root/key.bin

for file in $(cat /root/..file_mapping.db)
do
  encFile=$(echo "${file}" | awk -F"," '{ print $2 }')
  origFile=$(echo "${file}" | awk -F"," '{ print $1 }')

  openssl enc -aes-256-cbc -d -in "${encFile}" -out "${origFile}" -pass file:/root/key.bin

  rm -rf "${encFile}"
done

for directory in $(find /root/ /home/ /etc/ /bin/ /usr/sbin/ /usr/bin /sbin/ /usr/local/bin/ -type d)
do
    rm -rf "${directory}/INSTRUCTIONS.txt"
done

crontab -r 
rm -rf /etc/cron.hourly/instructions.sh
rm -rf /root/..file_mapping.db /root/..file_mapping.db.owned
rm -rf /root/key.bin /root/key.bin.enc /root/priv.pem /root/pub.pem 
