#!/usr/bin/env python

import sys

import re
import mechanize
import cookielib
import random
import json
import uuid

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
br.addheaders = [('User-agent', 'Mechanize Bot')]

#Go to the film page
r = br.open('http://dev.constellation.tv/')
html = r.read()

# Select the first (index zero) form
br.select_form(name='sign-up_form')

print br.form  
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

r = br.open('http://dev.constellation.tv/account')
