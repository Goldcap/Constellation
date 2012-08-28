#!/usr/bin/env python

import sys

import re
import mechanize
import cookielib
import json
import time
import random
import uuid

from datetime import date
from datetime import datetime

# Browser
br = mechanize.Browser()

# Cookie Jar
cj = cookielib.LWPCookieJar()
br.set_cookiejar(cj)

# Browser options
br.set_handle_equiv(True)
#br.set_handle_gzip(True)
br.set_handle_redirect(True)
br.set_handle_referer(True)
br.set_handle_robots(False)

# Follows refresh 0 but not hangs on refresh > 0
br.set_handle_refresh(mechanize._http.HTTPRefreshProcessor(), max_time=1)

# Want debugging messages?
#br.set_debug_http(True)
#br.set_debug_redirects(True)
#br.set_debug_responses(True)
domain = 'http://dev.constellation.tv'

# User-Agent (this is cheating, ok?)
br.addheaders = [('User-agent', 'Mechanize Bot')]

films = [ 4, 14, 15 ]
film = str(random.choice( films ))
#Go to the film page
r = br.open(domain + '/film/' + film)
r.read()

# Select the first (index zero) form
br.select_form(name='sign-up_form')

# Let's search
pwd = ''
for i in range(10):
  numba = random.randint(48,122)
  if ((numba != 96) and (numba != 94) and (numba != 92) and (numba != 69) and (numba != 60) and (numba != 62) and (numba != 58) and (numba != 59)):
    pwd += chr(numba)
br.form["name"] = str(uuid.uuid1())
br.form["username"] = "User " + str(random.randint(0,255))
month = "%02d" % random.randint(1,12)
br.form["month"] = [month]
day = "%02d" % random.randint(1,28)
br.form["day"] = [day]
year = "%04d" % random.randint(1920,1979)
br.form["year"] = [year]
br.form["email"] = pwd+"@constellation.tv"
br.form["password"] = pwd
br.form["password2"] = pwd
br.submit()

r = br.open(domain + '/film/' + film)
r.read()

br.select_form(name='host_detail')
#print br.form
today = str(date.today())
currenttime = time.time()
time = time.strftime('%H:%M', time.localtime(currenttime + 60 * 60))
print today + ' ' + time
br.form["fld-host_date"] = today
br.form["fld-host_time"] = time
br.submit()
vals = json.loads(br.response().read())
screening = vals["hostResponse"]["screening"]
print screening

r = br.open(domain + '/film/' + film)
br.select_form(name='host_purchase')

br.form["first_name"] = "Some First Name"
br.form["last_name"] = "Some Last Name"
br.form["credit_card_number"] = "4286546374372331"
br.form["card_verification_number"] = "962"
br.form["expiration_date_month"] = ["3"]
br.form["expiration_date_year"] = ["2012"]
br.form["b_address1"] = "1 main street"
br.form["b_address2"] = "apt 3"
br.form["b_city"] = "San Jose"
br.form["b_state"] = ["CA"]
br.form["b_zipcode"] = "95131"
br.form["b_country"] = ["US"]
br.form.action=br.form.action + "/" + screening
br.submit()
vals = json.loads(br.response().read())
status = vals["hostResponse"]["status"]
screening = vals["hostResponse"]["screening"]

if status == "failure":
  print "FAIL: " + vals["hostResponse"]["message"]
elif status == "success":
  print "SUCCESS: " + vals["hostResponse"]["result"]
  r = br.open(domain + '/theater/'+screening)
  html = r.read()
  
  the_film = re.search('<span id="film" class="reqs">([^<].*)</span>', html)
  the_instance = re.search('<div id="instance" class="reqs">([^<].*)</div>', html)
  the_room = re.search('<span id="room" class="reqs">([^<].*)</span>', html)
  the_user = re.search('<span id="userid" class="reqs">([^<].*)</span>', html)
  the_host = re.search('<div id="host" class="reqs">([^<].*)</div>', html)
  the_port = re.search('<div id="port" class="reqs">([^<].*)</div>', html)
  if the_port != None:
    a_port = int(the_port.group(1))
  else:
    a_port = 0
    
  br.select_form(name='chat_post')
  br.new_control("HIDDEN", "author", {})
  br.new_control("HIDDEN", "film", {})
  br.new_control("HIDDEN", "cookie", {})
  br.new_control("HIDDEN", "instance", {})
  br.new_control("HIDDEN", "room", {})
  control = br.form.find_control("author")
  control.readonly = False
  control = br.form.find_control("film")
  control.readonly = False
  control = br.form.find_control("cookie")
  control.readonly = False
  control = br.form.find_control("instance")
  control.readonly = False
  control = br.form.find_control("room")
  control.readonly = False
  br.form["body"] = "Robot Host " + datetime.now().strftime("%d/%m/%Y %H:%M")
  br.form["author"] = str(the_user.group(1))
  br.form["film"] = str(the_film.group(1))
  for index, cookie in enumerate(cj):
      if cookie.name == "constellation_frontend":
        thacook = cookie.value
        print thacook
  br.form["cookie"] = thacook
  br.form["instance"] = str(the_instance.group(1))
  br.form["room"] = str(the_room.group(1))
  port = str(the_host.group(1)) + ":" + str(a_port + 9090)
  br.form.action= domain + "/services/chat/post?i="+port
  br.submit()
  vals = json.loads(br.response().read())
  print vals["body"]
