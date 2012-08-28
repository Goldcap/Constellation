<?php

/**
 * Webdriver_PageWidget.class.php, Symfony PageWidgets
 * Symfony Page Widget Test Controller.
 * @author Andy Madsen <amadsen@operislabs.com>
 * @version 1.0
 * @package com.Operis.PageWidgets.test
 */
 
set_include_path(".:".dirname(__FILE__)."/../../PHPUnit:/../../web");

require_once (dirname(__FILE__).'/../../utils.php');
require_once (dirname(__FILE__).'/../../wtvr/1.3/WTVRUtils.php');
require_once (dirname(__FILE__).'/../../Selenium/php-webdriver-bindings-0.9.0/phpwebdriver/WebDriver.php');


/**
 * Manages Selenium Webdriver Unit Tests using YML
 */
 
class Webdriver_PageWidget extends WebdriverUtil_PageWidget {
  
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
  * @param array $arguments	
  */
	public function __construct( $context, $arguments ) {
    
    if (! isset($arguments["config"])) {
			$arguments["config"] = "default";
		}
		
		define("TEST_GROUP",nowAsId());
		
		if (isset($arguments["config"])) {
			cli_text("READING CONFIGURATION " . $arguments["config"], "cyan");
			$figs = sfYaml::load(sfConfig::get("sf_test_dir")."/functional/".$arguments["application"]."/selenium/config/".$arguments["config"].".yml");
			foreach($figs as $fig=>$leaf) {
				cli_text($fig ." DEFINED AS " . $leaf, "green");
				define($fig, $leaf);
			}
		}
		
		//A few generic params
		define("TEST_NAME",$arguments['test']);
		$dir = sfConfig::get("sf_log_dir")."/".$arguments['test']."/".TEST_GROUP;
		define("OUTPUT_PATH",$dir);
		
    if (extension_loaded('xdebug')) {
		    xdebug_disable();
		}
		
		$test = new WebdriverUtil_PageWidget();
				
  }
  
  
}

?>
