import string
import logging
import tornado.auth
import tornado.escape
import tornado.httpserver
import tornado.ioloop
import tornado.options
import tornado.web 
import asyncmongo
import datetime
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

class MessageApproveHandler(BaseHandler, MessageMixin):

		r = None

		id = ''
		message = ''
		room = 'x'

		@tornado.web.authenticated
		@tornado.web.asynchronous

		def get(self):
				
				self.r = redis.StrictRedis(host=constellation.ConstellationUtils.getHost(), port=6379, db=0)
	
				try:
					
					self.userId = self.get_argument("u",0)
					self.shard = self.get_argument("p",None)
					self.room=self.get_argument("room")
					self.instance=self.get_argument("instance")
					#QANDA Messages are ROOMWIDE
					if (options.type == "qanda"):
						self.instance=""
						self.shard=0
					self.moderator=int(self.get_argument("mdt",0))
					self.moderated=self.get_argument("cmo","false")
					self.allow=int(self.get_argument("allow",-1))
					self.cursor=self.get_argument("cursor")
					self.promote=self.get_argument("reply_id","false")
					self.asmoderator = 0
					self.type = options.type
					thefrom = str(self.get_secure_cookie("user")).replace("\n","").split('@')
					self.isfrom = thefrom[0]
					
					#print ("TYPE IS " + self.type)
					
					self.ishost=self.get_argument("ishost",0)
					if (self.ishost == "true"):
						self.ishost= 1
					elif ((self.ishost == "false") or (self.ishost == "undefined")):
						self.ishost = 0
					else:
						self.ishost = int(self.ishost)
						
					self.asmoderator = 1
					
					if (self.ishost == 0) and (self.moderator == 0):
						self.finish()
					else:
						#This is a read, so use getMongoReadHost
						aclient = asyncmongo.Client(pool_id='thepool', host=constellation.ConstellationUtils.getMongoReadHost(), port=constellation.ConstellationUtils.getMongoShard( self.shard ), dbname=constellation.ConstellationUtils.getDBName())
						adb = aclient.connection(collectionname="chat")
	
						adb.find_one({"room":self.room,"instance":self.instance,"id":self.cursor}, callback=self._on_response)
			
				except:
					constellation.ConstellationUtils.doError( self )
		
		def _on_response(self, response, error):
				
				#print len(response)
				#Add this message to the "Approved" Queue
				self.pychat = defaultdict(list)
				self.pychat[unicode('id')] = unicode(response["id"])
				self.pychat[unicode('isfrom')] = unicode(response["isfrom"])
				self.pychat[unicode('author')] = int(response["author"])
				self.pychat[unicode('user_image')] = unicode(response["user_image"])
				self.pychat[unicode('ishost')] = int(response["ishost"])
				self.pychat[unicode('to')] = unicode('public')
				self.pychat[unicode('body')] = unicode(response["body"].replace('\n','<br />\n'))
				self.pychat[unicode('film')] = int(response["film"])
				self.pychat[unicode('room')] = unicode(response["room"])
				self.pychat[unicode('instance')] = unicode(response["instance"])
				self.pychat[unicode('pair')] = unicode('public')
				self.pychat[unicode('mod_time')] = float(response["mod_time"])
				self.pychat[unicode('type')] = unicode(response["type"])
				self.pychat[unicode('sequence')] = int(response["sequence"])
				self.pychat[unicode('sequence_approved')] = int(response["sequence_approved"])
				self.pychat[unicode('approved')] = int(response["approved"])
				self.pychat[unicode('asmoderator')] = int(response["asmoderator"])
				self.pychat[unicode('colorme')] = int(response["colorme"])
									
				#Remove the old message from Redis Queues
				if self.allow == 1:
					constellation.ConstellationUtils.unIndexRedisChat( self.r, self.shard, response, self.type, self.pychat, "unapproved" )
				else:
					constellation.ConstellationUtils.unIndexRedisChat( self.r, self.shard, response, self.type, self.pychat, "unapproved" )
				
				#This is an insert, so use getHost
				aclient = asyncmongo.Client(pool_id='thepool', host=constellation.ConstellationUtils.getHost(), port=constellation.ConstellationUtils.getMongoShard( self.shard ), dbname=constellation.ConstellationUtils.getDBName())
				adb = aclient.connection(collectionname="chat")
				
				cls=MessageMixin
				if (self.allow == 1):
					if (options.type == "chat"):
						thecount_approved = constellation.ConstellationUtils.getCounter(self.room,self.instance,self.shard,"thecount_approved")
						if (thecount_approved != None):
							thecount_approved = constellation.ConstellationUtils.incCounter(self.room,self.instance,self.shard,"thecount_approved")
						else:
							thecount_approved = constellation.ConstellationUtils.setCounter(self.room,self.instance,self.shard,"thecount_approved",1)
					elif (options.type == "qanda"):
						thecount_approved = constellation.ConstellationUtils.getCounter(self.room,self.instance,self.shard,"qacount_approved")
						if (thecount_approved != None):
							thecount_approved = constellation.ConstellationUtils.incCounter(self.room,self.instance,self.shard,"qacount_approved")
						else:          
							thecount_approved = constellation.ConstellationUtils.setCounter(self.room,self.instance,self.shard,"qacount_approved",1)
				else:
					thecount_approved = -1
				
				#Keep the timestamp as close to the update as possible
				self.timestamp = time.time()
				
				self.pychat[unicode('mod_time')] = unicode(self.timestamp)
				self.pychat[unicode('sequence_approved')] = int(thecount_approved)
				self.pychat[unicode('approved')] = unicode(self.allow)
				
				#Relocate the Redis Message as Needed
				if self.allow == 1:
					self.pychat[unicode('approved')] = int(1)
					constellation.ConstellationUtils.indexRedisChat( self.r, self.shard, response, self.type, self.pychat, "approved" )
				else:
					self.pychat[unicode('approved')] = int(0)
					constellation.ConstellationUtils.indexRedisChat( self.r, self.shard, response, self.type, self.pychat, "denied" )
						
				adb.update({"room":self.room,"instance":self.instance,"id":self.cursor}, {"$set": {"approved": self.allow, "sequence_approved": thecount_approved, "mod_time": self.timestamp }}, callback=self._on_finish)
       	
		def _on_finish(self, response, error):
				
				if error:
					raise tornado.web.HTTPError(500)
				if (len(response) > 0):
					
					if (self.pychat["type"] == "chat"):
						self._output(response,None,None)
					else:
						self._output(None,None,response)
				else:
					self.finish()
				