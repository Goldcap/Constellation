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
import pymongo
import oursql
import asyncmongo
import re
import urllib
import urllib2
import threading
import datetime
import time
import Cookie
import os  

from urlparse import urlparse
from datetime import datetime
  
from tornado.options import define, options
from pymongo import Connection
from collections import defaultdict

import constellation.ConstellationUtils
from constellation.ConstellationSession import SessionHandler
from constellation.ConstellationSession import BaseHandler 

define("port", default=20100, help="run on the given port", type=int)
define("env", default="dev", help="where are we running")

def pretty_date(time=False):
    """
    Get a datetime object or a int() Epoch timestamp and return a
    pretty string like 'an hour ago', 'Yesterday', '3 months ago',
    'just now', etc
    """
    
    now = datetime.now()
    if type(time) is int:
        diff = now - datetime.fromtimestamp(time)
    elif isinstance(time,datetime):
        diff = now - time 
    elif not time:
        diff = now - now
    second_diff = diff.seconds
    day_diff = diff.days

    if day_diff < 0:
        return ''

    if day_diff == 0:
        if second_diff < 10:
            return "just now"
        if second_diff < 60:
            return str(second_diff) + " seconds ago"
        if second_diff < 120:
            return  "a minute ago"
        if second_diff < 3600:
            return str( second_diff / 60 ) + " minutes ago"
        if second_diff < 7200:
            return "an hour ago"
        if second_diff < 86400:
            return str( second_diff / 3600 ) + " hours ago"
    if day_diff == 1:
        return "Yesterday"
    if day_diff < 7:
        return str(day_diff) + " days ago"
    if day_diff < 14:
        return "1 week ago"
    if day_diff < 31:
      if day_diff/7 == 1:
        return str(day_diff/7) + " week ago"
      else:
        return str(day_diff/7) + " weeks ago"
    if day_diff < 60:
      return "1 month ago"
    if day_diff < 365:
        return str(day_diff/30) + " months ago"
    return str(day_diff/365) + " years ago"
    
class Application(tornado.web.Application):
    def __init__(self):
        handlers = [
            (r"/services/conversation/init", InitHandler),
            (r"/services/conversation/post", MessageNewHandler),
            (r"/services/conversation/vote", MessageVoteHandler), 
            (r"/services/conversation/promote", MessagePromoteHandler),
            (r"/services/conversation/flag", MessageFlagHandler)
        ]
        settings = dict(
            cookie_secret="43oETzKXQAGaYdkL5gEmGeJJFuYh7EQnp2XdTP1o/Vo=",
            template_path=os.path.join(os.path.dirname(__file__), "templates"),
            static_path=os.path.join(os.path.dirname(__file__), "static")
        )
        tornado.web.Application.__init__(self, handlers, **settings)

class InitHandler(BaseHandler):
		#@tornado.web.authenticated
		@tornado.web.asynchronous
		
		def get(self):
				try:
					#id: 20,
			    #username: 'Matthew',
			    #avatar: '/uploads/hosts/3176/f557112ff27a20320a6602bd77632921.jpg',
			    #timestamp: '3 hours ago',
			    #comment: 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
			    #favorite_count: 10,
					
					self.conversation_id=self.get_argument("c",None)
					self.rpp = int(self.get_argument("r",10))
					self.page=int(self.get_argument("p",1)) 
					self.film=int(self.get_argument("f",0)) 
					self.screening=int(self.get_argument("e",0)) 
					self.user=int(self.get_argument("u",0)) 
					self.sort=self.get_argument("s","popular")
					startrec = ((self.page - 1) * self.rpp)
					endrec = ((self.page - 1) * self.rpp) + self.rpp
					print ("PAGINATION IS " + str(startrec) + " TO " + str(endrec))
					
					if (self.sort == 'popular'):
						sortclause = 'conversation_rating desc'
					else:
						sortclause = 'conversation_date_created desc'
						
					jsonobj = defaultdict(list)
					jsonobj["meta"] = defaultdict(list)
					jsonobj["response"] = defaultdict(list)
					jsonobj["response"]["data"] = []
					
					if self.conversation_id != None:
						# and conversation_status is null
						csql = ("select count(conversation_id) from conversation where conversation_thread = ? and conversation_sequence = 0;")
						cmcs = constellation.ConstellationUtils.doQuery( csql, [ self.conversation_id ], 1)
						thecount = cmcs[0]
						print "COUNTED " + str(thecount)
						sql = ("select conversation_id, conversation_guid, conversation_author, fk_author_id, conversation_author_image, case conversation_status when 0 then 'This post has been flagged as inappropriate.' else conversation_body end, conversation_rating, conversation_date_created, fk_film_id, film_name, film_logo, conversation_status, concat(conversation_asset_guid,'.',conversation_asset_type)  from conversation inner join film on fk_film_id = film_id where conversation_thread = ? and conversation_sequence = 0 order by fk_film_id, " + sortclause + ", conversation_sequence limit ? offset ? ;")
						mcs = constellation.ConstellationUtils.doQuery( sql, [ self.conversation_id, self.rpp, startrec ])
					elif self.user != 0:
						# and conversation_status is null

						csql = ("select count(conversation_id) from conversation where fk_user_id = ? and conversation_sequence = 0;")
						cmcs = constellation.ConstellationUtils.doQuery( csql, [ self.user ], 1)
						thecount = cmcs[0]
						print "COUNTED " + str(thecount)
						sql = ("select conversation_id, conversation_guid, conversation_author, fk_author_id, conversation_author_image, case conversation_status when 0 then 'This post has been flagged as inappropriate.' else conversation_body end, conversation_rating, conversation_date_created, conversation_status, concat(conversation_asset_guid,'.',conversation_asset_type)  from conversation where fk_user_id = ? and conversation_sequence = 0 order by conversation_sequence, " + sortclause + ", conversation_sequence limit ? offset ? ;")
						mcs = constellation.ConstellationUtils.doQuery( sql, [ self.user, self.rpp, startrec ])
					elif self.screening != 0:
						# and conversation_status is null
						csql = ("select count(conversation_id) from conversation where fk_screening_id = ? and conversation_sequence = 0;")
						cmcs = constellation.ConstellationUtils.doQuery( csql, [ self.screening ], 1)
						thecount = cmcs[0]
						print "COUNTED " + str(thecount)
						sql = ("select conversation_id, conversation_guid, conversation_author, fk_author_id, conversation_author_image, case conversation_status when 0 then 'This post has been flagged as inappropriate.' else conversation_body end, conversation_rating, conversation_date_created, fk_film_id, film_name, film_logo, conversation_status, concat(conversation_asset_guid,'.',conversation_asset_type)  from conversation inner join film on fk_film_id = film_id where fk_screening_id = ? and conversation_sequence = 0 order by conversation_sequence, " + sortclause + ", conversation_sequence limit ? offset ? ;")
						mcs = constellation.ConstellationUtils.doQuery( sql, [ self.screening, self.rpp, startrec ])
					else:
						# and conversation_status is null
						csql = ("select count(conversation_id) from conversation where conversation_sequence = 0 and fk_film_id = ?;")
						cmcs = constellation.ConstellationUtils.doQuery( csql, [ self.film ], 1)
						thecount = cmcs[0]
						print "COUNTED " + str(thecount)
						sql = ("select conversation_id, conversation_guid, conversation_author, fk_author_id, conversation_author_image, case conversation_status when 0 then 'This post has been flagged as inappropriate.' else conversation_body end, conversation_rating, conversation_date_created, fk_film_id, film_name, film_logo, conversation_status, concat(conversation_asset_guid,'.',conversation_asset_type) from conversation inner join film on fk_film_id = film_id where conversation_sequence = 0 and fk_film_id = ? order by fk_film_id, " + sortclause + ", conversation_sequence limit ? offset ? ;")
						mcs = constellation.ConstellationUtils.doQuery( sql, [ self.film, self.rpp, startrec ] )

						
					##Add timestamp
					if len(mcs) == 0:
						mcsid = 0
					else:
						for row in mcs:
							sql = ("select conversation_id, conversation_guid, conversation_author, fk_author_id, conversation_author_image, conversation_body, conversation_rating, conversation_date_created, concat(conversation_asset_guid,'.',conversation_asset_type) from conversation where conversation_thread = ? and conversation_status is null and conversation_sequence > 0 order by conversation_sequence;")

							mcr = constellation.ConstellationUtils.doQuery( sql, [ row[1] ])
							item = defaultdict(list)
							print "Found POST " + str(row[1])
							doAdd = "true"
							item["id"] = row[1]
							item["authorname"] = row[2]
							item["authorid"] = row[3]
							item["avatar"] = row[4]
							item["comment"] = row[5]       
							item["favorite_count"] = row[6]
							item["timestamp"] = pretty_date(row[7]) 
							item["asset"] = row[9]
							if len(mcr) > 0:
								item["replies"] = []
								for rrow in mcr:
									ritem = defaultdict(list)
									ritem["id"] = rrow[2]
									ritem["authorname"] = rrow[2] 
									ritem["authorid"] = rrow[3]
									ritem["avatar"] = rrow[4]
									ritem["comment"] = rrow[5]
									ritem["favorite_count"] = rrow[6]
									ritem["timestamp"] = pretty_date(rrow[7]) 
									ritem["asset"] = rrow[8]
									item["replies"].append(ritem)
							elif len(mcr) == 0 and row[8] == 0:
								print "Not Adding?" + doAdd
								doAdd = "false"
								
							if doAdd == "true":
								print "Adding?" + doAdd
								jsonobj["response"]["data"].append(item)
							else:
								thecount = thecount -1				    	
					jsonobj["meta"]["success"] = "true"
					jsonobj["meta"]["name"] = "comments"
					jsonobj["meta"]["totalresults"] = thecount
					jsonobj["meta"]["rpp"] = self.rpp
					jsonobj["meta"]["page"] = self.page		
					self.write (tornado.escape.json_encode(jsonobj))
					self.finish()
				
				except:
					self.write ('boom')
					self.finish()				
					constellation.ConstellationUtils.doError( self )
					self.finish()
					
					
class MessageNewHandler(BaseHandler):

		@tornado.web.authenticated
		@tornado.web.asynchronous

		def get(self):
				try:
					print "User ID is "+self.user["user_id"]
					print "User Image Is "+self.user["user_image"]
					self.conversation_id=self.get_argument("c",None)
					self.author_name=self.get_argument("a",self.user["user_username"])
					self.author_image=self.get_argument("a_image",self.user["user_image"])
					self.fk_author_id=self.get_argument("a_id",self.user["user_id"])
					if (self.author_image == None):
						self.author_image = "/images/alt1/chat_icon.png"
					elif (self.author_image == '/images/icon-custom.png'):
						self.author_image = "/images/alt1/chat_icon.png"
					elif (self.author_image[0:4] != 'http'):
						self.author_image = '/uploads/hosts/'+str(self.fk_author_id)+'/'+self.author_image
					else:
						self.author_image = self.author_image
					self.body=self.get_argument("b",None) 
					self.screening=int(self.get_argument("e",0)) 
					self.film=int(self.get_argument("f",0)) 
					self.user=int(self.get_argument("u",0)) 
					self.asset=self.get_argument("asset","null") 
					self.asset_type="null"
					if self.asset !="null":
						asOb = self.asset.split(".")
						if len(asOb) == 2:
							self.asset = asOb[0]
							self.asset_type = asOb[1]
						else:
							self.asset = "null"
						
					if self.film == None or self.screening == None or self.user == None:
						self.write('{"meta": {"rpp": 0, "totalresults": 0, "name": "comments", "success": "false"}}')
						self.finish()
						return None
					else:		
						patterns = [":\)", ":D", ":p", ":P", ":\("];
						replacements = [" <img src='/images/smiles/smile.gif'/>", " <img src='/images/smiles/bigsmile.png'/>", " <img src='/images/smiles/tongue.png'/>", " <img src='/images/smiles/tongue.png'/>", " <img src='/images/smiles/sad.png'/>"];
						
						i=0
						for pat in patterns:
							srpt = re.compile(pat)
							self.body = srpt.sub(replacements[i], self.body)
							i += 1
	
						self.guid = str(uuid.uuid4())
	
						if self.conversation_id == None:
							cst = 0
							mcsid = 0
						else:
							cst = self.conversation_id
							#Check for pre-existing thread or comment
							sql = ("select max(conversation_sequence) from conversation where conversation_thread = ?")
							mcs = constellation.ConstellationUtils.doQuery( sql, [ self.conversation_id ])
							if len(mcs) == 0:
								mcsid = 0
							else:
								print "MCSID"
								if mcs[0][0] == None:
									mcsid = 0
								else:
									mcsid = mcs[0][0] + 1
						
						print "CONVO IS " + str(cst)		
						#Put the item in the DB
						sql = ("insert into conversation (conversation_author, conversation_author_image, fk_author_id, fk_film_id, conversation_date_created, conversation_sequence, conversation_thread, conversation_body, conversation_guid, conversation_asset_type, conversation_asset_guid, fk_screening_id, fk_user_id) values (?,?,?,?,?,?,?,?,?,?,?,?,?)")
						constellation.ConstellationUtils.doQuery( sql, [ self.author_name, self.author_image, self.fk_author_id, self.film, str(datetime.now()), mcsid, cst, self.body, self.guid, self.asset_type, self.asset, self.screening, self.user ], 0)
						
						if self.conversation_id == None:
							sql = ("update conversation set conversation_thread = conversation_guid where conversation_sequence = 0 and conversation_thread = 0")
							constellation.ConstellationUtils.doQuery( sql, None, 0)
						#This is a response, so send a notification
						else:
							sql = ("insert into conversation_notification (fk_conversation_guid,conversation_notification_type,conversation_notification_date_created) values (?,?,?);")	
							constellation.ConstellationUtils.doQuery( sql, [self.conversation_id, 1, str(datetime.now())], 0)
							
						#Add username, user_image, body, timestamp, guid
						self.write('{"meta": {"rpp": 0, "totalresults": 0, "name": "comments", "success": "true"},"response":{"data":[{"comment":"'+self.body+'","authorname":"'+self.author_name+'","authorid":"'+self.fk_author_id+'","avatar":"'+self.author_image+'","id":"'+self.guid+'","timestamp":"Less Than a Minute Ago"}]}}')
						self.finish()
				except:
					constellation.ConstellationUtils.doError( self )
					self.finish()
		
class MessageVoteHandler(BaseHandler):
		@tornado.web.authenticated
		@tornado.web.asynchronous

		def get(self):
				try:
					self.conversation_id=self.get_argument("c","")
					self.fk_author_id=self.get_argument("a_id",self.user["user_id"])
					
					sql = ("select conversation_vote_id from conversation_vote where fk_conversation_guid = ? and fk_user_id = ?")
					mcs = constellation.ConstellationUtils.doQuery( sql, [ self.conversation_id, self.fk_author_id ])
					print len(mcs)
					if len(mcs) > 0:
						self.write('{"meta": {"rpp": 0, "totalresults": 0, "name": "commentVote", "success": "false"}}')
						self.finish()
					else:
							
						sql = ("select max(conversation_rating) from conversation where conversation_guid = ?")
						mcs = constellation.ConstellationUtils.doQuery( sql, [ self.conversation_id ])
						if len(mcs) == 0:
							mcsid = 0
						else:
							print("Current Rating is " + str(mcs[0][0]))
							if mcs[0][0] == None:
								mcsid = 1
							else:
								mcsid = mcs[0][0] + 1
							sql = ("update conversation set conversation_rating = ? where conversation_guid = ?")
							mcs = constellation.ConstellationUtils.doQuery( sql, [ mcsid, self.conversation_id ], 0)
						
						sql = ("insert into conversation_vote (fk_conversation_guid, fk_user_id, conversation_vote_date_created) values ( ?, ?, ? )")
						mcs = constellation.ConstellationUtils.doQuery( sql, [ self.conversation_id, self.fk_author_id, str(datetime.now()) ], 0)
						
						sql = ("insert into conversation_notification (fk_conversation_guid,conversation_notification_type,conversation_notification_date_created) values (?,?,?);")	
						constellation.ConstellationUtils.doQuery( sql, [self.conversation_id, 0, str(datetime.now())], 0)
						
						self.write('{"meta": {"rpp": 0, "totalresults": 0, "name": "commentVote", "success": "true"}}')
						self.finish()
				
				except:
					constellation.ConstellationUtils.doError( self )
					self.finish()

class MessagePromoteHandler(BaseHandler):
		@tornado.web.authenticated
		@tornado.web.asynchronous

		def get(self):
				try:
					self.promote=self.get_argument("c","")
					self.room=self.get_argument("room")
					self.instance=self.get_argument("instance")
					self.moderated=self.get_argument("cmo","false")
					self.moderator=int(self.get_argument("mdt",0))
					
					self.aclient = asyncmongo.Client(pool_id='some_pool', host=constellation.ConstellationUtils.getHost(), port=constellation.ConstellationUtils.getMongoShard( self.shard ), dbname=getDBName())
					self.adb = self.aclient.connection(collectionname="chat")
	
					if (self.promote != "false"):
						print "C is "+str(self.promote)
						if (self.moderated == "true") and (self.moderator == 0):
							self.adb.find_one({"room":self.room,"instance":self.instance,"approved":1,"id":self.promote}, callback=self._on_promoted)  
						else:
							self.adb.find_one({"room":self.room,"instance":self.instance,"id":self.promote}, callback=self._on_promoted)
					else:
						self._on_response(response.error)
						
				except:
					doError( self )
					self.finish()
		
		def _on_promoted(self, response, error):
				try:
					print "Body is "+str(response["body"])
					msql = ("select conversation_id from conversation where conversation_guid = ? order by conversation_sequence;")
					mcr = constellation.ConstellationUtils.doQuery( msql, [ response["id"] ])
					if len(mcr) == 0:
						asql = ("insert into conversation (conversation_author, conversation_author_image, fk_author_id, fk_film_id, conversation_date_created, conversation_sequence, conversation_thread, conversation_body, conversation_guid, fk_promoter_id) values (?,?,?,?,?,?,?,?,?,?)")
						constellation.ConstellationUtils.doQuery( asql, [ response["from"], response["user_image"], response["author"], response["film"], str(datetime.now()), 0, response["id"], response["body"].replace("\n","<br />\n"), response["id"], int(self.user["user_id"]) ], 0)
					
					self.write('{"meta": {"rpp": 0, "totalresults": 0, "name": "commentVote", "success": "true"}}')
					self.finish()
				
				except:
					constellation.ConstellationUtils.doError( self )
					self.write('{"meta": {"rpp": 0, "totalresults": 0, "name": "commentVote", "success": "false"}}')
					self.finish()	
							
class MessageFlagHandler(BaseHandler):
		@tornado.web.authenticated
		@tornado.web.asynchronous

		def get(self):
				try:
					self.conversation_id=self.get_argument("c","")
					
					sql = ("update conversation set conversation_status = 0 where conversation_guid = ?")
					mcs = constellation.ConstellationUtils.doQuery( sql, [ self.conversation_id ], 0)
					
					self.write('{"meta": {"rpp": 0, "totalresults": 0, "name": "commentFlag", "success": "true"}}')
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
