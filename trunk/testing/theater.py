#!/usr/bin/env python

import sys

import re
import mechanize
import cookielib
import random
import json
import time
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

# User-Agent (this is cheating, ok?)
br.addheaders = [('User-agent', 'Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.0.1) Gecko/2008071615 Fedora/3.0.1-1.fc9 Firefox/3.0.1')]

#Go to the film page
r = br.open('http://dev.constellation.tv/film/4')
html = r.read()

# Select the first (index zero) form
br.select_form(name='login_form')
  
# Let's search
br.form["email"] = "Av_DOR_ZB6@constellation.tv"
br.form["password"] = "Av_DOR_ZB6"
br.submit()
    
r = br.open('http://dev.constellation.tv/services/Screenings/get')

links = []
random.seed()
for l in br.links(url_regex='\/theater\/'):
    links.append(l.url)
rndurl = str(random.sample(links, 1)).strip('[]\'')
print 'http://dev.constellation.tv'+rndurl
r = br.open('http://dev.constellation.tv'+rndurl)
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
br.form["body"] = "This is a robot at " + datetime.now().strftime("%d/%m/%Y %H:%M")
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
br.form.action= "http://dev.constellation.tv/services/chat/post?i="+port
br.submit()
vals = json.loads(br.response().read())
print vals["body"]

