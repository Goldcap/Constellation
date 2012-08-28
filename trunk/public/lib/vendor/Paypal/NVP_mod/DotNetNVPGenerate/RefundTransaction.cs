/*
 * Copyright 2005, 2008 PayPal, Inc. All Rights Reserved.
 *
 * RefundTransaction NVP example; last modified 08MAY23. 
 *
 * Issue a refund for a prior transaction.  
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
	public class RefundTransaction
	{
		public RefundTransaction()
		{
		}
		public string RefundTransactionCode(String refundType , String transactionId,String amount, String note, String currencyCode)
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

			NVPCodec encoder= new NVPCodec();
			encoder["VERSION"] =  "51.0";	
			encoder["METHOD"] =  "RefundTransaction";

		// Add request-specific fields to the request.
			encoder["TRANSACTIONID"] =  transactionId;	
			if(refundType!="Full")
			{
				encoder["AMT"] =  amount;
				encoder["NOTE"] =  note;
			}
			encoder["REFUNDTYPE"] =  refundType;
	
			
		// Execute the API operation and obtain the response.
			string pStrrequestforNvp= encoder.Encode();
			string pStresponsenvp=caller.Call(pStrrequestforNvp);

			NVPCodec decoder = new NVPCodec();
			decoder.Decode(pStresponsenvp);
			return decoder["ACK"];

		}
	}
}
