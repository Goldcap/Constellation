<?php


/**
 * WebdriverUtil_PageWidget.class.php, Symfony PageWidgets
 * Symfony Page Widget Test Controller.
 * @author Andy Madsen <amadsen@operislabs.com>
 * @version 1.0
 * @package com.Operis.PageWidgets.test
 **/

/**
 * Utils for Selenium Webdriver Unit Tests using YML
 * 
 * Used to control YAML-Based Tests from CLI
 * Be careful about Namespaces, as the PHPUnit_Extensions_SeleniumTestCase
 * Is FULL of methods that will conflict, such as runTest(), doRun(), run(), etc...
 * NOTE:: All Methods in class are Predicated with a Namespace Constraint
 * Except setUp and tearDown
 *	 
 */
  
class WebdriverUtil_PageWidget {
	
	/** 
  * Array register for results.
  *   
  * @property  
  * @access public
  * @var array
  * @name $logs  
  */
	public $logs = array('all'=>array());
	
	/** 
  * Name of Test Class
  *   
  * @property  
  * @access public
  * @var string
  * @name $test_class_name  
  */
	public $test_class_name = null;
	
	/** 
  * Path to list of tests
  *   
  * @property  
  * @access public
  * @var string
  * @name $yaml_path  
  */
	public $yaml_path;		
  
	/** 
  * Object to hold the list of tests
  *   
  * @property  
  * @access public
  * @var int
  * @name $testObj  
  */
	public $testObj;
  
	/** 
  * URL to REST Logging Facility
  *   
  * @property  
  * @access public
  * @var string
  * @name $logger  
  */
	public $logger;
  
	/** 
  * Object to Hold Curl
  *   
  * @property  
  * @access public
  * @var curlObject
  * @name $curl  
  */
	public $curl;
  
	/** 
  * Location of output for results file
  *   
  * @property  
  * @access public
  * @var string
  * @name $output_path  
  */
	public $output_path;
  
	/** 
  * Register for Webdriver Controller
  *   
  * @property  
  * @access public
  * @var string
  * @name $webdriver  
  */
	public $webdriver;
  
  /** 
  * Allow screenshots to be captured
  *   
  * @property  
  * @access public
  * @var boolean
  * @name $captureScreenshotOnFailure  
  */
	protected $captureScreenshotOnFailure = TRUE;
  
  /** 
  * Screenshot save path
  *   
  * @property  
  * @access public
  * @var string
  * @name $screenshotPath  
  */
	protected $screenshotPath = OUTPUT_PATH;
  
	/** 
  * Web view path to screenshots
  *   
  * @property  
  * @access public
  * @var string
  * @name $screenshotUrl  
  */
	protected $screenshotUrl = 'http://localhost/screenshots';
  
  /** 
  * Class Constructor
  *   
  * @name __construct
  * @param string $test  
  */
	function __construct( $test=null ) {
		$this -> webdriver = new WebDriver("localhost", "4444");
		switch (BROWSER) {
			case "*firefox":
				$browser = "internet explorer";
				break;
			case "*iexplore":
				$browser = "internet explorer";
				break;
			case "*safari":
				$browser = "internet explorer";
				break;
			case "*googlechrome":
				$browser = "internet explorer";
				break;
		}
		$webdriver->connect($browser);    
		
	}
  
	/** 
  * Selenium "Magic Method" to trigger Selenium Test
  *   
  * Setup Method - Configure browser (Can also specify array $browsers method 
  * for multi browser support in one test)
  * @name setUp 
  */
	protected function setUp(){                      
	}	
  
	/** 
  * Creates the context, util, etc... for a Widget to be run. Also sets the test
  * fixtures.  
  *   
  * @name runWebdriverPageWidgetTest  
  */
	function runWebdriverPageWidgetTest() {
		
  	$this -> loadWebdriverPageWidgetTest();
  	$this -> initWebdriverPageWidgetTest();
  	
  	foreach($this -> testObj['test'] as $testitem) {
  		cli_text("Running '".$testitem["type"]."' as ".implode(",",$testitem),"green");
  		$testitem = $this -> setSeleniumPageWidgetTestValues($testitem);    		
			$this -> runSeleniumPageWidgetTestAction($testitem);
		}
		
	}
	
	/** 
  * Pulls the test configurationsfrom the database, and prepares to execute
  * them in order
  *   
  * @name loadWebdriverPageWidgetTest  
  */
	function loadWebdriverPageWidgetTest() {
		$this -> yaml_path = sfConfig::get("sf_root_dir")."/test/functional/frontend/selenium/auto/yml/";	
		$this -> testObj = sfYaml::load($this -> yaml_path.TEST_NAME.".yml");
		if (isset($this -> testObj['manager'])) {
			$this -> logger = $this -> testObj['manager'];
		}
		if (isset($this -> testObj['group'])) {
			$this -> group = $this -> testObj['group'];
		} else {
			$this -> group = TEST_GROUP;
		}
		$this -> output_path = sfConfig::get("sf_log_dir")."/".TEST_NAME."/".$this -> group;
		createDirectory($this -> output_path);
		
	}
	
	/** 
  * Prepare CURL, and do an initial sleep if the test requests that. Then 
  * start the test by opening the initial URL.
  *   
	* Pull Yaml Configuration Files
	* And open the Browser Session
  * @name initWebdriverPageWidgetTest  
  */					
	function initWebdriverPageWidgetTest() {
		$this -> curl = new Curl();
		if ($this -> testObj["sleep"] != 0) {
			$this->setSleep($this -> testObj["sleep"]);
		}
					
		$this -> webdriver->get(SITE_URL);
		
	}
	
	/** 
  * Sets values for a specific Selenium Test Action
  * 
  * Do variable replacements from YAML 
  * 		  
  * @name setWebdriverPageWidgetTestValues
  * @param array $testitem	  
  */			
	function setWebdriverPageWidgetTestValues( $testitem ) {
		if (! isset($testitem["value"]))
    			$testitem["value"] = null;
		if (left($testitem["value"],3) == "$::") {
			$var = str_replace("$::","",$testitem["value"]);
			cli_text("Setting ".$testitem["value"]." from profile to ".constant($var),"green");
			$testitem["value"] = constant($var);
		}
		return $testitem;		
	}
	
	/** 
  * Applies a specific Selenium Test Action with the specified value
  *   
  * @name runWebdriverPageWidgetTestAction  
  * @param array $testitem  
  */
	function runWebdriverPageWidgetTestAction( $testitem ) {
		switch (true) {
			case (left($testitem["type"],6) == "assert"):
				$result = $this->customSeleniumPageWidgetAssert($testitem["type"], array('target'=>$testitem["locator"], 
																										'label'=>$testitem["value"]));
				if (! is_null($this -> logger)) {
					$vars = array("a"=>$testitem["value"],"b"=>$result,"c"=>BROWSER,"d"=>$this -> group);
					$this -> curl -> get("http://".$this -> logger[0]."/services/Selenium?i=".$this -> logger[1].":".$this -> logger[2],$vars);
					//$this -> curl -> get("http://".$this -> logger[0]."/services/Selenium", $vars);
				}
				break;
			case ($testitem["type"] == "log"):
				$this->addSeleniumPageWidgetLog("notice",array($testitem["locator"],$testitem["value"]));			
				break;
			case ($testitem["type"] == "screenCapture"):
				$this->addSeleniumPageWidgetLog("notice",array($testitem["locator"],$testitem["value"]));
				break;
			case ($testitem["type"] == "screenshot"):
				$screen = $this -> captureEntirePageScreenshotToString();
				$this -> outputSeleniumPageScreencapture($testitem["value"],$screen);
				break;		
			default:
				cli_text($testitem["type"],"red");
				eval("\$this->".$testitem["type"]."(\$testitem[\"locator\"],\$testitem[\"value\"]);");			
				break;																	
		}
	}		
		
	/** 
  * Utility function to create a unique ID
  *   
  * @name uid  
  */
	function uid() {
      return md5(uniqid(getmypid(), 1));
	}
	
	/** 
  * Custom assert function to support our own custom logging 
  *   
  * @name customWebdriverPageWidgetAssert
  * @param string $method
  * @param array $args		  
  */
	function customWebdriverPageWidgetAssert($method, $args = array()) {
		 $target = $args['target'];
		 $value = isset($args['value']) ? $args['value'] : null;
		 $label = isset($args['label']) ? $args['label'] : $method;
		 $continue_on_fail = isset($args['continue_on_fail']) ? $args['continue_on_fail'] : false;
		 $assertion = array($method, $label);
	   try {
		  	$this->$method($target, $value); 
     } catch (Exception $e) {
		  	$assertion[] = $e->getMessage();
				$this->addSeleniumPageWidgetLog("failure",$assertion);
				if ($continue_on_fail) {
	     		  return false;
				} else {
				  return $this->tearDown();
				}
		 }
		 $assertion[] = '';
		 $this->addSeleniumPageWidgetLog("success",$assertion);
		 return true;
	}
	
	/** 
  * Adds a log value of type to our logs array.
  *   
  * @name addWebdriverPageWidgetLog  
  * @param string $type
  * @param boolean $assertion	  
  */
	function addWebdriverPageWidgetLog($type, $assertion) {
		$assertion[2]="===========";
		$this->logs[$type][] = $assertion;
		$assertion[3] = ucwords($type);
		$this->logs['all'][] = $assertion;
	}
	
	/** 
  * Custom output of asserts to both CLI and a CSV File
  *   
  * @name outputWebdriverPageWidgetLogs  
  * @param string $format  
  */
  function outputWebdriverPageWidgetLogs($format = 'csv') {
     if (isset($this->logs['fails'])) {
	     cli_text("Test Failed","red");
	   } else {
	     cli_text("Test Succeeded","green");
	   }
	   cli_text("Count Notices: ".(isset($this->logs['notice']) ? count($this->logs['notice']) : 0),"cyan");
	   cli_text("Count Success: ".(isset($this->logs['success']) ? count($this->logs['success']) : 0),"yellow");
	   cli_text("Count Errors: ".(isset($this->logs['fails']) ? count($this->logs['fails']) : 0),"red");	  
	     switch($format) {
		    case 'print':
			  print_r($this->logs['all']);
			  break;
			default:
         
	    $head = '"Method"|"Label"|"Message"|"Status"';
	    cli_text($head,"magenta");
			$output = $head."\n";
			foreach($this->logs['all'] as $log) {
         cli_text(implode("|",$log),"magenta");
         $output .= $this->formatSeleniumPageWidgetQuoteRow($log);
      }
			$output_dir = $this -> output_path.'/'.$this->test_class_name.'_'.time().'.csv';
      cli_text('Log output saved to '.$output_dir,"cyan");
			file_put_contents($output_dir, $output);			
			//echo $output;
		    
		}
  }	
	
	/** 
  * Records a Selenium Screencapture (delivered as base64) as a png file.
  *   
  * @name outputWebdriverPageScreencapture  
  * @param string $name - Name of File
  * @param string $output - Base64 Screencapture Data	  
  */
  function outputWebdriverPageScreencapture($name, $output) {
     	if (BROWSER != '*firefox') return;
			$output_dir = $this -> output_path.'/screencaptures/';
			createDirectory($output_dir);
			$output_dir = $output_dir .$name.'.png';
      cli_text('Image output saved to '.$output_dir,"cyan");
      $image = "";
      for ($i=0; $i < ceil(strlen($output)/256); $i++) 
   			$image = $image . base64_decode(substr($output,$i*256,256)); 
			file_put_contents($output_dir, $image);			
			//echo $output;
		    
  }	
	
	/** 
  * Formats output for CSV File
  *   
  * @name formatWebdriverPageWidgetQuoteRow
  * @param array $row	  
  */
	function formatWebdriverPageWidgetQuoteRow($row){
      $_formatted_row = null;
      foreach($row as $r){
       $cr = str_replace('"',"'",$r);
       $_formatted_row[] = '"'.$cr.'"';
      }
     return implode(',', $_formatted_row)."\n";
   }
 
}

?>
