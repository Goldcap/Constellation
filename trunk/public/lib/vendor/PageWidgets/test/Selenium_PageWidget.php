<?php

/**
 * Selenium_PageWidget.class.php, Symfony PageWidgets
 * Symfony Page Widget Test Controller.
 * @author Andy Madsen <amadsen@operislabs.com>
 * @version 1.0
 * @package com.Operis.PageWidgets.test
 */
 
set_include_path(".:".dirname(__FILE__)."/../../PHPUnit:/../../web");

require_once (dirname(__FILE__).'/../../utils.php');
require_once (dirname(__FILE__).'/../../wtvr/1.3/WTVRUtils.php');
require_once (dirname(__FILE__).'/../../PHPUnit/PHP/CodeCoverage/Filter.php');
require_once (dirname(__FILE__).'/../../PHPUnit/PHPUnit/Autoload.php');


/**
 * Manages Selenium Unit Tests using YML
 *
 */

class Selenium_PageWidget extends SeleniumUtil_PageWidget {
  
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
		
		if (strpos('@php_bin@', '@php_bin') === 0) {
		  set_include_path(dirname(__FILE__) . PATH_SEPARATOR . get_include_path());
		}

    PHP_CodeCoverage_Filter::getInstance()->addFileToBlacklist(__FILE__, 'PHPUNIT');
		define('PHPUnit_MAIN_METHOD', 'PHPUnit_TextUI_Command::main');
		define('PHP_CODECOVERAGE_TESTSUITE', true);
		
		if ($arguments['test'] != 'ALL') {
			$path = sfConfig::get("sf_root_dir").'/test/functional/'.$arguments["application"].'/selenium/tests/testStub.php';
		}
		$sendargs = array("selenium","-c",sfConfig::get("sf_root_dir").'/test/functional/'.$arguments["application"].'/selenium/config/phpunit-constellation.xml',$path);
		//$sendargs = array("selenium",$path);
		
		PHPUnit_TextUI_Command::main(true,$sendargs);
    
  }
  
  
}

?>
