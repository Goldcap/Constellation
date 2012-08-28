<?php

class TestAdmin_format_utility {
  
  public $context;
  public $result;
  public $terms;
  public $maplinks;
  
  function __construct( $context ) {
    $this -> context = $context;
    $this -> terms = false;  
  }
  
  function formatResult( $value ) {
    if (strtolower($value) == "success") {
			return '<span style="color: green">Success</span>';
		} elseif (strtolower($value) == "failure") {
			return '<span style="color: red">Failure</span>';
		} else {
			return '<span style="color: grey">No Result</span>';
		}
  }
  
  
}
?>
