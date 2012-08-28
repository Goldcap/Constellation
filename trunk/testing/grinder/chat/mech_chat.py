#!/usr/bin/env python
#
#  Copyright (c) 2010 Corey Goldberg (corey@goldb.org)
#  License: GNU LGPLv3
#  
#  This file is part of Multi-Mechanize
                                   
from net.grinder.script.Grinder import grinder
from net.grinder.script import Test
from net.grinder.plugin.http import HTTPRequest
from net.grinder.plugin.http import HTTPPluginControl
from HTTPClient import NVPair 
from HTTPClient import Cookie, CookieModule, CookiePolicyHandler

import sys

import re
import time
import random

from datetime import date
from datetime import datetime

# A shorter alias for the grinder.logger.info() method.
log = grinder.logger.info

test1 = Test(1, "User Signup")    
test2 = Test(2, "View Theater")
test3 = Test(3, "Purchase Ticket")
test4 = Test(4, "Enter Theater")
test5 = Test(5, "Read Channel")
test6 = Test(6, "Write Channel")

time_to_run = 	 60

# Set up a cookie handler to log all cookies that are sent and received.
class MyCookiePolicyHandler(CookiePolicyHandler):
		def acceptCookie(self, cookie, request, response):
				log("accept cookie: %s" % cookie)
				return 1

		def sendCookie(self, cookie, request):
				log("send cookie: %s" % cookie)
				return 1
 
CookieModule.setCookiePolicyHandler(MyCookiePolicyHandler())

class TestRunner:
		def __call__(self):
				domain = 'http://test.constellation.tv'
				screening = 'thevowevent'
				films = [ 87 ]
				film = str(random.choice( films ))
				
				# start signup service
				pwd = '123456'
				for i in range(10):
					numba = random.randint(48,122)
					if ((numba != 96) and (numba != 94) and (numba != 92) and (numba != 69) and (numba != 60) and (numba != 62) and (numba != 58) and (numba != 59)):
						pwd += chr(numba)
				
				signupRequest = test1.wrap(HTTPRequest(url=domain + "/services/Join"))
				parameters = (
						NVPair("username", str(random.randint(0,255))),
						NVPair("email", "grind-" + pwd + "@constellation.tv"),
						NVPair("password", pwd),
						NVPair("password2", pwd)
				)
				signupRequest.POST(parameters)
				
				viewRequest = test2.wrap(HTTPRequest(url=domain + '/theater/'+screening))
				viewRequest.GET()
				
				purchaseRequest = test3.wrap(HTTPRequest(url=domain + '/screening/'+film+'/purchase/'+screening))
				post_body=(
						NVPair("b_address1", str('1 main street')),
						NVPair("b_address2", str('Apt 3')),
						NVPair("b_city", str('San Jose')),
						NVPair("b_country", str('US')),
						NVPair("b_state", str('CA')),
						NVPair("b_zipcode", str('95131')),
						NVPair("card_verification_number", str('962')),
						NVPair("confirm_email", str('grind-'+pwd+'@constellation.tv')),
						NVPair("credit_card_number", str('4286546374372331')),
						NVPair("dohbr", str('false')),
						NVPair("email", str('grind-'+pwd+'@constellation.tv')),
						NVPair("expiration_date_month", str("6")),
						NVPair("expiration_date_year", str("2012")),
						NVPair("first_name", str('First')),
						NVPair("invite_count",  str('0')),
						NVPair("last_name", str('Last')),
						NVPair("promo_code", str('0')),
						NVPair("ticket_code", str('false')),
						NVPair("ticket_price", str('0.00')), 
						NVPair("username", str('User ' + pwd))			
				)
				result = purchaseRequest.POST(post_body)
				
				enterRequest = test4.wrap(HTTPRequest(url=domain + '/theater/'+screening))
				result = enterRequest.GET()
				html = result.getText()
					
				the_user = re.search('<span class="reqs" id="mechanize_userId">([^<].*)</span>', html)
				log("USER IS: " + str(the_user.group(1)))
				user = str(the_user.group(1))
				
				the_cmo = re.search('<span class="reqs" id="mechanize_cmo">([^<].*)</span>', html)
				log("CMO IS: " + str(the_cmo.group(1)))
				cmo = str(the_cmo.group(1))
				
				threadContext = HTTPPluginControl.getThreadHTTPClientContext()
				cookies = CookieModule.listAllCookies(threadContext)
				for c in cookies:
					if c.getName() == "constellation_frontend":
						log("retrieved cookie: %s" % c.getValue())
						thacook = c.getValue()
				the_film = re.search('<span class="reqs" id="mechanize_filmId">([^<].*)</span>', html)
				log("FILM IS: " + str(the_film.group(1)))
				film = str(the_film.group(1))
				the_instance = re.search('<span class="reqs" id="mechanize_instance">([^<].*)</span>', html)
				log("THE INSTANCE IS: " + str(the_instance.group(1)))
				instance = str(the_instance.group(1))
				is_host = re.search('<span class="reqs" id="mechanize_ishost">([^<].*)</span>', html)
				log("THE HOST IS: " + str(is_host.group(1)))
				host = str(is_host.group(1))
				the_mdt = re.search('<span class="reqs" id="mechanize_mdt">([^<].*)</span>', html)
				log("THE MDT IS: " + str(the_mdt.group(1)))
				mdt = str(the_mdt.group(1))
				the_image = re.search('<span class="reqs" id="mechanize_userImage">([^<].*)</span>', html)
				log("THE IMAGE IS: " + str(the_image.group(1)))
				image = str(the_image.group(1))
				the_port = re.search('<span class="reqs" id="mechanize_port">([^<].*)</span>', html)
				log("THE PORT IS: " + str(the_port.group(1)))
				aport = str(the_port.group(1))
				
				time_to_stop = time.time() + time_to_run
				#chatRequest = test5.wrap(HTTPRequest(url='http://www.google.com/'))
				#chatRequest.GET()
	
				myRandom = random.random()
		
				if myRandom > .5:
					#READ CHANNEL
					while time.time() < time_to_stop:
						read_chatRequest = test5.wrap(HTTPRequest(url=domain + '/services/chat/update'))
						read_chat_body=(
							NVPair("cmo", str(cmo)),
							NVPair("cookie", str(thacook)),
							NVPair("instance", str(instance)),
							NVPair("ishost", str(host)),
							NVPair("mdt", str(mdt)),
							NVPair("room", str(screening)),
							NVPair("s", "0"),
							NVPair("a", "0"),
							NVPair("c", "6"),
							NVPair("t", time.time()),
							NVPair("p", str(aport)),
							NVPair("u", str(user))
						)
						read_chatRequest.POST(read_chat_body)
				else:
					#WRITE CHANNEL
					while time.time() < time_to_stop:
						chatRequest = test6.wrap(HTTPRequest(url=domain + '/services/chat/post'))
						chat_body=(
							NVPair("author", str(user)),
							NVPair("body", str(user + datetime.now().strftime("%H:%M %s"))),
							NVPair("cmo", str(cmo)),
							NVPair("cookie", str(thacook)),
							NVPair("film", str(film)),
							NVPair("instance", str(instance)),
							NVPair("ishost", str(host)),
							NVPair("mdt", str(mdt)),
							NVPair("room", str(screening)),
							NVPair("type", str('chat')),
							NVPair("user_image", str(image)),
							NVPair("p", str(aport))
						)
						chatRequest.POST(chat_body)
                    

				
