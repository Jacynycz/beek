#!/bin/bash

if [[ $(pgrep -c vlc) < 1 ]]
then
OPTIND=1
while getopts "h?i:" opt; do
	case "$opt" in
		h|\?)
			show_help
			exit 0
			;;
		i) 
			echo "Server running"
			exit 0
			;;
	esac
done

shift $((OPTIND-1))

[ "$1" = "--" ] && shift

usrdir="/home/pi/Music/$@"

if [ -d $usrdir ]; then
cvlc -I rc --random --loop $usrdir/*.wav -q --rc-host $hostname:8334 &
echo "Starting $@'s playlist at port 8334 with PID $!"
else
mkdir -p $usrdir
cvlc -I rc --playlist-autostart ~/Music/default/*.wav -q --rc-host $hostname:8334 &
echo "Starting vlc at port 8334 with PID $!"
fi

else
echo "Already running"
fi
