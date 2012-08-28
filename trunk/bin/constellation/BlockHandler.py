import string
import logging
import tornado.auth
import tornado.escape
import tornado.httpserver
import tornado.ioloop
import tornado.options
import tornado.web
import redis

from tornado.options import define, options
from pymongo import Connection
from collections import defaultdict
from datetime import datetime

import constellation.ConstellationUtils
from constellation.ConstellationSession import SessionHandler
from constellation.ConstellationSession import BaseHandler
from constellation.MessageMixin import MessageMixin 

#Publishes and activity to listeners
class BlockHandler(tornado.web.RequestHandler, MessageMixin):

		@tornado.web.asynchronous
		
		def get(self):
				try:
					self.room = self.get_argument("room", None)
					self.user_id = self.get_argument("userId", None)
					self.action = self.get_argument("act", None)
					self.severity = self.get_argument("sev", "block")
					self.moderated = self.get_argument("cmo", None)
					self.moderator = int(self.get_argument("mdt", 0))
					self.ishost = int(self.get_argument("ish", 0))
					
					if ((self.moderator == 1) or (self.ishost == 1)):
						r = redis.StrictRedis(host=constellation.ConstellationUtils.getHost(), port=6379, db=0)
						#print(options.type)
						if (self.action == "block"):
							constellation.ConstellationUtils.blockRedisUser( r, self.room, self.user_id, self.severity )
						else:
							constellation.ConstellationUtils.unBlockRedisUser( r, self.room, self.user_id, self.severity )
					
					self.write('{"result":"ok"}')
					self.finish()
							
				except:
					constellation.ConstellationUtils.doError( self )
