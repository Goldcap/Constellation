/**
 * AkamaiTokenConfig.java - The token generation configuration.
 *
 * author: James Mutton <jmutton@akamai.com>
 *
 * Copyright (c) 2011, Akamai Technologies, Inc.
 * All rights reserved.
 * 
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *     * Neither the name of Akamai Technologies nor the
 *       names of its contributors may be used to endorse or promote products
 *       derived from this software without specific prior written permission.
 * 
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL AKAMAI TECHNOLOGIES BE LIABLE FOR ANY
 * DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 * ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * AkamaiToken
 * Notes:
 */
package com.akamai.edgeauth;

import java.net.URLEncoder;
import java.util.regex.*;
import sun.net.util.IPAddressUtil;

public class AkamaiTokenConfig
{
	private Algorithm algo = Algorithm.SHA256;
	private String ip = "";
	private long startTime = 0;
	private long window = 300;
	private String acl = "/*";
	private boolean isUrl = false;
	private String sessionID = "";
	private String payload = "";
	private String salt = "";
	// Customize this to match your edgeauth configuration
	private String key = "aabbccddeeff00112233445566778899";
	private char fieldDelimiter = '~';
	private boolean earlyURLEncoding = false;

	public AkamaiTokenConfig() {
	}

	public void setAlgo(Algorithm algo) {this.algo = algo;}
	public Algorithm getAlgo() {return this.algo;}

	public void setIP(String ip) throws Exception {
		if(IPAddressUtil.isIPv4LiteralAddress(ip) || IPAddressUtil.isIPv6LiteralAddress(ip)) {
			this.ip = ip;
		} else {
			throw new Exception("invalid IP");
		}
	}
	public String getIP() {return this.ip;}
	public String getIPField() {
		if (this.ip == "") {
			return "";
		} else {
			return "ip=" + this.ip + this.fieldDelimiter;
		}
	}

	public void setStartTime(long startTime) throws Exception {
		// for sanity, limit to 32bit unsigned
		if ( startTime <= 4294967295L ) {
			this.startTime = startTime;
		} else {
			throw new Exception("Please use a sane start-time value in unix-timestamp format");
		}
	}
	public long getStartTime() {
		if (this.startTime == 0) {
			return (System.currentTimeMillis() / 1000L);
		} else {
			return this.startTime;
		}
	}
	public String getStartTimeField() {
		return "st=" + this.getStartTime() + this.fieldDelimiter;
	}

	public void setWindow(long window) throws Exception {
		// again, inject some sanity into the world.
		if ((window + this.getStartTime()) < 4294967295L) {
			this.window = window;
		} else {
			throw new Exception("Please use a sane window that doesn't overflow unix timestamp");
		}
	}
	public long getWindow() {return this.window;}
	public String getExpirationField() {
		Long ret = new Long(this.getStartTime()+this.window);
		return "exp=" + ret.toString() + this.fieldDelimiter;
	}

	public void setAcl(String acl) {this.acl = acl;}
	public String getAcl() {return this.acl;}
	public String getAclField() {
		if (this.isUrl) {
			return "";
		} else {
			// escape the string and catch characters that OTHER encoders also replace
			//String escapedACL = URLEncoder.encode(this.acl).toLowerCase().replace(",","%2c").replace("*","%2a");
			String escapedACL = this.conditionallyEscapeString(this.acl);
			return "acl=" + escapedACL + this.fieldDelimiter;
		}
	}

	public void setIsUrl(boolean isUrl) {this.isUrl = isUrl;}
	public boolean getIsUrl() {return this.isUrl;}
	public String getUrlField() {
		if (this.isUrl) {
			String escapedURL = this.conditionallyEscapeString(this.acl);
			return "url=" + escapedURL + this.fieldDelimiter;
			//return "url=" + this.acl + this.fieldDelimiter;
		} else {
			return "";
		}
	}

	public void setSessionID(String sessionID) {this.sessionID = sessionID;}
	public String getSessionID() {return this.sessionID;}
	public String getSessionIDField() {
		if (this.sessionID != "") {
			return "id=" + this.sessionID + this.fieldDelimiter;
		} else {
			return "";
		}
	}

	public void setPayload(String payload) {this.payload = payload;}
	public String getPayload() {return this.payload;}
	public String getPayloadField() {
		if (this.payload != "") {
			return "data=" + this.payload + this.fieldDelimiter;
		} else {
			return "";
		}
	}

	public void setSalt(String salt) {this.salt = salt;}
	public String getSalt() {return this.salt;}
	public String getSaltField() {
		if (this.salt != "") {
			return "salt=" + this.salt + this.fieldDelimiter;
		} else {
			return "";
		}
	}

	public void setKey(String key) throws Exception {
		if ( (key.length() % 2) != 0 || !key.matches("\\p{XDigit}*") ) {
		//if ( !key.matches("\\p{XDigit}*") ) {
			throw new Exception("Key must be a hexidecimal string of only 0-9, a-f and in pairs");
		}
		this.key = key;
	}
	public String getKey() {return this.key;}

	public void setFieldDelimiter(char fieldDelimiter) {this.fieldDelimiter = fieldDelimiter;}
	public char getFieldDelimiter() {return this.fieldDelimiter;}

	public void setEarlyURLEncoding(boolean earlyURLEncoding) {this.earlyURLEncoding = earlyURLEncoding;}
	public boolean getEarlyURLEncoding() {return this.earlyURLEncoding;}

	private String conditionallyEscapeString(String toEscape) {
		if (this.earlyURLEncoding) {
			// Escape it in such a way that it matches other implementations and generated tokens
			StringBuilder escapedACL = new StringBuilder(URLEncoder.encode(toEscape).replace(",","%2c").replace("*","%2a"));
			String regexStr = "%[0-9A-Fa-f][0-9A-Fa-f]";
			Pattern p = Pattern.compile(regexStr);
			Matcher m = p.matcher(escapedACL);
			while (m.find()) {
				String buf = escapedACL.substring(m.start(), m.end()).toLowerCase();
				escapedACL.replace(m.start(), m.end(), buf);
			}
			//fix casing
			return escapedACL.toString();
		} else {
			return toEscape;
		}
	}

}
