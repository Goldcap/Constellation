#!/usr/bin/env python

import sys

import re
import mechanize
import cookielib
import random
import json

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
br.form["email"] = "amadsen@gmail.com"
br.form["password"] = "1hsvy5qb"
br.submit()
    
r = br.open('http://dev.constellation.tv/account')

br.select_form(name='Account')
print br.form
