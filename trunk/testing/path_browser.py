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

r = br.open(domain + '/')
r = br.open(domain + '/services/Screenings/upcoming')
today = date.today().strftime("%m/%d/%Y")
r = br.open(domain + '/services/Screenings/date?date='+today+'&film=null')

films = [ 4, 14, 15 ]
film = str(random.choice( films ))
#Go to the film page
r = br.open(domain + '/film/' + film)
r = br.open(domain + '/services/Screenings/upcoming?film=' + film)
today = date.today().strftime("%m/%d/%Y")
r = br.open(domain + '/services/Screenings/date?date='+today+'&film=' + film)
