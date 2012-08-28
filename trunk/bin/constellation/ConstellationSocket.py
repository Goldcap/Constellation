#!/usr/bin/env python
#

import socket
import threading
import SocketServer

class ThreadedUDPRequestHandler(SocketServer.BaseRequestHandler):

		def handle(self):
				data = self.request[0].strip()
				socket = self.request[1]
				print "{} wrote:".format(self.client_address[0])
				print data
				socket.sendto(data.upper(), self.client_address)
				
class ThreadedUDPServer(SocketServer.ThreadingMixIn, SocketServer.UDPServer):
		pass
