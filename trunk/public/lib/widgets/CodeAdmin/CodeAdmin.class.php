<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/AdminHelper.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/CodeAdmin_crud.php';
  
   class CodeAdmin_PageWidget extends Widget_PageWidget {
	
  var $XMLForm;
	var $crud;
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
    $this -> XMLForm = new XMLForm($this -> widget_name);
	  $this -> XMLForm -> item_forcehidden = true;
    $this -> crud = new PromoCodeCrud( $context );
    parent::__construct( $context );
  }

	function parse() {
	 
	 //php symfony widget exec CodeAdmin $app $env $total,$type,$value,$film,$usage,$codelength
	 if ($this -> as_cli) {
	 	$codes = Array();
		$total = $this ->widget_vars["args"][0];
	 	$type = $this ->widget_vars["args"][1];
	 	$value = $this ->widget_vars["args"][2];
	 	$film = $this ->widget_vars["args"][3];
	 	$usage = $this ->widget_vars["args"][4];
	 	if (! isset($this ->widget_vars["args"][5])) {
		 	$codelength = 4;
		} else {
		 	$codelength = $this ->widget_vars["args"][5];
		}
	 	for ($i=0;$i<$total;$i++) {
			$pc = new PromoCode();
			$pc -> setPromoCodeType($type);
			$pc -> setPromoCodeValue($value);
			$code = $this -> generateCode($codelength);
			//HackTastic!
			if (in_array($code,$codes)) {
				$code = $this -> generateCode($codelength);
				if (in_array($code,$codes)) {
					$code = $this -> generateCode($codelength);
					if (in_array($code,$codes)) {
						$code = $this -> generateCode($codelength);
						if (in_array($code,$codes)) {
							$code = $this -> generateCode($codelength);
						}
					}
				}
			}
			$codes[] = $code;
			$pc -> setPromoCodeCode($code);
			$pc -> setFkFilmId($film);
			$pc -> setPromoCodeTotalUsage($usage);
			$pc -> save();
			echo ($code."\n");
		}
	 }
	 //return $this -> widget_vars;
   
    $c = new Criteria();
    $films = FilmPeer::doSelect( $c );
    
  	$filma["sel_key"] = "1";
  	$filma["sel_value"] = "All Films";
    $filmarray[] = $filma;
    foreach ($films as $film) {
    	$filma["sel_key"] = $film -> getFilmId();
    	$filma["sel_value"] = $film -> getFilmName();
    	$filmarray[] = $filma;
    }
    $this -> XMLForm -> registerArray("films",$filmarray);
    
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
          if ($this -> postVar("fk_film_id") == 1) {
            $this -> crud -> setFkFilmId(0);
            $this -> crud -> save();
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
    }
    
  }

  function drawPage(){
    
    if ($this ->getOp() == "search" ) {
      $this -> SearchForm = new XMLForm($this -> widget_name, "searchconf.php");
      $this -> SearchForm -> validated= false;
      $searchform = $this -> SearchForm -> drawForm();
      $this -> widget_vars["search_form"] = $searchform["form"];
      
      $form = $this -> discountSearch();
      $this -> widget_vars["form"] = $form["form"];
      return $this -> widget_vars;
      
    } elseif (($this ->getOp() == "detail") && ($this -> getId()>=0) && ($this -> crud)) {
      return $this -> returnForm();
    } elseif ($this ->getOp() == "list" ) {
      $this -> SearchForm = new XMLForm($this -> widget_name, "searchconf.php");
       $this -> SearchForm -> validated= false;
       $searchform = $this -> SearchForm -> drawForm();
       $this -> widget_vars["search_form"] = $searchform["form"];
       
      $form = $this -> returnList();
      $this -> widget_vars["form"] = $form["form"];
      return $this -> widget_vars;
    }
    
  }
	
  function discountSearch( $query=false ) {
    
    //$this -> showData();
    //$this -> showXML();  
  
    $util = new CodeAdmin_format_utility( $this -> context );
    
    if (! $query) {
      
      if ($this -> greedyVar("fk_film_id") > 0) {
        $util -> film = $this -> greedyVar("fk_film_id");
      }
      if (($this -> greedyVar("promo_term"))) {
        $util -> term = $this -> greedyVar("promo_term");
      }
      
      $search = ($util -> term != "") ? $util -> term : "";
      
      if (is_numeric($util -> term) && ($util -> term < 1000000)) {
        $num = $util -> term;
      } else {
        $num = 0;
      }
      
      sfConfig::set("search",$util -> term);
      sfConfig::set("num",$num);
      
    }
    
    return $this -> returnList( "CodeAdminSearch_list_datamap.xml", true, true, "standard", $util );
    
  }
  
	function generateCode ($length = 4) {

    // start with a blank password
    $password = "";

    // define possible characters - any character in this string can be
    // picked for use in the password, so if you want to put vowels back in
    // or add special characters such as exclamation marks, this is where
    // you should do it
    $possible = "2346789bcdfghjkmnpqrtvwxyzBCDFGHJKLMNPQRTVWXYZ";

    // we refer to the length of $possible a few times, so let's grab it now
    $maxlength = strlen($possible);
  
    // check for length overflow and truncate if necessary
    if ($length > $maxlength) {
      $length = $maxlength;
    }
	
    // set up a counter for how many characters are in the password so far
    $i = 0; 
    
    // add random characters to $password until $length is reached
    while ($i < $length) {
      // pick a random character from the possible ones
      $char = substr($possible, mt_rand(0, $maxlength-1), 1);
      // have we already used this character in $password?
      if (!strstr($password, $char)) { 
        // no, so it's OK to add it onto the end of whatever we've already got...
        $password .= $char;
        // ... and increase the counter by one
        $i++;
      }
    }

    // done!
    return $password;

  }
  
}

  ?>
