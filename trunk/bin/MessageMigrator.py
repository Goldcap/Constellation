#!/usr/bin/env python

#Message Migrator
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
import pymongo 
import oursql
import argparse


from pymongo import Connection
from collections import defaultdict
from datetime import datetime

parser = argparse.ArgumentParser(description='Process some integers.')
parser.add_argument('--host', dest='host')
args = parser.parse_args()

f = open('curcount', 'r+')
res = f.read()

print "READ TIME: " + str(res)


connection = Connection(args.host, 27017)
db =  connection.constellation
collection = db.chat
r = redis.StrictRedis(host=args.host, port=6379, db=0)

print "TOTAL POSTS: " + str(collection.count())
curtime = None

if res == '':
	print "NO RES, BIATCH"
	first = collection.find( {"mod_time": {"$gte": 0 }} ).sort( "mod_time" , 1 ).limit( 1000 )
else:
	first = collection.find( {"mod_time": {"$gte": float(res) }} ).sort( "mod_time" , 1 ).limit( 1000 )
	
thisroom = ""
for item in first:
	curtime = item["mod_time"]
	newroom = item["room"] + item["instance"]
	if (newroom != thisroom):
		query = "select chat_instance_port from chat_instance where fk_screening_key = '" + item["room"] + "' and chat_instance_key = '" + item["instance"] + "' limit 1;"
		print "QUERY SQL"
		print query
		conn = oursql.connect (host = args.host, user = "root", passwd = 'constellation2010', db = 'constellation')
		cursor = conn.cursor()
		cursor.execute ( query )
		rows = cursor.fetchone()
		cursor.close()
		conn.close ()
		if (rows != None):
			print "FOUND SHARD " + str(rows[0]) + " for " + str(item["room"]) + ":" + str(item["instance"])
			shard = rows[0]
			thisroom = item["room"] + item["instance"]
		else:
			print "No Row for " + str(item["room"]) + ":" + str(item["instance"])
			shard = 0
			thisroom = ""
	    
	thefrom = item.get('isfrom', None)
	if thefrom == None:
		 thefrom = item.get('from', 'null')
	
	theimage = item.get('user_image', '')
	thehost = item.get('ishost', 0)
	if (thehost == 'undefined'):
		thehost = 0
	sequence_approved = item.get('sequence_approved', 1)
	approved = item.get('approved', 1)
	asmoderator = item.get('asmoderator', 0)  
	colorme = item.get('colorme', 0)
	
	achat = { "id": item["id"],
									"isfrom": thefrom,
									"author": int(item["author"]),
									"user_image": theimage,
									"ishost": thehost,
									"to": item["to"],
									"body": item["body"],
									"film": item["film"],
									"room": item["room"],
									"instance": item["instance"],
									"pair": item["pair"],
									"mod_time": item["mod_time"],
									"type": item["type"],
									"sequence": item["sequence"],
									"sequence_approved": sequence_approved,
									"approved": approved,
									"asmoderator": asmoderator,
									"colorme": colorme}
									
	pychat = defaultdict(list)
	pychat[unicode('id')] = unicode(item["id"])
	pychat[unicode('isfrom')] = unicode(thefrom)
	pychat[unicode('author')] = int(item["author"])
	pychat[unicode('user_image')] = unicode(theimage)
	pychat[unicode('ishost')] = int(thehost)
	pychat[unicode('to')] = unicode('public')
	pychat[unicode('body')] = unicode(item["body"])
	pychat[unicode('film')] = int(item["film"])
	pychat[unicode('room')] = unicode(item["room"])
	pychat[unicode('instance')] = unicode(item["instance"])
	pychat[unicode('pair')] = unicode(item["pair"])
	pychat[unicode('mod_time')] = float(item["mod_time"])
	pychat[unicode('type')] = unicode(item["type"])
	pychat[unicode('sequence')] = int(item["sequence"])
	pychat[unicode('sequence_approved')] = int(sequence_approved)
	pychat[unicode('approved')] = int(approved)
	pychat[unicode('asmoderator')] = int(asmoderator)
	pychat[unicode('colorme')] = int(colorme)
	
	approval = "approved" if approved == 1 else "unapproved"
	print("INDEX " + str(item["room"])+":"+str(item["instance"])+":shard"+str(shard)+":"+str(item["type"])+":"+approval)
	r.zadd(str(item["room"])+":"+str(item["instance"])+":shard"+str(shard)+":"+str(item["type"])+":"+approval, item["mod_time"], json.dumps(pychat))
	print json.dumps(item["mod_time"])	

if curtime == None:
	print "DONE!"
else:
	f.seek(0)
	f.write(str(curtime))
	f.close()
