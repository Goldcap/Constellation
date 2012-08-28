#	
#.__       _____   ____    ______      ______   __  __     
#/\ \     /\  __`\/\  _`\ /\__  _\    /\__  _\ /\ \/\ \    
#\ \ \    \ \ \/\ \ \,\L\_\/_/\ \/    \/_/\ \/ \ \ `\\ \   
#.\ \ \  __\ \ \ \ \/_\__ \  \ \ \       \ \ \  \ \ , ` \  
#..\ \ \L\ \\ \ \_\ \/\ \L\ \ \ \ \       \_\ \__\ \ \`\ \ 
#...\ \____/ \ \_____\ `\____\ \ \_\      /\_____\\ \_\ \_\
#....\/___/   \/_____/\/_____/  \/_/      \/_____/ \/_/\/_/
	                                                          
	                                                          
#.______  ____    ______  ______   _____   __  __  ____    ____     ____    ______   ____    ______   
#/\  _  \/\  _`\ /\__  _\/\__  _\ /\  __`\/\ \/\ \/\  _`\ /\  _`\  /\  _`\ /\__  _\ /\  _`\ /\__  _\  
#\ \ \L\ \ \ \/\_\/_/\ \/\/_/\ \/ \ \ \/\ \ \ `\\ \ \,\L\_\ \ \/\_\\ \ \L\ \/_/\ \/ \ \ \L\ \/_/\ \/  
#.\ \  __ \ \ \/_/_ \ \ \   \ \ \  \ \ \ \ \ \ , ` \/_\__ \\ \ \/_/_\ \ ,  /  \ \ \  \ \ ,__/  \ \ \  
#..\ \ \/\ \ \ \L\ \ \ \ \   \_\ \__\ \ \_\ \ \ \`\ \/\ \L\ \ \ \L\ \\ \ \\ \  \_\ \__\ \ \/    \ \ \ 
#...\ \_\ \_\ \____/  \ \_\  /\_____\\ \_____\ \_\ \_\ `\____\ \____/ \ \_\ \_\/\_____\\ \_\     \ \_\
#....\/_/\/_/\/___/    \/_/  \/_____/ \/_____/\/_/\/_/\/_____/\/___/   \/_/\/ /\/_____/ \/_/      \/_/


#Copyright (c) 2009 Lost In Actionscript - Shane McCartney

#Permission is hereby granted, free of charge, to any person obtaining a copy
#of this software and associated documentation files (the "Software"), to deal
#in the Software without restriction, including without limitation the rights
#to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
#copies of the Software, and to permit persons to whom the Software is
#furnished to do so, subject to the following conditions:

#The above copyright notice and this permission notice shall be included in
#all copies or substantial portions of the Software.

#THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
#IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
#FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
#AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
#LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
#OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
#THE SOFTWARE.

#*/

#/*AES Counter-mode for Actionscript ported from AES Counter-mode implementation in JavaScript by Chris Veness
# *- see http://csrc.nist.gov/public statications/nistpubs/800-38a/sp800-38a.pdf
# */

import base64
import math
from datetime import datetime
from collections import defaultdict


#BEGIN CLASS AES
class AES:
  
	BIT_KEY_128 = 128
	BIT_KEY_192 = 192
	BIT_KEY_256 = 256
	
	#// Sbox is pre-computed multiplicative inverse in GF(2^8) used in subBytes and keyExpansion [5.1.1]
	SBOX = [0x63,0x7c,0x77,0x7b,0xf2,0x6b,0x6f,0xc5,0x30,0x01,0x67,0x2b,0xfe,0xd7,0xab,0x76,
		             0xca,0x82,0xc9,0x7d,0xfa,0x59,0x47,0xf0,0xad,0xd4,0xa2,0xaf,0x9c,0xa4,0x72,0xc0,
		             0xb7,0xfd,0x93,0x26,0x36,0x3f,0xf7,0xcc,0x34,0xa5,0xe5,0xf1,0x71,0xd8,0x31,0x15,
		             0x04,0xc7,0x23,0xc3,0x18,0x96,0x05,0x9a,0x07,0x12,0x80,0xe2,0xeb,0x27,0xb2,0x75,
		             0x09,0x83,0x2c,0x1a,0x1b,0x6e,0x5a,0xa0,0x52,0x3b,0xd6,0xb3,0x29,0xe3,0x2f,0x84,
		             0x53,0xd1,0x00,0xed,0x20,0xfc,0xb1,0x5b,0x6a,0xcb,0xbe,0x39,0x4a,0x4c,0x58,0xcf,
		             0xd0,0xef,0xaa,0xfb,0x43,0x4d,0x33,0x85,0x45,0xf9,0x02,0x7f,0x50,0x3c,0x9f,0xa8,
		             0x51,0xa3,0x40,0x8f,0x92,0x9d,0x38,0xf5,0xbc,0xb6,0xda,0x21,0x10,0xff,0xf3,0xd2,
		             0xcd,0x0c,0x13,0xec,0x5f,0x97,0x44,0x17,0xc4,0xa7,0x7e,0x3d,0x64,0x5d,0x19,0x73,
		             0x60,0x81,0x4f,0xdc,0x22,0x2a,0x90,0x88,0x46,0xee,0xb8,0x14,0xde,0x5e,0x0b,0xdb,
		             0xe0,0x32,0x3a,0x0a,0x49,0x06,0x24,0x5c,0xc2,0xd3,0xac,0x62,0x91,0x95,0xe4,0x79,
		             0xe7,0xc8,0x37,0x6d,0x8d,0xd5,0x4e,0xa9,0x6c,0x56,0xf4,0xea,0x65,0x7a,0xae,0x08,
		             0xba,0x78,0x25,0x2e,0x1c,0xa6,0xb4,0xc6,0xe8,0xdd,0x74,0x1f,0x4b,0xbd,0x8b,0x8a,
		             0x70,0x3e,0xb5,0x66,0x48,0x03,0xf6,0x0e,0x61,0x35,0x57,0xb9,0x86,0xc1,0x1d,0x9e,
		             0xe1,0xf8,0x98,0x11,0x69,0xd9,0x8e,0x94,0x9b,0x1e,0x87,0xe9,0xce,0x55,0x28,0xdf,
		             0x8c,0xa1,0x89,0x0d,0xbf,0xe6,0x42,0x68,0x41,0x99,0x2d,0x0f,0xb0,0x54,0xbb,0x16]

	#// Rcon is Round Constant used for the Key Expansion [1st col is 2^(r-1) in GF(2^8)] [5.2]
	RCON = [[0x00, 0x00, 0x00, 0x00],
		             [0x01, 0x00, 0x00, 0x00],
		             [0x02, 0x00, 0x00, 0x00],
		             [0x04, 0x00, 0x00, 0x00],
		             [0x08, 0x00, 0x00, 0x00],
		             [0x10, 0x00, 0x00, 0x00],
		             [0x20, 0x00, 0x00, 0x00],
		             [0x40, 0x00, 0x00, 0x00],
		             [0x80, 0x00, 0x00, 0x00],
		             [0x1b, 0x00, 0x00, 0x00],
		             [0x36, 0x00, 0x00, 0x00]]

	#/** 
	# * Encrypt a text using AES encryption in Counter mode of operation
	# *
	# * Unicode multi-byte character safe
	# *
	# * @param plaintext Source text to be encrypted
	# * @param password  The password to use to generate a key
	# * @param nBits     Number of bits to be used in the key (128, 192, or 256)
	# * @returns         Encrypted text
	# */
	def encrypt(self, plaintext, password, nBits) :
		blockSize = 16  #// block size fixed at 16 bytes / 128 bits (Nb=4) for AES

		if (nBits != self.BIT_KEY_128) and (nBits != self.BIT_KEY_192) and (nBits != self.BIT_KEY_256):
			#// standard allows 128/192/256 bit keys
			raise Exception("Must be a key mode of either 128, 192, 256 bits")
			
		nBytes = nBits/8  #// no bytes in key
		pwBytes = {}
		for i in range (0,nBytes):
			try:
				pwBytes[i] = ord(password[i:i+1]);
			except:
				pwBytes[i] = 0;
		
		key = self.cipher(pwBytes, self.keyExpansion(pwBytes))  #// gives us 16-byte key
		kbytes = len(key)
		for k in range(0,nBytes - 16):
			key[kbytes + k] = key[k] #// expand key to 16/24/32 bytes long
		
		#key.update(slice)  #// expand key to 16/24/32 bytes long
		#// initialise counter block (NIST SP800-38A B.2): millisecond time-stamp for nonce in 1st 8 bytes,
		#// block counter in 2nd 8 bytes
		counterBlock = {}
		
		dt = datetime.now()
		nonce = dt.microsecond #// timestamp: milliseconds since 1-Jan-1970
		nonceSec = nonce / 1000
		nonceMs = nonce % 1000
		#// encode nonce with seconds in 1st 4 bytes, and (repeated) ms part filling 2nd 4 bytes
		for i in range(0,4):
			counterBlock[i] = self.urs(nonceSec, i * 8) & 0xff
		for i in range(0,4):
			counterBlock[i + 4] = nonceMs & 0xff
		
		#// and convert it to a string to go on the front of the ciphertext
		ctrTxt = ''
		for i in range(0,8):
			ctrTxt += chr(counterBlock[i])
		
		#// generate key schedule - an expansion of the key into distinct Key Rounds for each round
		keySchedule = self.keyExpansion(key)
		blockCount = int(math.ceil(float(len(plaintext)) / blockSize))
		ciphertxt = {}  #// ciphertext as array of strings

		for b in range(0,blockCount):
			ciphertxt[b] = ""
			#// set counter (block #) in last 8 bytes of counter block (leaving nonce in 1st 8 bytes)
			#// done in two stages for 32-bit ops: using two words allows us to go past 2^32 blocks (68GB)
			for c in range(0,4):
				counterBlock[15 - c] = self.urs(b,c * 8) & 0xff
			for c in range(0,4):
				counterBlock[15 - c - 4] = self.urs(b / 0x100000000,c * 8)
		
			cipherCntr = self.cipher(counterBlock, keySchedule)  #// -- encrypt counter block --
		    
			#// block size is reduced on final block
			if b < blockCount - 1:
				blockLength = blockSize 
			else:
				blockLength = (len(plaintext) - 1) % blockSize + 1
			cipherChar = {}
			
			for i in range(0,blockLength):
				#// -- xor plaintext with ciphered counter char-by-char --
				cipherChar[i] = cipherCntr[i] ^ ord(plaintext[b * blockSize + i:b * blockSize + i + 1])
				cipherChar[i] = chr(cipherChar[i])
			
			for m in range(0,len(cipherChar)):
				ciphertxt[b] = ciphertxt[b] + str(cipherChar[m])
			
		#// Array.join is more efficient than repeated string concatenation in IE
		ciphertext = ctrTxt
		for m in range(0,len(ciphertxt)):
			ciphertext = ciphertext + ciphertxt[m]
		ciphertext = base64.b64encode(ciphertext)  #// encode in base64
		  
		#ciphertext = ciphertext.replace("=","-")
		#ciphertext = ciphertext.replace("/",".")
		#//alert((new Date()) - t)
		return ciphertext
		

	#/** 
	# * Decrypt a text encrypted by AES in counter mode of operation
	# *
	# * @param ciphertext Source text to be encrypted
	# * @param password   The password to use to generate a key
	# * @param nBits      Number of bits to be used in the key (128, 192, or 256)
	# * @returns          Decrypted text
	# */
	def decrypt(self, ciphertext, password, nBits):
		blockSize = 16  #// block size fixed at 16 bytes / 128 bits (Nb=4) for AES
		
		if (nBits != self.BIT_KEY_128) and (nBits != self.BIT_KEY_192) and (nBits != self.BIT_KEY_256) :
			#// standard allows 128/192/256 bit keys
			raise Exception("Must be a key mode of either 128, 192, 256 bits")
		
		#print ciphertext
		#ciphertext = ciphertext.replace("-","=")
		#ciphertext = ciphertext.replace(".","/")
		#We have to do this, as Python is url_decoding by default
		ciphertext = ciphertext.replace(" ","+")
		#print ciphertext
		#thelength = len(ciphertext)
		#print ("LENGTH::"+ str(thelength))
		ciphertext = base64.b64decode(ciphertext)
		#return ciphertext
		#password = unicode(password)
		#//t = new Date()  // timer
		  
		#// use AES to encrypt password (mirroring encrypt routine)
		nBytes = nBits / 8  #// no bytes in key
		pwBytes = {}
		for i in range(0,nBytes) :
			try:
				pwBytes[i] = ord(password[i:i+1]);
			except:
				pwBytes[i] = 0;
		
		key = self.cipher(pwBytes, self.keyExpansion(pwBytes))
		kbytes = len(key)
		for k in range(0,nBytes - 16):
			key[kbytes + k] = key[k] #// expand key to 16/24/32 bytes long
		
		#// recover nonce from 1st 8 bytes of ciphertext
		counterBlock = {}
		
		ctrTxt = ciphertext[0:8]
		for i in range(0,8):
			try:
				counterBlock[i] = ord(ctrTxt[i:i+1])
			except:
				print("skipped")
				#counterBlock[i] = 0
		
		#// generate key schedule
		keySchedule = self.keyExpansion(key)
		
		#// separate ciphertext into blocks (skipping past initial 8 bytes)
		#$nBlocks = ceil((strlen($ciphertext)-8) / $blockSize);
		nBlocks = int(math.ceil(float((len(ciphertext) - 8)) / blockSize))
		ct = {}
		
		for b in range(0,nBlocks):
			ct[b] = ciphertext[8 + b * blockSize:8 + b * blockSize + blockSize];
		ciphertextArr = ct  #// ciphertext is now array of block-length strings
		
		#// plaintext will get generated block-by-block into array of block-length strings
		plaintxt = {}
		
		for b in range(0,nBlocks) :
			plaintxt[b] = ""
			#// set counter (block #) in last 8 bytes of counter block (leaving nonce in 1st 8 bytes)
			for c in range(0,4):
				counterBlock[15 - c] = self.urs(b,c*8) & 0xff
			for c in range(0,4):
				counterBlock[15 - c - 4] = self.urs(float((b+1))/0x100000000-1,c*8) & 0xff
			
			cipherCntr = self.cipher(counterBlock, keySchedule)  #// encrypt counter block
			plaintxtByte = {}
			for i in range(0,len(ciphertextArr[b])) :
				#// -- xor plaintxt with ciphered counter byte-by-byte --
				plaintxtByte[i] = cipherCntr[i] ^ ord(ciphertextArr[b][i:i+1])
				plaintxtByte[i] = chr(plaintxtByte[i])
			
			for m in range(0,len(plaintxtByte)):
					plaintxt[b] = plaintxt[b] + str(plaintxtByte[m])
			
		#// join array of blocks into single plaintext string
		plaintext = ""
		for m in range(0,len(plaintxt)):
			plaintext = plaintext + plaintxt[m]
		
		#plaintext = base64.b64decode(plaintext)  #// decode from UTF8 back to Unicode multi-byte chars

		return plaintext

	def cipher(self, input, w):
		
		def nested_dict_factory(): 
			return defaultdict(list)
      
		#// main cipher function [5.1]
		Nb = 4               #// block size (in words): no of columns in state (fixed at 4 for AES)
		Nr = len(w) / Nb - 1 #// no of rounds: 10/12/14 for 128/192/256-bit keys

		#state = [[],[],[],[]]  #// initialise 4xNb byte-array 'state' with input [3.4]
		state = defaultdict(nested_dict_factory)
		for i in range(0,4 * Nb):
			state[i % 4][int(math.floor(i / 4))] = input[i]
		
		state = self.addRoundKey(state, w, 0, Nb)
		
		for round in range(1,Nr):
			state = self.subBytes(state, Nb)
			state = self.shiftRows(state, Nb)
			state = self.mixColumns(state)
			state = self.addRoundKey(state, w, round, Nb)
		
		state = self.subBytes(state, Nb)
		state = self.shiftRows(state, Nb)
		state = self.addRoundKey(state, w, Nr, Nb)
		
		output = {}  #// convert state to 1-d array before returning [3.4]
		for i in range(0,4 * Nb):
			output[i] = state[i % 4][int(math.floor(i / 4))]
		
		return output

	def keyExpansion(self, key): 
    
		def nested_dict_factory(): 
			return defaultdict(list)
      
		#// generate Key Schedule (byte-array Nr+1 x Nb) from Key [5.2]
		Nb = 4            #// block size (in words): no of columns in state (fixed at 4 for AES)
		Nk = len(key) / 4  #// key length (in words): 4/6/8 for 128/192/256-bit keys
		Nr = Nk + 6       #// no of rounds: 10/12/14 for 128/192/256-bit keys

		w = defaultdict(nested_dict_factory)
		temp = {}
		tmp = {}
		
		for i in range(0,Nk):
			r = [key[4 * i], key[4 * i + 1], key[4 * i + 2], key[4 * i + 3]]
			w[i] = r
		
		for i in range (Nk,(Nb * (Nr + 1))):
			for t in range(0,4):
				temp[t] = w[i - 1][t]
			if (i % Nk == 0) :
				temp = self.subWord(self.rotWord(temp))
				for t in range(0,4):
					temp[t] ^= self.RCON[i / Nk][t]
			elif (Nk > 6) and (i % Nk == 4):
				temp = self.subWord(temp)
			
			for t in range(0,4) :
				tmp[t] = w[i - Nk][t] ^ temp[t]
			w[i] = [tmp[0],tmp[1],tmp[2],tmp[3]]
			
		return w

	def subBytes(self, s, Nb) :
		#// apply SBox to state S [5.1.1]
		for r in range(0,4) :
			for c in range(0,Nb):
				s[r][c] = self.SBOX[s[r][c]]
		
		return s

	def shiftRows(self, s, Nb) :
		#// shift row r of state S left by r bytes [5.1.2]
		t = {}
		for r in range(1,4):
			for c in range(0,4):
				t[c] = s[r][(c + r) % Nb]  #// shift into temp copy
			for c in range(0,4):
				s[r][c] = t[c]         #// and copy back
			#// note that this will work for Nb=4,5,6, but not 7,8 (always 4 for AES):

		return s  
		#// see asmaes.sourceforge.net/rijndael/rijndaelImplementation.pdf

	def mixColumns(self, s) : 
		#// combine bytes of each col of state S [5.1.3]
		for c in range(0,4) :
			a = {}  #// 'a' is a copy of the current column from 's'
			b = {}  #// 'b' is a {02} in GF(2^8)
			for i in range(0,4) :
				a[i] = s[i][c]
				if (s[i][c] & 0x80):
					b[i] =  s[i][c] << 1 ^ 0x011b 
				else: 
					b[i] =  s[i][c] << 1
			
			#// a[n] ^ b[n] is a{03} in GF(2^8)
			s[0][c] = b[0] ^ a[1] ^ b[1] ^ a[2] ^ a[3] #// 2*a0 + 3*a1 + a2 + a3
			s[1][c] = a[0] ^ b[1] ^ a[2] ^ b[2] ^ a[3] #// a0 * 2*a1 + 3*a2 + a3
			s[2][c] = a[0] ^ a[1] ^ b[2] ^ a[3] ^ b[3] #// a0 + a1 + 2*a2 + 3*a3
			s[3][c] = a[0] ^ b[0] ^ a[1] ^ a[2] ^ b[3] #// 3*a0 + a1 + a2 + 2*a3
		
		return s

	def addRoundKey(self, state, w, rnd, Nb) :  
		#// xor Round Key into state S [5.1.4]
		for r in range(0,4) :
			for c in range(0,Nb):
				state[r][c] ^= w[rnd * 4 + c][r]
		
		return state

	def subWord(self, w) :
		#// apply SBox to 4-byte word w
		for i in range(0,4):
			w[i] = self.SBOX[w[i]]
		
		return w

	def rotWord(self,w) :    
		#// rotate 4-byte word w left by one byte
		tmp = w[0]
		for i in range(0,3):
			w[i] = w[i + 1]
		w[3] = tmp
		
		return w
	
	#http://www.frankdu.com/notes/2011/03/11/implement-unsigned-right-shift-if-the-operator-is-unsupported/
	#Thanks Frank!
	def urs(self, a, b):
		z = int("0x80000000",0)
		if (b < 0) or (a == 0):
			return 0
		if (b == 0):
			return int(a)
		if a < 0:
			a = int(a) >> 1 & 0x7fffffff
			a = (a >> (b - 1))
		else :
			a = a >> b
		return int(a) 

#END CLASS AES
