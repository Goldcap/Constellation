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

from datetime import date
from datetime import datetime



class Transaction(object):
    def __init__(self):
        self.custom_timers = {}
    
    def run(self):
        
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
        domain = 'http://test.constellation.tv'
        films = [ 4, 14, 15 ]
        film = str(random.choice( films ))
        
        # User-Agent (this is cheating, ok?)
        br.addheaders = [('User-agent', 'Mechanize Bot')]

        # start homepage view
        start_timer = time.time()
        r = br.open(domain + '/')
        r.read()
        latency = time.time() - start_timer
        self.custom_timers['Homepage'] = latency   
        assert (r.code == 200), 'Bad HTTP Response'
        # end homepage
        
        # start homepage service
        start_timer = time.time()
        r = br.open(domain + '/services/Screenings/upcoming')
        r.read()
        latency = time.time() - start_timer
        self.custom_timers['Homepage Upcoming Service'] = latency 
        assert (r.code == 200), 'Bad HTTP Response'
        # end homepage service
        
        # start homepage select
        start_timer = time.time()
        today = date.today().strftime("%m/%d/%Y")
        r = br.open(domain + '/services/Screenings/date?date='+today+'&film=null')
        r.read()
        latency = time.time() - start_timer
        self.custom_timers['Homepage Upcoming Select'] = latency 
        assert (r.code == 200), 'Bad HTTP Response'
        # end homepage select
        
        # think-time
        time.sleep(2)  
        
        # start filmpage view
        start_timer = time.time()
        r = br.open(domain + '/film/' + film)
        r.read()
        latency = time.time() - start_timer
        self.custom_timers['Filmpage View'] = latency 
        assert (r.code == 200), 'Bad HTTP Response'
        # end filmpage view
        
        # start filmpage service
        start_timer = time.time()
        r = br.open(domain + '/services/Screenings/upcoming?film=' + film)
        r.read()
        latency = time.time() - start_timer
        self.custom_timers['Filmpage Upcoming Service'] = latency 
        assert (r.code == 200), 'Bad HTTP Response'
        # end filmpage service
        
        # start filmpage select
        start_timer = time.time()
        today = date.today().strftime("%m/%d/%Y")
        r = br.open(domain + '/services/Screenings/date?date='+today+'&film=' + film)
        r.read()
        latency = time.time() - start_timer
        self.custom_timers['Filmpage Upcoming Select'] = latency 
        assert (r.code == 200), 'Bad HTTP Response'
        # end filmpage select
        
        # think-time
        time.sleep(2)  


if __name__ == '__main__':
    trans = Transaction()
    trans.run()
    print trans.custom_timers
