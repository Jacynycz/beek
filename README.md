![](beek.png)
# beek
Framework for domotizing houses using various raspberries pi 3

## Installation guide:

### 1. Master Beek: Raspberry Pi 3 acting as router

Install `hostapd` and `isc-dhcp-server`
via:

```shell
sudo apt-get update
sudo apt-get install hostapd isc-dhcp-server
```
#### DHCP server

Backup of the default config:
```shell
sudo cp /etc/dhcp/dhcpd.conf /etc/dhcp/dhcpd.conf.default
```

Edit the defult config file:
```shell
sudo nano /etc/dhcp/dhcpd.conf
```

Comment the following lines:
```shell
#option domain-name "example.org";
#option domain-name-servers ns1.example.org, ns2.example.org;
```
Un-comment this line
```shell
# If this DHCP server is the official DHCP server for the local
# network, the authoritative directive should be uncommented.
authoritative;
```
Scroll down at the bottom of the file (CTRL + V) and paste:
```shell
subnet 192.168.42.0 netmask 255.255.255.0 {
    range 192.168.42.10 192.168.42.50;
    option broadcast-address 192.168.42.255;
    option routers 192.168.42.1;
    default-lease-time 600;
    max-lease-time 7200;
    option domain-name "local";
    option domain-name-servers 8.8.8.8, 8.8.4.4;
}
```
Now, with this configuration we are assigning the subnet `192.168.42.10–50`(40 devices in total) and we are configuring our WiFi local IP address to be `192.168.42.1`. While we’re at it, we’re assigning Google’s public DNS: `8.8.8.8`, `8.8.4.4`.
Next, let’s specify on what interface should the DHCP server servce DHCP requests (__wlan0__ in this case):

```shell
sudo nano /etc/default/isc-dhcp-server
```

Edit this line:
```shell
# File /etc/default/isc-dhcp-server
INTERFACES="wlan0"
```

Let’s setup wlan0 for static IP:

Shut it down:
```shell
sudo ifdown wlan0
```
Make a backup file:
```shell
sudo cp /etc/network/interfaces /etc/network/interfaces.backup
```
Edit the network interfaces file:
```shell
sudo nano /etc/network/interfaces
```

Edit accordingly to read:
```shell
source-directory /etc/network/interfaces.d
auto lo
iface lo inet loopback
iface eth0 inet dhcp
allow-hotplug wlan0
iface wlan0 inet static
  address 192.168.42.1
  netmask 255.255.255.0
  post-up iw dev $IFACE set power_save off
```

Close the file and assign a static IP now:
```shell
sudo ifconfig wlan0 192.168.42.1
```
#### Hostapd

Create a file and edit it:
```shell
sudo nano /etc/hostapd/hostapd.conf
```
Modify ssid with a name and wpa_passphrase to a password
```shell
interface=wlan0
ssid=WiPi
hw_mode=g
channel=6
macaddr_acl=0
auth_algs=1
ignore_broadcast_ssid=0
wpa=2
wpa_passphrase=xyz
wpa_key_mgmt=WPA-PSK
wpa_pairwise=TKIP
rsn_pairwise=CCMP
```
Next, let’s configure the network address translation:

Create a backup file
```shell
sudo cp /etc/sysctl.conf /etc/sysctl.conf.backup
```
Edit the config file
```shell
sudo nano /etc/sysctl.conf
```
Add to the bottom:
```shell
net.ipv4.ip_forward=1
```
Activate it immediately:
```shell
sudo sh -c "echo 1 > /proc/sys/net/ipv4/ip_forward"
```
Modify the iptables to create a network translation between eth0 and the wifi port wlan0
```shell
sudo iptables -A FORWARD -i eth0 -o wlan0 -m state --state RELATED,ESTABLISHED -j ACCEPT
sudo iptables -t nat -A POSTROUTING -o eth0 -j MASQUERADE
sudo iptables -A FORWARD -i wlan0 -o eth0 -j ACCEPT
```
Make this happen on reboot by runnig
```shell
sudo sh -c "iptables-save > /etc/iptables.ipv4.nat"
```
And editing again
```shell
sudo nano /etc/network/interfaces
```
Appending at then end:
```shell
up iptables-restore < /etc/iptables.ipv4.nat
```
 Let's clean everything:
```shell
 sudo service hostapd start
 sudo service isc-dhcp-server start
```
 And make sure that we're up and running:
```shell
 sudo service hostapd status
 sudo service isc-dhcp-server status
```
 Configure our daemons to start at boot time:
```shell
 sudo update-rc.d hostapd enable
 sudo update-rc.d isc-dhcp-server enable
```
 Reboot the pi.
 
```shell
 sudo reboot
```

