<?php

/**
 * Test_PageWidget.class.php, Symfony PageWidgets
 * Symfony Page Widget Test Controller.
 * @author Andy Madsen <amadsen@operislabs.com>
 * @version 1.0
 * @package com.Operis.PageWidgets.test
 */

 
/**
 * Manages Lime Unit Tests using YML
 */

class Test_PageWidget extends TestUtil_PageWidget {
  
  /** 
  * Internal Test Count Register
  *   
  * @property  
  * @access public
  * @var int
  * @name $testcount  
  */
	var $testcount;
  
	/** 
  * Page Vars
  *   
  * @property  
  * @access public
  * @var array
  * @name $pvars  
  */
	var $pvars;
  
	/** 
  * Widget Vars
  *   
  * @property  
  * @access public
  * @var array
  * @name $wvars  
  */
	var $wvars;
  
  /** 
  * Class Constructor
  * 
  *	@access public
  * @name __construct  
  * @param sfContext context
  * @param string $test_name
  * @param string $test	  
  * @param string $config - YML Test File Location
  */
	public function __construct( $context, $test_name, $test=null, $config="testVars.yml" ) {
    
    $this -> test = $test;
    parent::__construct( $context, $test_name, $config );
    
  }
  
  /** 
  * Initializes and then Parses the widget
  * 
  * @name run
  * 	  
  */
	public function run() {
    $this -> init();
    $this -> parse();
  }
  
  /** 
  * Creates DB Fixtures and YML Fixtures
  * 
  * @name init
  * 	  
  */
	public function init() {
    
    $this -> test_count = 0;
    
    $this -> login();
    
    $this -> createDbFixtures();
    $this -> setFixtures();
    
  }
  
  /** 
  * Runs the Widget's Parse Method
  * 
  * @name post
  * 	  
  */
	public function parse() {
    
    $this -> assignVars();
    
    $this -> t = new lime_test( $this -> test_count, array("testname" => $this -> test_name . " Widget") );
    
    eval('$this -> widget = new '.$this -> test_name.'_PageWidget( $wvars, $pvars, $this -> context );');
    
    if ($this -> service_mode) {
      $this -> widget -> as_service = true;
    }
    
    //Submit the form
    $this -> widget -> parse();
    
    $this -> createDbObjects();
    $this -> testInsert();
    $this -> testWvars();
    $this -> testPvars();
  }
  
  
}

?>
