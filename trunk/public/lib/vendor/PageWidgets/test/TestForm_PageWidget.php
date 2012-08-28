<?php

/**
 * TestForm_PageWidget.class.php, Symfony PageWidgets
 * Symfony Page Widget Test Controller.
 * @author Andy Madsen <amadsen@operislabs.com>
 * @version 1.0
 * @package com.Operis.PageWidgets.test
 */

 
/**
 * Manages Line Unit Tests on StyroForms using YML
 */
 
class TestForm_PageWidget extends TestUtil_PageWidget {
  
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
  * Initializes and then Posts the form
  * 
  * @name run
  * 	  
  */
	public function run() {
    $this -> init();
    $this -> post();
  }
  
  /** 
  * Creates DB Fixtures and YML Fixtures
  * 
  * @name init
  * 	  
  */
	public function init() {
    
    $this -> test_count = 1;
    
    $this -> login();
    
    $this -> createDbFixtures();
    $this -> setFixtures();
    
  }
  
  /** 
  * Posts data to a specific Widget's Form
  * 
  * @name post
  * 	  
  */
	public function post() {
    
    $this -> assignVars();
    
    $this -> t = new lime_test( $this -> test_count, array("testname" => $this -> test_name . " Widget") );
    
    eval('$this -> widget = new '.$this -> test_name.'_PageWidget( $wvars, $pvars, $this -> context );');
    
    if ($this -> service_mode) {
      $this -> widget -> as_service = true;
    }
    
    //Set some basic form values
    $this -> initForm();
    
    //Submit the form
    $this -> widget -> parse();
    
    //Test Form Validation
    $this -> testForm();
    
    //Test Form DB Insert
    $this -> createDbObjects();
    $this -> testInsert();
    $this -> testWvars();
    $this -> testPvars();
  }
  
  /** 
  * Sets specific XMLForm settings to allow for internal test post.
  * 
  * @name initForm
  * 	  
  */
	public function initForm() {
    
    //Tell the Form that we're posting data
    sfConfig::set("force_post",true);
    
    //Put the form in debug mode
    sfConfig::set("validate_debug",1);
    sfConfig::set("terminal_debug",true);
    
    //And we can set explicitly, 
    //As long as we're not creating forms outside the widget's constructor
    $this -> widget -> XMLForm -> force_post = true;
    $this -> widget -> XMLForm -> validate_debug = 1;
    $this -> widget -> XMLForm -> terminal_debug = true;
    
    $_SERVER["REQUEST_METHOD"] = "POST";
  
  }
  
  /** 
  * Tests the form's validation register to see if errors occured.
  * 
  * @name testForm
  * 	  
  */
	public function testForm() {
    
    //Test the form validation
    if ($this -> widget -> XMLForm -> objValidateDoc -> err > 0) {
      $this -> t -> fail("Form Data Errors: ".$this -> widget -> XMLForm -> objValidateDoc -> err);
      $this -> t -> skip("Form Failed, Data Insert Tests Skipped", $this -> test_count - 1);
    } else {
      $this -> t -> pass("No Form Data Errors");
    }
    
  }
  
  
}

?>
