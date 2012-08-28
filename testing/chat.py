from net.grinder.plugin.http import HTTPRequest, HTTPPluginControl
from net.grinder.script.Grinder import grinder
from net.grinder.script import Test
import time
import random
import uuid
import httplib
import urllib 
import urllib2


#connection options
domain =        'http://test.constellation.tv'
screening =     'Wx5rFpEf4S5KLks'
time_to_run =   60

#Getting System data to fill in certain required parts for the chat room
def getRoomDetails():
    
    html = HTTPRequest().GET( domain + "/screening/" + screening ).text
    
    cmo = str(re.search('<span class="reqs" id="mechanize_cmo">([^<].*)</span>', html).group(1))
    for index, cookie in enumerate(cj):
                if cookie.name == "constellation_frontend":
                    thacook = cookie.value    
    
    return {
        'user':        str( re.search('<span class="reqs" id="mechanize_userId">([^<].*)</span>', html).group(1) ),
        'cmo':         cmo,
        'cookie':      thacook,
        'film':        str( re.search('<span class="reqs" id="mechanize_filmId">([^<].*)</span>', html).group(1) ),
        'instance':    str( re.search('<span class="reqs" id="mechanize_instance">([^<].*)</span>', html).group(1) ),
        'ishost':      str( re.search('<span class="reqs" id="mechanize_ishost">([^<].*)</span>', html).group(1) ),
        'mdt':         str( re.search('<span class="reqs" id="mechanize_mdt">([^<].*)</span>', html).group(1) ),
        'room':        screening,
        'type':        'chat',
        'user_image':  str( re.search('<span class="reqs" id="mechanize_userImage">([^<].*)</span>', html).group(1) ),
        'p':           str( re.search('<span class="reqs" id="mechanize_port">([^<].*)</span>', html).group(1) ),
    }
        


class TestRunner:

    #decide whether this is a reader or writing thread
    def __init__(self):
    
        tid = grinder.threadNumber
      
        if tid % 4 == 2:
            self.testRunnter = self.reader;
        else:
            self.testRunnter = self.writer;
        
        self.barrier = grinder.barrier("Phase 1")    
        
        #This could possibly cause a desync on the script
        #Which is what teh Barrier is for  
        self.roomInfo = getRoomDetails()
       
            
    def __call__(self):
         
        self.testRunnter();
        
        
    def reader(self):
    
        #Syncing All Worker Threads
        self.barrier.await()
        
        time_to_stop = time.time() + time_to_run
        
        request = Test( grinder.threadNumber, "r").wrap( HTTPRequest() )
               
        while time.time() < time_to_stop:
            data=urllib.urlencode({
                'author':    self.roomInfo.user,
                'body':      self.roomInfo.user + " Grind Test " + datetime.now().strftime("%H:%M %s"),
                'cmo':       self.roomInfo.cmo,
                'cookie':    self.roomInfo.thacook,
                'film':      self.roomInfo.film,
                'instance':  self.roomInfo.instance,
                'ishost':    self.roomInfo.ishost,
                'mdt':       self.roomInfo.mdt,
                'room':      self.roomInfo.room,
                'type':      self.roomInfo.chat,
                'user_image':self.roominfo.user_image,
                'u':         self.roomInfo.user,,
                "s":         "0",
                'a':         "0",
                'c':         "6",
                't':         time.time(),
                'p':         self.roomInfo.p
                })
        
            request.POST( domain + '/services/chat/updatet', data )
        
        
    def writer(self):
    
        #Syncing All Worker Threads
        self.barrier.await()
        
        time_to_stop = time.time() + time_to_run
        
        request = Test( grinder.threadNumber, "w").wrap( HTTPRequest() )
        
        while time.time() < time_to_stop:
            data=urllib.urlencode({
                'author':    self.roomInfo.user,
                'body':      self.roomInfo.user + " Grind Test " + datetime.now().strftime("%H:%M %s"),
                'cmo':       self.roomInfo.cmo,
                'cookie':    self.roomInfo.thacook,
                'film':      self.roomInfo.film,
                'instance':  self.roomInfo.instance,
                'ishost':    self.roomInfo.ishost,
                'mdt':       self.roomInfo.mdt,
                'room':      self.roomInfo.room,
                'type':      self.roomInfo.chat,
                'user_image':self.roominfo.user_image,
                'p':         self.roomInfo.p
                })
                
            request.POST( domain + '/services/chat/post', data )
            
            #Since number of clients is the same, vary the write load in linear progression
            time.sleep( ( time_to_stop - time.time() ) / time_to_run  )
            
            
    
    
