#!/bin/bash
#sed -i '8s/.*/nadie/' /var/www/html/index.html
someoneconnected=true
for ((i=1;i<=20;i++)); do
	connected=$(iw dev wlan0 station dump)
	echo "Users connected now:" > /etc/beek/users.now
	while read p; do
	   MAC=${p%;*}
	   USR=${p#*;}
	   case "$connected" in
		*$MAC*) 
		   now=$(date)     	  
		   someoneconnected=false
		   echo $USR\;$now >> /etc/beek/log.txt
		   echo $USR >> /etc/beek/users.now
		   (runuser -l pi -c '/etc/beek/beek-music -s')
		;;
		*)
		   
		   ;;
	   esac
	done</etc/beek/trusted.conf
	
 	if [ "$someoneconnected" = true ] ; then
		echo none\;$now >> /etc/beek/log.txt
		echo "Nadie conectado"
		(runuser -l pi -c '/etc/beek/beek-music -t')
	fi
	
	pending=$(cat /etc/beek/.pending)
	if [ "$pending" != "" ]
	then
		(runuser -l pi -c '/etc/beek/beek-music sudoadd')
	fi
	sleep 3
done
