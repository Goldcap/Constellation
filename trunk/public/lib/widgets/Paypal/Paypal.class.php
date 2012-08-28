<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/Paypal_crud.php';
  
   class Paypal_PageWidget extends Widget_PageWidget {
	
  var $XMLForm;
	var $crud;
	var $cancelUrl;
	var $module;
	var $key;
	var $cost;
	
	var $errorURL;
	var $successURL;
	var $cancelURL;
	var $obj;
	var $amount;
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));;
    parent::__construct( $context );
  }

	function parse() {
	   $this -> module = $this -> getVar("rev");
	   $this -> vars = $this -> getVar("vars");
	   $vars = explode("-",$this -> vars);
	   $this -> item = $vars[1];
	   $this -> key = $vars[0];
	   $this -> cost = str_replace("_",".",$vars[2]);
	   if (! $this -> as_service) {
      die();
     }
     
     switch ($this -> module) {
      case "screening":
      	//Object is the screening
        if ($this -> key == "hbr") {
					  sfConfig::set("film",$this -> item);
   					$film = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Code/query/Film_list_datamap.xml");
  
						$order = new OrderManager_PageWidget( $this -> widget_vars, $this -> page_vars, $this -> context, null );
						//Set a user for this order
						
						$order -> postOrderUser();
    				//Create the Order
    				$order -> insertOrder($order -> orderuser, $film);
						
						//This is five minutes, since the user needs to go to Paypal and complete the order
						$thedate = strtotime(now()) + 300;
			      $order -> insertHostItem( $order -> orderuser, $film, formatDate($thedate,"MDY-"), formatDate($thedate,"time"));
			      sfConfig::set("screening_unique_key",$order -> orderitems[0] -> Screening -> getScreeningUniqueKey());
			      $this -> obj = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Theater/query/ScreeningByKeySQL_list_datamap.xml");
			    	if ($this -> obj["data"][0]["screening_unique_key"] != "") {
			    		$this -> key = $this -> obj["data"][0]["screening_unique_key"];
			    	} else {
							die("HBR Screening Insert Error");
						}
						$this -> vars = $this -> key."-".$vars[1]."-".$vars[2];
          $this -> cancelURL = "/boxoffice/screening/none/?film=".$this -> item .'&dohbr=true';
          $this -> errorURL = "/boxoffice/screening/none/".$this -> item."/purchase?err=cc";
				} else {
					sfConfig::set("screening_unique_key",$this -> key);
        	$this -> obj = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Theater/query/ScreeningByKey_list_datamap.xml");
          $this -> cancelURL = "/boxoffice/screening/".$this -> item;
          $this -> errorURL = "/boxoffice/screening/".$this -> key."?err=cc";
				}
        
        $this -> successURL = "/boxoffice/screening/".$this -> key."/paypal/true/";
        break;
      case "host":
        //Object is screening
        sfConfig::set("screening_unique_key",$this -> key);
        $this -> obj = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Host/query/Screening_list_datamap.xml");
        //.$this -> obj["data"][0]["fk_film_id"].
        $this -> errorURL = "/film/".$this -> item."/host_purchase/".$this -> key."?err=cc";
        $this -> successURL = "/film/".$this -> item."/host_confirm/".$this -> key;
        $this -> cancelURL = "/film/".$this -> item."/host_purchase/".$this -> key;
        break;
      case "subscription":
        //Object is the Subscription
        sfConfig::set("subscription_unique_key",$this -> key);
        $this -> obj = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Subscription/query/Subscription_list_datamap.xml");
        $this -> errorURL = "/".$this -> module."/purchase/".$this -> key."?err=cc";
        $this -> successURL = "/".$this -> module."/invite/".$this -> key;
        $this -> cancelURL = "/".$this -> module."/purchase/".$this -> key;
        $this -> amount = $this -> obj["data"][0]["subscription_total_price"];
        break;
    }
    
	   switch($this -> getVar("id")) {
      case "return":
        $this -> getExpressDetails();
        break;
      case "cancel":
        $this -> cancelExpressService();
        break;
      default:
        $this -> callExpressService();
        break;
     }
	 
  }
  
  function cancelExpressService() {
    switch ($this -> module) {
      case "screening":
        $this -> redirect("/boxoffice/screening/".$this -> key);
        break;
      case "host":
        $this -> redirect("/film/".$this -> item."/host_purchase/".$this -> key);
        break;
      case "subscription":
        $this -> redirect("/".$this -> module."/purchase/".$this ->key);
        break;
    }
  }
  
  //First Step, we get a token and auth, and forward the user to paypal
  function callExpressService() {
    
    $pp = new PaypalExpressOrders( null, $this -> context );
    return $pp -> callExpressService( $this -> obj["data"][0], $this -> key, $this -> item, $this -> vars, $this -> module );
    
  }
  
  //Second step, the user has finished at paypal, 
  //They return, we make sure the order was approved via CURL
  //Then Capture the money via CURL
  function getExpressDetails() {
    
    $pp = new PaypalExpressOrders( null, $this -> context );
    return $pp -> getExpressDetails( $this -> obj["data"][0], $this ->key, $this -> module, $this -> item );
  }
  

}
?>
