<?php

class PFPro_Gateway {
  
  //Controller Settings
  var $action;
  var $cancel_url;
  var $returl_url;
  var $submit_url;
  var $PayPal_url;
  
  //PFPro Environment
  var $pfpro_environment;
  var $pfpro_fraud;
  var $pfpro_currency;
  var $pfpro_user;
  var $pfpro_vendor;
  var $pfpro_partner;
  var $pfpro_password;
  
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
  var $paypal_string;
  var $paypal_query;
  var $unique_id;
  var $trans_id;
  var $auth_code;
  
  //Transaction Response
  var $nvpArray;
  var $error;
  var $result_code;
  var $response_message;
  
  //PFPro Gateway Results
  //PFPro_Result codes are:
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
  
  var $PFPro_Result_Code;
  //Boolean for the following
  var $PFPro_Result_AVS_Object;
  var $PFPro_Result_AVS;
  
  //Report Data
  var $PFPro_Report_Result;
  var $PFPro_Report_Items;
  
  function __construct() {
    $this -> cancel_url = 'http://'.$_SERVER['HTTP_HOST'].'Download';
    $this -> return_url = 'http://'.$_SERVER['HTTP_HOST'].'Download/GetExpressCheckoutDetails/order_num/'.$order_num.'/amount/'.$amount;
    
    list($this -> pfpro_environment,$this -> pfpro_fraud,$this -> pfpro_currency,$this -> pfpro_user,$this -> pfpro_vendor,$this -> pfpro_partner,$this -> pfpro_password) = sfConfig::get("app_pfpro");
    
    if ($this -> pfpro_environment=='live') {
        $this -> submit_url = 'https://payflowpro.paypal.com';
        $this -> PayPal_url = 'https://www.paypal.com/cgi-bin/webscr?cmd=_express-checkout&useraction=commit&token=';
    } else {
        $this -> submit_url = 'https://pilot-payflowpro.paypal.com';
        $this -> PayPal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_express-checkout&useraction=commit&token=';
    }
    
    $this -> paypal_string = 'USER='.$this -> pfpro_user.'&VENDOR='.$this -> pfpro_vendor.'&PARTNER='.$this -> pfpro_partner.'&PWD='.$this -> pfpro_password;
    
    $this -> PFPro_Result_AVS["ADDR"] = false;
    $this -> PFPro_Result_AVS["ZIP"] = false;
    $this -> PFPro_Result_AVS["CVV2"] = false;
    
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
        case 'SetExpressCheckout':
          die("Not Implemented");
          break;
        case 'GetExpressCheckoutDetails':
          die("Not Implemented");
          break;
        case 'DoExpressCheckout':
          die("Not Implemented");
          break;
        case 'DoDirectPayment':
          $this -> DoDirectPayment();
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
  
  //Not Yet Implemented
  function ExpressCheckout() {
  
    // Mike Challis (www.carmosaic.com) added feature:
    // use an array to build the query for better visual representation in the php code.
    // and for the bracketed numbers

    $paypal_query_array = array(
        'USER'       => $this -> pfpro_user,
        'VENDOR'     => $this -> pfpro_vendor,
        'PARTNER'    => $this -> pfpro_partner,
        'PWD'        => $this -> pfpro_password,
        'TENDER'     => 'P',  // P - Express Checkout using PayPal account
        'TRXTYPE'    => 'S',  // S - Sale
        'ACTION'     => 'S',  // S - Sale
        'AMT'        => $this -> amount,
        'CURRENCY'   => $this -> pfpro_currency,
        'CANCELURL'  => $this -> cancel_url,
        'RETURNURL'  => $this -> return_url,
        'INVNUM'     => $this -> order_num,
        'ORDERDESC'  => $this -> desc,
        // Display the billing address on the GetEC call.  Account must be setup with PayPal to allow this feature.
        'REQBILLINGADDRESS' => '1', // 0 = Do not display, 1 = Display
        );

    // Mike Challis (www.carmosaic.com) added feature: bracketed numbers.
    // Bracketed numbers are length tags which allow you
    // to use the special characters of "&" and "=" in the value sent.

    foreach ($paypal_query_array as $key => $value) {
				$this -> paypal_query[]= $key.'['.strlen($value).']='.$value;
		}
		$this -> paypal_query=implode('&', $this -> paypal_query);
    
    // The $order_num field is storing our unique id that we'll use in the request id header.  By storing the id
    // in this manner, we are able to allowing reposting of the form without creating a duplicate transaction.
    $this -> unique_id = $this -> order_num;

    // call function to return name-value pair
    $this -> fetch_data();

    if($this -> nvpArray['RESPMSG']=='Approved') {
        // After you receive a successful response from PayPal, you should add the value of the Token from SetExpressCheckoutResponse as a
        // name/value pair to the following URL, and redirect your customer’s browser to it:
        // Live: https://www.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=value_from_SetExpressCheckoutResponse
        // Pilot: https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=value_from_SetExpressCheckoutResponse
        // Express Checkout has a variation on this redirect URL that allows you to bypass calling the second API (GetExpressCheckoutDetails)
        // and to change the text of the final button displayed on PayPal. See the Developer's Guide for more details.
        // Recommendation for Browser Redirection:
        // For redirecting the customer’s browser to the PayPal URL, PayPal recommends that you use the HTTPS response 302 “Object Moved” with
        // your URL as the value of the Location header in the HTTPS response. Ensure that you use an SSL-enabled server to prevent browser
        // warnings about a mix of secure and insecure graphics.
        $this -> PayPal_url = $this -> PayPal_url.urldecode($this -> nvpArray["TOKEN"]);
        echo '
        <html>
        <head>
        <META HTTP-EQUIV="Refresh"CONTENT="0;URL='.$this -> PayPal_url.'">
        </head>
        <body>
        <a href="'.$this -> PayPal_url.'">Click here</a> if you are not redirected to PayPal within 5 seconds.
        </body>
        </html>';
    } else {
        // error_handle($nvpArray);
        // Check for results and display approval or decline.
    		$this -> response_handler();
        exit;
    }
  }
  
  //Not Yet Implemented
  function GetExpressCheckoutDetails() {
    // After the customer has selected shipping and billing information on the PayPal website and clicks Pay, which is the customer’s approval
    // of the use of PayPal. PayPal then redirects the customer’s browser to your website using the ReturnURL specified by you in
    // the SetExpressCheckoutRequest. If the customer clicks the Cancel button, PayPal returns him to the CancelURL specified in the
    // SetExpressCheckoutRequest.

    $this -> paypal_query = $this -> paypal_string.'&TENDER=P&TRXTYPE=S&ACTION=G&TOKEN='.$_REQUEST['token'];
    
    // prepare unique id for Action=G.  Each part of Express Checkout must
    // have a unique request ID.
    $this -> generateGUID();
    // call function to return name-value pair
    
    $this -> fetch_data();
    
    //echo $nvpArray;
    if($this -> nvpArray['RESPMSG']=='Approved') {
        // Since the transaction was approved, display the information returned from PayPal.  At this time, you'd determine
        // what to display to your customer and what data to store in your db.
        echo '<h4>Processing order ... GetEC Response</h4>
        <form name="DoExpressCheckout" action="" method="POST">
        <table border="1" width="400">
        <tr><td>Token:</td><td>'.$_REQUEST['token'].'</td></tr>
        <tr><td>Order Total:</td><td>'.number_format($this -> amount,2).' '.$this -> pfpro_currency.'</td></tr>
        <tr><td>Order Number:</td><td>'.$this -> nvpArray['INVNUM'].'</td></tr>
        <tr><td colspan="2">Buyer Details:</td></tr>
        <tr><td>First Name:</td><td>'.$this -> nvpArray['FIRSTNAME'].'</td></tr>
        <tr><td>Last Name:</td><td>'.$this -> nvpArray['LASTNAME'].'</td></tr>
        <tr><td>Email Address:</td><td>'.$this -> nvpArray['EMAIL'].'</td></tr>
        <tr><td>Payer Status:</td><td>'.$this -> nvpArray['PAYERSTATUS'].'</td></tr>';
        if (isset($this -> nvpArray['STREET'])) {
        	echo '
        	<tr><td colspan="2">Billing Address:</td></tr>
        	<tr><td>Address Line 1:</td><td>'.$this -> nvpArray['STREET'].'</td></tr>
        	<tr><td>City:</td><td>'.$this -> nvpArray['CITY'].'</td></tr>
        	<tr><td>State:</td><td>'.$this -> nvpArray['STATE'].'</td></tr>
        	<tr><td>Postal Code:</td><td>'.$this -> nvpArray['ZIP'].'</td></tr>
        	<tr><td>Country:</td><td>'.$this -> nvpArray['COUNTRYCODE'].'</td></tr>';
        }
      	echo '
        <tr><td colspan="2">Shipping Address:</td></tr>
        <tr><td>Address Line 1:</td><td>'.$this -> nvpArray['SHIPTOSTREET'].'</td></tr>';
        if (isset($this -> nvpArray['SHIPTOSTREET2'])) {
            echo '<tr><td>Address Line 2:</td><td>'.$this -> nvpArray['SHIPTOSTREET2'].'</td></tr>';
        }
        echo '<tr><td>City:</td><td>'.$this -> nvpArray['SHIPTOCITY'].'</td></tr>
        <tr><td>State:</td><td>'.$this -> nvpArray['SHIPTOSTATE'].'</td></tr>
        <tr><td>Postal code:</td><td>'.$this -> nvpArray['SHIPTOZIP'].'</td></tr>
        <tr><td>Country:</td><td>'.$this -> nvpArray['SHIPTOCOUNTRY'].'</td></tr><tr>
				<tr><td>Address Status:</td><td>'.$this -> nvpArray['ADDRESSSTATUS'].'</td></tr>
        <td colspan="2"><input type="hidden" name="x" value="DoExpressCheckout"><input type="submit" value="Pay" /></td></tr>
        </table>
        <input type="hidden" name="token" value="'.$_REQUEST['token'].'">
        <input type="hidden" name="payerid" value="'.$_REQUEST['PayerID'].'">
        <input type="hidden" name="order_num" value="'.$this -> nvpArray['INVNUM'].'">
        <input type="hidden" name="unique" value="'.$this -> generateGUID().'">
        </form>';
    } else {
        //Whoops, something went wrong.  Display generic error.
        // error_handle($nvpArray);
        // Check for results and display approval or decline.
    		$this -> response_handler();
        exit;
    }
  }
  
  //Not Yet Implemented
  function DoExpressCheckout() {
    // After you receive a successful GetExpressCheckoutDetailsResponse, you would display a order review page (ie shipping information) or a
    // page on which the customer can select a shipping method, enter shipping instructions, or specify any other information necessary to
    // complete the purchase.
    //
    // When the customer clicks the “Place Order” button, send DoExpressCheckoutPaymentRequest to initiate the payment. After a successful
    // response is sent from PayPal, direct the customer to your order completion page to inform him that you received his order.
    
    // Mike Challis (www.carmosaic.com) added feature:
    // use an array to build the query for better visual representation in the php code.
    // and for the bracketed numbers

    $paypal_query_array = array(

        'USER'       => $this -> pfpro_user,
        'VENDOR'     => $this -> pfpro_vendor,
        'PARTNER'    => $this -> pfpro_partner,
        'PWD'        => $this -> pfpro_password,
        'TENDER'     => 'P',  // P - Express Checkout using PayPal account
        'TRXTYPE'    => 'S',  // S - Sale
        'ACTION'     => 'D',  //
        'TOKEN'      => urlencode($_REQUEST['token']),
        'PAYERID'    => urlencode($_REQUEST['payerid']),
        'AMT'        => $this -> amount,
        'CURRENCY'   => $this -> pfpro_currency,
        'IPADDRESS'  => urlencode($_SERVER['SERVER_NAME']),
        'INVNUM'     => $this -> order_num,
        'ORDERDESC'  => $this -> desc,
        );

    // Mike Challis (www.carmosaic.com) added feature: bracketed numbers.
    // Bracketed numbers are length tags which allow you
    // to use the special characters of "&" and "=" in the value sent.
    // http://paypaldeveloper.com/pdn/board/message?board.id=payflow&thread.id=1008

    foreach ($paypal_query_array as $key => $value) {
				$this -> paypal_query[]= $key.'['.strlen($value).']='.$value;
		}
		$this -> paypal_query=implode('&', $this -> paypal_query);

    $this -> unique_id = $_REQUEST['unique'];        // get it from GetExpressCheckoutDetails form so that no duplication

    // call function to return name-value pair
    $this -> fetch_data();

    // Check for results and display approval or decline.
    $this -> response_handler();
    
    }

  function DoDirectPayment() {
    $this -> paypal_query="";
    // Transaction results (especially values for declines and error conditions) returned by each PayPal-supported
    // processor vary in detail level and in format. The Payflow Verbosity parameter enables you to control the kind
    // and level of information you want returned.
    // By default, Verbosity is set to LOW. A LOW setting causes PayPal to normalize the transaction result values.
    // Normalizing the values limits them to a standardized set of values and simplifies the process of integrating
    // the Payflow SDK.
    // By setting Verbosity to MEDIUM, you can view the processor’s raw response values. This setting is more “verbose”
    // than the LOW setting in that it returns more detailed, processor-specific information.
    // Review the chapter in the Developer's Guides regarding VERBOSITY and the INQUIRY function for more details.
    // Set the transaction verbosity to MEDIUM.

    // Mike Challis (www.carmosaic.com) added feature:
    // use an array to build the query for better visual representation in the php code.
    // and for the bracketed numbers
    $paypal_query_array = array(

        'USER'       => $this -> pfpro_user,
        'VENDOR'     => $this -> pfpro_vendor,
        'PARTNER'    => $this -> pfpro_partner,
        'PWD'        => $this -> pfpro_password,
        'TENDER'     => 'C',  // C - Direct Payment using credit card
        'TRXTYPE'    => 'A',  // A - Authorization, S - Sale
        'ACCT'       => $this -> card_num,
        'CVV2'       => $this -> cvv2,
        'EXPDATE'    => $this -> expiry,
        'ACCTTYPE'   => $this -> card,
        'AMT'        => $this -> amount,
        'CURRENCY'   => $this -> pfpro_currency,
        'FIRSTNAME'  => $this -> fname,
        'LASTNAME'   => $this -> lname,
        'STREET'     => $this -> addr1,
        'CITY'       => $this -> addr2,
        'STATE'      => $this -> addr3,
        'ZIP'        => $this -> addr4,
        'COUNTRY'    => $this -> country,
        'EMAIL'      => $this -> email,
        'CUSTIP'     => $this -> cust_ip,
        'COMMENT1'   => $this -> custom1,
        'COMMENT2'   => $this -> custom2,
        'INVNUM'     => $this -> order_num,
        'ORDERDESC'  => $this -> desc,
        'VERBOSITY'  => 'MEDIUM',
       	'CARDSTART'  => $this -> card_start,
       	'CARDISSUE'  => $this -> card_issue,
				);
				
    // Mike Challis (www.carmosaic.com) added feature: bracketed numbers.
    // Bracketed numbers are length tags which allow you
    // to use the special characters of "&" and "=" in the value sent.

    foreach ($paypal_query_array as $key => $value) {
				$this -> paypal_query[]= $key.'['.strlen($value).']='.$value;
		}
		$this -> paypal_query=implode('&', $this -> paypal_query);

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

        'USER'       => $this -> pfpro_user,
        'VENDOR'     => $this -> pfpro_vendor,
        'PARTNER'    => $this -> pfpro_partner,
        'PWD'        => $this -> pfpro_password,
        'TENDER'     => 'C',  // C - Direct Payment using credit card
        'TRXTYPE'    => 'V',  // A - Authorization, S - Sale
        'ORIGID'   =>  $this -> trans_id 
        
				);
				
    // Mike Challis (www.carmosaic.com) added feature: bracketed numbers.
    // Bracketed numbers are length tags which allow you
    // to use the special characters of "&" and "=" in the value sent.

    foreach ($paypal_query_array as $key => $value) {
				$this -> paypal_query[]= $key.'['.strlen($value).']='.$value;
		}
		$this -> paypal_query=implode('&', $this -> paypal_query);

    // Call the function to send data to PayPal and return the data into an Array.
    $this -> fetch_data();

  }
  
  function refundTransaction() {
    $this -> paypal_query="";
    $paypal_query_array = array(

        'USER'       => $this -> pfpro_user,
        'VENDOR'     => $this -> pfpro_vendor,
        'PARTNER'    => $this -> pfpro_partner,
        'PWD'        => $this -> pfpro_password,
        'TENDER'     => 'C',  // C - Direct Payment using credit card
        'TRXTYPE'    => 'C',  // A - Authorization, S - Sale
        'ORIGID'   =>  $this -> trans_id 
        
				);
				
    // Mike Challis (www.carmosaic.com) added feature: bracketed numbers.
    // Bracketed numbers are length tags which allow you
    // to use the special characters of "&" and "=" in the value sent.

    foreach ($paypal_query_array as $key => $value) {
				$this -> paypal_query[]= $key.'['.strlen($value).']='.$value;
		}
		$this -> paypal_query=implode('&', $this -> paypal_query);

    // Call the function to send data to PayPal and return the data into an Array.
    $this -> fetch_data();

  }
  
  function creditTransaction() {
    $this -> paypal_query="";
    $paypal_query_array = array(

        'USER'       => $this -> pfpro_user,
        'VENDOR'     => $this -> pfpro_vendor,
        'PARTNER'    => $this -> pfpro_partner,
        'PWD'        => $this -> pfpro_password,
        'TENDER'     => 'C',  // C - Direct Payment using credit card
        'TRXTYPE'    => 'C',  // A - Authorization, S - Sale
        'ACCT'       => $this -> trans_id[0],
        'EXPDATE'    => sprintf("%04d",$this -> trans_id[1]),
        'AMT'        => sprintf("%.02f",$this -> trans_id[2])
        
				);
    // Mike Challis (www.carmosaic.com) added feature: bracketed numbers.
    // Bracketed numbers are length tags which allow you
    // to use the special characters of "&" and "=" in the value sent.
    
    foreach ($paypal_query_array as $key => $value) {
				$this -> paypal_query[]= $key.'['.strlen($value).']='.$value;
		}
		$this -> paypal_query=implode('&', $this -> paypal_query);

    // Call the function to send data to PayPal and return the data into an Array.
    $this -> fetch_data();

  }
  
  function captureTransaction() {
    $this -> paypal_query="";
    $paypal_query_array = array(

        'USER'       => $this -> pfpro_user,
        'VENDOR'     => $this -> pfpro_vendor,
        'PARTNER'    => $this -> pfpro_partner,
        'PWD'        => $this -> pfpro_password,
        'TENDER'     => 'C',  // C - Direct Payment using credit card
        'TRXTYPE'    => 'D',  // A - Authorization, S - Sale
        'ORIGID'   =>  $this -> trans_id 
        
				);
				
    // Mike Challis (www.carmosaic.com) added feature: bracketed numbers.
    // Bracketed numbers are length tags which allow you
    // to use the special characters of "&" and "=" in the value sent.

    foreach ($paypal_query_array as $key => $value) {
				$this -> paypal_query[]= $key.'['.strlen($value).']='.$value;
		}
		$this -> paypal_query=implode('&', $this -> paypal_query);

    // Call the function to send data to PayPal and return the data into an Array.
    $this -> fetch_data();

  }
  
  function voiceAuthTransaction() {
    
    $this -> paypal_query="";
    $paypal_query_array = array(

        'USER'       => $this -> pfpro_user,
        'VENDOR'     => $this -> pfpro_vendor,
        'PARTNER'    => $this -> pfpro_partner,
        'PWD'        => $this -> pfpro_password,
        'TENDER'     => 'C',  // C - Direct Payment using credit card
        'TRXTYPE'    => 'F',  // A - Authorization, S - Sale
        'ORIGID'     =>  $this -> trans_id,
        'AUTHCODE'   =>  $this -> auth_code
        
				);
				
    // Mike Challis (www.carmosaic.com) added feature: bracketed numbers.
    // Bracketed numbers are length tags which allow you
    // to use the special characters of "&" and "=" in the value sent.

    foreach ($paypal_query_array as $key => $value) {
				$this -> paypal_query[]= $key.'['.strlen($value).']='.$value;
		}
		$this -> paypal_query=implode('&', $this -> paypal_query);

    // Call the function to send data to PayPal and return the data into an Array.
    $this -> fetch_data();

  }
  
  function inquiryTransaction() {
    $this -> paypal_query="";
    $paypal_query_array = array(

        'USER'       => $this -> pfpro_user,
        'VENDOR'     => $this -> pfpro_vendor,
        'PARTNER'    => $this -> pfpro_partner,
        'PWD'        => $this -> pfpro_password,
        'TENDER'     => 'C',  // C - Direct Payment using credit card
        'TRXTYPE'    => 'I',  // A - Authorization, S - Sale
        'VERBOSITY'  => 'MEDIUM',  // A - Authorization, S - Sale
        'ORIGID'   =>  $this -> trans_id 
        
				);
				
    // Mike Challis (www.carmosaic.com) added feature: bracketed numbers.
    // Bracketed numbers are length tags which allow you
    // to use the special characters of "&" and "=" in the value sent.

    foreach ($paypal_query_array as $key => $value) {
				$this -> paypal_query[]= $key.'['.strlen($value).']='.$value;
		}
		$this -> paypal_query=implode('&', $this -> paypal_query);

    // Call the function to send data to PayPal and return the data into an Array.
    $this -> fetch_data();

  }
  
  // API functions and error handling
  function fetch_data() {
  
      // get data ready for API
      $user_agent = $_SERVER['HTTP_USER_AGENT'];
      // Here's your custom headers; adjust appropriately for your setup:
      $headers[] = "Content-Type: text/namevalue"; //or text/xml if using XMLPay.
      $headers[] = "Content-Length : " . strlen ($this -> paypal_query);  // Length of data to be passed 
      // Here I set the server timeout value to 45, but notice below in the cURL section, I set the timeout
      // for cURL to 90 seconds.  You want to make sure the server timeout is less, then the connection.
      $headers[] = "X-VPS-Timeout: 45";
      $headers[] = "X-VPS-Request-ID:" . $this -> unique_id;
  
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
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
      curl_setopt($ch, CURLOPT_HEADER, 1);                // tells curl to include headers in response
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);        // return into a variable
      curl_setopt($ch, CURLOPT_TIMEOUT, 90);              // times out after 90 secs
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);        // this line makes it work under https
      curl_setopt($ch, CURLOPT_POSTFIELDS, $this -> paypal_query);        //adding POST data
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  2);       //verifies ssl certificate
      curl_setopt($ch, CURLOPT_FORBID_REUSE, TRUE);       //forces closure of connection when done
      curl_setopt($ch, CURLOPT_POST, 1); 									//data sent as POST
  
      $i=1;
      while ($i++ <= 3) {
          $result = curl_exec($ch);
          $headers = curl_getinfo($ch);
          //print_r($headers);
          //echo '<br>';
          //print_r($result);
          //echo '<br>';
          if ($headers['http_code'] != 200) {
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
          $this -> PFPro_Result_Code = -10;
          $this -> error = $headers['http_code'];
          curl_close($ch);
          return false;
      }
      
      curl_close($ch);
      $result = strstr($result, "RESULT");
      
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
      
      return true;
  }
  
  function response_handler() {
      
      $this -> result_code = $this -> nvpArray['RESULT']; // get the result code to validate.
      
      //dump($this -> nvpArray);
      switch (TRUE) {
        
        //For Cart Items
        //Over 1000, or AVS Mismatch, or Billing<>Shipping, RESULT 0
        //Velocity is fraud trigger
        
        //Invalid Login Credentials
        case (($this -> result_code == 1 || $this -> result_code == 26)) :
           $this -> PFPro_Result_Code = -21;
          break;
        //Approval
        case ($this -> result_code == 0) :
            $this -> PFPro_Result_Code = 2;
            //Check AVSADDR results
            if (isset($this -> nvpArray['AVSADDR'])) {
                if ($this -> nvpArray['AVSADDR'] == "Y") {
                    $this -> PFPro_Result_AVS_Object["ADDR"] = true;
                    $this -> PFPro_Result_AVS = "ADDR:Y,";
                } else {
                  $this -> PFPro_Result_Code = 2;
                  $this -> PFPro_Result_AVS_Object["ADDR"] = false;
                  $this -> PFPro_Result_AVS = "ADDR:N,";
                }
            }
            //Check AVSZIP results
            if (isset($this -> nvpArray['AVSZIP'])) {
                if ($this -> nvpArray['AVSZIP'] == "Y") {
                   $this -> PFPro_Result_AVS_Object["ZIP"] = true;
                   $this -> PFPro_Result_AVS .= "ZIP:Y,";
                } else {
                  $this -> PFPro_Result_Code = 2;
                   $this -> PFPro_Result_AVS_Object["ZIP"] = false;
                   $this -> PFPro_Result_AVS .= "ZIP:N,";
                }
            }
            //Check CVV2 results
            if (isset($this -> nvpArray['CVV2MATCH'])) {
                if ($this -> nvpArray['CVV2MATCH'] == "Y") {
                   $this -> PFPro_Result_AVS_Object["CVV2"] = true;
                   $this -> PFPro_Result_AVS .= "CVV2:Y";
                } else {
                  $this -> PFPro_Result_Code = 2;
                  $this -> PFPro_Result_AVS_Object["CVV2"] = false;
                  $this -> PFPro_Result_AVS .= "CVV2:N";
                }
            }
          break;
        // Hard decline from bank.
        case ($this -> result_code == 12) :
            $this -> PFPro_Result_Code = -1;
          break;
        // Voice authorization required.
        case ($this -> result_code == 13) :
            $this -> PFPro_Result_Code = 0;
            $subject="Voice Authorization Required";
            $message = "This order requires a 'Voice Authorization'";
            QAMail($message,$subject,false,"tiffany@tattoojohnny.com");
          break;
        // Issue with credit card number or expiration date.
        case ($this -> result_code == 23 || $this -> result_code == 24):
            $this -> PFPro_Result_Code = -2;
          break;
        // 125, 126 and 127 are Fraud Responses.
        // Refer to the Payflow Pro Fraud Protection Services User's Guide or
        // Website Payments Pro Payflow Edition - Fraud Protection Services User's Guide.
        case ($this -> result_code == 125):
            $this -> PFPro_Result_Code = -1;
            $this -> error = "Fraud Protection was triggered";
          break;
        // 126 = One of more filters were triggered.  Here you would check the fraud message returned if you
        // want to validate data.  For example, you might have 3 filters set, but you'll allow 2 out of the
        // 3 to consider this a valid transaction.  You would then send the request to the server to modify the
        // status of the transaction.  This outside the scope of this sample.  Refer to the Fraud Developer's Guide.
        case ($this -> result_code == 126):
            $this -> PFPro_Result_Code = 1;
          break;
        // 127 = Issue with fraud service.  Manually, approve?
        case ($this -> result_code == 127):
            $this -> PFPro_Result_Code = 1;
          break;
        default:
          $this -> PFPro_Result_Code = -8;
          $this -> error = "Unused Code:: ". $this -> result_code;
          break;
      }
      
  }
  
  function getResponseVal( $val ) {
    
    switch (TRUE) {
        
        //For Cart Items
        //Over 1000, or AVS Mismatch, or Billing<>Shipping, RESULT 0
        //Velocity is fraud trigger
        
        //Invalid Login Credentials
        case (($val == 1 || $val == 26)) :
           $result_code = 0;
          break;
        //Approval
        case ($val == 0) :
            $result_code = 2;
          break;
        // Hard decline from bank.
        case ($val == 12) :
            $result_code = -1;
          break;
        // Voice authorization required.
        case ($val == 13) :
            $result_code = -4;
          break;
        // Issue with credit card number or expiration date.
        case ($val == 23 || $val == 24):
            $result_code = -2;
          break;
        // 125, 126 and 127 are Fraud Responses.
        // Refer to the Payflow Pro Fraud Protection Services User's Guide or
        // Website Payments Pro Payflow Edition - Fraud Protection Services User's Guide.
        case ($val == 125):
            $result_code = -1;
          break;
        // 126 = One of more filters were triggered.  Here you would check the fraud message returned if you
        // want to validate data.  For example, you might have 3 filters set, but you'll allow 2 out of the
        // 3 to consider this a valid transaction.  You would then send the request to the server to modify the
        // status of the transaction.  This outside the scope of this sample.  Refer to the Fraud Developer's Guide.
        case ($val == 126):
            $result_code = 1;
          break;
        // 127 = Issue with fraud service.  Manually, approve?
        case ($val == 127):
            $result_code = 1;
          break;
        default:
          $result_code = -8;
          break;
      }
      
      return $result_code;
  }
  
  function displayResponse() {
  
      echo '<p>Results returned from server: <br><br>';
      while (list($key, $val) = each($this -> nvpArray)) {
          echo "\n" . $key . ": " . $val . "\n<br>";
      }
      echo '</p>';
      // Was this a duplicate transaction, ie the request ID was NOT changed.
      // Remember, a duplicate response will return the results of the orignal transaction which
      // could be misleading if you are debugging your software.
      // For Example, let's say you got a result code 4, Invalid Amount from the orignal request because
      // you were sending an amount like: 1,050.98.  Since the comma is invalid, you'd receive result code 4.
      // RESULT=4&PNREF=V18A0C24920E&RESPMSG=Invalid amount&PREFPSMSG=No Rules Triggered
      // Now, let's say you modified your code to fix this issue and ran another transaction but did not change
      // the request ID.  Notice the PNREF below is the same as above, but DUPLICATE=1 is now appended.
      // RESULT=4&PNREF=V18A0C24920E&RESPMSG=Invalid amount&DUPLICATE=1
      // This would tell you that you are receving the results from a previous transaction.  This goes for
      // all transactions even a Sale transaction.  In this example, let's say a customer ordered something and got
      // a valid response and now a different customer with different credit card information orders something, but again
      // the request ID is NOT changed, notice the results of these two sales.  In this case, you would have not received
      // funds for the second order.
      // First order: RESULT=0&PNREF=V79A0BC5E9CC&RESPMSG=Approved&AUTHCODE=166PNI&AVSADDR=X&AVSZIP=X&CVV2MATCH=Y&IAVS=X
      // Second order: RESULT=0&PNREF=V79A0BC5E9CC&RESPMSG=Approved&AUTHCODE=166PNI&AVSADDR=X&AVSZIP=X&CVV2MATCH=Y&IAVS=X&DUPLICATE=1
      // Again, notice the PNREF is from the first transaction, this goes for all the other fields as well.
      // It is suggested that your use this to your benefit to prevent duplicate transaction from the same customer, but you want
      // to check for DUPLICATE=1 to ensure it is not the same results as a previous one.
      if(isset ($this -> nvpArray['DUPLICATE'])) {
              echo '<h2>Error!</h2><p>This is a duplicate of your previous order.</p>';
              echo '<p>Notice that DUPLICATE=1 is returned and the PNREF is the same ';
              echo 'as the previous one.  You can see this in Manager as the Transaction ';
              echo 'Type will be "N".';
      }
      if (isset($this -> nvpArray['PPREF'])) {
          // Check if PayPal Express Checkout and if order is Pending.
          if (isset($this -> nvpArray['PENDINGREASON'])) {
          	if ($this -> nvpArray['PENDINGREASON']=='completed') {
              	echo '<h2>Transaction Completed!</h2>';
              	echo '<h3>'.$RespMsg.'</h3><p>';
              	echo '<h4>Note: To simulate a duplicate transaction, refresh this page in your browser.  ';
              	echo 'Notice that you will see DUPLICATE=1 returned.</h4>';
          	} elseif($this -> nvpArray['PENDINGREASON']=='echeck') {
              	// PayPal transaction
              	echo '<h2>Transaction Completed!</h2>';
              	echo '<h3>The payment is pending because it was made by an eCheck that has not yet cleared.</h3';
          	} else {
       		// PENDINGREASON not 'completed' or 'echeck'.  See Integration guide for more responses.
       		echo '<h2>Transaction Completed!</h2>';
       		echo '<h3>The payment is pending due to: '.$nvpArray['PENDINGREASON'];
       		echo '<h4>Please login to your PayPal account for more details.</h4>';
       		}
       	}
      } else {
      	if ($this -> nvpArray['RESULT'] == "0") {
      		echo '<h2>Transaction Completed!</h2>';
      	} else {
      		echo '<h2>Transaction Failure!</h2>';
      	}
      	echo '<h3>'.$RespMsg.'</h3><p>';
      	if ($this -> nvpArray['RESULT'] != "26" && $this -> nvpArray['RESULT'] != "1") {
      		echo '<h4>Note: To simulate a duplicate transaction, refresh this page in your browser.&nbsp';
      		echo 'Notice that you will see DUPLICATE=1 returned.</h4>';
      	}
      }
  }
  
  function generateCharacter () {
      $possible = "1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
      $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
      return $char;
  }
  
  function generateGUID () {
      $this -> unique_id = generateCharacter().generateCharacter().generateCharacter().generateCharacter().generateCharacter().generateCharacter().generateCharacter().generateCharacter().generateCharacter()."-";
      $this -> unique_id = $this -> unique_id .generateCharacter().generateCharacter().generateCharacter().generateCharacter()."-";
      $this -> unique_id = $this -> unique_id .generateCharacter().generateCharacter().generateCharacter().generateCharacter()."-";
      $this -> unique_id = $this -> unique_id .generateCharacter().generateCharacter().generateCharacter().generateCharacter()."-";
      $this -> unique_id = $this -> unique_id .generateCharacter().generateCharacter().generateCharacter().generateCharacter().generateCharacter().generateCharacter().generateCharacter().generateCharacter().generateCharacter().generateCharacter().generateCharacter().generateCharacter();
  }
  
  function error_handle($nvpArray) {
      echo '<h2>Error!</h2><p>We were unable to process your order.</p>';
      echo '<p>Error '.$nvpArray['RESULT'].': '.$nvpArray['RESPMSG'].'.</p>';
      while (list($key, $val) = each($nvpArray)) {
          echo "\n" .  $key .  ": " . $val .  "\n<br>";
      }
  }
  
  
  function getDailyReport( $date=false, $pages=30 ) {
    
    if (! $date)
      $date = now();
    
    if ($this -> pfpro_environment=='live') {
        $this -> submit_url = 'https://payments-reports.paypal.com/reportingengine';
    } else {  
        $this -> submit_url = 'https://payments-reports.paypal.com/reportingengine';
        //$this -> submit_url = 'https://payments-reports.paypal.com/test-reportingengine';
    }
    
    $curl = new Curl();
    $curl -> headers["Accept-Encoding"] = "*";
    $curl -> headers["TE"] = "deflate;q=0";
    
    $response = $curl -> post($this -> submit_url, array("xml"=>$this -> getReportRequestXML($date)));
    
    $result = new XML();
    $result -> loadXML($response->body);
    $id = $result -> getElementsByTagname("reportId") -> item(0) -> nodeValue;
    
    $this -> PFPro_Report_Result = new XML();
    $this -> PFPro_Report_Result -> loadXML( sfConfig::get("sf_lib_dir")."/vendor/PFPro/payflow_shell.xml" );
    //$compiled_results -> setValueByPath("//reportId",0,$id);
    
    for ($i=1;$i<$pages+1;$i++) {
      $response = $curl -> post($this -> submit_url, array("xml"=>$this -> getReportDataXML( $id, $i )));
      
      $result = new XML();
      $result -> loadXML($response->body);
      //$result -> saveXML();
      $item = $result -> documentElement -> getElementsByTagname( "getDataResponse" );
      $itemcount = $result -> documentElement -> getElementsByTagname( "reportDataRow" );
      if($itemcount -> length > 0) {
        $this -> PFPro_Report_Result -> importDeepNode( $item -> item(0),"reportingEngineResponse" );
      }
      
      if($itemcount -> length < 10) {
        break;
      }
    }
    
    //$this -> PFPro_Report_Result -> saveXML();
    //$xsl = new XSL();
    
    //$xsl_location = sfConfig::get("sf_lib_dir")."/vendor/styroform/1.2/xsl/index.xsl";
    //return array("form"=>$xsl -> convertDoc($xsl_location,$compiled_results->documentElement,"true"));
      
  }
  
  /*
  <reportDataRow rowNum="1">
  -
  <columnData colNum="1">
  <data>VUHF4B498E00</data>
  </columnData>
  -
  <columnData colNum="2">
  <data>2009-10-11 00:00:35</data>
  </columnData>
  -
  <columnData colNum="3">
  <data>Sale</data>
  </columnData>
  -
  <columnData colNum="4">
  <data>Visa</data>
  </columnData>
  -
  <columnData colNum="5">
  <data>4863XXXXXXXX0150</data>
  </columnData>
  -
  <columnData colNum="6">
  <data>0310</data>
  </columnData>
  -
  <columnData colNum="7">
  <data>1546</data>
  </columnData>
  -
  <columnData colNum="8">
  <data>126</data>
  </columnData>
  -
  <columnData colNum="9">
  <data>Under Review by Fraud Service</data>
  </columnData>
  -
  <columnData colNum="10">
  <data/>
  </columnData>
  -
  <columnData colNum="11">
  <data/>
  </columnData>
  -
  <columnData colNum="12">
  <data>70.86.111.98</data>
  </columnData>
  -
  <columnData colNum="13">
  <data>010920</data>
  </columnData>
  -
  <columnData colNum="14">
  <data>23 rondy way</data>
  </columnData>
  -
  <columnData colNum="15">
  <data>lm 345</data>
  </columnData>
  -
  <columnData colNum="16">
  <data>USD</data>
  </columnData>
  </reportDataRow>
  */
  function mapToStyroformArray( $start_date=false, $end_date=false) {
    
    $this -> PFPro_Result_Items = array();
    
    $nodes = $this -> PFPro_Report_Result -> query("//reportDataRow");
    if ($nodes -> length > 0) {
    foreach ($nodes as $nodeitem) {
      $doadd=true;
      $sale["rowNum"] = $nodeitem -> getAttribute("rowNum");
      foreach ($nodeitem -> childNodes as $dataNode) {
        $col = $dataNode -> getAttribute("colNum");
        if( $col == 2) {
          $sale["user_order_date"] = $dataNode -> firstChild -> nodeValue;
        }
        if( $col == 1) {
          $sale["user_order_id"] = $dataNode -> firstChild -> nodeValue;
          $sale["user_order_guid"] = $dataNode -> firstChild -> nodeValue;
        }
        $sale["user_order_user_fname"] = "null";
        $sale["user_order_user_lname"] = "null";
        $sale["user_order_product"] = "null";
        if( $col == 7) {
          $amt = $dataNode -> firstChild -> nodeValue;
          $sale["user_order_total_fs"] = substr($amt,0,-2).".".substr($amt,-2);
        }
        if( $col == 8) {
          $sale["user_order_status"] = $this -> getResponseVal($dataNode -> firstChild -> nodeValue);
        }
        if( $col == 3) {
          $sale["user_order_vtype"] = $dataNode -> firstChild -> nodeValue;
        }
        $sale["user_order_process"] = "1";
        
        $sale["user_order_download"] = "";
        $sale["user_order_payment_processor"] = "Verisign";
      }
      if (($start_date) && ($sale["user_order_date"] < $start_date)) {
        $doadd=false;
      }
      if (($end_date) && ($sale["user_order_date"] > $end_date)) {
        $doadd=false;
      }
      if ($doadd)
        $this -> PFPro_Result_Items[] = $sale;
    }}
    
    return $this -> PFPro_Result_Items;
  }
  
  function getReportRequestXML( $date=false, $pages=30 ) {
    
    if (! $date)
      $date = now();
      
    $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
          <reportingEngineRequest>
          <authRequest>
          <user>tattoojohnny</user>
          <vendor>tattoojohnny</vendor>
          <partner>verisign</partner>
          <password>RgVU8Jn^#bb</password>
          </authRequest>
          <runReportRequest>
          <reportName>DailyActivityReport</reportName>
          <reportParam>
          <paramName>report_date</paramName>
          <paramValue>".formatDate($date,"MDY-")."</paramValue>
          </reportParam>
          <pageSize>$pages</pageSize>
          </runReportRequest>
          </reportingEngineRequest>";
    
    return $xml;
    
  }
  
  function getReportDataXML ( $id, $page=1 ) {
    
    $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
            <reportingEngineRequest>
            <authRequest>
            <user>tattoojohnny</user>
            <vendor>tattoojohnny</vendor>
            <partner>verisign</partner>
            <password>RgVU8Jn^#bb</password>
            </authRequest>
            <getDataRequest>
            <reportId>".$id."</reportId>
            <pageNum>".$page."</pageNum>
            </getDataRequest>
            </reportingEngineRequest>";
    
    return $xml;
  }
}

?>
