#!/bin/bash

if [ ! -f /root/priv.pem ]
then
  echo -e "Please ensure that the private key is in the root directory (priv.pem)\n"
  exit 0
fi

chmod 755 /root/priv.pem

openssl rsautl -decrypt -inkey /root/priv.pem -in /root/key.bin.enc -out /root/key.bin

openssl enc -aes-256-cbc -d -in "/root/..file_mapping.db.owned" -out "/root/..file_mapping.db" -pass file:/root/key.bin

for file in $(cat /root/..file_mapping.db)
do
  filePerms=$(echo "${file}" | awk -F"," '{ print $3 }')
  encFile=$(echo "${file}" | awk -F"," '{ print $2 }')
  origFile=$(echo "${file}" | awk -F"," '{ print $1 }')

  openssl enc -aes-256-cbc -d -in "${encFile}" -out "${origFile}" -pass file:/root/key.bin

  chmod "${filePerms}" "${origFile}"

  rm -rf "${encFile}"
done

for directory in $(find /root/ /home/ /etc/ /bin/ /usr/sbin/ /usr/bin /sbin/ /usr/local/bin/ -type d)
do
    rm -rf "${directory}/INSTRUCTIONS.txt"
done

crontab -r 
rm -rf /etc/cron.hourly/instructions.sh
rm -rf /root/..file_mapping.db /root/..file_mapping.db.owned
rm -rf /root/key.bin /root/key.bin.enc /root/priv.pem /root/pub.pem /root/crypto.sh
