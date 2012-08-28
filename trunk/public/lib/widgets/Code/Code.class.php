<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/Code_crud.php';
  
  class Code_PageWidget extends Widget_PageWidget {
	
  
  var $XMLForm;
	var $crud;
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
    $this -> crud = new SponsorCodeCrud( $context );
    parent::__construct( $context );
  }

	function parse() {
	 
    if (! $this -> postVar("ticket")) {
      $result = new StdClass();
      $result -> codeResponse = array("result"=>"failure","message"=>"Your code wasn't found, please try again.");
      $this -> widget_vars["result"] = json_encode($result);
      return $this -> widget_vars;
    }
    
    $sql = "select * from sponsor_code where fk_film_id = ? and sponsor_code = ? limit 1";
    $args[0] = $this -> postVar("film");
    $args[1] = $this -> postVar("ticket");
    
    $res =  $this -> propelArgs($sql,$args);
    while ($row = $res->fetch()) {
      $code = $row;
    }
    
    if (! $code) {
      $result = new StdClass();
      $result -> codeResponse = array("result"=>"failure","message"=>"Your code wasn't found, please try again.");
      $this -> widget_vars["result"] = json_encode($result);
      return $this -> widget_vars;
    }
    
    //If the code use is zero, give the code unimited screening usage
    if (($code["sponsor_code_use"] > 0) && ($code["sponsor_code_use"] < $code["sponsor_code_use_count"])) {
      $result = new StdClass();
      $result -> codeResponse = array("result"=>"failure","message"=>"This code has been used the maximum of ".$code["sponsor_code_use"]." times and is no longer valid.");
      $this -> widget_vars["result"] = json_encode($result);
      return $this -> widget_vars;
    }
    
    //If the code use is zero, give the code unimited screening usage
    if (($code["sponsor_code_start_date"] > formatDate(null,"TSTZ")) || ($code["sponsor_code_end_date"] < formatDate(null,"TSTZ"))) {
      $result = new StdClass();
      $result -> codeResponse = array("result"=>"failure","message"=>"This ticket is no longer valid, please try another.");
      $this -> widget_vars["result"] = json_encode($result);
      return $this -> widget_vars;
    }
    
    sfConfig::set("film",$this -> postVar("film"));
    $film = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Code/query/Film_list_datamap.xml");

    //This screening is ok for view count, film, and dates
    $order = new OrderManager_PageWidget( $this -> widget_vars, $this -> page_vars, $this -> context, null );
    
    $uid = $order -> createOrderUser( $code["sponsor_code_user_fname"], $code["sponsor_code_user_lname"], $code["sponsor_code_user_email"], $code["sponsor_code_user_username"], $code["fk_user_id"], $code["sponsor_code_id"], $code["sponsor_code_spawn_new_users"], $film );
    
    //Create the Order
    $order -> insertOrder($order -> orderuser, $film);
    
    //Create the Screening if Necessary
    if ($this -> postVar("screening")) {
      sfConfig::set("screening_unique_key",$this -> postVar("screening"));
      $screen = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Theater/query/ScreeningByKey_list_datamap.xml");
    } else {
      //Create new screening
      $thedate = strtotime(now()) + 60;
      $order -> insertHostItem( $order -> orderuser, $film, formatDate($thedate,"MDY-"), formatDate($thedate,"time"));
      sfConfig::set("screening_unique_key",$order -> orderitems[0] -> Screening -> getScreeningUniqueKey());
      $screen = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Theater/query/ScreeningByKeySQL_list_datamap.xml");
    }
    
    if ($screen["data"][0]["screening_unique_key"] == "") {
      $result = new StdClass();
      $result -> codeResponse = array("result"=>"failure","message"=>"There was an error, please contact an administrator.");
      $this -> widget_vars["result"] = json_encode($result);
      return $this -> widget_vars;
    }
    
    //Check for this SKU and User, and redirect to invite if exists
    $oid = $order -> checkPrior( 'screening', $uid, $screen["data"][0]["screening_unique_key"] );
    
    if (! $oid) {
    	//Create the "Audience"
    	$audience = $order -> insertScreeningItem( $screen,$this -> postVar("ticket") );
    
    } else {
			
			$payment = new PaymentCrud($this -> context);
			$payment -> populate("payment_unique_code",$oid);
			$audience = new AudienceCrud( $this -> context );
			$audience -> hydrate( $payment -> Payment -> getFkAudienceId() );
			$audience -> Audience -> setAudienceHmacKey( null );
			$audience -> Audience -> setAudienceStatus( 0 );
			$audience -> Audience -> save();
			
		}
		
		//Update the "Sponsor Code" Table
    $this -> crud -> hydrate($code["sponsor_code_id"]);
    $this -> crud -> setFkScreeningId( $screen["data"][0]["screening_id"] );
    $this -> crud -> setFkScreeningUniqueKey( $screen["data"][0]["screening_unique_key"] );
    if ($code["sponsor_code_spawn_new_users"] == 0) {
      $this -> crud -> setFkUserId( $this -> sessionVar("user_id") );
    } else {
      $this -> crud -> setFkUserId( null );
    }
    $this -> crud -> setSponsorCodeUseCount( $this -> crud -> SponsorCode -> getSponsorCodeUseCount() + 1 );
    $this -> crud -> save();
    
		//Insert into an audit trail of some sort
    $usage = new SponsorCodeUsageCrud( $this -> context );
    $usage -> setFkSponsorCodeId( $this -> crud -> SponsorCode -> getSponsorCodeId() );
    $usage -> setFkUserId( $this -> sessionVar("user_id") );
    $usage -> setSponsorCodeUsageDate( now() );
    $usage -> save();
		
    $result = new StdClass();
    $result -> codeResponse = array("result"=>"success","theurl"=>$audience -> Audience -> getAudienceInviteCode()."|".$this -> postVar("film"),"user_id"=>$this -> sessionVar("user_id"));
    $this -> widget_vars["result"] = json_encode($result);
    return $this -> widget_vars;
  }

}

?>
