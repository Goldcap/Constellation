<?php

class CodeAdmin_format_utility {
  
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
    
    
    if ($this -> film > 0) {
      $this -> map -> createSingleElementByPath("criteria","//criteria[@column='promo_code_id']",array("value"=>$this -> film,"column"=>"fk_film_id"));
    }
    
    if ($this -> term == '') {
      $this -> map -> removeSingleElementByPath("//criteria[@column='promo_code_code']"); 
      $this -> map -> removeSingleElementByPath("//criteria[@column='promo_code_code']");
    }
    
    //$this -> map -> saveXML();
    return $this -> map;
    
  }
  
}
?>
