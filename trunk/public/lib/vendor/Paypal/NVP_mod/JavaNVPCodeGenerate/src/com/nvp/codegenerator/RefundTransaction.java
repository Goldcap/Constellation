/*
 * Copyright 2005, 2008 PayPal, Inc. All Rights Reserved.
 *
 * RefundTransaction NVP example; last modified 08MAY23. 
 *
 * Issue a refund for a prior transaction.  
 */
package com.nvp.codegenerator;

import com.paypal.sdk.core.nvp.NVPDecoder;
import com.paypal.sdk.core.nvp.NVPEncoder;
import com.paypal.sdk.profiles.APIProfile;
import com.paypal.sdk.profiles.ProfileFactory;
import com.paypal.sdk.services.NVPCallerServices;
/**
 * PayPal Java SDK sample code
 */
public class RefundTransaction 
{

	public String RefundTransactionCode(String refundType , String transactionId,String amount, String note)
	{
		NVPEncoder encoder = new NVPEncoder();
		NVPDecoder decoder = new NVPDecoder();
		NVPCallerServices caller = null;
		
	  	try
	  	{
		  	caller = new NVPCallerServices();
			APIProfile profile = ProfileFactory.createSignatureAPIProfile();
			/*
			 WARNING: Do not embed plaintext credentials in your application code.
			 Doing so is insecure and against best practices.
			 Your API credentials must be handled securely. Please consider
			 encrypting them for use in any production environment, and ensure
			 that only authorized individuals may view or modify them.
			 */

		// Set up your API credentials, PayPal end point, API operation and version.
			profile.setAPIUsername("sdk-three_api1.sdk.com");
 			profile.setAPIPassword("QFZCWN5HZM8VBG7Q");
			profile.setSignature("AVGidzoSQiGWu.lGj3z15HLczXaaAcK6imHawrjefqgclVwBe8imgCHZ");
			profile.setEnvironment("sandbox");
			profile.setSubject("");
			caller.setAPIProfile(profile);
			encoder.add("VERSION", "51.0");			
			encoder.add("METHOD","RefundTransaction");

		// Add request-specific fields to the request string.
			encoder.add("REFUNDTYPE",refundType);
			encoder.add("TRANSACTIONID",transactionId);
			if((refundType != null) && (refundType.length() > 0) && (refundType.equalsIgnoreCase("Partial")))
			{
				encoder.add("AMT",amount);					
			}
			encoder.add("NOTE",note);
						
		// Execute the API operation and obtain the response.
			String NVPRequest = encoder.encode();
			String NVPResponse = (String) caller.call(NVPRequest);
			decoder.decode(NVPResponse);
		  	}catch (Exception ex) {
		  		
		  		ex.printStackTrace();
		  	}
			return decoder.get("ACK"); 
	}

}
