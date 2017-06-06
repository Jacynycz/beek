from socket import *
from sense_emu import SenseHat
import os
import subprocess

sock = socket(AF_INET, SOCK_STREAM)
sense = SenseHat()

server_address = os.popen('ifconfig wlan0').read().split("inet addr:")[1].split("Bcast")[0].strip()
server_port = 8334
sock.bind((server_address, server_port))
sock.listen(1)
print("Listening on " + server_address + ":" + str(server_port))

while True:
	#payload, client_address = sock.recvfrom(1024)
	#print("Echoing back to " + str(client_address))
	#print(payload)
	#sent = sock.sendto(payload, client_address)
	connection, client_address = sock.accept()
	try:
		data,address = connection.recvfrom(1024)
		data = data.replace( '\r' , '').replace('\r','').strip()
		#print("Recibidos "+data+" datos")
		if data == "sense":
			response = str(sense.get_temperature())+"\n"
			connection.sendall(str(response))
		if data == "raise":
			response = "Encendiendo el calefactor"
			connection.sendall(str(response))
		if data == "lower":
			response = "Endendiendo el aire acondicionado"
			connection.sendall(str(response))			
		if data == "abort":
			break
	finally:
		connection.close()

