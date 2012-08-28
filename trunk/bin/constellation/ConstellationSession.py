import tornado.auth
import tornado.escape
import tornado.httpserver
import tornado.ioloop
import tornado.options
import tornado.web
import constellation.ConstellationUtils
import re
import pylibmc 
import memcache

from pymongo import Connection  
from tornado.options import define, options

class SessionHandler(object):
		
		def get_session_data(self,cookie):
				
				print "SESSION ENVIRONMENT IS " + str(options.env)
				session = {}
				if cookie == None:
					session_uuid = self.get_cookie("constellation_frontend")
				else:
					session_uuid = cookie
				#Comment this out when live
				#if session_uuid == None:
				#	session["user_username"] = "Anonymous"
				#	return session
				
				#this is a read, so use getMongoReadHost
				if options.env == "andy":
					self.mc = memcache.Client([constellation.ConstellationUtils.getHost()+":11211"], debug=0)
				else:	
					self.mc = pylibmc.Client([constellation.ConstellationUtils.getHost()], 
											behaviors={"tcp_nodelay": True})
				sessiondata = self.mc.get(str(session_uuid))
				
				#connection = Connection( constellation.ConstellationUtils.getMongoReadHost( options.env ), 27017 )
				#db = constellation.ConstellationUtils.getDB(options.env,connection)
				
				#print ("LOOKING FOR "+ str(session_uuid) + " IN " + constellation.ConstellationUtils.getHost( options.env ))
				
				#user = db.session.find({"session_uuid":session_uuid})
				#if ((user == None) or (user.count() == 0)):
				if (sessiondata == None):
					#print ("FOUND NOTHING WITH " + str(session_uuid))
					return None
				
				#sessiondata = user[0]["session_data"]
				
				authenticated = re.search("symfony/user/sfUser/authenticated\|b\:1",sessiondata)
				if (authenticated):
					session["authenticated"] = True;
					attributes = re.search('{s:30:"symfony/user/sfUser/attributes";a:(\d)+:{([^}].+)}',sessiondata)
					if attributes != None:
						thevals = attributes.group(2).split(";")
						i=1
						for val in thevals:
							arr = val.split(":")
							if i % 2 == 0:
								if len(arr) == 2:
									session[key] = arr[1].replace("\"","")
								elif len(arr) == 3:
									session[key] = arr[2].replace("\"","")
								elif len(arr) == 4:
									session[key] = arr[2].replace("\"","") + ":" + arr[3].replace("\"","")
							else:
								if len(arr) == 3:
									key = arr[2].replace("\"","")
							i+=1
					else:
						session["user_username"] = "Anonymous"
					return session
				else:
					return None

class BaseHandler(tornado.web.RequestHandler, SessionHandler):
		
		def get_current_user(self):
				self.shard=self.get_argument("p",None)
				self.cookie = self.get_argument("cookie",None)
				user = self.user = self.get_session_data( self.cookie )
				if not user: 
					#print "No User"
					return None
				username = user.get('user_username', "")
				if (username == ""):
					#print "IS User " + username
				#else:
					user["user_username"] = "Anonymous"
				#print(user["user_username"])
				self.set_secure_cookie("user",user["user_username"])
				return user["user_username"]