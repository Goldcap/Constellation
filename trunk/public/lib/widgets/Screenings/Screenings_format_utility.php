<?php

class Screenings_format_utility {
  
  public $context;
  public $result;
  public $terms;
  public $maplinks;
  public $sids; 
  public $security;
  
  function __construct( $context ) {
    $this -> context = $context;
    $this -> terms = false;
    $this -> skip = false;
    $this -> security = new TheaterSecurity_PageWidget($this -> context);
  }
  
  function getGeoBlocked( $item ) {
    return $this -> security -> checkGeoBlock($item["screening_film_geoblocking_type"],REMOTE_ADDR());
  }
  
  function getTimeRemaining( $value ) {
    $var = daySpan(now(),formatDate($value,"TSRound"));
    return (30 + $var);
  }
  
  function getConf( $xmlObj ) {
    $this -> map = $xmlObj;
    
    if (count($this -> sids) > 0) {
			$this -> map -> removeSingleElementByPath("//criteria[@column='-screening_id']");
      foreach($this -> sids as $sid) {
				$this -> map -> createSingleElementByPath("criteria","//criteria[@column='screening_end_time']",array("value"=>$sid,"column"=>"-screening_id","type"=>"AND","constant"=>"native"));
			}
		}
    //$this -> map -> saveXML();
    return $this -> map;
    
  }
  
}
?>
