/**
 * AkamaiEdgeAuthCLI.java - An Akamai EdgeAuth Token 2.0 command-line client for Java
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
 */
package com.akamai.edgeauth;

import java.security.SignatureException;

public class AkamaiEdgeAuthCLI {
	protected static AkamaiTokenConfig GenerateConfig(String[] args) throws Exception {
		// Do several checks and throw an exception to bail if we can't continure
		if (args.length == 0) {
			throw new Exception("Error: Must specify either an ACL or a URL and not both");
		}
		AkamaiTokenConfig config = new AkamaiTokenConfig();
		String arg = "";
		for(int i = 0;i <args.length; i++) {
			arg = args[i];
			if ("-h".equals(arg) || "--help".equals(arg)) {
				ShowHelp();
				System.exit(0);
			} else if ("-e".equals(arg) || "--escape_early".equals(arg)) {
				config.setEarlyURLEncoding(true);
			} else if (arg.startsWith("--algo=")) {
				String value = arg.split("=")[1];
				if (value.equalsIgnoreCase("SHA256")) config.setAlgo(Algorithm.SHA256);
				else if (value.equalsIgnoreCase("MD5")) config.setAlgo(Algorithm.MD5);
				else if (value.equalsIgnoreCase("SHA1")) config.setAlgo(Algorithm.SHA1);
				else { throw new Exception("Invalid Algorithm");}
			} else if (arg.startsWith("--salt=")) {
				String value = arg.split("=")[1];
				config.setSalt(value);
			} else if (arg.startsWith("--key=")) {
				String value = arg.split("=")[1];
				config.setKey(value);
			} else if (arg.startsWith("--ip=")) {
				String value = arg.split("=")[1];
				config.setIP(value);
			} else if (arg.startsWith("--start_time=")) {
				String value = arg.split("=")[1];
				config.setStartTime(Long.parseLong(value));
			} else if (arg.startsWith("--window=")) {
				String value = arg.split("=")[1];
				config.setWindow(Long.parseLong(value));
			} else if (arg.startsWith("--session_id=")) {
				String value = arg.split("=")[1];
				config.setSessionID(value);
			} else if (arg.startsWith("--payload=")) {
				String value = arg.split("=")[1];
				config.setPayload(value);
			} else if (arg.startsWith("--acl=")) {
				String value = arg.split("=")[1];
				config.setAcl(value);
			} else if (arg.startsWith("--field_delimiter=")) {
				String value = arg.split("=")[1];
				config.setFieldDelimiter(value.toCharArray()[0]);
			} else if (arg.startsWith("--url=")) {
				String value = arg.split("=")[1];
				config.setAcl(value);
				config.setIsUrl(true);
			} else {
				throw new Exception("Error: Unknown argument '"+arg+"'");
			}
		}
		return config;
	}

	protected static void ShowHelp() {
        System.out.println("Implementation of Akamai's EdgeToken 2.0 Link Protection");
        System.out.println("usage: java -jar AkamaiToken.jar [options]");
		System.out.println("options are as follows:");
		System.out.println("--help, -h                     : Display the help documentation");
		System.out.println("--algo=[STRING 'SHA256']       : Token Algorithm used (sha1, sha256, or md5)");
		System.out.println("--salt=[STRING '']             : Additional data validated by the token but NOT included in the token body");
		System.out.println("--key=[STRING '']              : The key (in hex) to use for the hmac");
		System.out.println("--debug                        : Emit more verbose debugging information and possibly raise exceptions to the command line");
		System.out.println("--ip=[IP_ADDR '']              : The IP Address for which the token will be restricted to");
		System.out.println("--start_time=[INT NOW()]       : The unix timestamp when the token will become valid");
		System.out.println("--window=[INT 300]             : Number of seconds beyond the start_time that the token will be valid for");
		System.out.println("--session_id=[STRING '']       : The session identifier for single use tokens or other advanced cases");
		System.out.println("--payload=[STRING '']          : Payload data carried and validated along with the token");
		System.out.println("--acl=[STRING '']              : The path for which this token will be valid for");
		System.out.println("--field_delimiter=[STRING '~'] : Character that delimits token body fields");
		System.out.println("--acl_delimiter=[STRING '!']   : Character that delimits ACL parameters");
		System.out.println("--url=[STRING '']              : The url for which the token will be validated NOTE THAT EITHER URL OR ACL ARE REQUIRED, NOT BOTH");
		System.out.println("--param=[STRING 'token']       : The name of the query string key or cookie");
		System.out.println("--escape_early                 : Performs legacy hehavior wheere values are escaped before being passed to the hmac");
	}

	public static void main(String[] args) {
		try {
			AkamaiTokenConfig conf = GenerateConfig(args);
			System.out.println(AkamaiTokenGenerator.generateToken(conf));
		} catch(SignatureException e) {
			System.out.println(e.toString());
		} catch(Exception e) {
			System.out.println(e.toString());
		}
	}
}
