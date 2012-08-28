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
import string
import logging
import tornado.auth
import tornado.escape
import tornado.httpserver
import tornado.ioloop
import tornado.options
import tornado.web
import os.path
import datetime
import time

from email.mime.multipart import MIMEMultipart
from email.mime.text import MIMEText
from tornado.options import define, options
from pymongo import Connection
from collections import defaultdict

import constellation.ConstellationUtils
from constellation.ConstellationSession import SessionHandler
from constellation.ConstellationSession import BaseHandler 

define("port", default=13090, help="run on the given port", type=int)
define("timelag", default=10, help="how long to poll for users", type=int)
define("env", default="dev", help="where are we running")


class Application(tornado.web.Application):
		def __init__(self):
				print "Initializing " + options.env
				handlers = [
						(r"/services/activity/status", ObserverHeaderHandler)
				]
				settings = dict(
						cookie_secret="43oETzKXQAGaYdkL5gEmGeJJFuYh7EQnp2XdTP1o/Vo="
				)
				tornado.web.Application.__init__(self, handlers, **settings)

#Update our timestamp, and remove old users
class ObserverHeaderHandler( BaseHandler ):
		
		@tornado.web.asynchronous
		
		def get(self):
			try:
				self.when = time.time() - options.timelag
				
				print "Finishing!"
				self.set_header("X-Server-Time", str(self.when))
				self.finish()
			except:
					constellation.ConstellationUtils.doError( self )
                    
		def head(self):
			try:
				self.when = time.time() - options.timelag
				
				print "Finishing!"
				self.set_header("X-Server-Time", str(self.when))
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
