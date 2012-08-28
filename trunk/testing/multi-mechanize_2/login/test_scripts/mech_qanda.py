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

				# start signup service
				pwd = '123456'
				for i in range(10):
					numba = random.randint(48,122)
					if ((numba != 96) and (numba != 94) and (numba != 92) and (numba != 69) and (numba != 60) and (numba != 62) and (numba != 58) and (numba != 59)):
						pwd += chr(numba)
				post_body=urllib.urlencode({'username': "User " + str(random.randint(0,255)),'email': pwd+"@constellation.tv",'password':pwd,'password2':pwd})
				Request = urllib2.Request( domain + "/services/Join", post_body)
				start_timer = time.time()
				resp = br.open(Request)
				resp.read()
				latency = time.time() - start_timer

				self.custom_timers['Signup'] = latency 
				assert (resp.code == 200), 'Bad Response: HTTP %s' % resp.code

				post_body=urllib.urlencode({
						'b_address1':'1 main street',
						'b_address2':'apt 3',
						'b_city':'San Jose',
						'b_country':'US',
						'b_state':'CA',
						'b_zipcode':'95131',
						'card_verification_number':'962',
						'confirm_email': pwd+'@constellation.tv',
						'credit_card_number':'4286546374372331',
						'dohbr':'false',
						'email': pwd+'@constellation.tv',
						'expiration_date_month':"3",
						'expiration_date_year':"2012",
						'first_name': 'Guest',
						'invite_count':0,
						'last_name': 'Last',
						'promo_code': 0,
						'ticket_code': 'false',
						'ticket_price':'0.00',
						'username': 'User ' + pwd												
						})
			
				start_timer = time.time()
				Request = urllib2.Request( securedomain + '/screening/'+film+'/purchase/'+screening, post_body)
				resp = br.open(Request)
				content = resp.read()
				latency = time.time() - start_timer

				self.custom_timers['Payment'] = latency 
				assert (resp.code == 200), 'Bad Response: HTTP %s' % resp.code
				
				vals = json.loads(content)
				status = vals["purchaseResponse"]["status"]
				message = vals["purchaseResponse"]["result"]
				if message == "":
					message = "No Message"
				
				#screening = vals["purchaseResponse"]["screening"]
				#print "Purchase was a " + status + ":" + str(message)
				assert (resp.code == 200), 'Bad Response: HTTP %s' % resp.code
				assert (str(status) == "success"), 'Purchase Failure: ' % str(message)
        # end hosting payment

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
				the_image = re.search('<span class="reqs" id="mechanize_userImage">([^<].*)</span>', html)
				#print "THE IMAGE IS: " + str(the_image.group(1))
				image = str(the_image.group(1))
				the_port = re.search('<span class="reqs" id="mechanize_port">([^<].*)</span>', html)
				#print "THE IMAGE IS: " + str(the_image.group(1))
				aport = str(the_port.group(1))
				
				for i in range(1,200):
					post_body=urllib.urlencode({
						'author': user,
						'body': user + datetime.now().strftime("%H:%M %s"),
						'cmo': cmo,
						'cookie': thacook,
						'film': film,
						'instance': instance,
						'ishost': host,
						'mdt': mdt,
						'room':screening,
						'type':'qanda',
						'user_image': image,
						'p': aport
						})
					
					start_timer = time.time()
					Request = urllib2.Request( domain + '/services/qanda/post', post_body)
					resp = br.open(Request)
					resp.read()
					latency = time.time() - start_timer
					
					self.custom_timers['Chat'] = latency
					assert (resp.code == 200), 'Bad Response: HTTP %s' % resp.code 
				
					time.sleep(random.randint(10,15))


if __name__ == '__main__':
		trans = Transaction()
		trans.run()
		print trans.custom_timers
