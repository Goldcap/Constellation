import string
import logging
import tornado.auth
import tornado.escape
import tornado.httpserver
import tornado.ioloop
import tornado.options
import tornado.web  
import pylibmc 
import memcache
import asyncmongo

from tornado.options import define, options
from pymongo import Connection
from collections import defaultdict
from datetime import datetime

import constellation.ConstellationUtils
from constellation.ConstellationSession import SessionHandler
from constellation.ConstellationSession import BaseHandler        
from constellation.MessageMixin import MessageMixin 

#Publishes and activity to listeners
class ReceiveHandler(tornado.web.RequestHandler, MessageMixin):

		@tornado.web.asynchronous
		
		def get(self):
				
				try:
					self.shard = self.get_argument("p", None)
					self.id = self.get_argument("id", None)
					#print(self.id)
					self.moderated = self.get_argument("cmo", None)
					self.moderator = int(self.get_argument("mdt", 0))
					self.asmoderator = int(self.get_argument("amo", 0))
					self.ishost = int(self.get_argument("ish", 0))
					
					if (self.id == None):
					  self.finish()
					else:
						if options.env == "andy":
							mc = memcache.Client([constellation.ConstellationUtils.getHost( self )+":11211"], debug=0)
						else:	
							mc = pylibmc.Client([constellation.ConstellationUtils.getHost( self )], binary=True, 
								behaviors={"tcp_nodelay": True,
								"ketama": True})
						response = mc.get(str(self.id))
						if response != None:
							self._on_found(response,None)
						else:
							#This is an insert, so use getHost
							self.aclient = asyncmongo.Client(pool_id='thepool', host=constellation.ConstellationUtils.getHost( self ), port=constellation.ConstellationUtils.getMongoShard( self.shard ), dbname=constellation.ConstellationUtils.getDBName(self))
							self.adb = self.aclient.connection(collectionname="chat")
							self.adb.find_one({"id":self.id}, callback=self._on_found)  
						
				except:
					constellation.ConstellationUtils.doError( self )
					
		def _on_found(self, response, error):
				
				self.room = response["room"]
				self.instance = response["instance"]
				
				self.initcount( self.room, self.instance, self.moderated )
					
				if (response["type"] == "colorme"):
					self.new_colorme([response], "false")
				elif (response["type"] == "qanda"):
					self.new_messages([response], "false")
					#self._output(None,None,response)
				elif ((self.asmoderator == 1) or (self.ishost == 1)):
					self.new_broadcast([response], "false")
					#self._output(response,None,None)
				else:                                       
					self.new_messages([response], "false")
				self.write("ok")
				self.finish()
				