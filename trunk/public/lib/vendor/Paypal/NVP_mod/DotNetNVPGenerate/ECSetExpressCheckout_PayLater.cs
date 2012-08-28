/*
 * Copyright 2005, 2008 PayPal, Inc. All Rights Reserved.
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
	/// <summary>
	/// Summary description for ECSetExpressCheckout.
	/// </summary>
	public class ECSetExpressCheckout_PayLater
	{
		public ECSetExpressCheckout_PayLater()
		{
			//
			// TODO: Add constructor logic here
			//
		}
		public string ECSetExpressCheckout_PayLaterCode(string returnURL,string cancelURL,string amount,string paymentType,string currencyCode)
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
			profile.APIUsername = "sdk-three_api1.sdk.com";
			profile.APIPassword = "QFZCWN5HZM8VBG7Q";
			profile.APISignature = "AVGidzoSQiGWu.lGj3z15HLczXaaAcK6imHawrjefqgclVwBe8imgCHZ";
			profile.Environment="sandbox";
			caller.APIProfile = profile;

			NVPCodec encoder = new NVPCodec();
			encoder["VERSION"] =  "51.0";
			encoder["METHOD"] =  "SetExpressCheckout";
			encoder["RETURNURL"] =  returnURL;
			encoder["CANCELURL"] =  cancelURL;	
			encoder["AMT"] =  amount;
			encoder["PAYMENTACTION"] =  paymentType;
			encoder["CURRENCYCODE"] =  currencyCode;
			encoder["L_PROMOCODE0"] = "101";
			string pStrrequestforNvp= encoder.Encode();
			string pStresponsenvp=caller.Call(pStrrequestforNvp);

			NVPCodec decoder = new NVPCodec();
			decoder.Decode(pStresponsenvp);
			return decoder["ACK"];
		}
	}
}
