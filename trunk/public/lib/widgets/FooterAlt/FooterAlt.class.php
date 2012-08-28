<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/FooterAlt_crud.php';
  
   class FooterAlt_PageWidget extends Widget_PageWidget {
	
  var $XMLForm;
	var $crud;
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
    parent::__construct( $context );
  }

	function parse() {
	 
	 if (sfConfig::get("app_site_id") == 3) {
    $this -> setTemplate("FooterSponsor");
   } else {
    if ($this -> getVar("action") == "index") {
      $this -> widget_vars["home"] = true;
    }
   }
   
   if ($this -> getVar("action") == "film") {
    $this -> widget_vars["share_show"] = $this -> page_vars["FilmView"]["column1"][0]["film"]["film_share"];
    $lt = "View '".$this -> page_vars["FilmView"]["column1"][0]["film"]["film_name"]."' on Constellation.tv: ".strip_tags($this -> page_vars["FilmView"]["column1"][0]["film"]["film_synopsis"]);
    $this -> widget_vars["fb_share"] = urlencode($lt);
    $this -> widget_vars["twitter_share"] = $lt;
   } else {
    $show = 1;
    $lt = "Constellation.tv is great films viewed online for yourself or with your friends.";
    $this -> widget_vars["fb_share"] = urlencode($lt);
    $this -> widget_vars["twitter_share"] = $lt;
	 }
	 
    $this -> widget_vars["domain"] = sfConfig::get("app_domain");
  return $this -> widget_vars;
   
  }

}

?>
