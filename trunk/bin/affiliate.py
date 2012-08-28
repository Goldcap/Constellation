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

import logging
import tornado.escape
import tornado.httpserver
import tornado.ioloop
import tornado.options
import tornado.web
import os
import sys
import oursql
import datetime
import re
import urllib2

from urlparse import urlparse

from tornado.options import define, options
from collections import defaultdict
from datetime import datetime, timedelta

import constellation.ConstellationUtils 

define("port", default=8889, help="run on the given port", type=int)
define("env", default="dev", help="where are we running")

class MainHandler(tornado.web.RequestHandler):
    def get(self):
      print("GETTIN"); 
      #self.write("========================================<br />")
      #self.write("Finding AFFILIATE Link<br /><br />")
      #self.write("========================================<br />")
      
      key = self.get_argument("rf")
      host = re.sub(':8888', '', self.request.headers.get('Host'))
      sql = ("select click_track_id from click_track where click_track_guid = ? limit 1")
      user = constellation.ConstellationUtils.doQuery(sql, [ key ], 1)
      
      if (self.request.headers.get('referer')):
        incoming = self.request.headers.get('referer');
        parsed = urlparse(self.request.headers.get('referer'))
        referer = parsed.hostname
      else:
      	incoming = 'None';
        parsed = 'None';
        referer = 'None'
        
      theurl = urlparse(self.request.uri)
      scriptname = theurl.path
      querystring = theurl.query
      
      if (user != None):
        sql = ("insert into click (click_incoming,click_referer,click_ip,click_date,click_guid,fk_click_track_id,click_script,click_querystring) values ( ?, ?, ?, ?, ?, ?, ?, ? )")
        constellation.ConstellationUtils.doQuery(sql, [ incoming, referer, self.request.remote_ip, datetime.utcnow(), key, user[0], scriptname, querystring ], 0)
        sql = ("update click_track set click_track_count = click_track_count + 1 where click_track_id= ?")
        constellation.ConstellationUtils.doQuery(sql, [ user[0] ], 0)
        expires = datetime.utcnow() + timedelta(days=365)
        self.set_cookie("ccl", value='ccl|'+key,expires=expires,domain='.constellation.tv')
      else:
        self.write("No User" + "<BR />")
        rows = None
       
      location = re.sub('rf=([^&(.+)=]*)', '', self.request.uri)
      location = re.sub('\?&', '?', location)
      print("http://" + constellation.ConstellationUtils.getHostName() + location)
      self.redirect("http://" + constellation.ConstellationUtils.getHostName() + location)
        
def main():
    tornado.options.parse_command_line()
    application = tornado.web.Application([
        (r"/.*", MainHandler),
    ])
    http_server = tornado.httpserver.HTTPServer(application)
    http_server.listen(options.port)
    tornado.ioloop.IOLoop.instance().start()


if __name__ == "__main__":
    main()
