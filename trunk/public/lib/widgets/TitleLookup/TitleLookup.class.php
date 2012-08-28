<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/TitleLookup_crud.php';
  
   class TitleLookup_PageWidget extends Widget_PageWidget {
	
  var $XMLForm;
	var $crud;
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    parent::__construct( $context );
  }

	function parse() {
	  
	  $c = new Criteria();
    $c->add(ProgramPeer::PROGRAM_SHORT_NAME,$this -> getVar("action"));
    //$c->add(ProgramPeer::PROGRAM_PREUSER,1);
    $theprogram = ProgramPeer::doSelect($c);
    
    if(($theprogram[0]) && ($theprogram[0] -> getFkFilmId() > 0)) {
      $this -> redirect("/film/".$theprogram[0] -> getProgramShortname());
      die();
    }
    
    $c = new Criteria();
    $c->add(FilmPeer::FILM_SHORT_NAME,$this -> getVar("action"));
    //$c->add(FilmPeer::FILM_PREUSER,1);
    $thefilm = FilmPeer::doSelect($c);
    
		if(($thefilm[0]) && ($thefilm[0] -> getFilmId() > 0)) {
      $this -> redirect("/film/".$thefilm[0] -> getFilmId());
      die();
    }
  }
  
  //Moves a film from the "film" page to another page
  function findAlias() {
    
    $c = new Criteria();
    $c->add(AliasPeer::FK_FILM_ID,$this -> getVar("op"));
    $c->add(AliasPeer::ALIAS_STATUS,1);
    $thealias = AliasPeer::doSelect($c);
    
    if(($thealias[0]) && ($thealias[0] -> getAliasName() != "")) {
      return $thealias[0] -> getAliasName();
    } else {
      return "film";
    }
    
  }
  
  //Moves a film from the "film" page to another page
  function getPartnerByUrl() {
    
    $apart = "";
    if($_SERVER["HTTP_HOST"] != sfConfig::get("app_domain")) {
    	if (($this -> context -> getRequest()->getCookie("partner") != "constellation") 
						&& ($this -> context -> getRequest()->getCookie("partner") != "") 
						&& (strpos($this->context->getRequest()->getCookie("partner"),$_SERVER["HTTP_HOST"]) > 0)) {
    	 	 return;
    	}
			$name = explode(".",str_replace("constellation.tv","",$_SERVER["HTTP_HOST"]));
    	foreach($name as $part) {
    	if ($part != sfConfig::get("sf_environment")) {
				$apart = $part;
				break;
			}
			}
			if ($apart == "") {
				redirect("http://".sfConfig::get("app_domain"));
				die();
			}
		} else {
			$this -> context -> getResponse() -> setCookie ("partner", "constellation", time() + 10800, "/", ".constellation.tv", 0);
			$this -> context -> getResponse() -> setCookie ("partner_logo", "", time() - 10800, "/", ".constellation.tv", 0);
			$this -> context -> getResponse() -> setCookie ("partner_url", "", time() - 10800, "/", ".constellation.tv", 0);
			return;
		}
   	
    $c = new Criteria();
    $c->add(ProgramPeer::PROGRAM_SHORT_NAME,$apart);
    //$c->add(ProgramPeer::PROGRAM_PREUSER,1);
    $theprogram = ProgramPeer::doSelect($c);	
    if($theprogram[0]) {
    	//Set Attributes, if we're here for the first time
    	//And cookies havent' been drawn to the header
			$this -> context -> getRequest() -> setAttribute("partner",$apart);
			$this -> context -> getRequest() -> setAttribute("partner_logo",$theprogram[0] -> getProgramLogo());
			$this -> context -> getRequest() -> setAttribute("partner_url",$theprogram[0] -> getProgramUrl());
			$this -> context -> getResponse() -> setCookie ("partner", $apart, time() + 10800, "/", ".constellation.tv", 0);
    	$this -> context -> getResponse() -> setCookie ("partner_logo", $theprogram[0] -> getProgramLogo(), time() + 10800, "/", ".constellation.tv", 0);
    	$this -> context -> getResponse() -> setCookie ("partner_url", $theprogram[0] -> getProgramUrl(), time() + 10800, "/", ".constellation.tv", 0);
		}
    
  }
  
}

?>
