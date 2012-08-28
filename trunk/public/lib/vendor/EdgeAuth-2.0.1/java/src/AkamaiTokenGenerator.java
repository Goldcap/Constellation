/**
 * AkamaiTokenGenerator.java - An Akamai EdgeAuth Token 2.0 generator for Java.
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

import java.security.SignatureException;
import javax.crypto.Mac;
import javax.crypto.spec.SecretKeySpec;

public class AkamaiTokenGenerator
{
	public static String generateToken(String url, String param, AkamaiTokenConfig tokenConf) throws java.security.SignatureException {
		String token = generateToken(tokenConf);
		if (url.indexOf("?") > 0) {
			return url + "&" + param + "=" + token;
		} else {
			return url + "?" + param + "=" + token;
		}
	}

	public static String generateToken(AkamaiTokenConfig tokenConf) throws java.security.SignatureException {
		String mToken = tokenConf.getIPField() + tokenConf.getStartTimeField() + 
						tokenConf.getExpirationField() + tokenConf.getAclField() +
						tokenConf.getSessionIDField() + tokenConf.getPayloadField();
		String digest = mToken + tokenConf.getUrlField() + tokenConf.getSaltField();

		// calculate the HMAC
		String hmac = calculateRFC2104HMAC(rtrim(digest, tokenConf.getFieldDelimiter()), tokenConf.getKey(), getAlgoString(tokenConf.getAlgo()));

		return mToken + "hmac=" + hmac;
	}

	/**
	*@param data
	* The data to be signed as a String
	*@param key
	* The signing key in hex
	* @return String
	* The signed hmac of the string data
	*/
	protected static String calculateRFC2104HMAC(String data, String key, String algo) throws java.security.SignatureException {
		StringBuilder result = new StringBuilder(128);
		
		try {
			SecretKeySpec signingKey = new SecretKeySpec(hexStringToByteArray(key), algo);
			 
			// Get an hmac_sha1 Mac instance and initialize with the signing key
			Mac mac = Mac.getInstance(algo);
			mac.init(signingKey);

			// Compute the hmac on input data bytes
			byte[] rawHmac = mac.doFinal(data.getBytes());
			
			// Convert raw bytes to Hex String
			for (byte b : rawHmac) {
				result.append(String.format("%02x", b));
			}
		} 
		catch (Exception e) {
			throw new SignatureException("Failed to generate HMAC : " + e.getMessage());
		}
		return result.toString();
	}

	protected static final byte[] intToByteArray(int value) {
		return new byte[] {
			(byte)(value >>> 24),
			(byte)(value >>> 16),
			(byte)(value >>> 8),
			(byte)value};
	}

	protected static byte[] hexStringToByteArray(String s) {
		int len = s.length();
		byte[] data = new byte[len / 2];
		for (int i = 0; i < len; i += 2) {
			data[i / 2] = (byte) ((Character.digit(s.charAt(i), 16) << 4)
				+ Character.digit(s.charAt(i+1), 16));
		}
		return data;
	}

	protected static String getAlgoString(Algorithm algo) {
		switch (algo) {
			case MD5: return "HmacMD5";
			case SHA1: return "HmacSHA1";
			case SHA256: return "HmacSHA256";
			default: return "";
		}
	}

	protected static String rtrim(String s, char t) {
		if (s == null) return null;
		if (s.length() < 1) return "";
		int i = s.length() - 1;
		while (i >= 0 && s.charAt(i) == t) i--;
		return s.substring(0,i+1);
	}
}
