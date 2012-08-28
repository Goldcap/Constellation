#!/usr/bin/env python
#
# Copyright 2009 Facebook
#
# Licensed under the Apache License, Version 2.0 (the "License"); you may
# not use this file except in compliance with the License. You may obtain
# a copy of the License at
#
#     http://www.apache.org/licenses/LICENSE-2.0
#
# Unless required by applicable law or agreed to in writing, software
# distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
# WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
# License for the specific language governing permissions and limitations
# under the License.

import sys
import traceback 
import smtplib
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
import urllib
import pymongo
import oursql
import json
import re
import cgi
import time
import datetime

from email.mime.multipart import MIMEMultipart
from email.mime.text import MIMEText
from tornado.options import define, options
from pymongo import Connection
from collections import defaultdict

import constellation.ConstellationUtils
from constellation.ConstellationSession import SessionHandler
from constellation.ConstellationSession import BaseHandler 
from constellation.AES import AES 

define("port", default=16090, help="run on the given port", type=int)
define("timelag", default=6, help="how long to poll for users", type=int)
define("env", default="dev", help="where are we running")

class Application(tornado.web.Application):
    def __init__(self):
        handlers = [
            (r"/heartbeat", ParticipantHeaderHandler)
        ]
        settings = dict(
            cookie_secret="43oETzKXQAGaYdkL5gEmGeJJFuYh7EQnp2XdTP1o/Vo="
        )
        tornado.web.Application.__init__(self, handlers, **settings)

class ParticipantHeaderHandler(BaseHandler):
		@tornado.web.authenticated
		@tornado.web.asynchronous

		#Update Question Status
		def post(self):
			try:
				print "USER IS:: " + self.user["user_id"]
				print "USER IS:: " + self.user["user_id"] + " - TZ is " + self.user["user_timezone"]
				os.environ['TZ'] = self.user["user_timezone"]
				time.tzset()
				
				self.startTime = int(self.get_argument("filmStartTime",0))
				self.ticketParams = urllib.unquote(self.get_argument("k"))
				self.ticketParams = string.replace(self.ticketParams, " ", "+")
				print "USER IS:: " + self.user["user_id"] + " - Crypt text is "+self.ticketParams
				
				self.nowTime = time.mktime(time.localtime())
				self.timeOffset = self.nowTime - self.startTime
				print "USER IS:: " + self.user["user_id"] + " - OFFSET is " + str(int(self.timeOffset))
				
				crypt = AES()
				plaintext = crypt.decrypt(self.ticketParams, "lockmeAmadeus256", 256)
				print "USER IS:: " + self.user["user_id"] + " - Plaintext is: "+plaintext
				vals = plaintext.split('|')
				print "USER IS:: " + self.user["user_id"] + " - Ticket is " + vals [0]
				print "USER IS:: " + self.user["user_id"] + " - Film is " + vals [1]
				print "USER IS:: " + self.user["user_id"] + " - HMAC is " + vals [2]
				
				sql = ("select * from audience where audience_invite_code = ? and fk_user_id = ? and audience_hmac_key = ? and audience_paid_status = 2 limit 1")
				rows = constellation.ConstellationUtils.doQuery(sql, [ vals[0], self.user["user_id"], vals[2] ])
				print "USER IS:: " + self.user["user_id"] + " - Found " + str(len(rows)) + " Tickets"
				
				if len(rows) == 0:
					print "USER IS:: " + self.user["user_id"] + " - Failed"
					self.write('{"heartResponse":{"status":"false","message":"Your account is being used to view this film from another location.<br /><br />Please refresh your browser to recapture your ticket, "}}')
				else:
					print "USER IS:: " + self.user["user_id"] + " - Success"
					if (rows[0][10] == 0) :
						print "User is Statified!"
						sql = "update audience set audience_status = 1 where audience_id = ?"
						constellation.ConstellationUtils.doQuery(sql, [ rows[0][0] ], 0)
					self.write('{"heartResponse":{"status":"true","message":"Seat Authenticated","time":'+str(int(self.timeOffset))+',"hmac":"'+vals [2]+'"}}')
				
				self.finish()

			except:
				constellation.ConstellationUtils.doError( self ) 

				self.finish()

def main():
    tornado.options.parse_command_line()
    http_server = tornado.httpserver.HTTPServer(Application())
    http_server.listen(options.port)
    tornado.ioloop.IOLoop.instance().start()


if __name__ == "__main__":
    main()
