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
import socket  
import datetime
import time
import Cookie
import os
import json
import pylibmc
import redis

from tornado.options import define, options
from pymongo import Connection
from collections import defaultdict
from datetime import datetime

import constellation.ConstellationUtils
from constellation.ConstellationSession import SessionHandler
from constellation.ConstellationSession import BaseHandler 
from constellation.MessageApproveHandler import MessageApproveHandler 
from constellation.MessageUpdatesHandler import MessageUpdatesHandler     
from constellation.MessageMixin import MessageMixin 

define("port", default=15090, help="run on the given port", type=int)
define("env", default="dev", help="where are we running")  
define("type", default="qanda", help="what type are we")

timeout = 2
socket.setdefaulttimeout(timeout)

class Application(tornado.web.Application):
		def __init__(self):
				handlers = [
						(r"/services/qanda/post", MessageNewHandler),
						(r"/services/qanda/update", MessageUpdatesHandler),
						(r"/services/qanda/approve", MessageApproveHandler)
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
					#self.shard=self.get_argument("p",None)
					self.shard=0
					self.film=self.get_argument("film",None)
					self.room=self.get_argument("room")
					#self.instance=self.get_argument("instance")
					#QANDA Messages are ROOMWIDE
					self.instance=""
					self.author=self.get_argument("author")
					#print "THE AUTHOR IS " + self.author
					self.user_image=self.get_argument("user_image",None)
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
					self.type = self.get_argument("type","qanda")
					thefrom = str(self.user["user_username"]).replace("\n","").split('@')
					self.isfrom = thefrom[0]
					self.timestamp = time.time()
					
					if ((self.ishost == 1) and (self.type == "qastart")):
						sql = "update screening set screening_chat_qanda_started = 1 where screening_unique_key = ?"
						constellation.ConstellationUtils.doQuery( sql, [ self.room ], 0)
						
						item = {"id": self.id,
									"isfrom": self.isfrom,
									"author": int(self.author),
									"room": self.room,
									"type": self.type,
									"approved" : 1,
									"instance": self.instance}
						
						self.pychat = defaultdict(list)
						self.pychat[unicode('id')] = unicode(self.id)
						self.pychat[unicode('isfrom')] = unicode(self.isfrom)
						self.pychat[unicode('author')] = int(self.author)
						self.pychat[unicode('film')] = int(self.film)
						self.pychat[unicode('room')] = unicode(self.room)
						self.pychat[unicode('mod_time')] = float(self.timestamp)
						self.pychat[unicode('type')] = unicode(self.type)
						self.pychat[unicode('approved')] = int(1)
						
						constellation.ConstellationUtils.indexRedisChat( self.r, self.shard, item, "qanda", self.pychat, "approved" )
						self._output(None,None,[ item ])
						
					elif ((self.ishost == 1) and (self.type == "qaend")):
						sql = "update screening set screening_chat_qanda_started = -1 where screening_unique_key = ?"
						constellation.ConstellationUtils.doQuery( sql, [ self.room ], 0)
						item = {"id": self.id,
									"isfrom": self.isfrom,
									"author": int(self.author),
									"room": self.room,
									"type": self.type,
									"approved" : 1,
									"instance": self.instance}
						
						self.pychat = defaultdict(list)
						self.pychat[unicode('id')] = unicode(self.id)
						self.pychat[unicode('isfrom')] = unicode(self.isfrom)
						self.pychat[unicode('author')] = int(self.author)
						self.pychat[unicode('film')] = int(self.film)
						self.pychat[unicode('room')] = unicode(self.room)
						self.pychat[unicode('mod_time')] = float(self.timestamp)
						self.pychat[unicode('type')] = unicode(self.type)
						self.pychat[unicode('approved')] = int(1)
						
						constellation.ConstellationUtils.indexRedisChat( self.r, self.shard, item, "qanda", self.pychat, "approved" )
						self._output(None,None, [ item ] )
						
					else:
						#Moderated message from a non-moderator
						if (self.moderated == "true") and ((self.moderator == 1) or (self.host == 1)):
							self.asmoderator = 1
					
						self.approved = -1
						self.qanda = 1
						
						self.initcount( self.room, self.instance, self.shard, self.moderated )
						
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

						#This double assignment is necessary
						#Because the MongoDB Insert modifies the object
						#Adding the "_id", which blows up json encoding in "self.write"
						self.achat = { "id": self.id,
											"isfrom": self.isfrom,
											"author": int(self.author),
											"user_image": self.user_image,
											"ishost": self.ishost,
											"to": "public",
											"body": self.message.replace("\n","<br />\n"),
											"film": self.film,
											"room": self.room,
											"instance": self.instance,
											"pair": "public",
											"mod_time": self.timestamp,
											"type": self.type,
											"sequence": self.asequence,
											"approved": self.approved,
											"sequence_approved": self.sequence_approved,
											"asmoderator": self.asmoderator,
											"colorme": 0}
						
						self.pychat = defaultdict(list)
						self.pychat[unicode('id')] = unicode(self.id)
						self.pychat[unicode('isfrom')] = unicode(self.isfrom)
						self.pychat[unicode('author')] = int(self.author)
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
						self.pychat[unicode('colorme')] = int(0)
						
						if self.approved == 1:
							constellation.ConstellationUtils.indexRedisChat( self.r, self.shard, self.achat, self.type, self.pychat, "approved" )
						else:
							constellation.ConstellationUtils.indexRedisChat( self.r, self.shard, self.achat, self.type, self.pychat, "unapproved" )
					
						self.aclient = asyncmongo.Client(pool_id='some_pool', host=constellation.ConstellationUtils.getHost(), port=constellation.ConstellationUtils.getMongoShard( self.shard ), dbname=constellation.ConstellationUtils.getDBName())
						self.adb = self.aclient.connection(collectionname="chat")
						self.adb.insert(self.achat,callback=self._on_response)
				except:
						constellation.ConstellationUtils.doError( self )

		def _on_response(self, response, error):   
				
				try:
					#self.new_messages([self.achat], "true")
					self._output(None,None,self.achat)

					self.finish()

				except:
					constellation.ConstellationUtils.doError( self )


def main():
    tornado.options.parse_command_line()
    http_server = tornado.httpserver.HTTPServer(Application())
    http_server.listen(options.port)
    tornado.ioloop.IOLoop.instance().start()


if __name__ == "__main__":
    main()
