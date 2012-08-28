<?php

class AccountTest_PageWidget {
  
  var $test;
  var $test_name;
  var $context;
  
  function __construct( $context ) {
  
    $this -> context = $context;
    $this -> test_name = str_replace("Test_PageWidget","",get_class($this));
    
  }
  
  function test() {
    
    $this -> test = new TestForm_PageWidget( $this -> context, $this -> test_name, $this );
    $this -> test -> run();
    $this -> test -> end();
    
    $this -> test = new TestForm_PageWidget( $this -> context, $this -> test_name, $this, "testPassVars.yml" );
    $this -> test -> run();
    $this -> test -> end();
    
  }
  
  function getUserPassword( $user ) {
    return decrypt( $user -> getUserPassword() );
  }
  
}
?>
