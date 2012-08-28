<?php

class AccountTabsTest_PageWidget {
  
  var $test;
  var $test_name;
  var $context;
  
  function __construct( $context ) {
  
    $this -> context = $context;
    $this -> test_name = str_replace("Test_PageWidget","",get_class($this));
    
  }
  
  function test() {
    
    $this -> test = new Test_PageWidget( $this -> context, $this -> test_name, $this );
    $this -> test -> run();
    $this -> test -> end();
    
  }
  
  
}
?>
