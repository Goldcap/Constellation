<?php

class UserAdmin_format_utility {
  
  public $context;
  public $result;
  public $terms;
  public $maplinks;
  
  function __construct( $context ) {
    $this -> context = $context;
    $this -> terms = false;  
  }
  
  function setTerm( $termname, $termvalue) {
    $term["name"] = $termname;
    $term["value"] = $termvalue;
    $this -> terms[] = $term;
  }
  
  function setMaplink( $column, $base, $javascript='', $paramnames=null, $params=null, $attribs=array()) {
    $this -> maplinks[] = 
      array($column=>
              array(
                "base"=>$base,
                "javascript"=>$javascript,
                "params"=>array_combine(explode(",",$paramnames),explode(",",$params)),
                "attribs"=>$attribs
                )
              );
  }
  
  /*
  function getConf( $xmlObj ) {
    //Use this method to modify the conf object prior to execution of the query
  }
  */
  
}
?>
