import urllib2

class MyHandler(urllib2.HTTPHandler):
		def http_response(self, req, response):
			try:
				return response
			except IOError, e:
				return None
			else:
				return None