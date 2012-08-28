#!/usr/bin/env python
#

import string
import logging
import tornado.auth
import tornado.escape
import tornado.httpserver
import tornado.ioloop
import tornado.options
import tornado.web
import os.path
import uuid
import pymongo
import asyncmongo
import re
import datetime
import time
import Cookie
import os
import json
import pylibmc 
import memcache
import redis

from tornado.options import define, options
from pymongo import Connection
from collections import defaultdict
from datetime import datetime

import constellation.ConstellationUtils
from constellation.MongoEncoder import MongoEncoder
from constellation.ConstellationSession import SessionHandler
from constellation.ConstellationSession import BaseHandler  
from constellation.BlockHandler import BlockHandler  
from constellation.MessageApproveHandler import MessageApproveHandler  
from constellation.MessageUpdatesHandler import MessageUpdatesHandler      
from constellation.MessageMixin import MessageMixin 

define("port", default=9090, help="run on the given port", type=int)
define("env", default="dev", help="where are we running") 
define("type", default="chat", help="what type are we")

class Application(tornado.web.Application):
		def __init__(self):
				handlers = [     
						(r"/services/chat/post", MessageNewHandler),
						(r"/services/chat/update", MessageUpdatesHandler),
						(r"/services/chat/approve", MessageApproveHandler),
						(r"/services/chat/block", BlockHandler),
						(r"/services/chat/search", SearchHandler),
						(r"/services/chat/rooms", RoomsHandler),
						(r"/services/chat/remove", RemoveHandler)
				]
				settings = dict(
					cookie_secret="43oETzKXQAGaYdkL5gEmGeJJFuYh7EQnp2XdTP1o/Vo="
				)
				tornado.web.Application.__init__(self, handlers, **settings)

class MessageNewHandler(BaseHandler, MessageMixin):
    
		r = None

		pfpt = re.compile("(shit|fuck|damn|cock|dick)", re.IGNORECASE)
		srp1 = re.compile(":\)")
		srp2 = re.compile(":D")
		srp3 = re.compile(":p")
		srp4 = re.compile(":P")
		srp5 = re.compile(":\(")
					
		id = ''
		message = ''
		room = 'x'
		sequence_approved = 0

		@tornado.web.authenticated
		@tornado.web.asynchronous
		def post(self):
				
				self.r = redis.StrictRedis(host=constellation.ConstellationUtils.getHost(), port=6379, db=0)
	
				try:
					self.userId = self.get_argument("u",0)
					self.shard=self.get_argument("p",None)
					self.film=self.get_argument("film",None)
					self.room=self.get_argument("room")
					self.instance=self.get_argument("instance")
					self.author=self.get_argument("author")
					#print "THE AUTHOR IS " + self.author
					self.user_image=self.get_argument("user_image",0)
					if (self.user_image == None):
						self.user_image = "/images/alt1/chat_icon.png"
					elif (self.user_image == '/images/icon-custom.png'):
						self.user_image = "/images/alt1/chat_icon.png"
					elif (self.user_image[0:4] != 'http'):
						self.user_image = '/uploads/hosts/'+str(self.author)+'/'+self.user_image
					else:
						self.user_image = self.user_image
						
					self.ishost=self.get_argument("ishost",0)
					if (self.ishost == "true"):
						self.ishost= 1
					elif ((self.ishost == "false") or (self.ishost == "undefined")):
						self.ishost = 0
					else:
						self.ishost = int(self.ishost)
						
					self.message=self.get_argument("body")
					p = re.compile(r'<(.+)')
					self.message = p.sub('', self.message)
					if (self.message == ''):
						doError( self )
						self.finish();
					
					self.color=self.get_argument("color", '')
					
					self.moderator=self.get_argument("mdt",0)
					if (self.moderator == "true"):
						self.moderator = 1
					elif ((self.moderator == "false") or (self.moderator == "undefined")):
						self.moderator = 0
					else:
						self.moderator = int(self.moderator)
						
					self.moderated=self.get_argument("cmo","false")
					if (self.moderated == "1"):
						self.moderated = "true"
					elif ((self.moderated == "0") or (self.moderated == "undefined")):
						self.moderated = "false"
						
					self.promote=self.get_argument("reply_id","false")
					self.asmoderator = 0
					self.type = self.get_argument("type","chat")
					thefrom = str(self.user["user_username"]).replace("\n","").split('@')
					self.isfrom = thefrom[0]
					
					#print ("ISFROM IS " + str(self.isfrom))	
					#Moderated message from a non-moderator
					if (self.moderated == "true") and (self.moderator == 0):
						self.approved = -1
					#Moderated message from a moderator
					elif (self.moderated == "true") and ((self.moderator == 1) or (self.ishost == 1)):
						#If the moderator has authored this message
						self.approved = 1
						self.asmoderator = 1
					#Non-Moderated messages
					else:
						self.approved = 1
					
					if (self.message[0:7] == "colorme"):
						self.approved = 1
						self.colorme = 1
						self.type = "colorme"
					else:
						self.colorme = 0
							
					self.initcount( self.room, self.instance, self.shard, self.moderated )
					
					#These numbers come from Memcached
					if (self.type == "colorme"):
						if constellation.ConstellationUtils.getCounter(self.room,self.instance,self.shard,"colorcount") == None:
							self.asequence = constellation.ConstellationUtils.setCounter(self.room,self.instance,self.shard,"colorcount",0) 
							#The approval sequence is always the same as the current count
							self.sequence_approved = self.asequence
						else:
							self.asequence = constellation.ConstellationUtils.incCounter(self.room,self.instance,self.shard,"colorcount")
							#The approval sequence is always the same as the current count
							self.sequence_approved = self.asequence 
					else:
						if constellation.ConstellationUtils.getCounter(self.room,self.instance,self.shard,"thecount") == None:
							self.asequence = constellation.ConstellationUtils.setCounter(self.room,self.instance,self.shard,"thecount",0) 
							if (self.moderated == "true") and (self.moderator == 0):
								self.sequence_approved = -1
							elif (self.moderated == "true") and ((self.moderator == 1) or (self.ishost == 1)):
								self.sequence_approved = constellation.ConstellationUtils.incCounter(self.room,self.instance,self.shard,"thecount_approved")
							else:
								self.sequence_approved = self.asequence
						else:
							self.asequence = constellation.ConstellationUtils.incCounter(self.room,self.instance,self.shard,"thecount")
							if (self.moderated == "true") and (self.moderator == 0):
								self.sequence_approved = -1
							elif (self.moderated == "true") and ((self.moderator == 1) or (self.ishost == 1)):
								self.sequence_approved = constellation.ConstellationUtils.incCounter(self.room,self.instance,self.shard,"thecount_approved")
							else:
								self.sequence_approved = self.asequence
					
					replacements = [" <img src='/images/smiles/smile.gif'/>", " <img src='/images/smiles/bigsmile.png'/>", " <img src='/images/smiles/tongue.png'/>", " <img src='/images/smiles/tongue.png'/>", " <img src='/images/smiles/sad.png'/>"];
					
					self.message = self.srp1.sub(replacements[0], self.message)
					self.message = self.srp2.sub(replacements[1], self.message)
					self.message = self.srp3.sub(replacements[2], self.message)
					self.message = self.srp4.sub(replacements[3], self.message)
					self.message = self.srp5.sub(replacements[4], self.message)
					
					self.message = self.pfpt.sub("*!@#", self.message)
					
					self.id = str(uuid.uuid4())
					
					self.aclient = asyncmongo.Client(pool_id='thepool', host=constellation.ConstellationUtils.getHost(), port=constellation.ConstellationUtils.getMongoShard( self.shard ), dbname=constellation.ConstellationUtils.getDBName())
					self.adb = self.aclient.connection(collectionname="chat")

					#Define the actual Chat Item
					#Keep the timestamp as close to the insert as possible
					self.timestamp = time.time()
					self.achat = { "id": self.id,
										"isfrom": self.isfrom,
										"author": int(self.author),
										"user_image": self.user_image,
										"ishost": self.ishost,
										"to": "public",
										"body": self.message.replace("\n","<br />\n"),
										"color": self.color,
										"film": self.film,
										"room": self.room,
										"instance": self.instance,
										"pair": "public",
										"mod_time": self.timestamp,
										"type": self.type,
										"sequence": self.asequence,
										"sequence_approved": self.sequence_approved,
										"approved": self.approved,
										"asmoderator": self.asmoderator,
										"colorme": self.colorme}
										
					self.pychat = defaultdict(list)
					self.pychat[unicode('id')] = unicode(self.id)
					self.pychat[unicode('isfrom')] = unicode(self.isfrom)
					self.pychat[unicode('author')] = int(self.author)
					self.pychat[unicode('color')] = unicode(self.color)
					self.pychat[unicode('user_image')] = unicode(self.user_image)
					self.pychat[unicode('ishost')] = int(self.ishost)
					self.pychat[unicode('to')] = unicode('public')
					self.pychat[unicode('body')] = unicode(self.message.replace('\n','<br />\n'))
					self.pychat[unicode('film')] = int(self.film)
					self.pychat[unicode('room')] = unicode(self.room)
					self.pychat[unicode('instance')] = unicode(self.instance)
					self.pychat[unicode('pair')] = unicode('public')
					self.pychat[unicode('mod_time')] = float(self.timestamp)
					self.pychat[unicode('type')] = unicode(self.type)
					self.pychat[unicode('sequence')] = int(self.asequence)
					self.pychat[unicode('sequence_approved')] = int(self.sequence_approved)
					self.pychat[unicode('approved')] = int(self.approved)
					self.pychat[unicode('asmoderator')] = int(self.asmoderator)
					self.pychat[unicode('colorme')] = int(self.colorme)
					
					if constellation.ConstellationUtils.checkBlockRedisUser( self.r ,str(self.room),int(self.author),"block") == 1:
						print "USER BLOCKED"
						self.approved = 0
											
					if self.approved == 1:
						if (self.moderator == 1) or (self.ishost == 1):
							sql = ("select chat_instance_key, chat_instance_port, chat_instance_name from chat_instance where fk_screening_key = ? order by chat_instance_name")
							rows = constellation.ConstellationUtils.doQuery( sql, [ self.room ])
							for row in rows:
									if (str(row[0]) == self.instance):
										self.sequence = constellation.ConstellationUtils.getCounter(self.room,str(row[0]),str(row[1]),"thecount")
										self.sequence_approved = constellation.ConstellationUtils.getCounter(self.room,str(row[0]),str(row[1]),"thecount_approved")
									else:
										self.sequence = constellation.ConstellationUtils.incCounter(self.room,str(row[0]),str(row[1]),"thecount")
										self.sequence_approved = constellation.ConstellationUtils.incCounter(self.room,str(row[0]),str(row[1]),"thecount_approved")	
									print ("SEQUENCE FOR ROOM " + str(row[0])+":"+str(row[1]) + " IS " + str(self.sequence_approved))
									self.pychat[unicode('sequence')]= self.sequence
									self.pychat[unicode('sequence_approved')]= self.sequence_approved
									constellation.ConstellationUtils.broadcastRedisChat(  self.r , str(row[0])+":shard"+str(row[1]), self.achat, self.type, self.pychat, "approved" )	
						else:
							constellation.ConstellationUtils.indexRedisChat(  self.r , self.shard, self.achat, self.type, self.pychat, "approved" )
					else:
						constellation.ConstellationUtils.indexRedisChat(  self.r , self.shard, self.achat, self.type, self.pychat, "unapproved" )
					
					if (self.promote != "false"):
							the_callback = self._on_promote
					else:
							the_callback = self._on_response
			 
					#Let's Keep a Copy In Mongo, just to be safe
					self.adb.insert(self.achat,callback=the_callback)
				except:
					constellation.ConstellationUtils.doError( self )
    
		def _on_promote(self, response, error):
				if ((self.promote != "false") and (self.promote != "undefined")):
					if (self.moderated == "true") and (self.moderator == 0):
						self.adb.find_one({"room":self.room,"instance":self.instance,"approved":1,"id":self.promote}, callback=self._on_promoted)  
					else:
						self.adb.find_one({"room":self.room,"instance":self.instance,"id":self.promote}, callback=self._on_promoted)
				else:
					self._on_response(response, error)
					
		def _on_promoted(self, response, error):
			
				msql = ("select conversation_id from conversation where conversation_guid = ? order by conversation_sequence;")
				mcr = constellation.ConstellationUtils.doQuery( options.env, msql, [ response["id"] ])
				if len(mcr) == 0:
					asql = ("insert into conversation (conversation_author, conversation_author_image, fk_author_id, fk_film_id, conversation_date_created, conversation_sequence, conversation_thread, conversation_body, conversation_guid, fk_promoter_id) values (?,?,?,?,?,?,?,?,?,?)")
					constellation.ConstellationUtils.doQuery( options.env, asql, [ response["from"], response["user_image"], response["author"], response["film"], str(datetime.now()), 0, response["id"], response["body"].replace("\n","<br />\n"), response["id"], int(self.author) ], 0)
				
				bsql = ("insert into conversation (conversation_author, conversation_author_image, fk_author_id, fk_film_id, conversation_date_created, conversation_sequence, conversation_thread, conversation_body, conversation_guid, fk_promoter_id) values (?,?,?,?,?,?,?,?,?,?)")
				constellation.ConstellationUtils.doQuery( options.env, bsql, [ str(self.get_secure_cookie("user")).replace("\n",""), self.user_image, int(self.author), self.film, str(datetime.now()), 1, response["id"], self.message.replace("\n","<br />\n"), self.id, int(self.author) ], 0)
				self._on_response(response, error)
						
		def _on_response(self, response, error):   
				
				#print ("IS HOST IS " + str(self.ishost))
				try:
					if (self.colorme == 1):
						#print ("SENDING COLORME")
						#self.new_colorme([self.achat], "true")
						self._output(None,self.achat,None)
					elif ((self.asmoderator == 1) or (self.ishost == 1)):
						#print ("SENDING BROADCAST")
						#self.new_broadcast([self.achat], "true")
						self._output(self.achat,None,None)
					else:
						#print ("SENDING MESSAGE")
						#self.new_messages([self.achat], "true")
						self._output(self.achat,None,None)
				    
					self.finish()

				except:
					constellation.ConstellationUtils.doError( self )

class SearchHandler(BaseHandler):

		r = None

		@tornado.web.authenticated
		@tornado.web.asynchronous
		
		def get(self):
	
			self.r = redis.StrictRedis(host=constellation.ConstellationUtils.getHost(), port=6379, db=0)
	
			try:
					self.userId = self.get_argument("u",0)
					self.room=self.get_argument("room")
					self.instance=self.get_argument("instance")
					self.author=self.get_argument("author")
					self.term=self.get_argument("term","null")	
					#print "THE AUTHOR IS " + self.author
					
					self.ishost=self.get_argument("ishost",0)
					if (self.ishost == "true"):
						self.ishost= 1
					elif ((self.ishost == "false") or (self.ishost == "undefined")):
						self.ishost = 0
					else:
						self.ishost = int(self.ishost)
					
					self.moderator=self.get_argument("mdt",0)
					if (self.moderator == "true"):
						self.moderator = 1
					elif ((self.moderator == "false") or (self.moderator == "undefined")):
						self.moderator = 0
					else:
						self.moderator = int(self.moderator)
						
					self.moderated=self.get_argument("cmo","false")
					if (self.moderated == "1"):
						self.moderated = "true"
					elif ((self.moderated == "0") or (self.moderated == "undefined")):
						self.moderated = "false"
						
					self.asmoderator = 0
					
					#Moderated message from a non-moderator
					if (self.moderator == 0):
						self.finish
					else:
						self.approved = 1
					
					containerobj = defaultdict(list)
					user_package = []
					sql = ("select user_id, user_username, user_photo_url from user inner join audience on audience.fk_user_id = `user`.user_id where user_username like ? and audience.fk_screening_unique_key = ?")
					rows = constellation.ConstellationUtils.doQuery( sql, ['%'+self.term+'%', self.room])
					
					for item in rows:
						if (item[2] == None):
							user_image = "/images/alt1/chat_icon.png"
						elif (item[2] == '/images/icon-custom.png'):
							user_image = "/images/alt1/chat_icon.png"
						elif (item[2][0:4] != 'http'):
							user_image = '/uploads/hosts/'+str(item[0])+'/'+str(item[2])
						else:
							user_image = item[2]
						if constellation.ConstellationUtils.checkBlockRedisUser( self.r ,str(self.room),int(item[0]),"block") == 1:
							stat = 'block'
						elif constellation.ConstellationUtils.checkBlockRedisUser( self.r ,str(self.room),int(item[0]),"warn") == 1:
							stat = 'warning'
						else:
							stat = 'none'
						user_package.append({"id":item[0],"email":item[1],"image":user_image,"stat":stat})
					containerobj["users"] = user_package	
					self.write(json.dumps(containerobj))
					self.finish()
			except:
					constellation.ConstellationUtils.doError( self )
								
#Host room list request
class RoomsHandler(BaseHandler):
		
		r = None

		#@tornado.web.authenticated
		@tornado.web.asynchronous
		
		def get(self):
		
				self.r = redis.StrictRedis(host=constellation.ConstellationUtils.getHost(), port=6379, db=0)
		
				self.user = int(0)
				self.room = self.get_argument("room")
				self.instance = self.get_argument("instance")
				
				sql = ("select chat_instance_key, chat_instance_port, chat_instance_name from chat_instance where fk_screening_key = ? order by chat_instance_name")
				rows = constellation.ConstellationUtils.doQuery( sql, [ self.room ])
				
				text = '{"rooms":['
				for row in rows:
						count = constellation.ConstellationUtils.showRedisCount( self.r , self.room, row[0], row[1] )
						if (self.instance == str(row[0])):
							current = "true"
						else:
							current = "false"
						text += '{"instance": "'+str(row[0])+'","port": "'+str(row[1])+'","count":"'+str(count)+'","name":"'+str(row[2])+'","current":"'+current+'"},'
				text += '{}]}'
				self.write(text)
				self.finish()

#Host room list request
class RemoveHandler(BaseHandler):
		
		r = None

		@tornado.web.authenticated
		@tornado.web.asynchronous
		
		def get(self):
		
				self.r = redis.StrictRedis(host=constellation.ConstellationUtils.getHost(), port=6379, db=0)
		
				self.thiid = self.get_argument("id")
				self.ishost=self.get_argument("ishost",0)
				
				if (self.ishost == "true"):
					self.ishost= 1
				elif ((self.ishost == "false") or (self.ishost == "undefined")):
					self.ishost = 0
				else:
					self.ishost = int(self.ishost)
				
				self.moderator=self.get_argument("mdt",0)
				if (self.moderator == "true"):
					self.moderator = 1
				elif ((self.moderator == "false") or (self.moderator == "undefined")):
					self.moderator = 0
				else:
					self.moderator = int(self.moderator)
					
				self.moderated=self.get_argument("cmo","false")
				if (self.moderated == "1"):
					self.moderated = "true"
				elif ((self.moderated == "0") or (self.moderated == "undefined")):
					self.moderated = "false"
					
				self.asmoderator = 0
				
				#Moderated message from a non-moderator
				if (self.moderator == 0):
					self.finish
				else:
					self.approved = 1
						
				self.aclient = asyncmongo.Client(pool_id='thepool', host=constellation.ConstellationUtils.getHost(), port=constellation.ConstellationUtils.getMongoShard( self.shard ), dbname=constellation.ConstellationUtils.getDBName())
				self.adb = self.aclient.connection(collectionname="chat")
				self.adb.find_one({"id":self.thiid},callback=self._remove)

		def _remove(self, response, error):
				
				if len(response) > 0:
					self.pychat = defaultdict(list)
					self.pychat[unicode('id')] = unicode(response["id"])
					self.pychat[unicode('isfrom')] = unicode(response["isfrom"])
					self.pychat[unicode('author')] = int(response["author"])
					self.pychat[unicode('user_image')] = unicode(response["user_image"])
					self.pychat[unicode('ishost')] = int(response["ishost"])
					self.pychat[unicode('to')] = unicode('public')
					self.pychat[unicode('body')] = unicode(response["body"])
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
					
					sql = ("select chat_instance_key, chat_instance_port from chat_instance where fk_screening_key = ? order by chat_instance_name")
					rows = constellation.ConstellationUtils.doQuery( sql, [ response["room"] ])
					
					for row in rows:
						print "FOUND INSTANCE " + str(response["room"]) + ":" + str(row[0]) + ":" + str(row[1])
						constellation.ConstellationUtils.removeRedisChat( self.r, response["room"], row[0], row[1], response["type"], self.pychat )
					
				self.adb.remove({"id":self.thiid},callback=self._finish)

		def _finish(self, response, error):
					
				self.write("ok")
				self.finish()
						 
def main():
		tornado.options.parse_command_line()
		http_server = tornado.httpserver.HTTPServer(Application())
		http_server.listen(options.port)
		tornado.ioloop.IOLoop.instance().start()


if __name__ == "__main__":
		main()
