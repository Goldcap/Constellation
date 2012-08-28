<?php

class FilmList_format_utility {
  
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
  
}
?>
