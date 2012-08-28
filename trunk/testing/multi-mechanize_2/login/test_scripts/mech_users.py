#!/usr/bin/env python
#
#  Copyright (c) 2010 Corey Goldberg (corey@goldb.org)
#  License: GNU LGPLv3
#  
#  This file is part of Multi-Mechanize

import sys

import re
import mechanize
import cookielib
import json
import time
import random
import uuid
import httplib
import urllib 
import urllib2

from datetime import date
from datetime import datetime


class Transaction(object):
		def __init__(self):
				self.custom_timers = {}
		
		def run(self):
				
				domain = 'http://test.constellation.tv'
				screening = 'thevowevent'
				films = [ 87 ]
				film = str(random.choice( films ))
				
				# Browser
				br = mechanize.Browser()
				#br.set_debug_http(True)
				
				# Cookie Jar
				cj = cookielib.LWPCookieJar()
				br.set_cookiejar(cj)
				
				# Browser options
				br.set_handle_equiv(True)
				#br.set_handle_gzip(True)
				br.set_handle_redirect(True)
				br.set_handle_referer(True)
				br.set_handle_robots(True)
				
				# Follows refresh 0 but not hangs on refresh > 0
				br.set_handle_refresh(mechanize._http.HTTPRefreshProcessor(), max_time=1)
				
				# Want debugging messages?
				#br.set_debug_http(True)
				br.set_debug_redirects(True)
				br.set_debug_responses(True)
				
				# User-Agent (this is cheating, ok?)
				br.addheaders = [('User-agent', 'Mechanize Bot')]

				# start signup service
				pwd = ''
				for i in range(10):
					numba = random.randint(48,122)
					if ((numba != 96) and (numba != 94) and (numba != 92) and (numba != 69) and (numba != 60) and (numba != 62) and (numba != 58) and (numba != 59)):
						pwd += chr(numba)
				post_body=urllib.urlencode({
						'username': "User " + str(random.randint(0,255)),
						'email': pwd+"@constellation.tv",
						'password':pwd,
						'password2':pwd})
				Request = urllib2.Request( domain + "/services/Join", post_body)
				start_timer = time.time()
				resp = br.open(Request)
				resp.read()
				latency = time.time() - start_timer

				self.custom_timers['Signup'] = latency 
				#assert (resp.code == 200), 'Bad Response: HTTP %s' % resp.code
			 
				
if __name__ == '__main__':
		trans = Transaction()
		trans.run()
		print trans.custom_timers
