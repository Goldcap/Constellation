<?php

class UserAdminSearch_format_utility {
  
  public $context;
  public $result;
  public $terms;
  public $maplinks;
  public $term;
  public $date;
  public $skip;
  
  function __construct( $context ) {
    $this -> context = $context;
    $this -> terms = false;
    $this -> skip = false;
  }
  
  function getTimeRemaining( $value ) {
    $var = daySpan(now(),formatDate($value,"TSRound"));
    return (30 + $var);
  }
  
  function getConf( $xmlObj ) {
    $this -> map = $xmlObj;
    
		if ($this -> date) {
    	$this -> map -> createSingleElementByPath("criteria","//criteria[@column='user_lname']",array("scope"=>"PROCESS","value"=>$this -> date,"column"=>"user_created_at","type"=>"daterange"));
    }
    
    //$this -> map -> saveXML();
    return $this -> map;
    
  }
  
}
?>
