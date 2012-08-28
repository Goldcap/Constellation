/*
 * Copyright 2005, 2008 PayPal, Inc. All Rights Reserved.*/

import com.paypal.sdk.services.NVPCallerServices;
import com.paypal.sdk.util.*;
import com.paypal.sdk.exceptions.PayPalException;
import com.paypal.sdk.profiles.APIProfile;
import com.paypal.sdk.profiles.ProfileFactory;

/**
 * PayPal Java SDK sample application
 */
public class CreateRecurringPaymentsProfile {
	NVPCallerServices caller;

    public static void main(String[] args) {
    	try {
    		CreateRecurringPaymentsProfile sample = new CreateRecurringPaymentsProfile();
    		sample.run();
		}
    	catch (Exception e) {
    		System.out.println("ERROR: " + e.getMessage());
		}
    }

    public CreateRecurringPaymentsProfile() throws PayPalException {
    	caller = new NVPCallerServices();

    	/*
    	 WARNING: Do not embed plaintext credentials in your application code.
    	 Doing so is insecure and against best practices.
    	 Your API credentials must be handled securely. Please consider
    	 encrypting them for use in any production environment, and ensure
    	 that only authorized individuals may view or modify them.
    	 */

    	APIProfile profile = ProfileFactory.createSignatureAPIProfile();
		profile.setAPIUsername("sdk-three_api1.sdk.com");
		profile.setAPIPassword("QFZCWN5HZM8VBG7Q");
		profile.setSignature("A.d9eRKfd1yVkRrtmMfCFLTqa6M9AyodL0SJkhYztxUi8W9pCXF6.4NI");
		profile.setEnvironment("sandbox");
    	caller.setAPIProfile(profile);
    }

    public void run() throws PayPalException {

		callCreateRecurringPaymentsProfileAPI();
		System.out.println("\nDone...");
    }



    public void callCreateRecurringPaymentsProfileAPI() throws PayPalException {
    	System.out.println("\n########## Starting CreateRecurringPaymentsProfile ##########\n");

        NVPEncoder encoder = new NVPEncoder();
        String token="RP-7SL9714342686113K";
        encoder.add("METHOD","CreateRecurringPaymentsProfile");
        encoder.add("TOKEN",token);
        encoder.add("AMT","25.00");
        encoder.add("PROFILESTARTDATE","2009-11-16T12:00:00M");
        encoder.add("BILLINGPERIOD","Day");
        encoder.add("BILLINGFREQUENCY","1");
        encoder.add("VERSION","51.0");

		String strNVPRequest = encoder.encode();
		//call method will send the request to the server and return the response NVPString
		String response = (String) caller.call( strNVPRequest);

		//NVPDecoder object is created
		NVPDecoder resultValues = new NVPDecoder();

        try {
			//decode method of NVPDecoder will parse the request and decode the name and value pair
			resultValues.decode(response);
	    } catch(Exception e) {

			e.printStackTrace();

		}

		//checks for Acknowledgement and redirects accordingly to display error messages
		String strAck = resultValues.get("ACK");
		if(strAck !=null && !(strAck.equals("Success") || strAck.equals("SuccessWithWarning")))	{
			// do error processing
              System.out.println("\n########## CreateRecurringPaymentsProfile call failed ##########\n");
		} else {

			//success
			 System.out.println("\n########## CreateRecurringPaymentsProfile call passed ##########\n");
		}

    }

}