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
				domain = 'http://www.constellation.tv'
				securedomain = 'https://www.constellation.tv'
				screening = 'abJyPfsRDxNqiTV'
				films = [ 92 ]
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

				resp = br.open(domain + '/theater/'+screening)
				html = resp.read()
				
				the_user = re.search('<span class="reqs" id="mechanize_userId">([^<].*)</span>', html)
				#print "USER IS: " + str(the_user.group(1))
				user = str(the_user.group(1))
				the_cmo = re.search('<span class="reqs" id="mechanize_cmo">([^<].*)</span>', html)
				#print "CMO IS: " + str(the_cmo.group(1))
				cmo = str(the_cmo.group(1))
				for index, cookie in enumerate(cj):
							if cookie.name == "constellation_frontend":
								thacook = cookie.value
				#print "COOKIE IS: " + str(thacook)
				the_film = re.search('<span class="reqs" id="mechanize_filmId">([^<].*)</span>', html)
				#print "FILM IS: " + str(the_film.group(1))
				film = str(the_film.group(1))
				the_instance = re.search('<span class="reqs" id="mechanize_instance">([^<].*)</span>', html)
				#print "THE INSTANCE IS: " + str(the_instance.group(1))
				instance = str(the_instance.group(1))
				is_host = re.search('<span class="reqs" id="mechanize_ishost">([^<].*)</span>', html)
				#print "THE HOST IS: " + str(is_host.group(1))
				host = str(is_host.group(1))
				the_mdt = re.search('<span class="reqs" id="mechanize_mdt">([^<].*)</span>', html)
				#print "THE MDT IS: " + str(the_mdt.group(1))
				mdt = str(the_mdt.group(1))
				the_port = re.search('<span class="reqs" id="mechanize_port">([^<].*)</span>', html)
				#print "THE IMAGE IS: " + str(the_image.group(1))
				aport = str(the_port.group(1))
				s=0
				t=0
				
				for i in range(1,200):
					post_body=urllib.urlencode({
						'a': 0,
						'c': 0,
						'cmo': cmo,
						'cookie': thacook,
						'instance': instance,
						'ishost': host,
						'mdt': mdt,
						'p': aport,
						'room':screening,
						's': s,
						't': t,
						'u': user
						})
					
					start_timer = time.time()
					Request = urllib2.Request( domain + '/services/chat/update', post_body)
					resp = br.open(Request)
					content = resp.read()
					vals = json.loads(content)
					s = int(vals["sequence"])
					t = int(vals["meta"]["timestamp"])
					
					latency = time.time() - start_timer
					
					self.custom_timers['Chat'] = latency
					assert (resp.code == 200), 'Bad Response: HTTP %s' % resp.code 
				
					time.sleep(3)


if __name__ == '__main__':
		trans = Transaction()
		trans.run()
		print trans.custom_timers
