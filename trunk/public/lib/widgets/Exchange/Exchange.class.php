<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/Exchange_crud.php';
  
   class Exchange_PageWidget extends Widget_PageWidget {
	
  var $XMLForm;
	var $crud;
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
    $this -> crud = new AudienceCrud( $context );
    parent::__construct( $context );
  }

	function parse() {
	 
	 if (! $this -> postVar("ticket")) {
	  $result = new StdClass();
	  $result -> exchangeResponse = array("result"=>"failure","message"=>"Your code wasn't found, please try again.");
    $this -> widget_vars["result"] = json_encode($result);
    return $this -> widget_vars;
   }
   
   $promoc = new PromoCodeCrud( $this -> context );
   $vars = array("promo_code_code"=>trim($this -> postVar("ticket")));
   $promoc -> checkUnique( $vars );
   if ($promoc -> PromoCode -> getPromoCodeId() > 0) {
   		//If the code has been used too often
		if (($promoc -> PromoCode -> getFkFilmId() != 0) && ($this -> getVar("film") != $promoc -> PromoCode -> getFkFilmId())) {
			$result = new StdClass();
			$result -> exchangeResponse = array("result"=>"failure","message"=>"That code is not available for this film, please try a different code.");
	    $this -> widget_vars["result"] = json_encode($result);
	    return $this -> widget_vars;
		} else if (($promoc -> PromoCode -> getPromoCodeTotalUsage() > 0) && ($promoc -> PromoCode -> getPromoCodeUses() >= $promoc -> PromoCode -> getPromoCodeTotalUsage())) {
		 	$result = new StdClass();
			 $result -> exchangeResponse = array("result"=>"failure","message"=>"That code is no longer available, please try again.");
	    $this -> widget_vars["result"] = json_encode($result);
	    return $this -> widget_vars;
		} else {
	    $result = new StdClass();
	    switch($promoc -> PromoCode -> getPromoCodeType()) {
		   case  1:
			 $result -> exchangeResponse = array("result"=>"promo","type"=>$promoc -> PromoCode -> getPromoCodeType(),"message"=>"We've given you a ".$promoc -> PromoCode -> getPromoCodeValue()."% discount.","discount"=>$promoc -> PromoCode -> getPromoCodeValue());
	    	break;
			 case 2:
	     $result -> exchangeResponse = array("result"=>"promo","type"=>$promoc -> PromoCode -> getPromoCodeType(),"message"=>"We've given you a $".sprintf("%.02f",$promoc -> PromoCode -> getPromoCodeValue())." discount.","discount"=>$promoc -> PromoCode -> getPromoCodeValue());
	    	break;
	     case 3:
	     $result -> exchangeResponse = array("result"=>"promo","type"=>$promoc -> PromoCode -> getPromoCodeType(),"message"=>"Your code has been validated.","discount"=>"free");
	    	break;
			}
	    $this -> widget_vars["result"] = json_encode($result);
	    return $this -> widget_vars;
	  }
   }
   
	 $vars = array("audience_invite_code"=>$this -> postVar("ticket"),"fk_user_id"=>$this -> sessionVar("user_id"));
	 $this -> crud -> checkUnique( $vars );
	 if (! $this -> crud -> Audience -> getAudienceId()) {
	  $result = new StdClass();
	  $result -> exchangeResponse = array("result"=>"failure","message"=>"Your code wasn't found, please try again.");
    $this -> widget_vars["result"] = json_encode($result);
    return $this -> widget_vars;
   }
   
	 if (($this -> crud -> Audience -> getAudiencePaidStatus() == 2) && ($this -> crud -> Audience -> getAudienceStatus() == 1)) {
    $result = new StdClass();
	  $result -> exchangeResponse = array("result"=>"failure","message"=>"This ticket was already used, please try another.");
    $this -> widget_vars["result"] = json_encode($result);
    return $this -> widget_vars;
   }
   
   if (($this -> crud -> Audience -> getAudiencePaidStatus() == 2) && ($this -> crud -> Audience -> getAudienceStatus() == 0)) {
    //Get this Screening
    sfConfig::set("screening_unique_key",$this -> postVar("screening"));
    $screen = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Exchange/query/ScreeningByKey_list_datamap.xml");
    //Update the PAYEMENT with the New Screening
    $payment = new PaymentCrud( $this -> context );
    $vars = array("fk_audience_id"=>$this -> crud -> Audience -> getAudienceId(),"fk_screening_id"=>$this -> crud -> Audience -> getFkScreeningId(),"fk_user_id"=>$this -> sessionVar("user_id"));
	  $payment -> checkUnique($vars);
	  
    if($payment -> Payment -> getPaymentId() > 0) {
      
      $payment -> setFkScreeningId($screen["data"][0]["screening_id"]);
  	  $payment -> save();
  	  
  	  //Update the TICKET with the New Screening
      $this -> crud -> setFkScreeningId($screen["data"][0]["screening_id"]);
      $this -> crud -> setFkScreeningUniqueKey($screen["data"][0]["screening_unique_key"]);
      $this -> crud -> setAudienceUpdatedAt(now());
      $this -> crud -> save();
	    
      $result = new StdClass();
      
      if ($payment -> Payment -> getPaymentAmount() == 0) {
				$amount = $screen["data"][0]["screening_film_ticket_price"];
			} else {
				$amount = $payment -> Payment -> getPaymentAmount();
			}
			$result -> exchangeResponse = array("result"=>"promo","type"=>2,"message"=>"We've given you a $".sprintf("%.02f",$amount)." discount.","discount"=>$amount);
	    //$result -> exchangeResponse = array("result"=>"success","theurl"=>$screen["data"][0]["screening_unique_key"]."/".$this -> crud -> Audience -> getAudienceInviteCode());
      $this -> widget_vars["result"] = json_encode($result);
      return $this -> widget_vars;
	   
    }
    
    $result = new StdClass();
	  $result -> exchangeResponse = array("result"=>"failure","message"=>"There was an error, please contact an administrator.");
    $this -> widget_vars["result"] = json_encode($result);
    return $this -> widget_vars;
   }
   
	 //return $this -> widget_vars;
   
  }


}

?>
