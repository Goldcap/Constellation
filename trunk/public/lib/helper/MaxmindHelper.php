<?php
function MaxmindCheck( $city,$state,$zip,$country,$phone,$email,$card,$order,$debug=false ) {
  //Check at Maxmind
  // Create a new CreditCardFraudDetection object
  $ccfs = new CreditCardFraudDetection;
  
  // Set inputs and store them in a hash
  // See http://www.maxmind.com/app/ccv for more details on the input fields
  
  // Enter your license key here (Required)
  $h["license_key"] = sfConfig::get("app_maxmind_key");
  
  // Required fields
  $h["i"] = REMOTE_ADDR();             // set the client ip address
  
  if (left(REMOTE_ADDR(),10) == "173.236.42") {
      $h["i"] = "";
  }
  
  //$h["i"] = "74.72.53.209";
  $h["city"] = $city;             // set the billing city
  $h["region"] = $state;                 // set the billing state
  $h["postal"] = $zip;              // set the billing zip code
  $h["country"] = $country;                // set the billing country
     
  // Recommended fields
  $mailarray = explode("@",$email);
  $h["domain"] = $mailarray[1];		// Email domain
  $h["emailMD5"] = strtolower($email);
  
  $h["bin"] = left($card,6);
  
  /*
  $h["bin"] = "549099";			// bank identification number
  $h["forwardedIP"] = "24.24.24.25";	// X-Forwarded-For or Client-IP HTTP Header
  // CreditCardFraudDetection.php will take
  // MD5 hash of e-mail address passed to emailMD5 if it detects '@' in the string
  // CreditCardFraudDetection.php will take the MD5 hash of the username/password if the length of the string is not 32
  $h["usernameMD5"] = "test_carder_username"; 
  $h["passwordMD5"] = "test_carder_password"; 
  
  // Optional fields
  $h["binName"] = "MBNA America Bank";	// bank name
  $h["binPhone"] = "800-421-2110";	// bank customer service phone number on back of credit card
  */
  $h["custPhone"] = $phone;
  $h["requested_type"] = "premium";	// Which level (free, city, premium) of CCFD to use
  
  /*
  $h["custPhone"] = "212-242";		// Area-code and local prefix of customer phone number
  $h["requested_type"] = "premium";	// Which level (free, city, premium) of CCFD to use
  $h["shipAddr"] = "145-50 157TH STREET";	// Shipping Address
  $h["shipCity"] = "Jamaica";	// the City to Ship to
  $h["shipRegion"] = "NY";	// the Region to Ship to
  $h["shipPostal"] = "11434";	// the Postal Code to Ship to
  $h["shipCountry"] = "US";	// the country to Ship to
  */
  
  $h["txnID"] = $order;			// Transaction ID
  $h["sessionID"] = session_id();		// Session ID
  
  $h["accept_language"] = "de-de";
  $h["user_agent"] = $_SERVER["USER_AGENT"];
  
  // set the timeout to be five seconds
  $ccfs->timeout = 5;
  
  // uncomment to turn on debugging
  if ($debug)
  $ccfs->debug = 1;
  
  // how many seconds to cache the ip addresses
  $ccfs->wsIpaddrRefreshTimeout = 3600*5;
  
  // file to store the ip address for minfraud3.maxmind.com, minfraud1.maxmind.com and minfraud2.maxmind.com
  createDirectory(sfConfig::get("sf_data_dir")."/maxmind");
  $ccfs->wsIpaddrCacheFile = sfConfig::get("sf_data_dir")."/maxmind/maxmind.ws.cache";
  
  // if useDNS is 1 then use DNS, otherwise use ip addresses directly
  $ccfs->useDNS = 0;
  $ccfs->isSecure = 0;
  
  // next we set up the input hash
  $ccfs->input($h);
  $ccfs->query();
  $h = $ccfs->output();
  
  return $h;
  
}
?>
