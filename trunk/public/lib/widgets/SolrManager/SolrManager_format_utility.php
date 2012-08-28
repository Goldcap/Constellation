<?php

class SolrManager_format_utility {
  
  public $context;
  public $result;
  public $terms;
  public $maplinks;
  public $rpp;
  
  function __construct( $context ) {
    $this -> context = $context;
    $this -> terms = false;
  }
  
  function setTerm( $termname, $termvalue) {
    $term["name"] = $termname;
    $term["value"] = $termvalue;
    $this -> terms[] = $term;
  }
  
  function dateW3XMLIN( $value, $item ) {
    if (array_key_exists("screening_default_timezone_id",$value)) {
      $tz = $value["screening_default_timezone_id"];
    } else {
      $tz = date_default_timezone_get();
    }
    return formatDate($value[$item],"W3XMLIN",$tz);
  }
  
}
?>
