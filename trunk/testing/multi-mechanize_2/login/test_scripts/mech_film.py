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
				films = [ 93 ]
				film = str(random.choice( films ))
				
				# Browser
				br = mechanize.Browser()
				br.set_debug_http(True)
				
				# Cookie Jar
				cj = cookielib.LWPCookieJar()
				br.set_cookiejar(cj)
				
				# Browser options
				br.set_handle_equiv(True)
				br.set_handle_gzip(True)
				br.set_handle_redirect(True)
				br.set_handle_referer(True)
				br.set_handle_robots(False)
				
				# Follows refresh 0 but not hangs on refresh > 0
				br.set_handle_refresh(mechanize._http.HTTPRefreshProcessor(), max_time=1)
				
				# Want debugging messages?
				#br.set_debug_http(True)
				#br.set_debug_redirects(True)
				#br.set_debug_responses(True)
				
        # User-Agent (this is cheating, ok?)
				br.addheaders = [('User-agent', 'Mechanize Bot')]

				# start filmpage view
				start_timer = time.time()
				resp = br.open(domain + '/film/' + film)
				resp.read()
				latency = time.time() - start_timer
				
				self.custom_timers['FilmView'] = latency 
				assert ((resp.code == 200) or (resp.code == 302)), 'Bad Response: HTTP %s' % resp.code
				# end filmpage view
				
if __name__ == '__main__':
		trans = Transaction()
		trans.run()
		print trans.custom_timers
