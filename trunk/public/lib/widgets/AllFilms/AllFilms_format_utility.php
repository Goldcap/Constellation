<?php

class AllFilms_format_utility extends Utils_PageWidget {
  
  public $context;
  public $result;
  public $terms;
  public $maplinks;
  public $map;
  public $as_service;
   
  function __construct( $context ) {
    $this -> context = $context;
    $this -> terms = false;
    
  }
  
  function setTerm( $termname, $termvalue) {
  }
  
  function getConf( $xmlObj ) {
    
    $this -> map = $xmlObj;
    
    //Adjust Records Per Page, with 120 max!
    if (($this -> getVar("sg")) && ($this -> getVar("sg") != "All")) {
      $this -> map -> createSingleElementByPath("criteria","//criteria[@column='film_status']",array("value"=>$this -> getVar('sg'),"column"=>"film_genre","type"=>"AND","constant"=>"native"));
    }
    
		//Adjust Records Per Page, with 120 max!
    if (($this -> getVar("records"))) {
      $this -> map -> setPathAttribute("//recordssperpage",0,"value",$this -> getVar("records"));
    }
    
    //$this -> map -> saveXML();
    return $this -> map;
  }
  
}
?>
