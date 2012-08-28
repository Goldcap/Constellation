<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/ContactValidator_crud.php';
  
  class ContactValidator_PageWidget extends Widget_PageWidget {
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
    parent::__construct( $context );
  }

	function parse() {
    
    /*
    // an optional sender
    $sender = 'no-reply@constellation.tv';
    // instantiate the class
    $SMTP_Validator = new SMTP_validateEmail();
    // turn on debugging if you want to view the SMTP transaction
    //$SMTP_Validator->debug = true;
    // do the validation
    $results = $SMTP_Validator->validate(array($this -> getVar("email")), $sender);
    // view results
    //echo $email.' is '.($results[$email] ? 'valid' : 'invalid')."\n";
    $emailValidationResult = new stdClass();
    $valid = ($results[$this -> getVar("email")] ? 'valid' : 'invalid');
    $emailValidationResult = array("result"=>$valid);
    */
    $emailValidationResult = array("result"=>$valid);
    $this -> widget_vars["result"] = json_encode($emailValidationResult);
    return $this -> widget_vars;
    
  }

}
?>
