<?php

class PaypalInterface {

  var $paypal_api_username;
  var $paypal_api_password;
  var $paypal_api_signature;
  var $paypal_api_token;
  var $paypal_api_environment;
  var $paypal_api_url;
  var $vars;
  var $response;
  var $result;
  
  var $RESPONSE_FIELDS;
  var $result_items;
  
  /*
  paypal_api_environment:         production
  paypal_api_username:            paypal_api1.tattoojohnny.com
  paypal_api_password:            ZUYXEBJ9NBQVNXUJ
  paypal_api_signature:           ASIdeafNapkSYB7A..nQqFEfa6XWAGEhMeYF3BJ
  paypal_token:                   0OWlBr9lN52hNA_xg9RC_1d8hHTggyBAmC-3riC3OL6ST8eSmyKJBJzR0F8
  */
  
  function __construct() {
    
    $this -> paypal_api_username = sfConfig::get("app_paypal_api_username");
    $this -> paypal_api_password = sfConfig::get("app_paypal_api_password");
    $this -> paypal_api_signature = sfConfig::get("app_paypal_api_signature");
    $this -> paypal_api_token = sfConfig::get("app_paypal_token");
    
    switch (sfConfig::get("app_paypal_api_environment")) {
      case "production":
        $this -> paypal_api_url = "https://api-3t.paypal.com/nvp";
        break;
      default:
        $this -> paypal_api_url = "https://api-3t.sandbox.paypal.com/nvp";
        break;
    }
    
    $this -> RESPONSE_FIELDS =  array('L_TIMESTAMP',
                                      'L_TIMEZONE',
                                      'L_TYPE',
                                      'L_EMAIL',
                                      'L_NAME',
                                      'L_TRANSACTIONID',
                                      'L_STATUS',
                                      'L_AMT',
                                      'L_CURRENCYCODE',
                                      'L_FEEAMT',
                                      'L_NETAMT');
  }
  
  function parse() {
  
    $this -> init();
    $curl = new Curl();
    $curl->options['CURLOPT_SSL_VERIFYPEER'] = 0;
    $this -> response = $curl->post($this -> paypal_api_url, $this -> vars);
    
    $this -> parseResult();
    
  }
  
  function init() {
    
    $this -> vars["USER"] = $this -> paypal_api_username;
    $this -> vars["PWD"] = $this -> paypal_api_password;
    $this -> vars["SIGNATURE"] = $this -> paypal_api_signature;
    $this -> vars["VERSION"] = "65.0";
    
  }
  
  function parseResult() {
    
    $temp = explode("&",$this -> response -> body);
    
    foreach ($temp as $item) {
      $part = explode("=",$item);
      $this -> result[$part[0]] = urldecode($part[1]);
    }
  }
  
  function addVar( $name, $value ) {
  
    $this -> vars[$name] = $value;
  
  }
  
  function getTransactionDetails( $tx ) {
  
    $this -> vars["METHOD"] = "GetTransactionDetails";
    $this -> vars["TRANSACTIONID"] = $tx;
    
    $this -> parse();
  
  }
  
  //https://cms.paypal.com/us/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_api_nvp_r_TransactionSearch#id085U80P0OPF
  function getTransactionDetailsByEmail( $email, $start, $end ) {
    
    $this -> vars["METHOD"] = "TransactionSearch";
    //Dates are in GMT 
    $this -> vars["STARTDATE"] = dateAdd($start,7,'H');
    $this -> vars["ENDDATE"] = dateAdd($end,7,'H');
    $this -> vars["EMAIL"] = $email;
    
    $this -> parse();
    
    $this -> parseTransactionResults();
    
  }
  
  //https://cms.paypal.com/us/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_api_nvp_r_TransactionSearch#id085U80P0OPF
  function issueRefund( $tx, $note="From API" ) {
    
    $this -> vars["METHOD"] = "RefundTransaction";
    $this -> vars["REFUNDTYPE"] = "Full"; //Can be "Partial", if you include the "AMT" field
    $this -> vars["NOTE"] = $note;
    
    $this -> parse();
    
  }
  
  //https://cms.paypal.com/us/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_api_nvp_r_TransactionSearch#id085U80P0OPF
  function searchTransactions( $start, $end ) {
    
    $this -> vars["METHOD"] = "TransactionSearch";
    //We are in EST, but these are recorded in Paypal as PST, so it's a 3 hour difference
    
    $this -> vars["STARTDATE"] = dateAdd($start,3,'H');
    $this -> vars["ENDDATE"] = dateAdd($end,3,'H');
    
    $this -> parse();
    $this -> parseTransactionResults();
    
  }
  
  function parseTransactionResults() {
    
    /*
     'L_TIMESTAMP0'=> string '2009-10-08T08:12:21Z' (length=20)
    'L_TIMEZONE0' => string 'GMT' (length=3)
    'L_TYPE0' => string 'Payment' (length=7)
    'L_EMAIL0' => string 'aaron.feast@hotmail.com' (length=23)
    'L_NAME0' => string 'AARON FEAST' (length=11)
    'L_TRANSACTIONID0' => string '5HL069802K457691S' (length=17)
    'L_STATUS0' => string 'Completed' (length=9)
    'L_AMT0' => string '14.34' (length=5)
    'L_CURRENCYCODE0' => string 'USD' (length=3)
    'L_FEEAMT0' => string '-0.86' (length=5)
    'L_NETAMT0' => string '13.48' (length=5)
    'TIMESTAMP' => string '2009-10-08T20:53:50Z' (length=20)
    'CORRELATIONID' => string 'a757b2a9e5e2a' (length=13)
    'ACK' => string 'Success' (length=7)
    'VERSION' => string '56.0' (length=4)
    'BUILD' => string '000000' (length=6)
    */
    
    $i=0;
    foreach ($this -> result as $key=>$value) {
    	if (left($key,11) == "L_TIMESTAMP") {
    	  $item["EST_DATE"] = formatDate($this -> result["L_TIMESTAMP".$i],"SM");
        foreach ($this -> RESPONSE_FIELDS as $field) {
          $item[$field] = $this -> result[$field.$i];
        }
        $this -> result_items[$i] = $item;
        $item = null;
        $i++;
      }
    }
    
  }
  
  function mapToStyroformArray( $start=false, $end=false ) {
    
    if (! $start) {
      $start = formatDate(null,"TSFloor");
    }
    
    if (! $end) {
      $start = formatDate(null,"Ceiling");
    }
    
    if (is_array($this -> result_items)){
    foreach($this -> result_items as $ppresult) {
      $tmp = str_replace(" PM",":00",$ppresult['EST_DATE']);
      $pts = explode(" ",$tmp);
      $dt = explode("/",$pts[0]);
      $item["user_order_date"] = $dt[2]."-".$dt[0]."-".$dt[1]." ".$pts[1];
      $item["user_order_timestamp"] = formatDate($ppresult['L_TIMESTAMP'],"TSEastern");
      //($ppresult['L_AMT'] > 0) && 
      $item["user_order_id"] = $ppresult['L_TRANSACTIONID'];
      $item["user_order_user_fname"] = $ppresult['L_NAME'];
      $item["user_order_user_lname"] = "null";
      $item["user_order_user_email"] = $ppresult['L_EMAIL'];
      $item["user_order_guid"] = $ppresult['L_TRANSACTIONID'];
      $item["user_order_total_fs"] = $ppresult['L_AMT'];
      $item["user_order_fee"] = $ppresult["L_FEEAMT"];
      $item["user_order_total_net"] = $ppresult["L_NETAMT"];
      $item["user_order_status"] = 2;
      $item["user_order_transaction_status"] = $ppresult["L_STATUS"];
      $item["user_order_vtype"] = "Paypal ".$ppresult["L_TYPE"];
      $item["user_order_payment_processor"] = "Paypal";
      $rs[] = $item;
   }}
   
   if (! is_array($rs)) {
    $rs = array();
   }
    //die();
    return $rs;
  }
  
}
?>
