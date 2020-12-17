#!/usr/bin/bash

m=`date -d +%m`
d=`date -d +%d`

if [ $m == '01' ]; then
  month='Jan'
fi

if [ $m == '02' ]; then
  month='Feb'
fi

if [ $m == '03' ]; then
  month='Mar'
fi

if [ $m == '04' ]; then
  month='Apr'
fi

if [ $m == '05' ]; then
  month='May'
fi

if [ $m == '06' ]; then
  month='Jun'
fi

if [ $m == '07' ]; then
  month='Jul'
fi

if [ $m == '08' ]; then
  month='Aug'
fi

if [ $m == '09' ]; then
  month='Sep'
fi

if [ $m == '10' ]; then
  month='Oct'
fi

if [ $m == '11' ]; then
  month='Nov'
fi


if [ $m == '12' ]; then
  month='Dec'
fi

day=`echo $d|sed s/^0//`

echo month=$month
echo day=$day

if [ $day -lt 10 ]; then
  dd=`echo "$month  $day"`
 else
  dd=`echo "$month $day"`
fi



less /var/log/maillog|grep "^$dd" > /tmp/mail.log.1
less `ls /var/log/maillog*|head -2|tail -1`|grep "^$dd" > /tmp/mail.log.0
cat /tmp/mail.log.0 /tmp/mail.log.1 > /tmp/mail.log && \
/usr/bin/chown poststat.poststat /tmp/mail.log
#chmod +r /var/postfix_logs/mail.log




