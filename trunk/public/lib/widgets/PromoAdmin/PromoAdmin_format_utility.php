<?php

class PromoAdmin_format_utility {
  
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
    
    if ($this -> startdate) {
      $this -> map -> createSingleElementByPath("criteria","//criteria[@column='object_type']",array("value"=>$this -> date,"column"=>"promo_start_date","type"=>"AND","constant"=>"native"));
    }
    
    if ($this -> enddate) {
      $this -> map -> createSingleElementByPath("criteria","//criteria[@column='object_type']",array("value"=>$this -> date,"column"=>"promo_end_date","type"=>"AND","constant"=>"native"));
    }
    
    //$this -> map -> saveXML();
    return $this -> map;
    
  }
  
}
?>
