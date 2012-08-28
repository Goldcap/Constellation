<?php

class PaypalWPP_Gateway {
  
  var $context;
  
  //Controller Settings
  var $action;
  var $cancel_url;
  var $returl_url;
  var $submit_url;
  var $PayPal_url;
  
  //PFPro Environment
  var $ppwpp_environment;
  var $ppwpp_fraud;
  var $ppwpp_currency;
  var $ppwpp_user;
  var $ppwpp_signature;
  var $ppwpp_password;
  var $ppwpp_version;
  
  //Express Checkout Details
  var $items;
  var $returnURL;
  var $cancelURL;
  var $token;
  var $payerId;
  
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
  var $addr2;//Street 1
  var $city;//City
  var $state;//State
  var $zip;//ZIP
  var $country;        // 3-digits ISO code
  // Other information
  var $custom1;
  var $custom2;
  var $lnamen;
 	var $ldescn;
 	var $lamtn;
 	var $lnumn;
 	var $lqtyn;
  
  //Transaction Data
  var $ppwpp_string;
  var $ppwpp_query;
  var $unique_id;
  var $trans_id;
  var $auth_code;
  
  //Transaction Response
  var $nvpArray;
  var $error;
  var $result_code;
  var $response_message;
  
  var $debug;
  
  //PPWPP Gateway Results
  //PPWPP_Result codes are:
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
  
  var $PPWPP_Result_Code;
  //Boolean for the following
  var $PPWPP_Result_AVS_Object;
  var $PPWPP_Result_AVS;
  
  //Report Data
  var $PPWPP_Report_Result;
  var $PPWPP_Report_Items;
  
  function __construct( $context ) {
  
    $this -> context = $context;
    $this -> debug = false;
    
    $this -> ppwpp_version = "65.1";
    list($this -> ppwpp_environment,$this -> ppwpp_fraud,$this -> ppwpp_currency,$this -> ppwpp_user,$this -> ppwpp_signature,$this -> ppwpp_password) = explode(",",sfConfig::get("app_ppwpp"));
    
    if ($this -> ppwpp_environment=='live') {
        $this -> submit_url = 'https://api-3t.paypal.com/nvp';
        $this -> PayPal_url = 'https://www.paypal.com/cgi-bin/webscr?cmd=_express-checkout&useraction=commit&token=';
    } else {
        $this -> submit_url = 'https://api-3t.sandbox.paypal.com/nvp';
        $this -> PayPal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_express-checkout&useraction=commit&token=';
    }
    
    $this -> ppwpp_string = 'VERSION='.$this -> ppwpp_version.'&PWD='.$this -> ppwpp_password.'&USER='.$this -> ppwpp_user.'&SIGNATURE='.$this -> ppwpp_signature;
    //$this -> paypal_string = 'USER='.$this -> ppwpp_user.'&VENDOR='.$this -> ppwpp_vendor.'&PARTNER='.$this -> ppwpp_partner.'&PWD='.$this -> ppwpp_password;
    
    $this -> PPWPP_Result_AVS["ADDR"] = false;
    $this -> PPWPP_Result_AVS["ZIP"] = false;
    $this -> PPWPP_Result_AVS["CVV2"] = false;
    
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
          $this -> SetExpressCheckout();
          break;
        case 'GetExpressCheckoutDetails':
          $this -> GetExpressCheckoutDetails();
          break;
        case 'DoExpressCheckoutPayment':
          $this -> DoExpressCheckoutPayment();
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
  function SetExpressCheckout() {
    
    /**********************************************/
    //NOTE -  THIS DOES NOT INCLUDE SHIPPING AMOUNTS
    /**********************************************/
    
    // Mike Challis (www.carmosaic.com) added feature:
    // use an array to build the query for better visual representation in the php code.
    // and for the bracketed numbers
    $this -> ppwpp_query = null;
    
    $ppwpp_query_array = array(
        'METHOD'      => 'SetExpressCheckout',
        'USER'       => $this -> ppwpp_user,
        'SIGNATURE'  => $this -> ppwpp_signature,
        'PWD'        => $this -> ppwpp_password,
        'VERSION'     => $this -> ppwpp_version,
        'MAXAMT'      =>  "1000",
        'CALLBACKTIMEOUT' => 4,
        'TAXAMT'      =>  '0.00',
        'RETURNURL'   =>  $this -> returnURL,
        'CANCELURL'   =>  $this -> cancelURL,
        'CURRENCYCODE' => $this -> ppwpp_currency);
    
    $i=0;
    foreach ($this -> items as $item) {
      $ppwpp_query_array["L_NAME".$i] = $item["name"];
      $ppwpp_query_array["L_AMT".$i] = $item["amount"];
      $ppwpp_query_array["L_QTY".$i] = $item["quantity"];
      $ppwpp_query_array["L_DESC".$i] = $item["description"];
      $ppwpp_query_array["L_NUMBER".$i] = $item["sku"];
      
      $ppwpp_query_array["AMT"] += ($item["amount"] * $item["quantity"]);
      $ppwpp_query_array["ITEMAMT"] += ($item["amount"] * $item["quantity"]);
    }
    
    foreach ($ppwpp_query_array as $key => $value) {
				$this -> ppwpp_query[]= $key.'='.$value;
		}
		$this -> ppwpp_query=implode('&', $this -> ppwpp_query);
    
    // The $order_num field is storing our unique id that we'll use in the request id header.  By storing the id
    // in this manner, we are able to allowing reposting of the form without creating a duplicate transaction.
    $this -> unique_id = $this -> order_num;
    
    // call function to return name-value pair
    $this -> fetch_data();
    
    /*
    array
      'TOKEN' => string 'EC-4R759937GF333464L' (length=20)
      'TIMESTAMP' => string '2011-01-11T00:29:04Z' (length=20)
      'CORRELATIONID' => string 'a800c0d552269' (length=13)
      'ACK' => string 'Success' (length=7)
      'VERSION' => string '65.1' (length=4)
      'BUILD' => string '1646991' (length=7)
    */
    if($this -> nvpArray['ACK']=='Success') {
        return true;
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
    
    $this -> ppwpp_query = null;
    $this -> nvpArray = null;
    
    $ppwpp_query_array = array(
        'METHOD'      => 'GetExpressCheckoutDetails',
        'USER'       => $this -> ppwpp_user,
        'SIGNATURE'  => $this -> ppwpp_signature,
        'PWD'        => $this -> ppwpp_password,
        'VERSION'     => $this -> ppwpp_version,
        'TOKEN'       => $this -> token);
    
    foreach ($ppwpp_query_array as $key => $value) {
				$this -> ppwpp_query[]= $key.'='.$value;
		}
		$this -> ppwpp_query=implode('&', $this -> ppwpp_query);
    
    $this -> fetch_data();
    
    /*
    'TOKEN' => string 'EC-8JA71118VP305911L' (length=20)
  'CHECKOUTSTATUS' => string 'PaymentActionNotInitiated' (length=25)
  'TIMESTAMP' => string '2011-01-11T00:49:06Z' (length=20)
  'CORRELATIONID' => string '4ff7f358a3d22' (length=13)
  'ACK' => string 'Success' (length=7)
  'VERSION' => string '65.1' (length=4)
  'BUILD' => string '1646991' (length=7)
  'EMAIL' => string 'buyer_1294699637_per@constellation.tv' (length=37)
  'PAYERID' => string 'G8M6YJDX2UGP2' (length=13)
  'PAYERSTATUS' => string 'unverified' (length=10)
  'FIRSTNAME' => string 'Test' (length=4)
  'LASTNAME' => string 'User' (length=4)
  'COUNTRYCODE' => string 'US' (length=2)
  'SHIPTONAME' => string 'Test User' (length=9)
  'SHIPTOSTREET' => string '1 Main St' (length=9)
  'SHIPTOCITY' => string 'San Jose' (length=8)
  'SHIPTOSTATE' => string 'CA' (length=2)
  'SHIPTOZIP' => string '95131' (length=5)
  'SHIPTOCOUNTRYCODE' => string 'US' (length=2)
  'SHIPTOCOUNTRYNAME' => string 'United States' (length=13)
  'ADDRESSSTATUS' => string 'Confirmed' (length=9)
  'CURRENCYCODE' => string 'USD' (length=3)
  'AMT' => string '2.99' (length=4)
  'ITEMAMT' => string '2.99' (length=4)
  'SHIPPINGAMT' => string '0.00' (length=4)
  'HANDLINGAMT' => string '0.00' (length=4)
  'TAXAMT' => string '0.00' (length=4)
  'INSURANCEAMT' => string '0.00' (length=4)
  'SHIPDISCAMT' => string '0.00' (length=4)
  'L_NAME0' => string 'Constellation.tv Ticket for The Lottery' (length=39)
  'L_NUMBER0' => string 'purchase' (length=8)
  'L_QTY0' => string '1' (length=1)
  'L_TAXAMT0' => string '0.00' (length=4)
  'L_AMT0' => string '2.99' (length=4)
  'L_DESC0' => string 'You could win an education' (length=26)
  'L_ITEMWEIGHTVALUE0' => string '   0.00000' (length=10)
  'L_ITEMLENGTHVALUE0' => string '   0.00000' (length=10)
  'L_ITEMWIDTHVALUE0' => string '   0.00000' (length=10)
  'L_ITEMHEIGHTVALUE0' => string '   0.00000' (length=10)
  'PAYMENTREQUEST_0_CURRENCYCODE' => string 'USD' (length=3)
  'PAYMENTREQUEST_0_AMT' => string '2.99' (length=4)
  'PAYMENTREQUEST_0_ITEMAMT' => string '2.99' (length=4)
  'PAYMENTREQUEST_0_SHIPPINGAMT' => string '0.00' (length=4)
  'PAYMENTREQUEST_0_HANDLINGAMT' => string '0.00' (length=4)
  'PAYMENTREQUEST_0_TAXAMT' => string '0.00' (length=4)
  'PAYMENTREQUEST_0_INSURANCEAMT' => string '0.00' (length=4)
  'PAYMENTREQUEST_0_SHIPDISCAMT' => string '0.00' (length=4)
  'PAYMENTREQUEST_0_INSURANCEOPTIONOFFERED' => string 'false' (length=5)
  'PAYMENTREQUEST_0_SHIPTONAME' => string 'Test User' (length=9)
  'PAYMENTREQUEST_0_SHIPTOSTREET' => string '1 Main St' (length=9)
  'PAYMENTREQUEST_0_SHIPTOCITY' => string 'San Jose' (length=8)
  'PAYMENTREQUEST_0_SHIPTOSTATE' => string 'CA' (length=2)
  'PAYMENTREQUEST_0_SHIPTOZIP' => string '95131' (length=5)
  'PAYMENTREQUEST_0_SHIPTOCOUNTRYCODE' => string 'US' (length=2)
  'PAYMENTREQUEST_0_SHIPTOCOUNTRYNAME' => string 'United States' (length=13)
  'L_PAYMENTREQUEST_0_NAME0' => string 'Constellation.tv Ticket for The Lottery' (length=39)
  'L_PAYMENTREQUEST_0_NUMBER0' => string 'purchase' (length=8)
  'L_PAYMENTREQUEST_0_QTY0' => string '1' (length=1)
  'L_PAYMENTREQUEST_0_TAXAMT0' => string '0.00' (length=4)
  'L_PAYMENTREQUEST_0_AMT0' => string '2.99' (length=4)
  'L_PAYMENTREQUEST_0_DESC0' => string 'You could win an education' (length=26)
  'L_PAYMENTREQUEST_0_ITEMWEIGHTVALUE0' => string '   0.00000' (length=10)
  'L_PAYMENTREQUEST_0_ITEMLENGTHVALUE0' => string '   0.00000' (length=10)
  'L_PAYMENTREQUEST_0_ITEMWIDTHVALUE0' => string '   0.00000' (length=10)
  'L_PAYMENTREQUEST_0_ITEMHEIGHTVALUE0' => string '   0.00000' (length=10)
  'PAYMENTREQUESTINFO_0_ERRORCODE' => string '0' (length=1)
  */
  
    //echo $nvpArray;
    if($this -> nvpArray['ACK']=='Success') {
        return true;
    } else {
        //Whoops, something went wrong.  Display generic error.
        // error_handle($nvpArray);
        // Check for results and display approval or decline.
    		$this -> response_handler();
        exit;
    }
  }
  
  //Not Yet Implemented
  function DoExpressCheckoutPayment() {
    // After you receive a successful GetExpressCheckoutDetailsResponse, you would display a order review page (ie shipping information) or a
    // page on which the customer can select a shipping method, enter shipping instructions, or specify any other information necessary to
    // complete the purchase.
    //
    // When the customer clicks the “Place Order” button, send DoExpressCheckoutPaymentRequest to initiate the payment. After a successful
    // response is sent from PayPal, direct the customer to your order completion page to inform him that you received his order.
    
    $this -> ppwpp_query = null;
    $this -> nvpArray = null;
    // Mike Challis (www.carmosaic.com) added feature:
    // use an array to build the query for better visual representation in the php code.
    // and for the bracketed numbers
     $ppwpp_query_array = array(

        'METHOD'      => 'DoExpressCheckoutPayment',
        'USER'       => $this -> ppwpp_user,
        'SIGNATURE'  => $this -> ppwpp_signature,
        'PWD'        => $this -> ppwpp_password,
        'VERSION'     => $this -> ppwpp_version,
        'PAYERID'     => $this -> payerId,
        'TOKEN'       => $this -> token,
        'AMT'         => $this -> amount,
        'CURRENCYCODE' => $this -> ppwpp_currency,
        'PAYMENTACTION' => 'Sale',
        'IPADDRESS'     => $this -> cust_ip
        );

    // Mike Challis (www.carmosaic.com) added feature: bracketed numbers.
    // Bracketed numbers are length tags which allow you
    // to use the special characters of "&" and "=" in the value sent.
    // http://paypaldeveloper.com/pdn/board/message?board.id=payflow&thread.id=1008

    foreach ($ppwpp_query_array as $key => $value) {
				$this -> ppwpp_query[]= $key.'='.$value;
		}
		$this -> ppwpp_query=implode('&', $this -> ppwpp_query);

    // call function to return name-value pair
    $this -> fetch_data();
    
    // Check for results and display approval or decline.
    $this -> response_handler();
    
    }

  function DoDirectPayment() {
    $this -> ppwpp_query="";
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
    
    if ($this -> card == "American Express") {
      $this -> card = "Amex";
    }
    $ppwpp_query_array = array(

        'METHOD'       => "doDirectPayment",
        'USER'       => $this -> ppwpp_user,
        'SIGNATURE'  => $this -> ppwpp_signature,
        'PWD'        => $this -> ppwpp_password,
        'VERSION'    => $this -> ppwpp_version,
        'PAYMENTACTION'    => 'Sale',  // "Authorization" or "Sale"
        'ACCT'       => $this -> card_num,
        'CVV2'       => $this -> cvv2,
        'EXPDATE'    => $this -> expiry,
        'CREDITCARDTYPE'   => str_replace(" ","",$this -> card),
        'AMT'        => $this -> amount,
        'CURRENCYCODE'   => $this -> ppwpp_currency,
        'FIRSTNAME'  => $this -> fname,
        'LASTNAME'   => $this -> lname,
        'STREET1'     => $this -> addr1,
        'STREET2'     => $this -> addr2,
        'CITY'       => $this -> city,
        'STATE'      => $this -> state,
        'ZIP'        => $this -> zip,
        'COUNTRYCODE'    => $this -> country,
        'EMAIL'      => $this -> email,
        'CUSTIP'     => $this -> cust_ip,
        'DESC'   => $this -> custom1,
        'CUSTOM'   => $this -> custom2,
        'INVNUM'     => $this -> order_num . time(),
       	'L_NAMEn'  => $this -> lnamen,
       	'L_DESCn'  => $this -> ldescn,
       	'L_AMTn'  =>  $this -> lamtn,
       	'L_NUMBERn' =>  $this -> lnumn,
       	'L_QTYn' => $this -> lqtyn
				);
				
    // Mike Challis (www.carmosaic.com) added feature: bracketed numbers.
    // Bracketed numbers are length tags which allow you
    // to use the special characters of "&" and "=" in the value sent.

    foreach ($ppwpp_query_array as $key => $value) {
				$this -> ppwpp_query[]= $key.'='.$value;
		}
		$this -> ppwpp_query=implode('&', $this -> ppwpp_query);

    // The $order_num field is storing our unique id that we'll use in the request id header.  By storing the id
    // in this manner, we are able to allowing reposting of the form without creating a duplicate transaction.
    $this -> unique_id = $this -> order_num . "_" . time();
    
    // Call the function to send data to PayPal and return the data into an Array.
    $this -> fetch_data();

    // Check for results and display approval or decline.
    $this -> response_handler();

  }
  
  function voidTransaction() {
    $this -> ppwpp_query="";
    $ppwpp_query_array = array(

        'USER'       => $this -> ppwpp_user,
        'VENDOR'     => $this -> ppwpp_vendor,
        'PARTNER'    => $this -> ppwpp_partner,
        'PWD'        => $this -> ppwpp_password,
        'TENDER'     => 'C',  // C - Direct Payment using credit card
        'TRXTYPE'    => 'V',  // A - Authorization, S - Sale
        'ORIGID'   =>  $this -> trans_id 
        
				);
				
    // Mike Challis (www.carmosaic.com) added feature: bracketed numbers.
    // Bracketed numbers are length tags which allow you
    // to use the special characters of "&" and "=" in the value sent.

    foreach ($ppwpp_query_array as $key => $value) {
				$this -> ppwpp_query[]= $key.'['.strlen($value).']='.$value;
		}
		$this -> ppwpp_query=implode('&', $this -> ppwpp_query);

    // Call the function to send data to PayPal and return the data into an Array.
    $this -> fetch_data();

  }
  
  function refundTransaction() {
    $this -> ppwpp_query="";
    $ppwpp_query_array = array(

        'USER'       => $this -> ppwpp_user,
        'VENDOR'     => $this -> ppwpp_vendor,
        'PARTNER'    => $this -> ppwpp_partner,
        'PWD'        => $this -> ppwpp_password,
        'TENDER'     => 'C',  // C - Direct Payment using credit card
        'TRXTYPE'    => 'C',  // A - Authorization, S - Sale
        'ORIGID'   =>  $this -> trans_id 
        
				);
				
    // Mike Challis (www.carmosaic.com) added feature: bracketed numbers.
    // Bracketed numbers are length tags which allow you
    // to use the special characters of "&" and "=" in the value sent.

    foreach ($ppwpp_query_array as $key => $value) {
				$this -> ppwpp_query[]= $key.'['.strlen($value).']='.$value;
		}
		$this -> ppwpp_query=implode('&', $this -> ppwpp_query);

    // Call the function to send data to PayPal and return the data into an Array.
    $this -> fetch_data();

  }
  
  function creditTransaction() {
    $this -> ppwpp_query="";
    $ppwpp_query_array = array(

        'USER'       => $this -> ppwpp_user,
        'VENDOR'     => $this -> ppwpp_vendor,
        'PARTNER'    => $this -> ppwpp_partner,
        'PWD'        => $this -> ppwpp_password,
        'TENDER'     => 'C',  // C - Direct Payment using credit card
        'TRXTYPE'    => 'C',  // A - Authorization, S - Sale
        'ACCT'       => $this -> trans_id[0],
        'EXPDATE'    => sprintf("%04d",$this -> trans_id[1]),
        'AMT'        => sprintf("%.02f",$this -> trans_id[2])
        
				);
    // Mike Challis (www.carmosaic.com) added feature: bracketed numbers.
    // Bracketed numbers are length tags which allow you
    // to use the special characters of "&" and "=" in the value sent.
    
    foreach ($ppwpp_query_array as $key => $value) {
				$this -> ppwpp_query[]= $key.'['.strlen($value).']='.$value;
		}
		$this -> ppwpp_query=implode('&', $this -> ppwpp_query);

    // Call the function to send data to PayPal and return the data into an Array.
    $this -> fetch_data();

  }
  
  function captureTransaction() {
    $this -> ppwpp_query="";
    $ppwpp_query_array = array(

        'USER'       => $this -> ppwpp_user,
        'VENDOR'     => $this -> ppwpp_vendor,
        'PARTNER'    => $this -> ppwpp_partner,
        'PWD'        => $this -> ppwpp_password,
        'TENDER'     => 'C',  // C - Direct Payment using credit card
        'TRXTYPE'    => 'D',  // A - Authorization, S - Sale
        'ORIGID'   =>  $this -> trans_id 
        
				);
				
    // Mike Challis (www.carmosaic.com) added feature: bracketed numbers.
    // Bracketed numbers are length tags which allow you
    // to use the special characters of "&" and "=" in the value sent.

    foreach ($ppwpp_query_array as $key => $value) {
				$this -> ppwpp_query[]= $key.'['.strlen($value).']='.$value;
		}
		$this -> ppwpp_query=implode('&', $this -> ppwpp_query);

    // Call the function to send data to PayPal and return the data into an Array.
    $this -> fetch_data();

  }
  
  function voiceAuthTransaction() {
    
    $this -> ppwpp_query="";
    $ppwpp_query_array = array(

        'USER'       => $this -> ppwpp_user,
        'VENDOR'     => $this -> ppwpp_vendor,
        'PARTNER'    => $this -> ppwpp_partner,
        'PWD'        => $this -> ppwpp_password,
        'TENDER'     => 'C',  // C - Direct Payment using credit card
        'TRXTYPE'    => 'F',  // A - Authorization, S - Sale
        'ORIGID'     =>  $this -> trans_id,
        'AUTHCODE'   =>  $this -> auth_code
        
				);
				
    // Mike Challis (www.carmosaic.com) added feature: bracketed numbers.
    // Bracketed numbers are length tags which allow you
    // to use the special characters of "&" and "=" in the value sent.

    foreach ($ppwpp_query_array as $key => $value) {
				$this -> ppwpp_query[]= $key.'['.strlen($value).']='.$value;
		}
		$this -> ppwpp_query=implode('&', $this -> ppwpp_query);

    // Call the function to send data to PayPal and return the data into an Array.
    $this -> fetch_data();

  }
  
  function inquiryTransaction() {
    $this -> ppwpp_query="";
    $ppwpp_query_array = array(

        'USER'       => $this -> ppwpp_user,
        'VENDOR'     => $this -> ppwpp_vendor,
        'PARTNER'    => $this -> ppwpp_partner,
        'PWD'        => $this -> ppwpp_password,
        'TENDER'     => 'C',  // C - Direct Payment using credit card
        'TRXTYPE'    => 'I',  // A - Authorization, S - Sale
        'VERBOSITY'  => 'MEDIUM',  // A - Authorization, S - Sale
        'ORIGID'   =>  $this -> trans_id 
        
				);
				
    // Mike Challis (www.carmosaic.com) added feature: bracketed numbers.
    // Bracketed numbers are length tags which allow you
    // to use the special characters of "&" and "=" in the value sent.

    foreach ($ppwpp_query_array as $key => $value) {
				$this -> ppwpp_query[]= $key.'['.strlen($value).']='.$value;
		}
		$this -> ppwpp_query=implode('&', $this -> ppwpp_query);

    // Call the function to send data to PayPal and return the data into an Array.
    $this -> fetch_data();

  }
  
  // API functions and error handling
  function fetch_data() {
      
      $this -> context ->getLogger()->info("{Order Manager} OrderQuery:: ".$this -> ppwpp_query."\"");
      
      //$this -> ppwpp_query = "METHOD=doDirectPayment&VERSION=65.1&PWD=MKMQ8A6ANUEEKGCL&USER=sell_1294515484_biz_api1.constellation.tv&SIGNATURE=AWhYimocr0tsEMGBLVqEj2mkkkGxA-OqHvGyRmSIfI.ycj1FeIL.qSMg&PAYMENTACTION=&AMT=1.00&CREDITCARDTYPE=Visa&ACCT=4646560168762172&EXPDATE=012012&CVV2=962&FIRSTNAME=John&LASTNAME=Doe&STREET=1+Main+St&CITY=San+Jose&STATE=CA&ZIP=95131&COUNTRYCODE=US&CURRENCYCODE=USD";
      //print($this -> ppwpp_query);
      //die();
      $ch = curl_init();
      //dump($this -> submit_url);
      curl_setopt($ch, CURLOPT_URL,$this -> submit_url);
    	curl_setopt($ch, CURLOPT_VERBOSE, 1);
      curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    
    	//turning off the server and peer verification(TrustManager Concept).
    	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    	curl_setopt($ch, CURLOPT_POST, 1);
    	
    	curl_setopt($ch, CURLOPT_FORBID_REUSE, TRUE);       //forces closure of connection when done
      curl_setopt($ch, CURLOPT_TIMEOUT, 90);              // times out after 90 secs
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
      
      //setting the nvpreq as POST FIELD to curl
    	curl_setopt($ch,CURLOPT_POSTFIELDS,$this -> ppwpp_query);
    	
    
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
          $this -> PPWPP_Result_Code = -10;
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
          $this -> nvpArray[$keyval] = urldecode($valval);
          $result = substr($result,$valuepos+1,strlen($result));
      }
      
      $this -> context ->getLogger()->info("{Order Manager} Result:: ".$this -> nvpArray["L_LONGMESSAGE0"]."\"");
    
      if (preg_match("/result of a duplicate invoice ID/",$this -> nvpArray["L_LONGMESSAGE0"])) {
        $this -> nvpArray["DUPLICATE"] = true;
      }
      if ($this -> debug)
        dump($this -> nvpArray);
      
      return true;
  }
  
  function response_handler() {
      
      $this -> result_code = $this -> nvpArray['ACK']; // get the result code to validate.
      
      //dump($this -> nvpArray);
      switch (TRUE) {
        
        //For Cart Items
        //Over 1000, or AVS Mismatch, or Billing<>Shipping, RESULT 0
        //Velocity is fraud trigger
        
        
        //Approval
        case ($this -> result_code == "Success") :
            $this -> PPWPP_Result_Code = 2;
            //Check AVSADDR results
            if (isset($this -> nvpArray['AVSCODE'])) {
                if ($this -> nvpArray['AVSCODE'] == "Y") {
                    $this -> PPWPP_Result_AVS_Object["ADDR"] = true;
                    $this -> PPWPP_Result_AVS = "ADDR:Y,";
                } else {
                  $this -> PPWPP_Result_Code = 2;
                  $this -> PPWPP_Result_AVS_Object["ADDR"] = false;
                  $this -> PPWPP_Result_AVS = "ADDR:N,";
                }
            }
            //Check CVV2 results
            if (isset($this -> nvpArray['CVV2MATCH'])) {
                if ($this -> nvpArray['CVV2MATCH'] == "M") {
                   $this -> PPWPP_Result_AVS_Object["CVV2"] = true;
                   $this -> PPWPP_Result_AVS .= "CVV2:Y";
                } else {
                  $this -> PPWPP_Result_Code = 2;
                  $this -> PPWPP_Result_AVS_Object["CVV2"] = false;
                  $this -> PPWPP_Result_AVS .= "CVV2:N";
                }
            }
          break;
        // Hard decline from bank.
        case ($this -> result_code == "Failure") :
            if ($this -> nvpArray["DUPLICATE"] === true) {
              $this -> PPWPP_Result_Code = 1;
            } else {
              $this -> PPWPP_Result_Code = -1;
            }
          break;
        // Voice authorization required.
        default:
          $this -> PPWPP_Result_Code = -8;
          $this -> error = "Unused Code:: ". $this -> result_code;
          break;
      }
      
  }
  
  function getResponseVal( $val ) {
    
    switch (TRUE) {
        
        //For Cart Items
        //Over 1000, or AVS Mismatch, or Billing<>Shipping, RESULT 0
        //Velocity is fraud trigger
        
        case ($val == "Success") :
            $result_code = 2;
          break;
        // Hard decline from bank.
        case ($val == "Failure") :
            $result_code = -1;
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
  
  function error_handle($nvpArray) {
      echo '<h2>Error!</h2><p>We were unable to process your order.</p>';
      echo '<p>Error '.$nvpArray['RESULT'].': '.$nvpArray['RESPMSG'].'.</p>';
      while (list($key, $val) = each($nvpArray)) {
          echo "\n" .  $key .  ": " . $val .  "\n<br>";
      }
  }
  
  function getDailyReport($date=false, $pages=30 ) {
    $this -> ppwpp_query="";
    if ($date) {
      $iso_start = date('Y-m-d\T00:00:00\Z',  strtotime($date));
      $iso_end = date('Y-m-d\T24:00:00\Z',  strtotime($date));
    } else {
      $date = date('Y-m-d');
      $iso_start = date('Y-m-d\T00:00:00\Z', strtotime($date));
      $iso_end = date('Y-m-d\T24:00:00\Z',  strtotime($date));
    }
    $ppwpp_query_array = array(

        'METHOD'       => "TransactionSearch",
        'USER'       => $this -> ppwpp_user,
        'SIGNATURE'  => $this -> ppwpp_signature,
        'PWD'        => $this -> ppwpp_password,
        'VERSION'    => $this -> ppwpp_version,
        'STARTDATE'     => $iso_start,
        'ENDDATE'    => $iso_end
        
				);
				
    // Mike Challis (www.carmosaic.com) added feature: bracketed numbers.
    // Bracketed numbers are length tags which allow you
    // to use the special characters of "&" and "=" in the value sent.

    foreach ($ppwpp_query_array as $key => $value) {
				$this -> ppwpp_query[]= $key.'='.$value;
		}
		$this -> ppwpp_query=implode('&', $this -> ppwpp_query);
    
    //METHOD=TransactionSearch&VERSION=65.1&PWD=TZATR27EYCBQXST8&USER=jlawler_api1.constellation.tv&SIGNATURE=A8HrO9DSJADwMTj7o5hHiRXqO7kRAXf-BuElnEqO1u-pWcZ-fw0xW7-6&STARTDATE=2011-07-06T00:00:00Z&ENDDATE=2011-07-11T24:00:00Z// Call the function to send data to PayPal and return the data into an Array.
    $this -> fetch_data();

  }
  
  function mapToStyroformArray( $start_date=false, $end_date=false) {
    $doadd=true;
    $this -> PPWPP_Result_Items = array();
    
    if (count($this ->nvpArray) > 0) {
    $count=0;
		//counting no.of  transaction IDs present in NVP response arrray.
		while (isset($this ->nvpArray["L_TRANSACTIONID".$count])) { 
			$sale["rowNum"] = $count;
      $sale["user_order_date"] = $this ->nvpArray["L_TIMESTAMP".$count] . ' ' . $this ->nvpArray["L_TIMEZONE".$count];;
      $sale["user_order_id"] = $this ->nvpArray["L_TRANSACTIONID".$count];
      $sale["user_order_guid"] = $this ->nvpArray["L_TRANSACTIONID".$count];
      $sale["user_order_user_fname"] = $this ->nvpArray["L_NAME".$count];
      $sale["user_order_user_lname"] = "null";
      $sale["user_order_user_email"] = $this ->nvpArray["L_EMAIL".$count];
      $sale["user_order_total_fs"] = $this ->nvpArray["L_AMT".$count];
      $sale["user_order_fee"] = $this ->nvpArray["L_FEEAMT".$count];
      $sale["user_order_total_net"] = $this ->nvpArray["L_NETAMT".$count];
      $sale["user_order_status_code"] =$this ->nvpArray["L_STATUS".$count];
      if ($sale["user_order_status_code"] == "Completed") {
        $sale["user_order_status"] ="2";
      }
      $sale["user_order_vtype"] = "Paypal WPP " .$this ->nvpArray["L_TYPE".$count];
      $sale["user_order_payment_processor"] = "Paypal_WPP";
      
      if (($start_date) && ($sale["user_order_date"] < $start_date)) {
        //$doadd=false;
      }
      if (($end_date) && ($sale["user_order_date"] > $end_date)) {
        //$doadd=false;
      }
      if ($doadd)
        $this -> PPWPP_Result_Items[] = $sale;
      
      $count++; 
      
    }}
    
    return $this -> PPWPP_Result_Items;
  }
  
}

?>
