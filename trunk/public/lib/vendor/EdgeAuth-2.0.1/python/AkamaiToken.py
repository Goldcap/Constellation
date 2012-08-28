#!/usr/bin/env python
#
# author: James Mutton <jmutton@akamai.com>
#
# Copyright (c) 2010, Akamai Technologies, Inc.
# All rights reserved.
#
# Redistribution and use in source and binary forms, with or without
# modification, are permitted provided that the following conditions are met:
#     * Redistributions of source code must retain the above copyright
#       notice, this list of conditions and the following disclaimer.
#     * Redistributions in binary form must reproduce the above copyright
#       notice, this list of conditions and the following disclaimer in the
#       documentation and/or other materials provided with the distribution.
#     * Neither the name of Akamai Technologies nor the
#       names of its contributors may be used to endorse or promote products
#       derived from this software without specific prior written permission.
#
# THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
# ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
# WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
# DISCLAIMED. IN NO EVENT SHALL AKAMAI TECHNOLOGIES BE LIABLE FOR ANY
# DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
# (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
# LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
# ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
# (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
# SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

"""
Implementation of Akamai's EdgeToken 2.0 Link Protection

OVERVIEW:
This library is accessible as both a class and a command line tool.

LIBRARY USAGE:
Place this file somewhere in your PYTHONPATH.  You can import the
library into your project using:

  from AkamaiToken import AkamaiToken

You first instantiate an instance of the AkamaiToken class, then assign the
properties specific to your configuration

  aka_token = AkamaiToken()
  aka_token.ip = '127.0.0.1'
  aka_token.acl = ['/*']
  aka_token.window = 300
  aka_token.start_time = time.time()		# The time from which the token will start
  aka_token.salt = 'my_secret_salt'
  aka_token.key = 'aabbccddeeff'
  token_string = aka_token.generate_token()

After supplying all of the applicable parameters, you call the generate_token()
method on the cookie object when you are ready to actually generate the token.
If a time was not specified, the expiration will be calculated from the current
system time. Whenever getSecureURL() is called and the token will be good for
"window" seconds after each call made to getSecureURL().  See the
command-line options for a description of each parameter.

COMMAND-LINE USAGE:
This library may also be used from the command line.  To do so,
either execute the file directly (on *nix-based systems) or pass
the file to the python runtime (python AkamaiToken.py).  The command-
line has several options.

python AkamaiToken.py [OPTIONS]

options are as follows:
--help, -h                     : Display the help documentation
--algo=[STRING 'SHA256']       : Token Algorithm used (sha1, sha256, or md5)
--salt=[STRING '']             : Additional data validated by the token but NOT included in the token body
--key=[STRING '']              : The key (in hex) to use for the hmac
--debug                        : Emit more verbose debugging information and possibly raise exceptions to the command line
--ip=[IP_ADDR '']              : The IP Address for which the token will be restricted to
--start_time=[INT NOW()]       : The unix timestamp when the token will become valid
--window=[INT 300]             : Number of seconds beyond the start_time that the token will be valid for
--session_id=[STRING '']       : The session identifier for single use tokens or other advanced cases
--payload=[STRING '']          : Payload data carried and validated along with the token
--acl=[STRING '']              : The path for which this token will be valid for
--field_delimiter=[STRING '~'] : Character that delimits token body fields
--acl_delimiter=[STRING '!']   : Character that delimits ACL parameters
--url=[STRING '']              : The url for which the token will be validated NOTE THAT EITHER URL OR ACL ARE REQUIRED, NOT BOTH
--param=[STRING 'token']       : The name of the query string key or cookie
--escape_early                 : Causes strings to be urlencoded befor being passed to the HMAC (Legacy behavior)
"""

import sys, os, getopt, time, urllib, hashlib, hmac, binascii, re

__AUTHOR__ = "James Mutton <jmutton@akamai.com>"

class TokenGenerationError(Exception):
	pass

class TokenValidationError(Exception):
	pass

class TokenParameterError(Exception):
	pass

url_param_ids = {'ip':'ip','st':'start_time','exp':'expiration','url':'url','acl':'acl','data':'payload','id':'session_id','hmac':'hmac'}

class AkamaiTokenConfig(object):
	def __init__(self):
		self._ip = ''
		self._start_time = None
		self.window = 300
		self._acl = ''
		self.session_id = ''
		self.data = ''
		self._url = ''
		self.salt = ''
		self.field_delimiter = '~'
		self._algo = 'sha256'
		self.tokenstr = ''
		self.param = None
		self._key = 'aabbccddeeff00112233445566778899'
		self.early_url_encoding = False
		self.hmac = ''

	
	def encode(self, value):
		if self.early_url_encoding:
			return urllib.urlencode({'x':value}).split('=')[1]
		else:
			return value
	
	def _set_ip(self, ip):
		#@TODO Validate IPV4 & IPV6 addrs
		self._ip = ip
	def _get_ip(self):
		return self._ip
	ip = property(_get_ip, _set_ip)

	def _set_start_time(self, st):
		try:
			st_int = int(st)
			if st_int < 0 or st_int > 4294967295:
				raise TypeError()
			self._start_time = st_int
		except TypeError, e:
			raise TokenParameterError("start_time invalid or out of range")
	def _get_start_time(self):
		if self._start_time is None:
			return int(time.time())
		return self._start_time
	start_time = property(_get_start_time, _set_start_time)
	
	def _set_key(self, key):
		try:
			self._key = binascii.a2b_hex(key)
		except TypeError, e:
			raise TokenParameterError("Error with key parameter: %s" % str(e))
	def _get_key(self):
		return binascii.b2a_hex(self._key)
	key = property(_get_key, _set_key)
	
	def _set_algo(self, algo):
		if algo not in ('md5', 'sha1', 'sha256'):
			raise TokenParameterError("Invalid algorithm")
		self._algo = algo
	def _get_algo(self):
		return self._algo
	algo = property(_get_algo, _set_algo)
	
	def _set_acl(self, acl):
		if self._url:
			raise TokenParameterError("Cannot set both an acl and url at the same time")
		self._acl = acl
	def _get_acl(self):
		return self._acl
	acl = property(_get_acl, _set_acl)

	def _set_url(self, url):
		if self._acl:
			raise TokenParameterError("Cannot set both an acl and url at the same time")
		self._url = url
	def _get_url(self):
		return self._url
	url = property(_get_url, _set_url)
	

	###
	# Field getters
	def get_start_time_field(self):
		return "%s=%s%s" % ('st', self.start_time, self.field_delimiter)
	
	def get_expiration_field(self):
		try:
			mytime = int(self.start_time)
		except TypeError:
			self.start_time = int(time.time())
			mytime = self.start_time
		expiration = mytime + int(self.window)
		return "%s=%s%s" % ('exp', expiration, self.field_delimiter)
	
	def get_acl_field(self):
		if self.acl:
			return "%s=%s%s" % ('acl', self.encode(self.acl), self.field_delimiter)
		elif not self.url:
			return 'acl=' + self.encode('/*') + self.field_delimiter
		return ''
	
	def get_url_field(self):
		if self.url and not self.acl:
			return "%s=%s%s" % ('url', self.encode(self.url), self.field_delimiter)
		return ''
	
	def get_ip_field(self):
		if self.ip:
			return "%s=%s%s" % ('ip', self.encode(self.ip), self.field_delimiter)
		return ''
	
	def get_session_field(self):
		if self.session_id:
			return "%s=%s%s" % ('id', self.encode(self.session_id), self.field_delimiter)
		return ''
	
	def get_data_field(self):
		if self.data:
			return "%s=%s%s" % ('data', self.encode(self.data), self.field_delimiter)
		return ''
	
	def get_salt_field(self):
		if self.salt:
			return "%s=%s%s" % ('salt', self.salt, self.field_delimiter)
		return ''

	

class AkamaiToken(object):
	def __init__(self, debug=False):
		self._debug = debug

	def generate_token(self, tc):
		'''
		Generates a token string, based on the config object properties, suitable for
		either a querystring or cookie depending on the token_type passed in.
		@param tokenConfig {AkamaiTokenConfig} the configuration to use for
		generating the token.
		@returns {String}
		'''
		# quick sanity check
		if (not tc.acl and not tc.url) or (tc.acl and tc.url):
			raise TokenParameterError("Must specify either an ACL or a URL and not both")

		# determine values for hashing
		mtoken = tc.get_ip_field() + \
			tc.get_start_time_field() + \
			tc.get_expiration_field() + \
			tc.get_acl_field() + \
			tc.get_session_field() + \
			tc.get_data_field()
		mtoken_digest = mtoken + \
			tc.get_url_field() + \
			tc.get_salt_field()

		# Produce the hmac and include in the output template
		enc_key = binascii.a2b_hex(tc.key)
		myhmac = binascii.hexlify(hmac.new(enc_key, mtoken_digest.rstrip(tc.field_delimiter), hashlib.__dict__[tc.algo]).digest())
		if self._debug:
			print "SIGNING VALUE: %s" % mtoken_digest.rstrip(tc.field_delimiter)
			print "PRODUCES: %s" % myhmac
		return "%s%s=%s" % (mtoken, 'hmac', myhmac)

def main(argv=None):
	'''
	A main function that allows us to use this class as a command line tool
	'''
	try: # parse command line options
		if argv is None:
			argv = sys.argv # etc., replacing sys.argv with arrgv in the getopt() call.
		debug = False
		escape_early = False
		valid_optargs = ('h', ['help','window=','start_time=','ip=','acl=',
				'session_id=','payload=','url=','salt=','field_delimiter=',
				'acl_delimiter=','algo=', 'debug', 'key=', 'escape_early',])
		opts, args = getopt.getopt(argv[1:], valid_optargs[0], valid_optargs[1])
		# process options
		kw = {}
		config = AkamaiTokenConfig()
		for o, a in opts:
			if o in ("-h", "--help"):
				print __doc__
				return 0
			if o in ("--debug",):
				debug = True
			if o in ("--escape_early",):
				config.early_url_encoding = True
			if o in ("--acl",):
				config.acl = a
			if o in ("--window",):
				config.window = int(a)
			if o in ("--start_time",):
				config.start_time = int(a)
			if o in ("--ip",):
				config.ip = a
			if o in ("--acl",):
				config.acl = a
			if o in ("--url",):
				config.url = a
			if o in ("--session_id",):
				config.session_id = a
			if o in ("--payload",):
				config.data = a
			if o in ("--salt",):
				config.salt = a
			if o in ("--field_delimiter",):
				config.field_delimiter = a
			if o in ("--algo",):
				config.algo = a
			if o in ("--key",):
				config.key = a
			else:
				kw[o.strip('-=')] = a
		# Generate and output signed URL
		ac = AkamaiToken(debug=debug)
		for k,v in kw.items():
			config.__dict__[k] = v
		print ac.generate_token(config)
		return 0
	except getopt.error, msg:
		print >>sys.stderr, "see --help"
		print >>sys.stderr, msg
		return 2
	except Exception, e:
		if debug:
			raise
		print >>sys.stderr, "Error: %s" % str(e)
		return 1

if __name__ == "__main__":
	sys.exit(main())
