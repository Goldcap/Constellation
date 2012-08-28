<?php

class ScreeningsUpcoming_format_utility {
  
  public $context;
  public $result;
  public $terms;
  public $maplinks;
  public $sids;
  
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
    
    if (count($this -> sids) > 0) {
			$this -> map -> removeSingleElementByPath("//criteria[@column='-upcoming_id']");
      foreach($this -> sids as $sid) {
				$this -> map -> createSingleElementByPath("criteria","//criteria[@column='upcoming_end_time']",array("value"=>$sid,"column"=>"-upcoming_id","type"=>"AND","constant"=>"native"));
			}
		}
    //$this -> map -> saveXML();
    return $this -> map;
    
  }
  
}
?>
