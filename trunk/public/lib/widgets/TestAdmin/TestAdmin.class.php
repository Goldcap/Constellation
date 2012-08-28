<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/TestAdmin_crud.php';
  
  class TestAdmin_PageWidget extends Widget_PageWidget {
	
  var $XMLForm;
	var $crud;
	var $arguments;
	var $test_name;
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
		$this -> crud = new SeleniumTestCrud( $context );
    parent::__construct( $context );
    
    if (($this ->getOp() == "new") && ($this -> getId()>=0) && ($this -> crud)) {
      $this -> XMLForm = new XMLForm($this -> widget_name,"newconf.php");
    } else {
    	$this -> XMLForm = new XMLForm($this -> widget_name);
	  }
    $this -> XMLForm -> item_forcehidden = true;
  	
  }
  
	function parse() 
	{
			
		if ($this -> as_cli) {
			$this -> test_name = $this -> widget_vars["args"][0];
			$this -> action = $this -> widget_vars["args"][1];
			
			$this -> yaml_path = sfConfig::get("sf_root_dir")."/test/functional/frontend/selenium/auto/yml/";	
			$this -> testObj = sfYaml::load($this -> yaml_path.$this -> test_name.".yml");
			$this -> crud -> populate("selenium_test_name",$this -> test_name);
			
			$sql = "delete from selenium_test_step where fk_selenium_test_id = ?";
			$args = array($this -> crud -> SeleniumTest -> getSeleniumTestId());
			$this -> propelArgs($sql,$args);	
			
			$i=1;
			foreach($this -> testObj['test'] as $testitem) {
				$step = new SeleniumTestStep();
				$step -> setFkSeleniumTestId($this -> crud -> SeleniumTest -> getSeleniumTestId());
				$step -> setSeleniumTestStepOrder($i);
				$step -> setSeleniumTestStepDescription($testitem["value"]);
				$step -> setSeleniumTestStepType($testitem["type"]);
				$step -> setSeleniumTestStepLocator($testitem["locator"]);
				$step -> setSeleniumTestStepValue($testitem["value"]);
				$step -> save();
				$i++;
			}
			
			die();
   	
		}
		
	  if ($this -> XMLForm -> isPosted()) {  
      $this -> doPost();
    }
    $this -> doGet();
    
    return $this -> drawPage();
    
  }

  function doPost(){
     
     if ($this -> XMLForm -> validateForm()) {
        switch ($this -> getFormMethod()) {
          case "submit":
          $this -> crud -> write();
          
					$file = new WTVRFile("selenium_test_file");
          $test = file ($file -> thefile["tmp_name"]);
          $started = false;
          $i=1;
					foreach($test as $line) {
						$line = str_replace("  ","",$line);
						$line = str_replace("\n","",$line);
						if ($started) {
						if (preg_match("/end/",$line)) {
							break;
						}}
						if ($started) {
							//$line = str_replace("\"","",$line);
							$testitem = str_replace("page.","",$line);
							$item = Array();
							$r=0;
							$inquoted=false;
							for($x=0;$x<strlen($testitem);$x++) {
								//kickdump($testitem[$x]);
								
								//Check to see if we're starting a qualified block
								if ((! $inquoted) && $testitem[$x] == "\"") {
									//kickdump("INQUOTED");
									$inquoted = true;
									continue;
								}
								
								//Check to see if we're ending a qualified block
								if (($inquoted) && $testitem[$x] == "\"") {
									//kickdump("NOT INQUOTED");
									$inquoted = false;
									continue;
								}
								
								//Ignore Chars outside of Delimited Values
								if ((! $inquoted) && $testitem[$x] == ",") {
									continue;
								}
								
								//If we're not in a qualified block, and it's not a space
								if ((! $inquoted) && $testitem[$x] != " ") {
									$item[$r]=$item[$r].$testitem[$x];
								}
								
								if (($inquoted) && $testitem[$x] != "\"") {
									$item[$r]=$item[$r].$testitem[$x];
								}
								
								if (((! $inquoted) && $testitem[$x] == " ") || (($inquoted) && $testitem[$x] == "\"")) {
									$r++;
								}
							}
							$testitem = $item;
							//kickdump($item);
							
							$action = str_replace(" ","",ucwords(str_replace("_"," ",$testitem[0])));
							$action[0] = strtolower($action[0]);
							$step = new SeleniumTestStep();
							$step -> setFkSeleniumTestId($this -> crud -> SeleniumTest -> getSeleniumTestId());
							$step -> setSeleniumTestStepOrder($i);
							$step -> setSeleniumTestStepDescription($testitem[2]);
							$step -> setSeleniumTestStepType($action);
							$step -> setSeleniumTestStepLocator($testitem[1]);
							$step -> setSeleniumTestStepValue($testitem[2]);
							$step -> save();
							$i++;
						}
						if (preg_match("/it \"(.+)\" do/",$line)) {
							//kickdump("STARTING");
							$started = true;	
						}
						$i++;
					}
					
          break;
          case "delete":
          $this -> crud -> remove();
          break;
        }
      }
    
  }

  function doGet(){
    
    if (($this ->getOp() == "detail") && ($this -> getId()>=0) && ($this -> crud)) {
      $this -> pushItem();
      
    	$util = new TestAdmin_format_utility( $this -> context );
      $this -> XMLForm -> item["test_results"] = $this -> returnList(sfConfig::get('sf_lib_dir')."/widgets/TestAdmin/query/TestResult_list_datamap.xml", false, true, "standard", $util);
      $this -> XMLForm -> item["test_steps"] = $this -> returnList(sfConfig::get('sf_lib_dir')."/widgets/TestAdmin/query/TestDetail_list_datamap.xml", false, true, "standard", $util);
      
    }
    
  }

  function drawPage(){
    
    if (($this ->getOp() == "new") && ($this -> getId()>=0) && ($this -> crud)) {
      return $this -> returnForm();
    } elseif (($this ->getOp() == "detail") && ($this -> getId()>=0) && ($this -> crud)) {
      return $this -> returnForm();
    } elseif ($this ->getOp() == "list" ) {
      $util = new TestAdmin_format_utility( $this -> context );
      return $this -> returnList(sfConfig::get('sf_lib_dir')."/widgets/TestAdmin/query/TestAdmin_list_datamap.xml", false, true, "standard", $util);
    }
    
  }

	}

  ?>
