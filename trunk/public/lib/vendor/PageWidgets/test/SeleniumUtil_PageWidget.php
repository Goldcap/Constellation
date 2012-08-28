<?php

/**
 * SeleniumUtil_PageWidget.class.php, Symfony PageWidgets
 * Symfony Page Widget Test Controller.
 * @author Andy Madsen <amadsen@operislabs.com>
 * @version 1.0
 * @package com.Operis.PageWidgets.test
 *
 **/

/**
 * Utils for Selenium Unit Tests using YML
 *  
 * Used to control YAML-Based Tests from CLI
 * Be careful about Namespaces, as the PHPUnit_Extensions_SeleniumTestCase
 * Is FULL of methods that will conflict, such as runTest(), doRun(), run(), etc...
 * NOTE:: All Methods in class are Predicated with a Namespace Constraint
 * Except setUp and tearDown
 *		
 */
  
class SeleniumUtil_PageWidget extends PHPUnit_Extensions_SeleniumTestCase {
	
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
  * Object to hold Utility Class
  *   
  * @property  
  * @access public
  * @var object
  * @name $util  
  */
	public $util;
	
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
  * Selenium "Magic Method" to trigger Selenium Test
  *   
  * Setup Method - Configure browser (Can also specify array $browsers method 
  * for multi browser support in one test)
  * @name setUp 
  */
	protected function setUp(){
    //$this->setBrowser(BROWSER);
    $this->setBrowser("seleniumProtocol=Selenium,browserName=firefox");
    $this->setBrowserUrl(SITE_URL);
    //$this->setHost("ec2-107-22-8-192.compute-1.amazonaws.com");
		$this->test_class_name = get_class($this);
	
  }
  
	/** 
  * Selenium "Magic Method" to wrap up Selenium Test
  *  
  * Tear Down method upon test finish - output logs
  * @name tearDown  
  */
	function tearDown() {
   static $teared = false;
   if ($teared === false) {
       $teared = true;	
       $this->stop();
     	 $this->outputSeleniumPageWidgetLogs('csv');
     exit;
  	}
  }	  
	
	/** 
  * Creates the context, util, etc... for a Widget to be run. Also sets the test
  * fixtures.  
  *   
  * @name runSeleniumPageWidgetTest  
  */
	function runSeleniumPageWidgetTest() {
		
		$configuration = sfApplicationConfiguration::getApplicationConfiguration(SF_APP,SF_ENV,true);
    $context = sfContext::createInstance($configuration);
    $this -> util = new Utils_PageWidget($context);
    
  	$this -> loadSeleniumPageWidgetTest();
  	$this -> initSeleniumPageWidgetTest();
  	
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
  * @name loadSeleniumPageWidgetTest  
  */
	function loadSeleniumPageWidgetTest() {
		
		/*YAML Based Test, deprecated
		$this -> yaml_path = sfConfig::get("sf_root_dir")."/test/functional/frontend/selenium/auto/yml/";	
		$this -> testObj = sfYaml::load($this -> yaml_path.TEST_NAME.".yml");
		*/
		//DB stuff
		$sql = "select * from selenium_test where selenium_test_name = ?";
		$args[0] = TEST_NAME;
		$res =  $this -> util -> propelArgs($sql,$args);
		while ($row = $res -> fetch()) {
			$this -> testObj["id"] = $row[0];
			$this -> testObj["group"] = $row[4];
			$this -> testObj["sleep"] = $row[5];
			$this -> testObj["meta"] = $row[6];
		}
		
		$sql = "select * from selenium_test_step where fk_selenium_test_id = ? order by selenium_test_step_order";
		$args[0] =$this -> testObj["id"];
		$res =  $this -> util -> propelArgs($sql,$args);
		while ($row = $res -> fetch()) {
			$step["type"] = $row[4];
			$step["locator"] = $row[5];
			$step["value"] = $row[6];
			$this -> testObj["test"][] = $step;	
		}
		
		if (isset($this -> testObj['meta'])) {
			$this -> logger = $this -> testObj['meta'];
		} else {
			$this -> logger = array("dev.constellation.tv","127.0.0.1",20000);
		}
		if (isset($this -> testObj['group'])) {
			$this -> group = $this -> testObj['group'];
		} else {
			$this -> group = TEST_GROUP;
		}
		if (isset($this -> testObj['host'])) {
			$this->setHost($this -> testObj['host']);
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
  * @name initSeleniumPageWidgetTest  
  */				
	function initSeleniumPageWidgetTest() {
		$this -> curl = new Curl();
		if ($this -> testObj["sleep"] != 0) {
			$this->setSleep((int) $this -> testObj["sleep"]);
		}
		
  	$this->open(SITE_URL);					
	}
	
	/** 
  * Sets values for a specific Selenium Test Action
  * 
  * Do variable replacements from YAML 
  * 		  
  * @name setSeleniumPageWidgetTestValues
  * @param array $testitem	  
  */	
	function setSeleniumPageWidgetTestValues( $testitem ) {
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
  * @name runSeleniumPageWidgetTestAction  
  * @param array $testitem
  * @param string $sub - String to mark an action as a "sub-action", for recursion	  
  */
	function runSeleniumPageWidgetTestAction( $testitem, $sub="" ) {
		
		if (strlen($sub) > 0) {
			$sub = "SUB::".$sub."-";
		}
		
		switch (true) {
			case (left($testitem["type"],6) == "assert"):
				$result = $this->customSeleniumPageWidgetAssert($testitem["type"], array('target'=>$testitem["locator"], 
																										'label'=>$sub.$testitem["value"]));
				if (! is_null($this -> logger)) {
					$vars = array("a"=>$sub.$testitem["value"],"b"=>$result,"c"=>BROWSER,"d"=>$this -> group);
					$this -> curl -> get("http://".$this -> logger[0]."/services/Selenium?i=".$this -> logger[1].":".$this -> logger[2],$vars);
					//$this -> curl -> get("http://".$this -> logger[0]."/services/Selenium", $vars);
				}
				break;
			case ($testitem["type"] == "test"):
				$this->runSeleniumPageWidgetSubTest( $testitem["value"] );			
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
  * Runs a recursive Selenium Test action from a Parent Action
  *   
  * @name runSeleniumPageWidgetSubTest
  * @param mixed $value	  
  */
	function runSeleniumPageWidgetSubTest( $value ) {
	
		$sql = "select * from selenium_test where selenium_test_name = ?";
		$args[0] = $value;
		$res =  $this -> util -> propelArgs($sql,$args);
		while ($row = $res -> fetch()) {
			$testObj["id"] = $row[0];
			$testObj["group"] = $row[4];
			$testObj["sleep"] = $row[5];
			$testObj["meta"] = $row[6];
		}
		
		$sql = "select * from selenium_test_step where fk_selenium_test_id = ? order by selenium_test_step_order";
		$args[0] = $testObj["id"];
		$res =  $this -> util -> propelArgs($sql,$args);
		while ($row = $res -> fetch()) {
			$step["type"] = $row[4];
			$step["locator"] = $row[5];
			$step["value"] = $row[6];
			$testObj["test"][] = $step;	
		}
		
		foreach($testObj['test'] as $testitem) {
  		cli_text("Running SUB::".$value." - '".$testitem["type"]."' as ".implode(",",$testitem),"green");
  		$testitem = $this -> setSeleniumPageWidgetTestValues($testitem);    		
			$this -> runSeleniumPageWidgetTestAction($testitem,$value);
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
  * @name customSeleniumPageWidgetAssert
  * @param string $method
  * @param array $args		  
  */
	function customSeleniumPageWidgetAssert($method, $args = array()) {
		 $target = $args['target'];
		 $value = isset($args['value']) ? $args['value'] : null;
		 $label = isset($args['label']) ? $args['label'] : $method;
		 $continue_on_fail = isset($args['continue_on_fail']) ? $args['continue_on_fail'] : true;
		 $assertion = array($method, $label);
	   try {
		  	$this->$method($target, $value); 
     } catch (Exception $e) {
		  	$assertion[] = $e->getMessage();
				$this->addSeleniumPageWidgetLog("failure",$assertion);
				if ($continue_on_fail) {
	     		  return "failure";
				} else {
				  return $this->tearDown();
				}
		 }
		 $assertion[] = '';
		 $this->addSeleniumPageWidgetLog("success",$assertion);
		 return "success";
	}
	
	/** 
  * Adds a log value of type to our logs array.
  *   
  * @name addSeleniumPageWidgetLog  
  * @param string $type
  * @param boolean $assertion	  
  */
	function addSeleniumPageWidgetLog($type, $assertion) {
		$assertion[2]="===========";
		$this->logs[$type][] = $assertion;
		$assertion[3] = ucwords($type);
		$this->logs['all'][] = $assertion;
	}
	
	/** 
  * Custom output of asserts to both CLI and a CSV File
  *   
  * @name outputSeleniumPageWidgetLogs  
  * @param string $format  
  */
  function outputSeleniumPageWidgetLogs($format = 'csv') {
     if (isset($this->logs['fails'])) {
	     cli_text("Test Failed","red");
	   } else {
	     cli_text("Test Succeeded","green");
	   }
	   cli_text("Count Notices: ".(isset($this->logs['notice']) ? count($this->logs['notice']) : 0),"cyan");
	   cli_text("Count Success: ".(isset($this->logs['success']) ? count($this->logs['success']) : 0),"yellow");
	   cli_text("Count Errors: ".(isset($this->logs['failure']) ? count($this->logs['failure']) : 0),"red");	  
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
			//Record test result in DB
			$sql = "insert into selenium_test_result(fk_selenium_test_id,selenium_test_result_date,selenium_test_result_result,selenium_test_result_failure,selenium_test_result_success) values (?,now(),?,?,?);";
			$args = array($this -> testObj["id"],(isset($this->logs['failure']) ? "failure" : "success"),(isset($this->logs['success']) ? count($this->logs['success']) : 0),(isset($this->logs['failure']) ? count($this->logs['failure']) : 0));
			$res =  $this -> util -> propelArgs($sql,$args);		
			$sql = "update selenium_test set selenium_test_latest_result = ?, selenium_test_latest_result_date = now() where selenium_test_id = ?;";
			$args = array((isset($this->logs['failure']) ? "failure" : "success"), $this -> testObj["id"]);
			$res =  $this -> util -> propelArgs($sql,$args);		
			//echo $output;
		    
		}
  }	
	
	/** 
  * Records a Selenium Screencapture (delivered as base64) as a png file.
  *   
  * @name outputSeleniumPageScreencapture  
  * @param string $name - Name of File
  * @param string $output - Base64 Screencapture Data	  
  */
  function outputSeleniumPageScreencapture($name, $output) {
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
  * @name formatSeleniumPageWidgetQuoteRow
  * @param array $row	  
  */
	function formatSeleniumPageWidgetQuoteRow($row){
      $_formatted_row = null;
      foreach($row as $r){
       $cr = str_replace('"',"'",$r);
       $_formatted_row[] = '"'.$cr.'"';
      }
     return implode(',', $_formatted_row)."\n";
   }
 
}

?>
