import string
import logging
import tornado.auth
import tornado.escape
import tornado.httpserver
import tornado.ioloop
import tornado.options
import tornado.web 
import time  
import redis

from tornado.options import define, options
from pymongo import Connection
from collections import defaultdict
from datetime import datetime

import constellation.ConstellationUtils
from constellation.ConstellationSession import SessionHandler
from constellation.ConstellationSession import BaseHandler        
from constellation.MessageMixin import MessageMixin

class MessageUpdatesHandler(BaseHandler, MessageMixin):
		
		r = None
		#@tornado.web.authenticated
		@tornado.web.asynchronous
		
		def post(self):
				try:
					self.r = redis.StrictRedis(host=constellation.ConstellationUtils.getHost(), port=6379, db=0)
		
					self.userId = self.get_argument("u",0)
					self.shard = self.get_argument("p",None)
					self.film = self.get_argument("film",None)
					self.room = self.get_argument("room")
					self.instance = self.get_argument("instance")
					self.cursor = self.get_argument("cursor", None)
					self.moderator = int(self.get_argument("mdt", 0))
					self.ishost = int(self.get_argument("ishost", 0))
					self.moderated = self.get_argument("cmo","false") 
					self.sequence = int(self.get_argument("s",0))      
					self.sequence_approved = int(self.get_argument("a",0))      
					self.colorsequence = int(self.get_argument("c",0))     
					self.timestamp = float(self.get_argument("t",0))      
					
					if (options.type == "qanda"):
						#QANDA Messages are ROOMWIDE
						self.instance=""
						self.shard=0
						
					self.initcount( self.room, self.instance, self.shard, self.moderated )	
					
					print("Moderated is: " + str(self.moderated)) 
					print("Moderator is: " + str(self.moderator))
					print("Sequence is: " + str(self.sequence))
					print("CHAT Sequence Count is: " + str(constellation.ConstellationUtils.getCounter(self.room,self.instance,self.shard,"thecount")))
					print("QA Sequence Count is: " + str(constellation.ConstellationUtils.getCounter(self.room,self.instance,self.shard,"qacount")))
					print("Color Sequence is: " + str(self.colorsequence))
					print("Color Sequence Count is: " + str(constellation.ConstellationUtils.getCounter(self.room,self.instance,self.shard,"colorcount"))) 
					print("Approved Sequence is: " + str(self.sequence_approved))
					print("CHAT Approved Sequence Count is: " + str(constellation.ConstellationUtils.getCounter(self.room,self.instance,self.shard,"thecount_approved")))
					print("QA Approved Sequence Count is: " + str(constellation.ConstellationUtils.getCounter(self.room,self.instance,self.shard,"qacount_approved")))
					print ("TYPE IS " + options.type)
					
					constellation.ConstellationUtils.countRedisUser(self.r,self.room,self.instance,self.shard,self.userId)
					
					if (options.type == "chat"):
						self.current_approved_sequence = constellation.ConstellationUtils.getCounter(self.room,self.instance,self.shard,"thecount_approved")
						self.current_sequence = constellation.ConstellationUtils.getCounter(self.room,self.instance,self.shard,"thecount")
						self.current_colorsequence = constellation.ConstellationUtils.getCounter(self.room,self.instance,self.shard,"colorcount")
					elif (options.type == "qanda"):
						self.current_approved_sequence = constellation.ConstellationUtils.getCounter(self.room,self.instance,self.shard,"qacount_approved")
						self.current_sequence = constellation.ConstellationUtils.getCounter(self.room,self.instance,self.shard,"qacount")
						self.current_colorsequence = 0
						
					#Are we out of sequence?
					if ((self.moderated == "true") and (self.moderator == 0)):
						if (((self.sequence_approved == 0) and (self.sequence_approved < self.current_approved_sequence)) or ((self.colorsequence == 0) and (self.colorsequence < self.current_colorsequence))):
							print("INIT MODERATED (LIMITED) WITH " + str(self.sequence_approved) + " AND " + str(self.current_approved_sequence) + " | " + str(self.colorsequence) + " AND " + str(self.current_colorsequence))
							self.init()
						else:
							print("LOOP1")
							self.loop_for_messages(self.async_callback(self.on_new_messages)) 
					elif ((self.moderated == "true") and ((self.moderator == 1) or (self.ishost == 1))) or (self.moderated == "false"):
						if (((self.sequence == 0) and (self.sequence < self.current_sequence)) or ((self.colorsequence == 0) and (self.colorsequence < self.current_colorsequence))):
							print("INIT UNMODERATED WITH " + str(self.sequence) + " AND " + str(self.current_sequence) + " | " + str(self.colorsequence) + " AND " + str(self.current_colorsequence))
							self.init()
						else:
							print("LOOP2")
							self.loop_for_messages(self.async_callback(self.on_new_messages)) 
						
				except:
					constellation.ConstellationUtils.doError( self )
					
		def on_new_messages(self, messages, colormes):
				# Closed client connection
				if self.request.connection.stream.closed():
						return
				if (messages == None) and (colormes == None):
					self._output(None,None,None)
				elif options.type == "qanda":                                        
					self._output(None,None,messages)
				else:
					#self._outputtest(messages,colormes,None)
					self._output(messages,colormes,None)
