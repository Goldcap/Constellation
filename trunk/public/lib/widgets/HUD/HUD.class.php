<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/HUD_crud.php';
  
  /*
    bandwidthLimit: in bytes per sec
         default:0 (default is 16384 bytes per second)
    qualityLevel: 0-100
        default:75
    keyFrameInterval: Number
         default:30
    captureWidth: Number
        default:320
    captureHeight: Number
        default:240
    captureFps: Number
        default:20
    bufferMin: Seconds - Number 
        default:2
    bufferMax: seconds - Number
         default:15
    micRate: 
        default:22 (values are 
    micGain: 0-100
        default:75
    hostViewerWidth: Number
        default:320
    hostcam_hostViewerHeight : Number
         default:240
    motionTimeout: Number
        default:10000 (in millisecond)
    favorArea: Boolean / "0" / "1"
        default:true
    camLoopback: Boolean / "0" / "1"
         default:false
    micLoopback: : Boolean / "0" / "1"
        default:false
    echoSuppression: : Boolean / "0" / "1"
        default:true

    enhancedMicrophone: : Boolean / "0" / "1"
        default:true

    silenceLevel: 0-100 Number
        default:5
    micSilenceTimeout: Number in millisecond
        default:10000
    enableVAD: 
        default:true
    */
  
   class HUD_PageWidget extends Widget_PageWidget {
	
  var $XMLForm;
	var $crud;
	var $default_settings;
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
    $this -> crud = new HudCrud( $context );
    $this -> default_settings = array("bandwidthLimit"=>16384,
																		  "qualityLevel"=>75,
																	    "keyFrameInterval"=>30,
																	    "captureWidth"=>320,
																	    "captureHeight"=>240,
																	    "captureFps"=>20,
																	    "bufferMin"=>2,
																	    "bufferMax"=>15,
																	    "micRate"=>22,
																	    "micGain"=>75,
																	    "hostViewerWidth"=>320,
																	    "hostcam_hostViewerHeight"=>240,
																	    "motionTimeout"=>10000,
																	    "favorArea"=>1,
																	    "camLoopback"=>0,
																	    "micLoopback"=>0,
																	    "echoSuppression"=>0,
																	    "enhancedMicrophone"=>1,
																	    "silenceLevel"=>5,
																	    "micSilenceTimeout"=>10000,
																	    "enableVAD"=>"true"
																			);
    parent::__construct( $context );
  }

	function parse() {
	 
	 	if ($this -> as_service) {
		 	switch ($this -> getVar("id")) {
			 	case "get":
					$vars = array("fk_host_id"=>$this -> getVar("host"));
					$this -> crud -> checkUnique($vars);
					if(is_null($this -> crud -> Hud -> getHudId())) {
						//create new HUD
						die(json_encode(array("hudSettings"=>$this -> default_settings)));
					} else {
						die('{"hudSettings":'.$this -> crud -> Hud -> getHudSettings().'}');
					}
			 		break;
			 	case "set":
			 		$vars = array("fk_host_id"=>$this -> getVar("host"));
					$this -> crud -> checkUnique($vars);
					$this -> crud -> setFkHostId($this -> getVar("host"));
					if($this -> getVar("settings")) {
						$this -> crud -> setHudSettings(json_encode($this -> getVar("settings")));
					} else {
						$this -> crud -> setHudSettings(json_encode($this -> default_settings));
					}
					$this -> crud -> write();
			 		break;
			 }
		 }
    
  }

	}

  ?>
