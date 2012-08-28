<?php

/**
 * TestWidget.class.php, Symfony PageWidgets
 * Symfony Page Widget Parser.
 * @author Andy Madsen <amadsen@operislabs.com>
 * @version 1.0
 * @package com.Operis.PageWidgets
 */

/**
 * Manages Unit Tests using YML
 */
 
class TestWidget extends Utils_PageWidget {
  
  /** 
  * Flag to return transformed forms as XML Objects
  *
  ** Takes the current standard URI and converts to Server File Path
  * If no path is given the $_SERVER["REQUEST_URI"] is used
  * @path URI, optional
  *  
	* @property  
  * @access public
  * @var boolean
  * @name $context  
  */
  var $context;
  
	/** 
  * Flag to return transformed forms as XML Objects
  *
  ** Takes the current standard URI and converts to Server File Path
  * If no path is given the $_SERVER["REQUEST_URI"] is used
  * @path URI, optional
  *  
	* @property  
  * @access public
  * @var boolean
  * @name $arguments  
  */
  var $arguments;
  
	/** 
  * Flag to return transformed forms as XML Objects
  *
  ** Takes the current standard URI and converts to Server File Path
  * If no path is given the $_SERVER["REQUEST_URI"] is used
  * @path URI, optional
  *  
	* @property  
  * @access public
  * @var boolean
  * @name $options  
  */
  var $options;
  
	/** 
  * Flag to return transformed forms as XML Objects
  *
  ** Takes the current standard URI and converts to Server File Path
  * If no path is given the $_SERVER["REQUEST_URI"] is used
  * @path URI, optional
  *  
	* @property  
  * @access public
  * @var boolean
  * @name $test  
  */
  var $test;
  
	/** 
  * Flag to return transformed forms as XML Objects
  *
  ** Takes the current standard URI and converts to Server File Path
  * If no path is given the $_SERVER["REQUEST_URI"] is used
  * @path URI, optional
  *  
	* @property  
  * @access public
  * @var boolean
  * @name $widget_name  
  */
  var $widget_name;
  
  /** 
  * Class Constructor
  *
  ** Takes the current standard URI and converts to Server File Path
  * If no path is given the $_SERVER["REQUEST_URI"] is used
  * @path URI, optional
  * <code>
  * Note, I've added an option to the "sfTestUnitTask" class
  * and so this can be invoked with CLI as below:
  * php symfony test:unit widget --widget=$widget_to_test
	* </code>	  
  *  
  * @access public
  * @name __construct
  * @param array arguments
  * @param array options
  * @param mixed object			  
  */
  function __construct( $arguments, $options, $object ) {
    
    if (is_null($options["app"])) {
      $app = "frontend";
    } else {
      $app = $options["app"];
    }
    // initialize the database connection
    $configuration = ProjectConfiguration::getApplicationConfiguration( $app, 'dev', true);
    $databaseManager = new sfDatabaseManager($configuration);
    $this -> context = sfContext::createInstance($configuration);
    
    $this -> widget_name = $options["widget"];
        
    if (is_null($this -> widget_name)) {
      $dir = sfConfig::get('sf_app_dir').'/config/';
      $tests = sfYaml::load($dir."testConfig.yml");
      if (! is_array($tests)) {
        return;
      }
      $this -> t = new lime_test(count(array_keys($tests["widgets"])));
      foreach(array_keys($tests["widgets"]) as $test) {
        if (file_exists(sfConfig::get("sf_lib_dir")."/widgets/".$test."/".$test.".class.php")) {
          $this -> t->pass('Found widget "'.$test.'"');
          eval("\$this -> test = new ".$test."Test_PageWidget( \$this -> context );");
          $this -> test -> test();
        } else {
          $this -> t->fail("'".$test.'" widget not found.');
        }
      }
    } else {
      $this -> t = new lime_test(1);
      $this -> t->pass('Found widget "'.$test.'"');
      eval("\$this -> test = new ".$this -> widget_name."Test_PageWidget( \$this -> context );");
      $this -> test -> test();
    }
  }
  
}

?>
