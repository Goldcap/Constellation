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
import MySQLdb

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
        
        # User-Agent (this is cheating, ok?)
        br.addheaders = [('User-agent', 'Mechanize Bot')]
        
        conn = MySQLdb.connect (host = "10.111.65.40", user = "root", passwd = "constellation2010", db = "constellation_test")
        cursor = conn.cursor ()  
        
        sql = 'select fk_user_id, fk_film_id, fk_screening_unique_key, chat_instance.chat_instance_key, chat_instance_host, 9090 + chat_instance_port from audience inner join chat_instance on chat_instance.fk_screening_key = fk_screening_unique_key inner join screening on audience.fk_screening_id = screening.screening_id where audience_paid_status = 2 order by rand() limit 1;'
        cursor.execute (sql)
        row = cursor.fetchone ()  
        
        r = br.open(domain + '/chat.html')
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
        br.form["author"] = str(row[0])
        br.form["film"] = str(row[1])
        br.form["room"] = str(row[2])
        br.form["instance"] = str(row[3])
        port = str(row[4]) + ":" + str(row[5])
        
        start_timer = time.time()
        br.form["body"] = "Robot Guest " + datetime.now().strftime("%d/%m/%Y %H:%M")
        br.form.action= domain + "/services/chat/post?i="+port
        br.submit()
        latency = time.time() - start_timer
        self.custom_timers['Chat Submission Guest'] = latency 
        assert (r.code == 200), 'Bad HTTP Response'
        # think-time
        time.sleep(random.randint(2,6))


if __name__ == '__main__':
    trans = Transaction()
    trans.run()
    print trans.custom_timers
