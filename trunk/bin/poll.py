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
import re
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

define("port", default=14090, help="run on the given port", type=int)
define("env", default="dev", help="where are we running")  
define("type", default="poll", help="what type are we")


class Application(tornado.web.Application):
		def __init__(self):
				handlers = [
						(r"/services/poll/post", PollNewHandler),
						(r"/services/poll/update", PollUpdatesHandler)
				]
				settings = dict(
					cookie_secret="43oETzKXQAGaYdkL5gEmGeJJFuYh7EQnp2XdTP1o/Vo="
				)
				tornado.web.Application.__init__(self, handlers, **settings)

class PollMixin(object):
    
		@tornado.web.asynchronous
		
		def _output(self,question,count):
				if self.request.connection.stream.closed():
						return
						
				containerobj = defaultdict(list)
				if count == None:
					count = 0
				
				thecount = (int(count) + 100) / 2
				#Adjust our timestamp to ASAFP
				containerobj["meta"] = {"status":"200","msg":"OK","count":thecount,"question":question}
				
				self.write (json.dumps(containerobj))
				self.set_header("Content-Type", "application/json")
				self.finish()
									
class PollNewHandler(BaseHandler, PollMixin):

		id = ''
		message = ''
		room = 'x'
		sequence_approved = 0

		@tornado.web.authenticated
		@tornado.web.asynchronous
		def get(self):
	
				self.r = redis.StrictRedis(host=constellation.ConstellationUtils.getHost(), port=6379, db=0)
	
				self.type = self.get_argument("t","poll")
				self.action = int(self.get_argument("a",0))
				self.room=self.get_argument("room")
				self.question = self.get_argument("q",constellation.ConstellationUtils.getRedisCurrentPoll( self.r, self.room ))
				
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
				
				#print self.type
				#print self.action
				#print self.room
				#print self.ishost
				#print self.moderator	
				#print self.question
					
				if (((self.ishost == 1) or (self.moderator == 1)) and (self.type == "pollstart")):
					constellation.ConstellationUtils.setRedisCurrentPoll( self.r, self.room, self.question )
					count = 0
					#self._output(None,None,[ item ])
					
				elif (((self.ishost == 1) or (self.moderator == 1)) and (self.type == "pollstop")):
					constellation.ConstellationUtils.setRedisCurrentPoll( self.r, self.room, 0 )
					count = 0
					#self._output(None,None,[ item ])
					
				else:
					
					#print "QUESTIONS IS " + str(self.question)
					count = constellation.ConstellationUtils.indexRedisPoll( self.r, self.room, self.question, self.action )
					self._output( self.question, count )
				
				self._output( self.question, count )

class PollUpdatesHandler(BaseHandler, PollMixin):
		
		#@tornado.web.authenticated
		@tornado.web.asynchronous
		
		def get(self):
	
				self.r = redis.StrictRedis(host=constellation.ConstellationUtils.getHost(), port=6379, db=0)
	
				try:
					self.room = self.get_argument("room")
					self.question = constellation.ConstellationUtils.getRedisCurrentPoll( self.r, self.room )
					if self.question == None:
						self.question = 0
						
					#print(self.room)
					#print(self.question)
					count = constellation.ConstellationUtils.countRedisPoll(self.r,self.room,self.question)
					
				except:
					count = 0
					constellation.ConstellationUtils.doError( self )
					
				self._output( self.question, count )

def main():
    tornado.options.parse_command_line()
    http_server = tornado.httpserver.HTTPServer(Application())
    http_server.listen(options.port)
    tornado.ioloop.IOLoop.instance().start()


if __name__ == "__main__":
    main()
