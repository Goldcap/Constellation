from net.grinder.plugin.http import HTTPRequest, HTTPPluginControl
from net.grinder.script.Grinder import grinder
from net.grinder.script import Test

from HTTPClient import Cookie, CookieModule, CookiePolicyHandler
import time
import random
import httplib
import urllib 
import urllib2


#connection options
domain = 	 	 	 	'http://test.constellation.tv'
screening = 	 	 'Wx5rFpEf4S5KLks'
time_to_run = 	 60
films =  	 	[ 87 ]
film = 	 	 	str(random.choice( films ))

# Set up a cookie handler to log all cookies that are sent and received.
class MyCookiePolicyHandler(CookiePolicyHandler):
	def acceptCookie(self, cookie, request, response):
		return 1
 
	def sendCookie(self, cookie, request):
		return 1
 
CookieModule.setCookiePolicyHandler(MyCookiePolicyHandler())


class TestRunner:

	#decide whether this is a reader or writing thread
	def __init__(self):
		
		tid = grinder.threadNumber
		
		#self.testRunnter = self.writer
			 	
		if tid % 4 == 2:
			self.testRunnter = self.reader
		else:
			self.testRunnter = self.writer
			 	 	
		#self.barrier = grinder.barrier("Phase 1")
        
			 	 	 	 	
	def __call__(self):
		
		self.request = HTTPRequest()
		self.createUser( '123456' )
		self.buyTicket( '123456' ) 
		self.roomInfo = getRoomDetails( self.request )
				 	 	 
		self.testRunnter()
 	 	 	 	
 	 	 	 	
	def reader(self):
	
		#Syncing All Worker Threads
		#self.barrier.await()
		
		time_to_stop = time.time() + time_to_run
		
		self.request = Test( grinder.threadNumber, "r").wrap( self.request )
		 	 	 	 
		while time.time() < time_to_stop:
	 	 	data=urllib.urlencode({
 	 	 	 	'author': 	 	self.roomInfo.user,
 	 	 	 	'body': 	 	 	self.roomInfo.user + " Grind Test " + datetime.now().strftime("%H:%M %s"),
 	 	 	 	'cmo': 	 	 	 self.roomInfo.cmo,
 	 	 	 	'cookie': 	 	self.roomInfo.thacook,
 	 	 	 	'film': 	 	 	self.roomInfo.film,
 	 	 	 	'instance': 	self.roomInfo.instance,
 	 	 	 	'ishost': 	 	self.roomInfo.ishost,
 	 	 	 	'mdt': 	 	 	 self.roomInfo.mdt,
 	 	 	 	'room': 	 	 	self.roomInfo.room,
 	 	 	 	'type': 	 	 	self.roomInfo.chat,
 	 	 	 	'user_image':self.roominfo.user_image,
 	 	 	 	'u': 	 	 	 	 self.roomInfo.user,
 	 	 	 	"s": 	 	 	 	 "0",
 	 	 	 	'a': 	 	 	 	 "0",
 	 	 	 	'c': 	 	 	 	 "6",
 	 	 	 	't': 	 	 	 	 time.time(),
 	 	 	 	'p': 	 	 	 	 self.roomInfo.p
            })
		
			self.request.POST( domain + '/services/chat/update', data )
 	 	 	 	
 	 	 	 	
	def writer(self):
		 	
		#Syncing All Worker Threads
		#self.barrier.await()
		
		time_to_stop = time.time() + time_to_run
		
		self.request = Test( grinder.threadNumber, "w").wrap( self.request )
		
		while time.time() < time_to_stop:
			data=urllib.urlencode({
		 	 	'author': 	 	self.roomInfo.user,
		 	 	'body': 	 	 	self.roomInfo.user + " Grind Test " + datetime.now().strftime("%H:%M %s"),
		 	 	'cmo': 	 	 	 self.roomInfo.cmo,
		 	 	'cookie': 	 	self.roomInfo.thacook,
		 	 	'film': 	 	 	self.roomInfo.film,
		 	 	'instance': 	self.roomInfo.instance,
		 	 	'ishost': 	 	self.roomInfo.ishost,
		 	 	'mdt': 	 	 	 self.roomInfo.mdt,
		 	 	'room': 	 	 	self.roomInfo.room,
		 	 	'type': 	 	 	self.roomInfo.chat,
		 	 	'user_image':self.roominfo.user_image,
		 	 	'p': 	 	 	 	 self.roomInfo.p
	 	 	})
	 	 	
		self.request.POST( domain + '/services/chat/post', data )
		
		#Since number of clients is the same, vary the write load in linear progression
		time.sleep( ( time_to_stop - time.time() ) / time_to_run 	)
	 	 	 	 	 	
 	 	 	 	 	 	
 	def createUser( self, pwd ):
 	
 	 	pwd = '123456'
 	
 	 	for i in range(10):
 	 	 	numba = random.randint(48,122)
 	 	 	if ((numba != 96) and (numba != 94) and (numba != 92) and (numba != 69) and (numba != 60) and (numba != 62) and (numba != 58) and (numba != 59)):
 	 	 	 	pwd += chr(numba)
 	 	 	 	
 	 	post_body=urllib.urlencode({'username': "User " + str(random.randint(0,255)),'email': pwd+"@constellation.tv",'password':pwd,'password2':pwd})
 	 	self.request.POST( domain + "/services/Join", post_body)
 	 	
 	def buyTicket( self, pwd ):
 	 	
 	 	post_body=urllib.urlencode({
 	 	 	'b_address1':'1 main street',
 	 	 	'b_address2':'apt 3',
 	 	 	'b_city':'San Jose',
 	 	 	'b_country':'US',
 	 	 	'b_state':'CA',
 	 	 	'b_zipcode':'95131',
 	 	 	'card_verification_number':'962',
 	 	 	'confirm_email': pwd+'@constellation.tv',
 	 	 	'credit_card_number':'4286546374372331',
 	 	 	'dohbr':'false',
 	 	 	'email': pwd+'@constellation.tv',
 	 	 	'expiration_date_month':"3",
 	 	 	'expiration_date_year':"2012",
 	 	 	'first_name': 'Guest',
 	 	 	'invite_count':0,
 	 	 	'last_name': 'Last',
 	 	 	'promo_code': 0,
 	 	 	'ticket_code': 'false',
 	 	 	'ticket_price':'0.00',
 	 	 	'username': 'User ' + pwd 	 	 	 	 	 	 	 	 	 	 	 	
 	 	 	})
 	 	 	
 	 	self.request.POST( domain + '/screening/'+film+'/purchase/'+screening, post_body)
 	
 	
 	#Getting System data to fill in certain required parts for the chat room
 	def getRoomDetails( self ):
 	 	 	
 	 	 	html = self.request.GET( domain + "/theater/" + screening ).text
 	 	 	
 	 	 	cmo = str(re.search('<span class="reqs" id="mechanize_cmo">([^<].*)</span>', html).group(1))
 	 	 	for index, cookie in enumerate(cj):
 	 	 	 	 	 	 	 	 	if cookie.name == "constellation_frontend":
 	 	 	 	 	 	 	 	 	 	 	thacook = cookie.value 	 	
 	 	 	
 	 	 	return {
 	 	 	 	 	'user': 	 	 	 	str( re.search('<span class="reqs" id="mechanize_userId">([^<].*)</span>', html).group(1) ),
 	 	 	 	 	'cmo': 	 	 	 	 cmo,
 	 	 	 	 	'cookie': 	 	 	thacook,
 	 	 	 	 	'film': 	 	 	 	str( re.search('<span class="reqs" id="mechanize_filmId">([^<].*)</span>', html).group(1) ),
 	 	 	 	 	'instance': 	 	str( re.search('<span class="reqs" id="mechanize_instance">([^<].*)</span>', html).group(1) ),
 	 	 	 	 	'ishost': 	 	 	str( re.search('<span class="reqs" id="mechanize_ishost">([^<].*)</span>', html).group(1) ),
 	 	 	 	 	'mdt': 	 	 	 	 str( re.search('<span class="reqs" id="mechanize_mdt">([^<].*)</span>', html).group(1) ),
 	 	 	 	 	'room': 	 	 	 	screening,
 	 	 	 	 	'type': 	 	 	 	'chat',
 	 	 	 	 	'user_image': 	str( re.search('<span class="reqs" id="mechanize_userImage">([^<].*)</span>', html).group(1) ),
 	 	 	 	 	'p': 	 	 	 	 	 str( re.search('<span class="reqs" id="mechanize_port">([^<].*)</span>', html).group(1) ),
 	 	 	} 	 	
 	 	 	
