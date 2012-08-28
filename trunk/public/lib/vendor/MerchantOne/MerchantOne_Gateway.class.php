<?php

class MerchantOne_Gateway {
  
  //Controller Settings
  var $action;
  var $submit_url;
  var $MONE_url;
  
  //MOne Environment
  var $MOne_environment;
  var $MOne_user;
  var $MOne_password;
  
  //Order Details
  var $amount;
  var $order_num;
  var $desc;
  var $card_num;
  var $card;
  var $cvv2;        // 123
  var $expiry; // We only use a 2-digit year.  Need this due to bug in PHP on the date function.
  var $card_start;
  var $card_issue;
  
  // Billing Details
  var $fname;//
  var $lname;
  var $email;
  var $addr1;//Street
  var $addr2;//City
  var $addr3;//State
  var $addr4;//ZIP
  var $country;        // 3-digits ISO code
  // Other information
  var $custom1;
  var $custom2;
  
  //Transaction Data
  var $MOne_string;
  var $MOne_query;
  var $unique_id;
  var $trans_id;
  var $auth_code;
  
  //Transaction Response
  var $nvpArray;
  var $error;
  var $result_code;
  var $response_message;
  
  //MOne Gateway Results
  //MOne_Result codes are:
  //  3 :: Captured
  //  2 :: All Good
  //  1 :: Fraud Review
  //  0 :: Unused
  //  -1 :: Declined
  //  -2 :: User Data Error
  //  -3 :: Fraud Error
  //  -4 :: Voice Auth Suggested
  //  -5 :: Fraud Service Issue, Voice Auth Suggested (These are approved)
  //  -6 :: Fraud Service Issue, Voice Auth Suggested (These are approved)
  //  -7 :: Cancelled
  //  -8 :: Unknown Declined
  //  -10 :: Network Error
  //  -20 :: Dismissed
  //  -21 :: Gateway Authentication Error
  
  var $MOne_Result_Code;
  //Boolean for the following
  var $MOne_Result_AVS_Object;
  var $MOne_Result_AVS;
  
  //Report Data
  var $MOne_Report_Result;
  var $MOne_Report_Items;
  
  function __construct() {
    
    list($this -> MOne_environment,$this -> MOne_user,$this -> MOne_password) = sfConfig::get("app_mone");
    
    //Only One URL for all requests
    if ($this -> MOne_environment=='live') {
        $this -> submit_url = 'https://secure.merchantonegateway.com/api/transact.php';
    } else {
        $this -> submit_url = 'https://secure.merchantonegateway.com/api/transact.php';
    }
    
    $this -> MOne_string = 'username='.$this -> MOne_user.'&password='.$this -> MOne_password;
    
    $this -> MOne_Result_AVS["ADDR"] = false;
    $this -> MOne_Result_AVS["ZIP"] = false;
    $this -> MOne_Result_AVS["CVV2"] = false;
    
    /*Test Data
    For an APPROVAL
    $this -> amount = "2.50";
    $this -> order_num = "123456ABC";
    $this -> desc = "Test Data For a Test Transaction";
    $this -> card_num = "5105105105105100";
    $this -> card = "";
    $this -> cvv2 = "123";        // 123
    $this -> expiry = "1211"; // We only use a 2-digit year.  Need this due to bug in PHP on the date function.
    $this -> card_start = "";
    $this -> card_issue = "";
    $this -> fname = "Bug";//
    $this -> lname = "Face";
    $this -> email = "amadsen@gmail.om";
    $this -> addr1 = "305 McGuinness Boulevard Apt 3K";//Street
    $this -> addr2 = "Brooklyn";//City
    $this -> addr3 = "NY";//State
    $this -> addr4 = "11222";//ZIP
    $this -> country = "US";// 3-digits ISO code
    $this -> custom = "Some Info About Something";
    
    */
    
  }
  
  function commit() {
      switch ($this -> action) {
        case 'DoDirectPayment':
          $this -> DoDirectPayment();
          break;
        case 'AuthPayment':
          $this -> AuthPayment();
          break;
        case 'RefundTransaction':
          $this -> refundTransaction();
          break;
        case 'CreditTransaction':
          $this -> creditTransaction();
          break;
        case 'CaptureTransaction':
          $this -> captureTransaction();
          break;
        case 'VoiceAuthTransaction':
          $this -> voiceAuthTransaction();
          break;
        case 'VoidTransaction':
          $this -> voidTransaction();
          break;
        case 'ReadTransaction':
          $this -> inquiryTransaction();
          break;
      }
  }

  function DoDirectPayment() {
    
    //Test Data
    //$this -> card_num = "4111111111111111";
    //$this -> expiry = "1010";
    
    //MOre Test Data
    //$this -> addr1 = "888 Main Street";
    //$this -> addr4 = "77777";
    //$this -> cvv2 = "999";
    
    $this -> MOne_query="";
    //Note that in order to stay consistent with the PFPro API, Shipping here is added to the "amount"
    //and is therefore "0" as a lineitem
    $MOne_query_array = array(

        'username'    => $this -> MOne_user,
        'password'    => $this -> MOne_password,
        'ccnumber'    => $this -> card_num,
        'cvv'         => $this -> cvv2,
        'ccexp'       => $this -> expiry,
        'amount'      => $this -> amount,
        'shipping'    => 0,
        'tax'         => 0,
        'firstname'   => $this -> fname,
        'lastname'    => $this -> lname,
        'address1'    => $this -> addr1,
        'city'        => $this -> addr2,
        'state'       => $this -> addr3,
        'zip'         => $this -> addr4,
        'country'     => $this -> country,
        'email'       => $this -> email,
        'ipaddress'   => $this -> cust_ip,
        'orderid'     => $this -> order_num,
        'orderdescription'  => $this -> custom1 . " | " . $this -> custom2,
       	'type'       => "sale"
				);
				
    // Mike Challis (www.carmosaic.com) added feature: bracketed numbers.
    // Bracketed numbers are length tags which allow you
    // to use the special characters of "&" and "=" in the value sent.

    foreach ($MOne_query_array as $key => $value) {
				$this -> MOne_query[]= $key.'='.$value;
		}
		$this -> MOne_query=implode('&', $this -> MOne_query);
    
    // The $order_num field is storing our unique id that we'll use in the request id header.  By storing the id
    // in this manner, we are able to allowing reposting of the form without creating a duplicate transaction.
    $this -> unique_id = $this -> order_num . "_" . time();
    
    // Call the function to send data to PayPal and return the data into an Array.
    $this -> fetch_data();

    // Check for results and display approval or decline.
    $this -> response_handler();

  }
  
  function AuthPayment() {
    
    //Test Data
    //$this -> card_num = "4111111111111111";
    //$this -> expiry = "1010";
    
    //MOre Test Data
    //$this -> addr1 = "888 Main Street";
    //$this -> addr4 = "77777";
    //$this -> cvv2 = "999";
    
    $this -> MOne_query="";
    //Note that in order to stay consistent with the PFPro API, Shipping here is added to the "amount"
    //and is therefore "0" as a lineitem
    $MOne_query_array = array(

        'username'    => $this -> MOne_user,
        'password'    => $this -> MOne_password,
        'ccnumber'    => $this -> card_num,
        'cvv'         => $this -> cvv2,
        'ccexp'       => $this -> expiry,
        'amount'      => $this -> amount,
        'shipping'    => 0,
        'tax'         => 0,
        'firstname'   => $this -> fname,
        'lastname'    => $this -> lname,
        'address1'    => $this -> addr1,
        'city'        => $this -> addr2,
        'state'       => $this -> addr3,
        'zip'         => $this -> addr4,
        'country'     => $this -> country,
        'email'       => $this -> email,
        'ipaddress'   => $this -> cust_ip,
        'orderid'     => $this -> order_num,
        'orderdescription'  => $this -> custom1 . " | " . $this -> custom2,
       	'type'       => "auth"
				);
				
    // Mike Challis (www.carmosaic.com) added feature: bracketed numbers.
    // Bracketed numbers are length tags which allow you
    // to use the special characters of "&" and "=" in the value sent.

    foreach ($MOne_query_array as $key => $value) {
        //$this -> MOne_query[]= $key.'['.strlen($value).']='.$value;
				$this -> MOne_query[]= $key.'='.$value;
		}
		$this -> MOne_query=implode('&', $this -> MOne_query);
    
    // The $order_num field is storing our unique id that we'll use in the request id header.  By storing the id
    // in this manner, we are able to allowing reposting of the form without creating a duplicate transaction.
    $this -> unique_id = $this -> order_num . "_" . time();
    
    // Call the function to send data to PayPal and return the data into an Array.
    $this -> fetch_data();

    // Check for results and display approval or decline.
    $this -> response_handler();

  }
  
  function voidTransaction() {
    $this -> paypal_query="";
    $paypal_query_array = array(

        'username'        => $this -> MOne_user,
        'password'        => $this -> MOne_password,
        'transactionid'   =>  $this -> trans_id,
        'type'            => "void"
				);
				
    // Mike Challis (www.carmosaic.com) added feature: bracketed numbers.
    // Bracketed numbers are length tags which allow you
    // to use the special characters of "&" and "=" in the value sent.

    foreach ($paypal_query_array as $key => $value) {
				$this -> MOne_query[]= $key.'='.$value;
		}
		$this -> MOne_query=implode('&', $this -> MOne_query);

    // Call the function to send data to PayPal and return the data into an Array.
    $this -> fetch_data();

  }
  
  /*
  // Login Information
		$query .= "username=" . urlencode($this->login['username']) . "&";
		$query .= "password=" . urlencode($this->login['password']) . "&";
		// Transaction Information
		$query .= "transactionid=" . urlencode($transactionid) . "&";
		if ($amount>0) {
			$query .= "amount=" . urlencode(number_format($amount,2,".","")) . "&";
		}
		$query .= "type=refund";
	*/
  function refundTransaction() {
    $this -> MOne_query="";
    $MOne_query_array = array(

        'username'       => $this -> MOne_user,
        'password'        => $this -> MOne_password,
        'transactionid'   =>  $this -> trans_id,
        'type'            => 'refund' 
        
				);
				
    // Mike Challis (www.carmosaic.com) added feature: bracketed numbers.
    // Bracketed numbers are length tags which allow you
    // to use the special characters of "&" and "=" in the value sent.

    foreach ($MOne_query_array as $key => $value) {
				$this -> MOne_query[]= $key.'='.$value;
		}
		$this -> MOne_query=implode('&', $this -> MOne_query);

    // Call the function to send data to PayPal and return the data into an Array.
    $this -> fetch_data();

  }
  
  /*
  $query  = "";
		// Login Information
		$query .= "username=" . urlencode($this->login['username']) . "&";
		$query .= "password=" . urlencode($this->login['password']) . "&";
		// Sales Information
		$query .= "ccnumber=" . urlencode($ccnumber) . "&";
		$query .= "ccexp=" . urlencode($ccexp) . "&";
		$query .= "amount=" . urlencode(number_format($amount,2,".","")) . "&";
		// Order Information
		$query .= "ipaddress=" . urlencode($this->order['ipaddress']) . "&";
		$query .= "orderid=" . urlencode($this->order['orderid']) . "&";
		$query .= "orderdescription=" . urlencode($this->order['orderdescription']) . "&";
		$query .= "tax=" . urlencode(number_format($this->order['tax'],2,".","")) . "&";
		$query .= "shipping=" . urlencode(number_format($this->order['shipping'],2,".","")) . "&";
		$query .= "ponumber=" . urlencode($this->order['ponumber']) . "&";
		// Billing Information
		$query .= "firstname=" . urlencode($this->billing['firstname']) . "&";
		$query .= "lastname=" . urlencode($this->billing['lastname']) . "&";
		$query .= "company=" . urlencode($this->billing['company']) . "&";
		$query .= "address1=" . urlencode($this->billing['address1']) . "&";
		$query .= "address2=" . urlencode($this->billing['address2']) . "&";
		$query .= "city=" . urlencode($this->billing['city']) . "&";
		$query .= "state=" . urlencode($this->billing['state']) . "&";
		$query .= "zip=" . urlencode($this->billing['zip']) . "&";
		$query .= "country=" . urlencode($this->billing['country']) . "&";
		$query .= "phone=" . urlencode($this->billing['phone']) . "&";
		$query .= "fax=" . urlencode($this->billing['fax']) . "&";
		$query .= "email=" . urlencode($this->billing['email']) . "&";
		$query .= "website=" . urlencode($this->billing['website']) . "&";
		$query .= "type=credit";
		*/
		
  function creditTransaction() {
    $this -> MOne_query="";
    $MOne_query_array = array(

        'username'      => $this -> MOne_user,
        'password'      => $this -> MOne_password,
        'ccnumber'      => $this -> trans_id[0],
        'ccexp'         => sprintf("%04d",$this -> trans_id[1]),
        'amount'        => sprintf("%.02f",$this -> trans_id[2]),
        'type'          => 'credit'
        
				);
    // Mike Challis (www.carmosaic.com) added feature: bracketed numbers.
    // Bracketed numbers are length tags which allow you
    // to use the special characters of "&" and "=" in the value sent.
    
    foreach ($MOne_query_array as $key => $value) {
				$this -> MOne_query[]= $key.'='.$value;
		}
		$this -> MOne_query=implode('&', $this -> MOne_query);

    // Call the function to send data to PayPal and return the data into an Array.
    $this -> fetch_data();

  }
  
  function captureTransaction() {
    $this -> MOne_query="";
    $MOne_query_array = array(

        'username'       => $this -> MOne_user,
        'password'        => $this -> MOne_password,
        'transactionid'   =>  $this -> trans_id,
        'type'            => "capture"
        
				);
				
    // Mike Challis (www.carmosaic.com) added feature: bracketed numbers.
    // Bracketed numbers are length tags which allow you
    // to use the special characters of "&" and "=" in the value sent.

    foreach ($MOne_query_array as $key => $value) {
				$this -> MOne_query[]= $key.'='.$value;
		}
		$this -> MOne_query=implode('&', $this -> MOne_query);

    // Call the function to send data to PayPal and return the data into an Array.
    $this -> fetch_data();

  }
  
  function inquiryTransaction() {
    //Queries come from a different URL, so set that here:
    $this -> submit_url = "https://secure.merchantonegateway.com/api/query.php";
    
    $this -> MOne_query="";
    $MOne_query_array = array(

        'username'       => $this -> MOne_user,
        'password'        => $this -> MOne_password,
        'transaction_id'   =>  $this -> trans_id
        
				);
				
    // Mike Challis (www.carmosaic.com) added feature: bracketed numbers.
    // Bracketed numbers are length tags which allow you
    // to use the special characters of "&" and "=" in the value sent.

    foreach ($MOne_query_array as $key => $value) {
				$this -> MOne_query[]= $key.'='.$value;
		}
		$this -> MOne_query=implode('&', $this -> MOne_query);

    // Call the function to send data to PayPal and return the data into an Array.
    $this -> fetch_data();

  }
  
  function searchTransaction( $order ) {
    //Queries come from a different URL, so set that here:
    $this -> submit_url = "https://secure.merchantonegateway.com/api/query.php";
    
    $this -> MOne_query="";
    $MOne_query_array = array(

        'username'       => $this -> MOne_user,
        'password'        => $this -> MOne_password,
        'order_id'   =>  $order
        
				);
				
    // Mike Challis (www.carmosaic.com) added feature: bracketed numbers.
    // Bracketed numbers are length tags which allow you
    // to use the special characters of "&" and "=" in the value sent.

    foreach ($MOne_query_array as $key => $value) {
				$this -> MOne_query[]= $key.'='.$value;
		}
		$this -> MOne_query=implode('&', $this -> MOne_query);

    // Call the function to send data to PayPal and return the data into an Array.
    $this -> fetch_xml_data();

  }
  
  // API functions and error handling
  function fetch_data() {
  
      // Optional Headers.  If used adjust as necessary.
      //$headers[] = "X-VPS-VIT-OS-Name: Linux";                    // Name of your OS
      //$headers[] = "X-VPS-VIT-OS-Version: RHEL 4";                // OS Version
      //$headers[] = "X-VPS-VIT-Client-Type: PHP/cURL";             // What you are using
      //$headers[] = "X-VPS-VIT-Client-Version: 0.01";              // For your info
      //$headers[] = "X-VPS-VIT-Client-Architecture: x86";          // For your info
      //$headers[] = "X-VPS-VIT-Integration-Product: PHPv4::cURL";  // For your info, would populate with application name
      //$headers[] = "X-VPS-VIT-Integration-Version: 0.01";         // Application version
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $this -> submit_url);
  		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
  		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
  		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  		curl_setopt($ch, CURLOPT_HEADER, 0);
  		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
  
  		curl_setopt($ch, CURLOPT_POSTFIELDS, $this -> MOne_query);
  		curl_setopt($ch, CURLOPT_POST, 1);
	
      $i=1;
      while ($i++ <= 3) {
          $result = curl_exec($ch);
          $headers = curl_getinfo($ch);
          //print_r($headers);
          //echo '<br>';
          //print_r($result);
          //echo '<br>';
          if ($headers['http_code'] != 200) {
            QAMail("Temporary network timeout with Merchant One");
            sleep(2);  // Let's wait 5 seconds to see if its a temporary network issue.
          }
          else if ($headers['http_code'] == 200) {
              // we got a good response, drop out of loop.
              break;
          }
      }
      
      
      // In this example I am looking for a 200 response from the server prior to continuing with
      // processing the order.  You can use this or other methods to validate a response from the
      // server and/or timeout issues due to network.
      if ($headers['http_code'] != 200) {
          QAMail("Terminal network timeout with Merchant One");
          $this -> MOne_Result_Code = -10;
          $this -> error = $headers['http_code'];
          curl_close($ch);
          return false;
      }
      
      curl_close($ch);
      
      //echo $result;
      // prepare responses into array
      $this -> nvpArray = array();
      while(strlen($result)){
          // name
          $keypos= strpos($result,'=');
          $keyval = substr($result,0,$keypos);
          // value
          $valuepos = strpos($result,'&') ? strpos($result,'&'): strlen($result);
          $valval = substr($result,$keypos+1,$valuepos-$keypos-1);
          // decoding the respose
          $this -> nvpArray[$keyval] = $valval;
          $result = substr($result,$valuepos+1,strlen($result));
      
      }

      //dump($this -> nvpArray); 
      if ($this -> nvpArray['response_code'] == 300) {
        $oid = $this -> checkPriorOrder();
        if ($oid) {
          header ("Location: /download/receipt/".$oid);
          die();
        }
      }
      /*
      if ($this -> order_num) {
        //QAMail("Order: ".$this -> order_num." -- Response: ".$this -> nvpArray['response_code']);
      } elseif ($this -> trans_id) {
        //QAMail("Transaction: ".$this -> trans_id." -- Response: ".$this -> nvpArray['response_code']);
      } else {
        //QAMail("Response: ".$this -> nvpArray['response_code']);
      }
      */
      return true;
  }
  
  // API functions and error handling
  function fetch_xml_data() {
  
      // Optional Headers.  If used adjust as necessary.
      //$headers[] = "X-VPS-VIT-OS-Name: Linux";                    // Name of your OS
      //$headers[] = "X-VPS-VIT-OS-Version: RHEL 4";                // OS Version
      //$headers[] = "X-VPS-VIT-Client-Type: PHP/cURL";             // What you are using
      //$headers[] = "X-VPS-VIT-Client-Version: 0.01";              // For your info
      //$headers[] = "X-VPS-VIT-Client-Architecture: x86";          // For your info
      //$headers[] = "X-VPS-VIT-Integration-Product: PHPv4::cURL";  // For your info, would populate with application name
      //$headers[] = "X-VPS-VIT-Integration-Version: 0.01";         // Application version
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $this -> submit_url);
  		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
  		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
  		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  		curl_setopt($ch, CURLOPT_HEADER, 0);
  		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
  
  		curl_setopt($ch, CURLOPT_POSTFIELDS, $this -> MOne_query);
  		curl_setopt($ch, CURLOPT_POST, 1);
	
      $i=1;
      while ($i++ <= 3) {
          $result = curl_exec($ch);
          $headers = curl_getinfo($ch);
          //print_r($headers);
          //echo '<br>';
          //print_r($result);
          //echo '<br>';
          if ($headers['http_code'] != 200) {
            QAMail("Temporary network timeout with Merchant One");
            sleep(2);  // Let's wait 5 seconds to see if its a temporary network issue.
          }
          else if ($headers['http_code'] == 200) {
              // we got a good response, drop out of loop.
              break;
          }
      }
      
      
      // In this example I am looking for a 200 response from the server prior to continuing with
      // processing the order.  You can use this or other methods to validate a response from the
      // server and/or timeout issues due to network.
      if ($headers['http_code'] != 200) {
          QAMail("Terminal network timeout with Merchant One");
          $this -> MOne_Result_Code = -10;
          $this -> error = $headers['http_code'];
          curl_close($ch);
          return false;
      }
      
      curl_close($ch);
      
      //echo $result;
      // prepare responses into array
      $this -> nvpArray = array();
      $XML = new XML();
      $XML -> loadXML($result);
      foreach ($XML -> query("//transaction") as $anode ) {
        foreach ($anode -> childNodes as $node) {
          $this -> nvpArray[$node -> nodeName] = $XML -> getNodeValue ($node);
        }
      }
      
      /*
      if ($this -> order_num) {
        //QAMail("Order: ".$this -> order_num." -- Response: ".$this -> nvpArray['response_code']);
      } elseif ($this -> trans_id) {
        //QAMail("Transaction: ".$this -> trans_id." -- Response: ".$this -> nvpArray['response_code']);
      } else {
        //QAMail("Response: ".$this -> nvpArray['response_code']);
      }
      */
      return true;
  }
  
  function response_handler() {
      
      $this -> result_code = $this -> nvpArray['response_code']; // get the result code to validate.
      
      //Since there is no actual "Fraud" response
      //We'll deduce one from the "result_code"
      $this ->nvpArray["POSTFPSMSG"] = "Null";
      
      switch (TRUE) {
        
        //For Cart Items
        //Over 1000, or AVS Mismatch, or Billing<>Shipping, RESULT 0
        //Velocity is fraud trigger
        
        //Invalid Login Credentials
        case (($this -> result_code >= 400 && $this -> result_code <= 499)) :
           $this -> MOne_Result_Code = -21;
          break;
        //Approval
        case ($this -> result_code == 100) :
            $this -> MOne_Result_Code = 2;
            //Check AVSADDR results
            if (isset($this -> nvpArray['avsresponse'])) {
                //Address is all OK
                if (in_array($this -> nvpArray['avsresponse'],array("X","Y","A","D","M"))) {
                  $this -> MOne_Result_AVS_Object["ADDR"] = true;
                  $this -> MOne_Result_AVS = "ADDR:Y,";
                //AVS is unavailable or AVS NA
                } elseif (in_array($this -> nvpArray['avsresponse'],array("U","G","I","R","E","S","0","O","B"))) {
                  $this -> MOne_Result_AVS_Object["ADDR"] = false;
                  $this -> MOne_Result_AVS = "ADDR:N,";
                //No Address Match
                } else {
                  $this -> MOne_Result_Code = 2;
                  $this -> MOne_Result_AVS_Object["ADDR"] = false;
                  $this -> MOne_Result_AVS = "ADDR:N,";
                }
            }
            //Check AVSZIP results
            if (isset($this -> nvpArray['avsresponse'])) {
                //ZIP is all OK
                if (in_array($this -> nvpArray['avsresponse'],array("X","Y","D","M","W","Z","P","L"))) {
                   $this -> MOne_Result_AVS_Object["ZIP"] = true;
                   $this -> MOne_Result_AVS .= "ZIP:Y,";
                //AVS is unavailable or AVS NA
                } elseif (in_array($this -> nvpArray['avsresponse'],array("U","G","I","R","E","S","0","O","B"))) {
                  $this -> MOne_Result_AVS_Object["ZIP"] = false;
                  $this -> MOne_Result_AVS = "ZIP:N,";
                //No Address Match
                } else {
                  $this -> MOne_Result_Code = 2;
                  $this -> MOne_Result_AVS_Object["ZIP"] = false;
                  $this -> MOne_Result_AVS .= "ZIP:N,";
                }
            }
            //Check CVV2 results
            if (isset($this -> nvpArray['cvvresponse'])) {
                if (in_array($this -> nvpArray['cvvresponse'],array("M"))) {
                   $this -> MOne_Result_AVS_Object["CVV2"] = true;
                   $this -> MOne_Result_AVS .= "CVV2:Y";
                } elseif (in_array($this -> nvpArray['cvvresponse'],array("P","S","U"))) {
                   $this -> MOne_Result_AVS_Object["CVV2"] = false;
                   $this -> MOne_Result_AVS .= "CVV2:Y";
                } else {
                  $this -> MOne_Result_Code = 2;
                  $this -> MOne_Result_AVS_Object["CVV2"] = false;
                  $this -> MOne_Result_AVS .= "CVV2:N";
                }
            }
          break;
        // Hard decline from bank.
        case (($this -> result_code == 201) || ($this -> result_code == 202) || ($this -> result_code == 203) || ($this -> result_code == 204)):
            $this -> MOne_Result_Code = -1;
          break;
        // Voice authorization required.
        case ($this -> result_code == 240) :
            $this -> MOne_Result_Code = 0;
            $subject="Voice Authorization Required";
            $message = "This order requires a 'Voice Authorization'";
            QAMail($message,$subject,false,"tiffany@tattoojohnny.com");
          break;
        // Issue with credit card number or expiration date.
        case ($this -> result_code >= 220 && $this -> result_code <= 224):
            $this -> MOne_Result_Code = -1;
          break;
        // 125, 126 and 127 are Fraud Responses.
        // Refer to the Payflow Pro Fraud Protection Services User's Guide or
        // Website Payments Pro Payflow Edition - Fraud Protection Services User's Guide.
        case ($this -> result_code == 250):
            $this -> MOne_Result_Code = -1;
            $this ->nvpArray["POSTFPSMSG"] = "Pick Up Card";
            $this -> error = "Fraud Protection was triggered";
          break;
        // 126 = One of more filters were triggered.  Here you would check the fraud message returned if you
        // want to validate data.  For example, you might have 3 filters set, but you'll allow 2 out of the
        // 3 to consider this a valid transaction.  You would then send the request to the server to modify the
        // status of the transaction.  This outside the scope of this sample.  Refer to the Fraud Developer's Guide.
        case ($this -> result_code == 251):
            $this -> MOne_Result_Code = 1;
            $this ->nvpArray["POSTFPSMSG"] = "Lost Card";
          break;
        // 252 = Lost or Stolen  Card
        case ($this -> result_code == 252):
            $this -> MOne_Result_Code = 1;
            $this ->nvpArray["POSTFPSMSG"] = "Stolen Card";
          break;
        case ($this -> result_code == 253):
            $this -> MOne_Result_Code = 1;
            $this ->nvpArray["POSTFPSMSG"] = "Fraudulent Card";
          break;
        case ($this -> result_code >= 260 && $this -> result_code <= 264):
            $this ->nvpArray["POSTFPSMSG"] = "Declined with Errors, contact Administrator";
            $this -> MOne_Result_Code = -3;
          break;
        default:
          $this -> MOne_Result_Code = -8;
          $this ->nvpArray["POSTFPSMSG"] = $this -> nvpArray["responsetext"];
          $this -> error = "Unused Code:: ". $this -> result_code;
          break;
      }
      
  }
  
  function getResponseVal( $val ) {
    
    //dump($this -> nvpArray);
    switch (TRUE) {
    
        //Invalid Login Credentials
        case (($val >= 400 && $val <= 499)) :
           $result_code = -21;
          break;
        //Approval
        case ($val == 100) :
            $result_code = 2;
          break;
        // Hard decline from bank.
        case (($val == 201) || ($val == 202) || ($val == 203) || ($val == 204)):
            $result_code = -1;
          break;
        // Voice authorization required.
        case ($val == 240) :
            $result_code = 0;
          break;
        // Issue with credit card number or expiration date.
        case ($val >= 220 && $val <= 224):
            $result_code = -2;
          break;
        case ($val == 250):
            $result_code = -1;
          break;
        case ($val == 251):
            $result_code = 1;
          break;
        // 252 = Lost or Stolen  Card
        case ($val == 252):
            $result_code = 1;
          break;
        case ($val == 253):
            $result_code = 1;
          break;
        case ($val >= 260 && $val <= 264):
            $result_code = -3;
          break;
        default:
          $result_code = -8;
          break;
      }
      
      return $result_code;
  }
  
  function generateCharacter () {
      $possible = "1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
      $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
      return $char;
  }
  
  function getDailyReport( $date=false, $pages=30 ) {
    
    if (! $date)
      $date = now();
    
    if ($this -> MOne_environment=='live') {
        $this -> submit_url = 'https://secure.merchantonegateway.com/api/query.php';
    } else {  
        $this -> submit_url = 'https://secure.merchantonegateway.com/api/query.php';
    }
    
    $this -> MOne_query="";
    
    //Merchant One's Reporting Gateway frankly is confusing
    //So grab all the data from 5 hours prior and after
    //And parse the Date from the Order Number
    $MOne_query_array = array(

        'username'      => $this -> MOne_user,
        'password'      => $this -> MOne_password,
        'start_date'    =>  formatDate($date,"MDY-")." 00:00:00",
        'end_date'    =>  formatDate(dateadd($date,1,"d"),"MDY-")." 08:59:59",
        'condition'     =>  "pending,failed,pendingsettlement,canceled,complete,unknown",
        'action_type'   =>  "sale,refund,credit,auth"
        
				);
				
				
    $curl = new Curl();
    $curl -> headers["Accept-Encoding"] = "*";
    $curl -> headers["TE"] = "deflate;q=0";
    
    $response = $curl -> post($this -> submit_url, $MOne_query_array);
    
    $result = new XML();
    $result -> loadXML($response->body);
    $this -> MOne_Report_Result = $result;
    //$result -> saveXML();
    //$this -> MOne_Report_Result -> saveXML();
    //$xsl = new XSL();
    
    //$xsl_location = sfConfig::get("sf_lib_dir")."/vendor/styroform/1.2/xsl/index.xsl";
    //return array("form"=>$xsl -> convertDoc($xsl_location,$compiled_results->documentElement,"true"));
      
  }
  
  /*
  ["user_order_date"]=>
    string(19) "2010-08-10 10:41:54"
    ["user_order_id"]=>
    string(17) "7G454423FN494622K"
    ["user_order_user_fname"]=>
    string(13) "matthew ohrel"
    ["user_order_user_lname"]=>
    string(19) "MROHREL@COMCAST.NET"
    ["user_order_guid"]=>
    string(17) "7G454423FN494622K"
    ["user_order_product"]=>
    string(4) "null"
    ["user_order_total_fs"]=>
    string(5) "15.68"
    ["user_order_status"]=>
    int(2)
    ["user_order_process"]=>
    int(2)
    ["user_order_download"]=>
    int(1)
    ["user_order_vtype"]=>
    string(11) "Paypal Sale"
    ["user_order_payment_processor"]=>
    string(6) "Paypal"
  */
  function mapToStyroformArray( $start_date=false, $end_date=false) {
    if (! $start_date) {
      $start_date = formatDate("MDY-")." 00:00:00";    
    }
    if (! $end_date) {
      $end_date = formatDate("MDY-")." 23:59:59";    
    }
    
    $this -> MOne_Result_Items = array();
    //$this -> MOne_Report_Result -> saveXML();
        
    $nodes = $this -> MOne_Report_Result -> query("//transaction");
    if ($nodes -> length > 0) {
    foreach ($nodes as $nodeitem) {
      $thesale = null;
      $doadd=false;
      foreach ($nodeitem -> childNodes as $dataNode) {
        if ($dataNode-> nodeType == 1){
          if ($dataNode -> nodeName != "action") {
            switch($dataNode -> nodeName) {
              case "order_id":
                $datearr = explode("-",$dataNode -> nodeValue);
                $thedate = $datearr[2];
                $thesale["user_order_date_oid"] = left($thedate,4)."-".substr($thedate,4,2)."-".substr($thedate,6,2)." ".(substr($thedate,8,2)).":".substr($thedate,10,2);
                $thesale["user_order_guid_oid"] = $dataNode -> nodeValue;
                if (($thesale["user_order_date"] == "") && ($dataNode -> nodeValue != "")) {
                  $thesale["user_order_date"] = $thesale["user_order_date_oid"];
                }
                if (($thesale["user_order_guid"] == "") && ($dataNode -> nodeValue != "")) {
                  $thesale["user_order_guid"] = $thesale["user_order_guid_oid"];
                }
                break;
              case "order_description":
                //Order: TTJ-6636304-201008182111
                $thesale["order_description"] = $dataNode -> nodeValue;
                $val = substr($dataNode -> nodeValue,7,24);
                $datearr = explode("-",$val);
                $thedate = $datearr[2];
                $thesale["user_order_date_desc"] = left($thedate,4)."-".substr($thedate,4,2)."-".substr($thedate,6,2)." ".(substr($thedate,8,2)).":".substr($thedate,10,2);
                $thesale["user_order_guid_desc"] = $val;
                if (($thesale["user_order_date"] == "") && ($val != "")) {
                  $thesale["user_order_date"] = $thesale["user_order_date_desc"];
                }
                if (($thesale["user_order_guid"] == "") && ($val != "")) {
                  $thesale["user_order_guid"] = $thesale["user_order_guid_desc"];
                }
                break;
              case "first_name":
                $thesale["user_order_fname"] = $dataNode -> nodeValue;
                break;
              case "last_name":
                $thesale["user_order_user_fname"] = $thesale["user_order_fname"] . " " . $dataNode -> nodeValue;
                break;
              case "email":
                $thesale["user_order_user_lname"] = $dataNode -> nodeValue;
                break;
              case "transaction_id":
                $thesale["user_order_id"] = $dataNode -> nodeValue;
                break;
              default:
                $thesale[$dataNode -> nodeName] = $dataNode -> nodeValue;
                break;
            }
          }
        } 
      }
      
      //if ($thesale["user_order_id"] == "1269398317") {
        //kickdump(strtotime($thesale["user_order_date"]) . ">=". strtotime($start_date));
        //kickdump(strtotime($thesale["user_order_date"]) . "<=". strtotime($end_date));
        
        if ((strtotime($thesale["user_order_date"]) >= strtotime($start_date)) && (strtotime($thesale["user_order_date"]) <= strtotime($end_date))) {
          //dump("yep");
          $doadd = true;
        } else {
          //dump("nope");
        }
      //}
      //$doadd = true;
      $nodeactions = $this -> MOne_Report_Result -> query("//transaction[transaction_id=".$thesale["user_order_id"]."]/action");
      
      if ($nodeactions -> length > 0) {
        $i=1;
        foreach ($nodeactions as $nodeitem) {
          $sale["rowNum"] = $i;
          foreach ($nodeitem -> childNodes as $dataNode) {
            if ($dataNode-> nodeType == 1){
              switch($dataNode -> nodeName) {
                //Taking four hours away from the time, as merchant one timestamps are in a different zone
                case "date":
                  $sale["mone_date"] = left($dataNode -> nodeValue,4)."-".substr($dataNode -> nodeValue,4,2)."-".substr($dataNode -> nodeValue,6,2)." ".(substr($dataNode -> nodeValue,8,2)).":".substr($dataNode -> nodeValue,10,2).":".substr($dataNode -> nodeValue,12,2);
                  break;
                //case "action_type":
                //  $sale["user_order_vtype"] =  $dataNode -> nodeValue;
                //  break;
                case "amount":
                  $thesale["user_order_total_fs"] = $dataNode -> nodeValue;
                  break;
                default:
                  $sale[$dataNode -> nodeName] = $dataNode -> nodeValue;
                  break;
              }
              
            }
          }
          $sale = array_merge($sale,$thesale);
          $i++;
        }
      }
      if ($sale["user_order_id"] == "1269337067") {
        //kickdump($sale);
      }
      if ($doadd) {
        $sale["user_order_process"]=2;
        switch(true){
          //If we haven't captured, still add it in
 	        case (($sale["condition"] == "pendingsettlement") && ($sale["action_type"] == "sale") && ($sale["success"]==1)):
            $sale["user_order_vtype"] =  "Merchant One Auth";
            $sale["user_order_status"]=2;
            break;
	       case (($sale["condition"] == "complete") && ($sale["action_type"] == "sale") && ($sale["success"]==1)):
            $sale["user_order_vtype"] =  "Merchant One Auth";
            $sale["user_order_status"]=2;
            break;
	       case (($sale["condition"] == "failed") && ($sale["action_type"] == "sale")):
            $sale["user_order_vtype"] =  "Merchant One Auth";
            $sale["user_order_status"]=-1;
            break;
         case (($sale["condition"] == "failed") && ($sale["action_type"] == "auth")):
            $sale["user_order_vtype"] =  "Merchant One Auth";
            $sale["user_order_status"]=-1;
            break;
         case (($sale["condition"] == "canceled") && ($sale["action_type"] == "auth")):
            $sale["user_order_vtype"] =  "Merchant One Auth";
            $sale["user_order_status"]=-1;
            break;
          default:
            $sale["user_order_vtype"] =  "Merchant One Auth";
            $sale["user_order_status"]=2;
            break;
        }
        $sale["user_order_download"]=1;
        $sale["user_order_payment_processor"]="Merchant One";
        $this -> MOne_Result_Items[] = $sale;
      }
      
    }}
    //die();
    //dump($this -> MOne_Result_Items);
    return $this -> MOne_Result_Items;
  }
  
  function checkPriorOrder() {
 
    if (strlen($this -> email) == 0) {
      return false;
    }
    if (strlen($this -> skus) == 0) {
      return false;
    }
    
    $sql = "select user_order_id,
            user_order_guid
            from user_order_product
            inner join user_order
            on fk_user_order_id = user_order_id
            inner join user
            on user.user_id = user_order.fk_user_id
            where user.user_email = '".$this -> email."'
             and fk_product_sku in (".$this -> skus.")
            and DATE_SUB(CURDATE(),INTERVAL 1 month) <= user_order.user_order_date 
            and user_order_status >0;";
 
    $d = new WTVRData( null );
    $res = $d -> propelQuery($sql);
    while ($res -> next()) {
      if($res -> get(1) > 0){
        return $res -> get(2);
      }
    }
    return false;
    
    /*sfConfig::set("user_id",$this -> orderuser -> getUserId());
    $exists = $this -> dataMap( sfConfig::get('sf_lib_dir')."/widgets/OrderManager/query/User_PriorOrder_list_datamap.xml");
    if($exists["data"][0]["fk_user_order_id"] > 0) {
      //$this -> setcookieVar("last_order",encrypt($exists["data"][0]["fk_user_order_id"]),.01,"/",".tattoojohnny.com",false,true);
      return $exists["data"][0]["fk_user_order_guid"];
      //redirect("download/receipt");
      //die();
    }
    return false;
    */
  }
  
}

?>
