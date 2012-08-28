<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/UserPayment_crud.php';
  
   class UserPayment_PageWidget extends Widget_PageWidget {
	
  var $XMLForm;
	var $crud;
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
    parent::__construct( $context );
  }

	function parse() {
	
	 $user = getUserById( $this -> getUser() -> getAttribute("user_id") );
   
   if ((sfConfig::get("sf_environment") == "dev") || (sfConfig::get("sf_environment") == "stage")) {
    
      //TESTING DATA
      $this -> post["email"] = $this -> sessionVar("user_email");
      $this -> post["email_confirm"] = $this -> sessionVar("user_email");
      $this -> post["b_address1"] = "1 Main Street";
      $this -> post["b_address2"] = "";
      $this -> post["b_city"] = "San Jose";
      $this -> post["b_state"] = "CA";
      $this -> post["b_zipcode"] = "95131";
      $this -> post["b_country"] = "US";
      $this -> post["cc_exp_month"] = "3";
      $this -> post["cc_exp_year"] = "2012";
      $this -> post["card_number"] = "4220496880267261";
      $this -> post["security_code"] = "962";
   
   }
   
   if ($user) {
      $this -> post["first_name"] = $user -> getUserFname();
      $this -> post["last_name"] = $user -> getUserLname();
      $this -> post["user_username"] = $user -> getUserUsername();
      $this -> post["email"] = $user -> getUserEmail();
      $this -> post["email_confirm"] = $user -> getUserEmail();
      
      //Check for email in database
      $c = new Criteria();
      $c->add(PaymentPeer::FK_USER_ID,$user -> getUserId());
      $c_tmp1 = $c->getNewCriterion(PaymentPeer::PAYMENT_B_ADDR_1, null, Criteria::ISNOTNULL);	
      $c_tmp1->addAnd($c->getNewCriterion(PaymentPeer::PAYMENT_B_ADDR_1, "", Criteria::NOT_EQUAL));	
      $c->addAnd($c_tmp1) ;
      
      $c -> setLimit(1);
      $c -> addDescendingOrderByColumn("payment_id");
      $payment = PaymentPeer::doSelect($c);
                    
			if($payment) {
        if ($user -> getUserEmail() == "") {
          $this -> post["email"] = $payment[0] -> getPaymentEmail();
          $this -> post["email_confirm"] = $payment[0] -> getPaymentEmail();
        }
        $this -> post["b_address1"] = $payment[0] -> getPaymentBAddr1();
        $this -> post["b_address2"] = $payment[0] -> getPaymentBAddr2();
        $this -> post["b_city"] = $payment[0] -> getPaymentBCity();
        $this -> post["b_state"] = $payment[0] -> getPaymentBState();
        $this -> post["b_zipcode"] = $payment[0] -> getPaymentBZipcode();
        $this -> post["b_country"] = $payment[0] -> getPaymentBCountry();
      }
    }
    return $this -> post;
    
  }

}

?>
