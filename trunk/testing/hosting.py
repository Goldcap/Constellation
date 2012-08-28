#!/usr/bin/env python

import sys

import re
import mechanize
import cookielib
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
br.form["email"] = "amadsen@gmail.com"
br.form["password"] = "1hsvy5qb"
br.submit()
    
r = br.open('http://dev.constellation.tv/film/4')
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

r = br.open('http://dev.constellation.tv/film/4')
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
result = vals["hostResponse"]["status"]
screening = vals["hostResponse"]["screening"]
print result

#for f in br.forms():
#    print f
#html = r.read()
#print r.info()
#br.select_form(nr=0)
#print html
