#!/usr/bin/env python
#

import traceback 
import smtplib
import datetime
import sys
import oursql
import pylibmc 
import memcache
import random 
import time
import json

from tornado.options import define, options
from datetime import datetime
from email.mime.multipart import MIMEMultipart
from email.mime.text import MIMEText

def sendEmail( self, the_text ):

	msg = MIMEMultipart('alternative')
	msg['Subject'] = "Error from CHAT.PY ("+self.request.headers.get('Host')+")"
	msg['From'] = "no-reply@constellation.tv"
	msg['To'] = "root@constellation.tv"
	
	# Record the MIME types of both parts - text/plain and text/html.
	part1 = MIMEText(the_text, 'plain')
	part2 = MIMEText(the_text, 'html')
	
	# Attach parts into message container.
	# According to RFC 2046, the last part of a multipart message, in this case
	# the HTML message, is best and preferred.
	msg.attach(part1)
	msg.attach(part2)
	
	server = smtplib.SMTP( 'localhost' )
	server.sendmail(msg['From'], msg['To'], msg.as_string())
	server.quit()
	
def doError( self ):
		et, ev, tb = sys.exc_info()
		print "ERROR!"
		print ( "Error: %s" % ev )
		
		
		the_error = ("Error: %s\n\n" % ev )
		the_text ='<table border="1" width="100%">'
		the_text = the_text + '<tr><td colspan="2" bgcolor="#CC0000"><strong>ERROR</strong></td></tr>'
		the_text = the_text + '<tr><td colspan="2" >'+ the_error + '</td></tr>'
		
		the_text = the_text + '<tr><td colspan="2" bgcolor="#EEEEEE"><strong>TIME</strong></td></tr>'
		now = datetime.now()
		the_text = the_text + '<tr><td colspan="2"><strong>' + now.strftime("%Y-%m-%d %H:%M") + '</strong></td></tr>'
		
		the_text = the_text + '<tr><td colspan="2" bgcolor="#FFCC33"><strong>ARGUMENTS</strong></td></tr>'
		for arg in self.request.arguments:
			the_text = the_text + '<tr><td>' + arg + "</td><td>" + str(self.get_argument(arg)) + "</td></tr>"
		
		bodyText = ''
		the_text = the_text + '<tr><td colspan="2" bgcolor="#EEEEEE"><strong>MESSAGE</strong></td></tr>'
		for tb_line in traceback.format_exception(et, ev, tb): 
				 bodyText	+=	str(tb_line) + '<br />' 
		the_text = the_text + '<tr><td colspan="2"><strong>' + str(bodyText) + '</strong></td></tr>'
		
		the_text = the_text + '</table>'
		
		#sendEmail( self, the_text )

def doQuery( query, args=None, count=None, innodb=None ):
		try:
			conn = oursql.connect (host = getHost(), user = "root", passwd = getDBPass(), db = getDBName())
			cursor = conn.cursor ()
		except oursql.Error, e:
			print "Error %d: %s" % (e.args[0], e.args[1])
			print query
			return
		
		if (args != None):
			iw = oursql.IterWrapper( args )
			cursor.execute ( query, iw )
		else:
			cursor.execute ( query )
		
		if (count == None):
			rows = cursor.fetchall()
		elif (count == 1):
			rows = cursor.fetchone()
		
		if (innodb != None):
			conn.commit()
		
		cursor.close ()
		conn.close ()
		
		if (count == 0):
			return
			
		return rows

def getMongoShard( port ):
	if (options.env == 'prod'):
		return 27017
	else:
		return 27017	
	#print "PORT IS " + str(port)
	#if port != None:
		#return 27017
		#print str(27017 + int(port))
		#return 27017
		#if (options.env == 'dev') or (options.env == 'andy') or (options.env == 'stage') or (options.env == 'prod'):
		#	return 27017
		#else:
			#return 27017
		#	return 27017 + int(port)
	#else:
	#	return 27017

def getMongoReadHost():
	
	if (options.env == 'andy'):
		return '192.168.2.107'
	elif (options.env == 'dev'):
		return '192.168.2.7'	
	elif (options.env == 'stage'):
		return '127.0.0.1'
	elif (options.env == 'test'):
		return  'test.db-1.constellation.tv'
		#return [ 'test.db-2.constellation.tv', 'test.db-3.constellation.tv', 'test.db-1.constellation.tv' ]
	else:
		return 'db.constellation.tv'
				
def getHost():
	
	if (options.env == 'andy'):
		return '192.168.2.107'
	elif (options.env == 'dev'):
		return '192.168.2.7'
	elif (options.env == 'stage'):
		return '127.0.0.1'
	elif (options.env == 'test'):
		return 'test.db-1.constellation.tv'
	else:
		return 'db.constellation.tv'

def getHostName():
  if (options.env == 'dev'):
    return 'dev.constellation.tv'
  elif (options.env == 'stage'):
    return 'stage.constellation.tv'
  elif (options.env == 'test'):
    return 'test.constellation.tv'
  else:
    return 'www.constellation.tv'
    
def getEnv():
	if (options.env == 'andy'):
		return 'andy'
	elif (options.env == 'dev'):
		return 'dev'
	elif (options.env == 'stage'):
		return 'stage'
	elif (options.env == 'test'):
		return 'test'
	else:
		return 'prod'
		
def getDB( connection ):
	if (options.env == 'andy'):
		db = connection.constellation_dev
	elif (options.env == 'dev'):
		db = connection.constellation_dev
	elif (options.env == 'stage'):
		db = connection.constellation_stage
	elif (options.env == 'test'):
		db = connection.constellation_test
	else:
		db = connection.constellation
	return db
	
def getDBName():
	if (options.env == 'andy'):
		db = "constellation_dev"
	elif (options.env == 'dev'):
		db = "constellation_dev"
	elif (options.env == 'stage'):
		db = "constellation_stage"
	elif (options.env == 'test'):
		db = "constellation_test"
	else:
		db = "constellation"
	return db

def getDBPass():
	if (options.env == 'andy'):
		apass = "1hsvy5qb"
	elif (options.env == 'dev'):
		apass = "1hsvy5qb"
	else:
		apass = "constellation2010"
	return apass

def getCounter( room, instance, shard, var ):
	if options.env == "andy":
		mc = memcache.Client([getHost()+":11211"], debug=0)
	else:	
		mc = pylibmc.Client([getHost()], binary=True, 
								behaviors={"tcp_nodelay": True,
								"ketama": True})
	val = mc.get(str(room+":"+instance+":shard"+str(shard)+":"+var))
	#print("GET COUNTER " + room+":"+instance+":shard"+str(shard)+":"+var + " AS " + str(val))
	if val == None:
		return 0
	else:
		return val
	
def setCounter( room, instance, shard, var, val ):
	#print("SET COUNTER " + var + " TO " + str(val))
	if options.env == "andy":
		mc = memcache.Client([getHost()+":11211"], debug=0)
	else:	
		mc = pylibmc.Client([getHost()], binary=True, 
								behaviors={"tcp_nodelay": True,
								"ketama": True})
	if val == None:
		val = 0
	mc.set(str(room+":"+instance+":shard"+str(shard)+":"+var), val)
	return val
	
def incCounter( room, instance, shard, var ):
	if options.env == "andy":
		mc = memcache.Client([getHost()+":11211"], debug=0)
	else:	
		mc = pylibmc.Client([getHost()], binary=True, 
								behaviors={"tcp_nodelay": True,
								"ketama": True})
	val = mc.get(str(room+":"+instance+":shard"+str(shard)+":"+var))
	if (val == None):
		val = 0
	nval = int(val) + 1
	mc.set(str(room+":"+instance+":shard"+str(shard)+":"+var), nval)
	#print("INC COUNTER " + var + " TO " + str(nval))
	return nval
	
def getRedis( r, room, instance, shard, var ):
	val = r.get(str(room+":"+instance+":shard"+str(shard)+":"+var))
	#print("GET COUNTER " + room+":"+instance+":shard"+str(shard)+":"+var + " AS " + str(val))
	if val == None:
		return 0
	else:
		return val

def setRedis( r, room, instance, shard, var, val ):
	#print("SET COUNTER " + var + " TO " + str(val))
	if val == None:
		val = 0
	r.set(str(room+":"+instance+":shard"+str(shard)+":"+var), val)
	return val
	
def incRedis( r, room, instance, shard, var ):
	val = r.incr(str(room+":"+instance+":shard"+str(shard)+":"+var))
	if (val == None):
		val = 0
	return val
	
def indexRedisChat( r, shard, item, var, obj, approval ):
	#print("INDEX " + str(item["room"])+":"+str(item["instance"])+":shard"+str(shard)+":"+str(var)+":"+approval)
	if item == None:
		return None
	r.zadd(str(item["room"])+":"+str(item["instance"])+":shard"+str(shard)+":"+str(var)+":"+approval, time.time(), json.dumps(obj))
	return 1

def removeRedisChat( r, room, instance, shard, type, obj ):
	print(str(room)+":"+str(instance)+":shard"+str(shard)+":"+str(type)+":approved")
	print(str(room)+":"+str(instance)+":shard"+str(shard)+":"+str(type)+":unapproved")
	r.zrem(str(room)+":"+str(instance)+":shard"+str(shard)+":"+str(type)+":approved", json.dumps(obj))
	r.zrem(str(room)+":"+str(instance)+":shard"+str(shard)+":"+str(type)+":unapproved", json.dumps(obj))
	return 1
	
def broadcastRedisChat( r, channel, item, var, obj, approval ):
	print("BROADCAST " + str(item["room"])+":"+str(channel)+":"+str(var)+":"+approval)
	if item == None:
		return None
	r.zadd(str(item["room"])+":"+str(channel)+":"+str(var)+":"+approval, time.time(), json.dumps(obj))
	return 1
	
def unIndexRedisChat( r, shard, item, var, obj, approval ):
	#print("UNINDEX " + str(item["room"])+":"+str(item["instance"])+":shard"+str(shard)+":"+str(var)+":"+approval)
	if item == None:
		return None
	#Redis is zero-indexed
	r.zrem(str(item["room"])+":"+str(item["instance"])+":shard"+str(shard)+":"+str(var)+":"+approval, json.dumps(obj))
	return 1

def blockRedisUser( r, room, user, severity ):
	#print ("BLOCK " + str(room)+":" + severity + " WITH " + str(user))
	r.sadd(str(room)+":" + severity,int(user))
	return 1

def unBlockRedisUser( r, room, user, severity ):
	#print ("UNBLOCK " + str(room)+":block" + " WITH " + str(user))
	#Redis is zero-indexed
	r.srem(str(room)+":block",int(user))
	r.srem(str(room)+":warning",int(user))
	return 1

def checkBlockRedisUser( r, room, user, severity ):
	#Redis is zero-indexed
	return r.sismember(str(room)+":" + severity, int(user))
		
def initRedisChat( r, shard, item, var, approval, count=-200 ):
	print("SET INIT " + str(item["room"])+":"+str(item["instance"])+":shard"+str(shard)+":"+str(var)+":"+approval+" with count " + str(count))
	if item == None:
		return None
	return r.zrange(str(item["room"])+":"+str(item["instance"])+":shard"+str(shard)+":"+str(var)+":"+approval, count,-1)
	
def countRedisUser( r, room, instance, shard, user ):
	#print ("COUNT " + str(room)+":"+str(instance)+":shard"+str(shard)+":users")
	r.sadd(str(room)+":list",str(instance)+":shard"+str(shard) )
	r.zadd(str(room)+":"+str(instance)+":shard"+str(shard)+":users", int(time.time()), int(user) )
	r.zremrangebyscore(str(room)+":"+str(instance)+":shard"+str(shard)+":users", 0, int(time.time()) - 10 )
	r.expire(str(room)+":"+str(instance)+":shard"+str(shard)+":users", 10 )
	return 1

def showRedisCount( r, room, instance, shard ):
	#print ("SHOW " + str(room)+":"+str(instance)+":shard"+str(shard)+":users")
	return r.zcard(str(room)+":"+str(instance)+":shard"+str(shard)+":users")
	
def getRedisRooms( r, room ):
	#print ("GET " + str(room)+":list")
	return r.smembers(str(room)+":list")

def setRedisCurrentPoll( r, room, question ):
	#print("INDEX " + str(item["room"])+":"+str(item["instance"])+":shard"+str(shard)+":"+str(var)+":"+approval)
	return r.set(str(room)+":currentpoll",question)

def getRedisCurrentPoll( r, room ):
	#print("INDEX " + str(item["room"])+":"+str(item["instance"])+":shard"+str(shard)+":"+str(var)+":"+approval)
	return r.get(str(room)+":currentpoll")
			
def indexRedisPoll( r, room, question, action ):
	#print("POLL " + str(room)+":poll"+":"+str(question) + " IS " + str(action))
	val = r.get(str(room)+":poll"+":"+str(question)) 
	#print ("val is " + str(val))
	if val == None:
		val = 0
	else:
		val = int(val)
	if (action == 0) and (val > -100):
		#print ("DECR")
		return r.decr(str(room)+":poll"+":"+str(question))
	elif (val < 100):
		#print ("INCR")
		return r.incr(str(room)+":poll"+":"+str(question))
	else:
		#print ("NOCR")
		return val

def countRedisPoll( r, room, question ):
	if int(question) == 0:
		return 0
	#print("INDEX " + str(item["room"])+":"+str(item["instance"])+":shard"+str(shard)+":"+str(var)+":"+approval)
	return r.get(str(room)+":poll"+":"+str(question))
