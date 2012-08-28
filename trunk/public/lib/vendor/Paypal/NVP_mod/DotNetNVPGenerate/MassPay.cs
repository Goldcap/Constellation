/*
 * Copyright 2005, 2008 PayPal, Inc. All Rights Reserved.
 *
 * MassPay NVP example; last modified 08MAY23. 
 *
 * Pay one or more recipients.  
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
	public class MassPay
	{
		public MassPay()
		{
		}
		public string MassPayCode(string emailSubject,string receiverType,string currencyCode,string ReceiverEmail0,string amount0,string UniqueID0,string Note0,string ReceiverEmail1,string amount1,string UniqueID1,string Note1)
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
			encoder["METHOD"] =  "MassPay";

            // Add request-specific fields to the request.
            encoder["EMAILSUBJECT"] =  emailSubject;
			encoder["RECEIVERTYPE"] =  receiverType;
			encoder["CURRENCYCODE"]=currencyCode;
			
			if(ReceiverEmail0.Length>0)
			{
				encoder["L_EMAIL0"] =  ReceiverEmail0;
				encoder["L_Amt0"] =  amount0;
				encoder["L_UNIQUEID0"] =  UniqueID0;
				encoder["L_NOTE0"] =  Note0;
			}

			if(ReceiverEmail1.Length>0)
			{
				encoder["L_EMAIL1"] =  ReceiverEmail1;
				encoder["L_Amt1"] =  amount1;
				encoder["L_UNIQUEID1"] =  UniqueID1;
				encoder["L_NOTE1"] =  Note1;
			}



            // Execute the API operation and obtain the response.
			string pStrrequestforNvp= encoder.Encode();
			string pStresponsenvp=caller.Call(pStrrequestforNvp);

			NVPCodec decoder = new NVPCodec();
			decoder.Decode(pStresponsenvp);
			return decoder["ACK"];

		}

	}
}
