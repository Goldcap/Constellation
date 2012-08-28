/*
 * Copyright 2005, 2008 PayPal, Inc. All Rights Reserved.
 *
 * TransactionSearch NVP example; last modified 08MAY23. 
 *
 * Search your account history for transactions that meet the criteria you specify.  
 */
package com.nvp.codegenerator;

import java.text.DateFormat;
import java.text.SimpleDateFormat;
import java.util.Calendar;

import com.paypal.sdk.core.nvp.NVPDecoder;
import com.paypal.sdk.core.nvp.NVPEncoder;
import com.paypal.sdk.profiles.APIProfile;
import com.paypal.sdk.profiles.ProfileFactory;
import com.paypal.sdk.services.NVPCallerServices;
/**
 * PayPal Java SDK sample code
 */
public class TransactionSearch 
{

	public String TransactionSearchCode(String startDate, String endDate, String transactionID)
	{
		NVPCallerServices caller = null;
		NVPEncoder encoder = new NVPEncoder();
		NVPDecoder decoder = new NVPDecoder();
		
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
		encoder.add("METHOD","TransactionSearch");

		// Add request-specific fields to the request string.
			encoder.add("TRXTYPE","Q"); 
			DateFormat dfRead = new SimpleDateFormat("MM/dd/yyyy");
			if(startDate !=null && !startDate.equals(""))
			{
				Calendar startDateObj = Calendar.getInstance();
				startDateObj.setTime(dfRead.parse(startDate));
				encoder.add("STARTDATE",startDateObj.get(Calendar.YEAR)+"-"+(startDateObj.get(Calendar.MONTH)+1)+"-"+startDateObj.get(Calendar.DAY_OF_MONTH)+"T00:00:00Z");
			}
			
			if(endDate !=null && !endDate.equals(""))
			{
				Calendar endDateObj = Calendar.getInstance();
				endDateObj.setTime(dfRead.parse(endDate));
				encoder.add("ENDDATE",endDateObj.get(Calendar.YEAR)+"-"+(endDateObj.get(Calendar.MONTH)+1)+"-"+endDateObj.get(Calendar.DAY_OF_MONTH)+"T24:00:00Z");
			}			
			// Date format from server is 2006-9-6T0:0:0.
			encoder.add("TRANSACTIONID",transactionID);
			
		// Execute the API operation and obtain the response.
			String NVPRequest = encoder.encode();
			String NVPResponse = caller.call(NVPRequest);
			decoder.decode(NVPResponse);
			
		}catch (Exception ex)
		{
			ex.printStackTrace();
		}
		return decoder.get("ACK");
	}
}
