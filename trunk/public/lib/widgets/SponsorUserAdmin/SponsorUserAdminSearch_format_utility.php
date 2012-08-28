<?php

class SponsorUserAdminSearch_format_utility {
  
  public $context;
  public $result;
  public $terms;
  public $maplinks;
  public $term;
  public $startdate;
  public $enddate;
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
      $this -> map -> createSingleElementByPath("criteria","//criteria[@column='sponsor_code_user_email']",array("value"=>$this -> startdate,"column"=>"sponsor_code_start_date","constant"=>"GREATER_THAN"));
    }
    if ($this -> enddate) {
      $this -> map -> createSingleElementByPath("criteria","//criteria[@column='sponsor_code_user_email']",array("value"=>$this -> enddate,"column"=>"sponsor_code_end_date","constant"=>"LESS_THAN"));
    }
    
    if ($this -> film > 0) {
        $this -> map -> createSingleElementByPath("criteria","//criteria[@column='sponsor_code_user_email']",array("value"=>$this -> film,"column"=>"fk_film_id"));
    }
    
		if ($this -> term == '') {
      $this -> map -> removeSingleElementByPath("//criteria[@column='sponsor_code_user_email']");
      $this -> map -> removeSingleElementByPath("//criteria[@column='sponsor_code_user_fname']");
      $this -> map -> removeSingleElementByPath("//criteria[@column='sponsor_code_user_lname']");
      $this -> map -> removeSingleElementByPath("//criteria[@column='sponsor_code_user_username']");
    }
      
    $text = '<br /><div><a href="/sponsor/export?fk_film_id='.$this -> film.'&amp;sponsor_code_user_email='.$this -> term.'&amp;sponsor_code_user_fname='.$this -> term.'&amp;sponsor_code_user_lname='.$this -> term.'&amp;sponsor_code_user_username='.$this -> term.'">Export Users to Excel</a></div>';
    
    $this -> map -> setCdataByPath("//textfooter",0,$text);
    //$this -> map -> saveXML();
    return $this -> map;
    
  }
  
}
?>
