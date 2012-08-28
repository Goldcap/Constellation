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

import tornado.httpserver
import tornado.ioloop
import tornado.options
import tornado.web
import MySQLdb
import pymongo
import datetime

from tornado.options import define, options
from pymongo import Connection

define("port", default=8888, help="run on the given port", type=int)

class MainHandler(tornado.web.RequestHandler):
    def get(self):
        
        self.domysql()
        
        self.domongo()
        
    def domysql(self):
        
        self.write("========================================<br />")
        self.write("Testing MYSQL Connections<br /><br />")
        self.write("========================================<br />")
        try:
          conn = MySQLdb.connect (host = "localhost", user = "root", passwd = "1hsvy5qb", db = "ttj_dev")
          cursor = conn.cursor ()
        except MySQLdb.Error, e:
          print "Error %d: %s" % (e.args[0], e.args[1])
          sys.exit (1)
        cursor.execute ("SELECT product_type_name,product_type_abbr from product_type")
        #row = cursor.fetchone ()
        rows = cursor.fetchall ()
        for row in rows:
          self.write("%s, %s" % (row[0], row[1]))
          self.write("<br />")
        self.write("<br />")
        self.write("Number of rows returned: %d" % cursor.rowcount)
        
        cursor.execute ("UPDATE test SET test_column = %s WHERE test_id = %s", ("snake",1))
        self.write("<br />")
        self.write("Number of rows updated: %d" % cursor.rowcount)
   
        cursor.close ()
        conn.close ()
        
        self.write("<br /><br />")
        
        
    def domongo(self):
        
        self.write("========================================<br />")
        self.write("Testing Mongo Connections<br /><br />")
        self.write("========================================<br />")
        connection = Connection()
        connection = Connection('localhost', 27017)
        db = connection.ttj_wishlist_dev
        
        awish = {"wish_user": 666,
                "wish_date": datetime.datetime.utcnow(),
                "wish_prods": ["FMU-101", "JZZ-424", "ABC-123"]}
        db.wishlist_queue.insert(awish)
        
        self.write("Number of rows returned: %d<br /><br />" % db.wishlist_queue.count())
        
        for queue in db.wishlist_queue.find({"wish_user":666}):
          self.write("User Found: %d<br />" % queue["wish_user"])
          self.write("Date Found: %s<br />" % queue["wish_date"])
          for prod in queue["wish_prods"]:
            self.write("Prod Found: %s<br />" % prod)
          db.wishlist_queue.remove({"_id":queue["_id"]})  
        
          #self.write("Prods Found: %d<br />" % queue["wish_prods"])
          
        
def main():
    tornado.options.parse_command_line()
    application = tornado.web.Application([
        (r"/jx/test", MainHandler),
    ])
    http_server = tornado.httpserver.HTTPServer(application)
    http_server.listen(options.port)
    tornado.ioloop.IOLoop.instance().start()


if __name__ == "__main__":
    main()
