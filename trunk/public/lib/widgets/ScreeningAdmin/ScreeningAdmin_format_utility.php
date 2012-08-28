<?php

class ScreeningAdmin_format_utility {
  
  public $context;
  public $result;
  public $terms;
  public $maplinks;
  public $term;
  public $date;
  public $skip;
  public $film;
  
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
      $this -> map -> createSingleElementByPath("criteria","//criteria[@column='object_type']",array("value"=>$this -> date,"column"=>"screening_date","type"=>"AND","constant"=>"native"));
    }
    
    if ($this -> term == '') {
      $this -> map -> removeSingleElementByPath("//criteria[@column='screening_film_id']");
      $this -> map -> removeSingleElementByPath("//criteria[@column='screening_film_total_seats']");
      $this -> map -> removeSingleElementByPath("//criteria[@column='screening_film_name']");
      $this -> map -> removeSingleElementByPath("//criteria[@column='screening_unique_key']");
      $this -> map -> removeSingleElementByPath("//criteria[@column='screening_film_short_name']");
      $this -> map -> removeSingleElementByPath("//criteria[@column='screening_film_synopsis']");
      $this -> map -> removeSingleElementByPath("//criteria[@column='screening_film_info']");
      $this -> map -> removeSingleElementByPath("//criteria[@column='screening_film_maker_message']");
      $this -> map -> removeSingleElementByPath("//criteria[@column='screening_film_movie_file']");
      $this -> map -> removeSingleElementByPath("//criteria[@column='screening_film_production_company']");
      $this -> map -> removeSingleElementByPath("//criteria[@column='screening_film_makers']");
      $this -> map -> removeSingleElementByPath("//criteria[@column='screening_text']");
      $this -> map -> removeSingleElementByPath("//criteria[@column='screening_film_trailer_file']");
    }
    
    if ($this -> film > 0) {
        $this -> map -> createSingleElementByPath("criteria","//criteria[@column='object_type']",array("value"=>$this -> film,"column"=>"screening_film_id","type"=>"AND","constant"=>"native"));
    }
    
    //$this -> map -> saveXML();
    return $this -> map;
    
  }
  
}
?>
