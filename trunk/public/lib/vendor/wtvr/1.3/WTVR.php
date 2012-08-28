<?php

/**
 * WTVR.php, Styroform XML Form Controller
 * This class takes a request from the DISPATCH
 * And determines if it's a page or a page fragment (Module)
 * It then returns the XHTML, XML, XSL, or CONF as required
 * XML Form Generator and Validator
 * in-code documentation through DocBlocks and tags.
 * @author Andy Madsen <amadsen@motormeme.com>
 * @version 1.2
 * @package XMLForm
 */
// WTVR

/**
* General Utils
*/
include_once("WTVRUtils.php");

/**
* Base Error Class
*/
include_once("WTVRError.php");

/**
* Base Extension Class
*/
include_once("GlobalBase.php");

/**
* Extender for This Local Application
*/
include_once("wtvr/WTVRBase.php");

/**
* XML Form Utilites for string manipulation and flow control
*/
include_once("wtvr/WTVRGlobals.php");

/**
* Scope Extension Classes
* Gives Scoped Units a consistent interface
*/
include_once("PageBase.php");

/**
* XML Form Utilites for string manipulation and flow control
*/
include_once("ModuleBase.php");

/**
* XML Form Utilites for string manipulation and flow control
*/
include_once("WTVRSession.php");

/**
* XML Form Utilites for string manipulation and flow control
*/
include_once("WTVRCache.php");

/**
* XML Form Utilites for string manipulation and flow control
*/
include_once("WTVRData.php");

/**
* XML Form Utilites for string manipulation and flow control
*/
include_once("WTVRAdmin.php");


/**
* Scope Filter Classes
* Modifies Scoped Units Globally, returns DOMDocument
* Specifically instantiates Persistence Objects
* For each scope
*/
include_once("wtvr/WTVRBaseCache.php");

/**
* XML Form Utilites for string manipulation and flow control
*/
include_once("wtvr/WTVRBasePagesFilter.php");

/**
* XML Form Utilites for string manipulation and flow control
*/
include_once("wtvr/WTVRBaseMailsFilter.php");

/**
* XML Form Utilites for string manipulation and flow control
*/
include_once("wtvr/WTVRBaseModulesFilter.php");

/**
* XML Form Utilites for string manipulation and flow control
*/
include_once("wtvr/WTVRBaseNewslettersFilter.php");


//*******Mail Classes
include_once("wtvr/WTVRBaseMail.php");

/**
* XML Form Utilites for string manipulation and flow control
*/
include_once("wtvr/WTVRBaseMailTemplate.php");

/**
* Output And Transformation Classes
*/
include_once("lib/XMLIncludes.php");

/**
* XML Form Utilites for string manipulation and flow control
*/
include_once("lib/XSLIncludes.php");

/**
* XML Form Utilites for string manipulation and flow control
*/
include_once("lib/XSLTransform.php");

/**
 * The Form Controller class, responsible for drawing the form, calling the validator, adding "Items" and "ItemLists", and navigating through formsets
 * @package WTVR
 * @subpackage classes
 */
class WTVR extends WTVRBase {
	
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $TYPE 
  */
	var $TYPE;
	
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $SCOPE 
  */
	var $SCOPE;
	
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $ID 
  */
	var $ID;
	
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $PATH  
  */
	var $PATH;
	
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $CACHE
  */
	var $CACHE;
	
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $PARSE
  */
	var $PARSE;
	
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $ERROR  
  */
	var $ERROR;
	
	/** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $RESULT  
  */
	var $RESULT;
	
/**
* Form Constructor.
* The formsettings array should look like this, either passed in the constructor or via WTVR:
* Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
* <code> 
*  $fpo = something();
*  $fpo = something();
*  $fpo = something();
*  $fpo = something();
* </code>
* @name Constructor
* @param array $formsettings  - Array with both Formset, and XSL Doc
*/
  public function __construct() {
	  parent::__construct( null );
	  
    $this -> genVars();
    
	  $this -> localSettings();
	}
	
/**
* Form Destructor.
* The formsettings array should look like this, either passed in the constructor or via WTVR:
* Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
* <code> 
*  $fpo = something();
*  $fpo = something();
*  $fpo = something();
*  $fpo = something();
* </code>
* @name Destructor
* @param array $formsettings  - Array with both Formset, and XSL Doc
*/
	public function __destruct() {
	  parent::__destruct();
  }
  
  /**
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * <code> 
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  * </code>
  * @name initWTVR
  */
	public function initWTVR() {
	  $this -> loadLock();
	  
    if ($this -> LASTCORE)
      $this -> cachePreloader();
    
    //Include Method from WTVRBase
	  $this -> initWTVRBase();
	  
    $this -> accessFilter();
    $this -> errorFilter();
    $this -> returnTYPEData();
    return  $this -> RESULT;
  }
	
	/**
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * <code> 
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  * </code>
  * @name localSettings
  */
	private function localSettings() {
	  
    if (! isset($this -> TYPE)) {
      $this -> TYPE = "xhtml";
    }
    
  }
	
  /**
  * In case you want a client facing LOCKOUT page
  * Look for this in Global Base
  * <code> 
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  * </code>
  * @name loadLock 
  */
	private function loadLock() {
    if (! $this -> isAdminModule()) {
      if (($this -> locked) && (! isset($_SESSION["devel_key"]))) {
        include_once("unlock.php");
        die();
      }
    }
	}
  /**
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * <code> 
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  * </code>
  * @name cachePreloader
  */
	private function cachePreloader() {
    if (! $this -> isAdminModule()) {
      if ($this -> preload) {
        if  (! $_COOKIE["preloader"]) {
          include("preload.html");
          die();
        } else {
          if ($this -> LASTCORE > $_COOKIE["preloader"]) {
            include("preload.html");
            die();
          } 
        }
      }
    }
  }
  
  /**
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * <code> 
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  * </code>
  * @name accessFilter
  */
	private function accessFilter() {
	  if (! $this -> isUsable( $this -> ID, $this -> SCOPE )) {
      errorDirect('access');
    }
    
  }
  
  /**
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * <code> 
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  * </code>
  * @name errorFilter
  */
	private function errorFilter() {
	  //Use below if you want an actual "directory" lookup
    //if (! is_dir($this -> SCOPE."/".$this -> ID)) {
    
    $avar = $this -> SCOPE . "_ual";
    $checker = $this -> $avar;
    if (! in_array($this->ID,array_keys($checker))) {
      errorDirect();
    }
  }
  
  /**
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * <code> 
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  * </code>
  * @name returnTYPEData
  */
	private function returnTYPEData() {
	  switch (TRUE) {
			
	  case ($this -> TYPE != 'xhtml'):
		  $this -> dynamicContent( $this -> TYPE );
    break;
			
	  case 'xhtml':
		    
        $this -> CACHEOBJ = new WTVRBaseCache( $this -> VARIABLES );
			  
			  eval("\$this -> SCOPEOBJ = new WTVRBase".initcap($this -> SCOPE)."Filter( \$this -> VARIABLES );");
        
        //Filter XML Through a SCOPE if any
        //Returns an XMLAbstract DOM Object
        $this -> scopeFilter( "persistence" );
        
        $this -> dynamicContent( "persistence" );
        
        $this -> dynamicContent( "smarty" );
        
        //Do the CONF Method
        $this -> dynamicContent( "conf" );
        
        if ($this -> conf -> getPathValue("//parse") == "SMARTY") {
          $this -> PARSE = "SMARTY";
        }
        
        if ($this -> conf -> getPathValue("//cache") == "SMARTY") {
          $this -> CACHE = "SMARTY";
        }
        
        if (($this -> CACHE == "SMARTY") && file_exists($this -> docroot.$this->PATH."/smarty/templates/indexCache.tpl")) {
          $this -> CACHEOBJ -> outputCache("SMARTY", $this -> ID, $this -> SCOPELOC );
          die();
        }
        
        //If this is a smarty page, pass this over to the SMARTY template
        if (($this -> PARSE == "SMARTY") && ($this -> CACHE != "GEN")) {
	        $this -> CACHEOBJ -> VARIABLES = $this -> VARIABLES;
	        $this -> CACHEOBJ -> TYPE = "SMARTY";
          $this -> CACHEOBJ -> outputCache("SMARTY", $this -> ID, $this -> SCOPELOC, "index.tpl", $this -> p_object, $this -> conf );
          die();
        }
        
        //Do the XHTML Method
        $this -> dynamicContent( "xhtml" );
        
        //Do the XML Method
        $this -> dynamicContent( "xml", $this -> conf );
	      
	      //Generate our XML Based on CONF and XHTML and XML
        //Should return a full DOMDocument
        
        $this -> source = $this -> fetchXML('xml',$this -> conf,$this -> source,$this -> p_object,$this -> VARIABLES);
        
        if (($this -> CACHE == "SMARTY") || ($this -> CACHE == "GEN")) {
          //We pull the "CONF" Value from the XML Transformation
          $this -> CACHEOBJ = new WTVRBaseCache();
          $this -> CACHEOBJ -> VARIABLES = $this -> VARIABLES;
          $this -> CACHEOBJ -> CACHEBASEOBJ = $this -> CACHEBASEOBJ;
    		  $this -> CACHEOBJ -> TYPE = $this -> CACHE;
    		}
        
        //Filter XML Through a SCOPE if any
        //Returns an XMLAbstract DOM Object
        $this -> source = $this -> scopeFilter();
        
        $this -> postProcessConf( $this -> source, $this -> p_object );
		    
		    $thetrans = new XSLTransform( $this -> VARIABLES );
				
        //Pass the object to the Transformer
        $thetrans -> XMLOBJ = $this -> source;
        $thetrans -> GENERATOR = $this -> generator;
        
        //Do the XSL Method
        $this -> dynamicContent( "xsl" );
  			
        $thetrans -> XSL = $this -> xsl;
        $thetrans -> SCOPE = $this -> SCOPELOC;
        
        $result = $thetrans -> drawpage ( $this -> outputType, $this -> CACHEOBJ, $this -> p_object );
  			//die();
  		  if ($this -> debug) {
  		    $result = $result . WTVRAdmin::adminToggle();
          $result = $result . WTVRAdmin::adminControl();
          $result = $result . WTVRAdmin::debugOutput();
        }
        
        $this -> RESULT = $result;
        
        break;
			
		}
	}
  
  /**
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * <code> 
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  * </code>
  * @name dynamicContent  
  * @param string $type - FPO copy here
  * @param string $conf - FPO copy here
  */
  public function displayPage() {
    print($this -> RESULT);
  }
  
  /**
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * <code> 
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  * </code>
  * @name dynamicContent  
  * @param string $type - FPO copy here
  * @param string $conf - FPO copy here
  */
  private function dynamicContent( $type, XML $conf = null ) {
    //Check for xml trigger in class
    //Stupid naming convention thing...
    
    if (($this -> checkClass($type,$this->SCOPELOC,$this->ID)) || $this -> isAdminModule() ) {
      //Instantiate the variable object
      //From the /$scope/$pagename/classes/ directory
      
      if ( $this -> isAdminModule() ) {
        include_once("modules/admin/classes/class_admin_mod.php");
      }
      
      eval("\$this -> theinclude = new ".$this -> ID.$this -> getScopeSig( $this -> SCOPE )."( \$this -> p_object, \$this -> VARIABLES );");
      
      //dump($this -> ID.$this -> scopesig.";");
      //switch on $type
      switch ($type) {
        
        case "persistence":
        
        $this -> p_object = $this -> theinclude -> persistence( $this -> p_object );
        break;
        
        case "xml":
        $this -> source = $this -> theinclude -> xml();
        break;
        
        case "xsl":
        $this -> xsl = $this -> theinclude -> xsl();
        break;
        
        case "xhtml":
        $this -> theinclude -> xhtml();
        break;
        
        case "conf":
        $this -> conf = $this -> theinclude -> conf();
        break;
        
        case "smarty":
        $this -> s_object = $this -> theinclude -> smarty( $this -> CACHEOBJ -> TEMPLATE, $this -> p_object );
        break;
        
      }
    } else {
      switch ($type) {
        case "persistence":
        return false;
        break;
        
        case "xml":
        if (($conf) && ($this -> SCOPE == "pages")) {
          $thecompiler = new XML;
          $thecompiler -> loadXML( $conf -> getPathValue("//xmltemplate") );
          $this -> source = $thecompiler; 
        } else {
          $this -> source = $this -> initXML($this -> SCOPELOC . "/" . $this -> ID, "xml");
        }
        break;
        
        case "xsl":
        $this -> xsl = $this -> fetchXML('xsl',$this -> conf,null,null,$this -> VARIABLES);
        break;
        
        case "xhtml":
        return false;
        break;
        
        case "conf":
        $this -> conf = $this -> initXML($this -> PATH, "conf");
        break;
        
        case "smarty":
        return false;
        break;
      }
      
    }
  }
  
  /**
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * <code> 
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  * </code>
  * @name scopeFilter 
  * @param string $thexml
  */
  private function scopeFilter( $action="xml" ) {
      
      //Filter XML Through a SCOPE if any
      //Send the object of persistence
     switch ($action) {
        case "persistence":
        $this -> p_object = $this -> SCOPEOBJ -> persistence();
        break;
        case "xml":
        return $this -> SCOPEOBJ -> xml( $this -> source, $this -> p_object );
        break;
      }
        
  }
}
?>
