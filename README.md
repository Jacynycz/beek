![](beek.png)
# beek
Framework for domotizing houses using various raspberries pi 3

## Installation guide:

### 1. Master Beek: Raspberry Pi 3 acting as router

Install ```hostapd``` and ```isc-dhcp-server```
via:

```
sudo apt-get update
sudo apt-get install hostapd isc-dhcp-server
```

Backup of the default config:
```
sudo cp /etc/dhcp/dhcpd.conf /etc/dhcp/dhcpd.conf.default
```

Edit the defult config file:
```
sudo nano /etc/dhcp/dhcpd.conf
```

Comment the following lines:
```
#option domain-name "example.org";
#option domain-name-servers ns1.example.org, ns2.example.org;
```
Un-comment this line
```
# If this DHCP server is the official DHCP server for the local
# network, the authoritative directive should be uncommented.
authoritative;
```
Scroll down at the bottom of the file (CTRL + V) and paste:
```
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
Now, with this configuration we are assigning the subnet ```192.168.42.10–50```(40 devices in total) and we are configuring our WiFi local IP address to be ```192.168.42.1```. While we’re at it, we’re assigning Google’s public DNS: ```8.8.8.8```, ```8.8.4.4```.
Next, let’s specify on what interface should the DHCP server servce DHCP requests (__wlan0__ in this case):

```
sudo nano /etc/default/isc-dhcp-server
```

Edit this line:
```
# File /etc/default/isc-dhcp-server
INTERFACES="wlan0"
```

Let’s setup wlan0 for static IP:

TODO: COMPLETAR CON https://medium.com/@edoardo849/turn-a-raspberrypi-3-into-a-wifi-router-hotspot-41b03500080e
