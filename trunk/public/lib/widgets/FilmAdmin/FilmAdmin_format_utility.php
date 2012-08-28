<?php

class FilmAdmin_format_utility {
  
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
      $this -> map -> createSingleElementByPath("criteria","//criteria[@column='object_type']",array("value"=>$this -> date,"column"=>"film_created_at","type"=>"AND","constant"=>"native"));
    }
    
    if ($this -> term == '') {
      $this -> map -> removeSingleElementByPath("//criteria[@column='film_id']");
      $this -> map -> removeSingleElementByPath("//criteria[@column='film_total_seats']");
      $this -> map -> removeSingleElementByPath("//criteria[@column='film_name']");
      $this -> map -> removeSingleElementByPath("//criteria[@column='film_short_name']");
      $this -> map -> removeSingleElementByPath("//criteria[@column='film_synopsis']");
      $this -> map -> removeSingleElementByPath("//criteria[@column='film_info']");
      $this -> map -> removeSingleElementByPath("//criteria[@column='film_maker_message']");
      $this -> map -> removeSingleElementByPath("//criteria[@column='film_movie_file']");
      $this -> map -> removeSingleElementByPath("//criteria[@column='film_production_company']");
      $this -> map -> removeSingleElementByPath("//criteria[@column='film_makers']");
      $this -> map -> removeSingleElementByPath("//criteria[@column='film_text']");
      $this -> map -> removeSingleElementByPath("//criteria[@column='film_trailer_file']");
    }
    
    //$this -> map -> saveXML();
    return $this -> map;
    
  }
  
}
?>
