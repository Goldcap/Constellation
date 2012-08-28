/*
 * Copyright 2005, 2008 PayPal, Inc. All Rights Reserved.
 *
 * DoDirectPayment NVP example; last modified 08MAY23. 
 *
 * Process a credit card payment.  
 */
using System;
using com.paypal.sdk.services;
using com.paypal.sdk.profiles;
using com.paypal.sdk.util;
/**
 * PayPal .NET SDK sample code
 */
namespace GenerateCodeNVP
{
	public class DoDirectPayment
	{
		public DoDirectPayment()
		{
		}
		public string DoDirectPaymentCode(string paymentAction,string amount,string creditCardType,string creditCardNumber,string expdate_month,string cvv2Number,string firstName,string lastName,string address1,string city,string state,string zip,string countryCode,string currencyCode)
		{
			NVPCallerServices caller = new NVPCallerServices();
			IAPIProfile profile = ProfileFactory.createSignatureAPIProfile();
			/*
			 WARNING: Do not embed plaintext credentials in your application code.
			 Doing so is insecure and against best practices.
			 Your API credentials must be handled securely. Please consider
			 encrypting them for use in any production environment, and ensure
			 that only authorized individuals may view or modify them.
			 */

            // Set up your API credentials, PayPal end point, API operation and version.
			profile.APIUsername = "sdk-three_api1.sdk.com";
			profile.APIPassword = "QFZCWN5HZM8VBG7Q";
			profile.APISignature = "AVGidzoSQiGWu.lGj3z15HLczXaaAcK6imHawrjefqgclVwBe8imgCHZ";
			profile.Environment="sandbox";
			caller.APIProfile = profile;

			NVPCodec encoder = new NVPCodec();
			encoder["VERSION"] =  "51.0";	
			encoder["METHOD"] =  "DoDirectPayment";

            // Add request-specific fields to the request.
			encoder["PAYMENTACTION"] =  paymentAction;
			encoder["AMT"] =  amount;
			encoder["CREDITCARDTYPE"] =  creditCardType;		
			encoder["ACCT"] =  creditCardNumber;						
			encoder["EXPDATE"] =  expdate_month;
			encoder["CVV2"] =  cvv2Number;
			encoder["FIRSTNAME"] =  firstName;
			encoder["LASTNAME"] =  lastName;										
			encoder["STREET"] =  address1;
			encoder["CITY"] =  city;	
			encoder["STATE"] =  state;			
			encoder["ZIP"] =  zip;	
			encoder["COUNTRYCODE"] = countryCode;
			encoder["CURRENCYCODE"] =  currencyCode;	

            // Execute the API operation and obtain the response.
			string pStrrequestforNvp= encoder.Encode();
			string pStresponsenvp=caller.Call(pStrrequestforNvp);

			NVPCodec decoder = new NVPCodec();
			decoder.Decode(pStresponsenvp);
			return decoder["ACK"];

		}
	}
}
