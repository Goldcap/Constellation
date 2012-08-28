import string
import logging
import tornado.auth
import tornado.escape
import tornado.httpserver
import tornado.ioloop
import tornado.options
import tornado.web 
import threading
import urllib
import urllib2
import asyncmongo
import json 
import redis
 
from time import time
from tornado.options import define, options
from pymongo import Connection
from collections import defaultdict
from datetime import datetime

import constellation.ConstellationUtils 
from constellation.MongoEncoder import MongoEncoder
from constellation.UrlLib import MyHandler

class MessageMixin(object):
		
		waiters = defaultdict(list)
		moderators = defaultdict(list)
		chat_daemons = defaultdict(list)
		thecount = None
		thecount_approved = None
		colorcount = None   
		colorcount_approved = None
				
		room = 'x'
		film = '0'
		
		#@tornado.web.authenticated
		@tornado.web.asynchronous
		def loop_for_messages(self, callback, t=50):
				
				messages = None
				unapprovedmessages = None
				deniedmessages = None
				colormes = None
				
				#print (str(self.room)+":"+str(self.instance)+":shard"+str(self.shard)+":"+str(options.type)+":approved")
				messages = self.r.zrangebyscore(str(self.room)+":"+str(self.instance)+":shard"+str(self.shard)+":"+str(options.type)+":approved", self.timestamp, time())	
				
				if ((self.moderated == "true") and ((self.moderator == 1) or (self.ishost == 1))) or ((options.type == "qanda") and ((self.moderator == 1) or (self.ishost == 1))):
					#print "MODERATED"
					unapprovedmessages = self.r.zrangebyscore(str(self.room)+":"+str(self.instance)+":shard"+str(self.shard)+":"+str(options.type)+":unapproved", self.timestamp, time())	
					deniedmessages = self.r.zrangebyscore(str(self.room)+":"+str(self.instance)+":shard"+str(self.shard)+":"+str(options.type)+":denied", self.timestamp, time())	
						
				if options.type != "qanda":
					colormes = self.r.zrangebyscore(str(self.room)+":"+str(self.instance)+":shard"+str(self.shard)+":"+"colorme:approved", self.timestamp, time())	
					#colormes = db.chat.find({"room":self.room,"instance":self.instance,"type":"colorme","mod_time":{"$gt":self.timestamp}})	 
				
				#print "FOUND " + str(len(messages)) + " APPROVED MESSAGES"
				#connection.disconnect()                                  
				#print "FOUND " + str(len(unapprovedmessages)) + " UNAPPROVED MESSAGES"
				
				if ((((messages == None) or (len(messages) == 0)) and ((colormes == None) or (len(colormes) == 0)) and ((unapprovedmessages == None) or (len(unapprovedmessages) == 0)) and ((deniedmessages == None) or (len(deniedmessages) == 0))) and (t)):
					tornado.ioloop.IOLoop.instance().add_timeout(time() + .25, lambda: self.loop_for_messages(callback, t-1))
				else:
					#print "FOUND " + str(len(colormes)) + " COLORME MESSAGES"
					#print "TYPE IS " + options.type
					colorme_package = None	
					message_package = None
					if (((messages != None) and (len(messages) > 0)) or ((colormes != None) and (len(colormes) > 0)) or ((unapprovedmessages != None) and (len(unapprovedmessages) > 0)) or ((deniedmessages != None) and (len(deniedmessages) > 0))):
						if ((messages != None) and (len(messages) > 0)):
							message_package = []
							for item in messages:
								obj = json.loads(item)
								message_package.append(obj)
						
						if ((unapprovedmessages != None) and (len(unapprovedmessages) > 0)):
							if message_package == None:
								message_package = []
							for item in unapprovedmessages:
								obj = json.loads(item)
								message_package.append(obj)
											
						if ((deniedmessages != None) and (len(deniedmessages) > 0)):
							if message_package == None:
								message_package = []
							for item in deniedmessages:
								obj = json.loads(item)
								message_package.append(obj)
											
						if ((colormes != None) and (len(colormes) > 0)):
							colorme_package = []
							for item in colormes:
								obj = json.loads(item)
								colorme_package.append(obj)
						else:
							colorme_package = None
				  
					callback( message_package, colorme_package )
			
		def initcount(self,room,instance,shard,moderated):
			
			#print "Count for Room " + str(room) + ":" + instance + " IS " + str(constellation.ConstellationUtils.getCounter(room,instance,shard,"thecount"))
			if constellation.ConstellationUtils.getCounter(room,instance,shard,"thecount") == 0:
				lastchat = self.r.zcount(str(room+":"+instance+":shard"+str(shard)+":chat"+":approved"),0,"+inf")
				if (lastchat > 0):
					constellation.ConstellationUtils.setCounter(room,instance,shard,"thecount",lastchat)
				else:
					constellation.ConstellationUtils.setCounter(room,instance,shard,"thecount",0)
			
			#Get the approved sequence count for this room+instance from memcached
			if (self.moderated == "true") and (constellation.ConstellationUtils.getCounter(room,instance,shard,"thecount_approved") == 0):
				lastchat = self.r.zcount(str(room+":"+instance+":shard"+str(shard)+":colorme"+":approved"),0,"+inf")
				if (lastchat > 0):
					constellation.ConstellationUtils.setCounter(room,instance,shard,"thecount_approved",lastchat)
				else:
					constellation.ConstellationUtils.setCounter(room,instance,shard,"thecount_approved",0)
			
			#Get the color sequence count for this room+instance from memcached
			if constellation.ConstellationUtils.getCounter(room,instance,shard,"colorcount") == 0:
				lastchat = self.r.zcount(str(room+":"+instance+":shard"+str(shard)+":colorme"+":approved"),0,"+inf")
				if (lastchat > 0):
					constellation.ConstellationUtils.setCounter(room,instance,shard,"colorcount",lastchat)
					constellation.ConstellationUtils.setCounter(room,instance,shard,"colorcount_approved",lastchat)
				else:
					constellation.ConstellationUtils.setCounter(room,instance,shard,"colorcount",0)
					constellation.ConstellationUtils.setCounter(room,instance,shard,"colorcount_approved",0)
			
			#Get the qasequence count for this room+instance from memcached
			if constellation.ConstellationUtils.getCounter(room,instance,shard,"qacount") == 0:
				lastchat = self.r.zcount(str(room+":"+instance+":shard"+str(shard)+":qanda"+":approved"),0,"+inf")
				if (lastchat > 0):
					constellation.ConstellationUtils.setCounter(room,instance,shard,"qacount",lastchat)
				else:
					constellation.ConstellationUtils.setCounter(room,instance,shard,"qacount",0)
					
			#Get the approved qasequence count for this room+instance from memcached
			if constellation.ConstellationUtils.getCounter(room,instance,shard,"qacount_approved") == 0:
				lastchat = self.r.zcount(str(room+":"+instance+":shard"+str(shard)+":qanda"+":approved"),0,"+inf")
				if (lastchat > 0):
					constellation.ConstellationUtils.setCounter(room,instance,shard,"qacount_approved",lastchat)
				else:
					constellation.ConstellationUtils.setCounter(room,instance,shard,"qacount_approved",0)
							
		def init(self):
				try:
					
					#print "Type IS " + str(options.type)
					message_package = None
					
					if self.sequence == 0:
						self.sequence = -1
					
					response = constellation.ConstellationUtils.initRedisChat( self.r, self.shard, {"instance":self.instance,"room":self.room}, options.type, "approved" )
					print("FOUND " + str(len(response))+ " UNMODERATED on init")
					if (response > 0):
						message_package = []
						for item in response:
							obj = json.loads(item)
							message_package.append(obj)
						
					if ((self.moderated == "true") and ((self.moderator == 1) or (self.ishost == 1))) or ((options.type == "qanda") and ((self.moderator == 1) or (self.ishost == 1))):
						print("LOOKING for MODERATED");
						response = constellation.ConstellationUtils.initRedisChat( self.r, self.shard, {"instance":self.instance,"room":self.room}, options.type, "unapproved" )
						print("FOUND " + str(len(response))+ " MODERATED on init")
						if (response > 0):
							if message_package == None:
								message_package = []
							for item in response:
								obj = json.loads(item)
								message_package.append(obj)	
					
					self._on_chat_response(message_package, None)
				
				except:
					constellation.ConstellationUtils.doError( self )
		
		def _on_chat_response(self, response, error):
			try:
					
					self.chats = response
					colorme_package = None
					
					if (options.type == "qanda"):
						self._on_response( None, None )
					else:	
						response = constellation.ConstellationUtils.initRedisChat( self.r, self.shard, {"instance":self.instance,"room":self.room}, "colorme", "approved", -50 )
						colorme_package = []
						if (len(response) > 0):
							for item in response:
								obj = json.loads(item)
								colorme_package.append(obj)	
					self._on_response(colorme_package, None)
			
			except:
					constellation.ConstellationUtils.doError( self )
			
		def _on_response(self, response, error):
				if error:
					raise tornado.web.HTTPError(500)
				if (options.type == "qanda"):
					print "QANDA"
					self._output(None,None,self.chats)
				else:
					#print "CHATTER"
					self._output(self.chats,response,None)
		
		def _output(self,chat,colorme,qanda):
				containerobj = defaultdict(list)
				jsonobj = defaultdict(list)
				
				if (chat == None):
					jsonobj["chat"] = []
				else:
					jsonobj["chat"] = chat
				
				if (qanda == None):
					jsonobj["qanda"] = []
				else:
					#print "QANDA"
					jsonobj["qanda"] = qanda
				
				if (options.type == "chat"):
					containerobj["sequence"] = constellation.ConstellationUtils.getCounter(self.room,self.instance,self.shard,"thecount")
					containerobj["sequence_approved"] = constellation.ConstellationUtils.getCounter(self.room,self.instance,self.shard,"thecount_approved")
				elif (options.type == "qanda"):
					containerobj["sequence"] = constellation.ConstellationUtils.getCounter(self.room,self.instance,self.shard,"qacount")
					containerobj["sequence_approved"] = constellation.ConstellationUtils.getCounter(self.room,self.instance,self.shard,"qacount_approved")
				
				if (colorme == None):
					jsonobj["colorme"] = []
				else:
					jsonobj["colorme"] = colorme
				containerobj["colorsequence"] = constellation.ConstellationUtils.getCounter(self.room,self.instance,self.shard,"colorcount")
				
				#Adjust our timestamp to ASAFP
				containerobj["meta"] = {"status":"200","msg":"OK","timestamp":time(),"block":constellation.ConstellationUtils.checkBlockRedisUser(self.r,str(self.room),int(self.userId),"block"),"warning":constellation.ConstellationUtils.checkBlockRedisUser(self.r,str(self.room),int(self.userId),"warning")}
				containerobj["response"] =  jsonobj
				
				self.write (json.dumps(containerobj))
				self.set_header("Content-Type", "application/json")
				self.finish()
