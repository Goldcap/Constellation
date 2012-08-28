<?php

/**
 * XSLIncludes.php, Styroform XML Form Controller
 * This class takes a module ID from the WTVR database and transforms the
 * xml and xsl into an xhtml code fragment via xsltransform
 * Then dumps the result
 * XML Form Generator and Validator
 * in-code documentation through DocBlocks and tags.
 * @author Andy Madsen <amadsen@motormeme.com>
 * @version 1.2
 * @package XMLForm
 */
// XSLIncludes

/**
 * The Form Controller class, responsible for drawing the form, calling the validator, adding "Items" and "ItemLists", and navigating through formsets
 * Creates the Message Body from a specific template
 * And puts it in the mail class Property "body"
 * @package XSLInclude
 * @subpackage classes
 */
class XSLInclude extends WTVRBase {
	
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
  * @name $XML_CONF_OBJ 
  */
	var $XML_CONF_OBJ;
	
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $MODULES 
  */
	var $MODULES;
	
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $XSL_TEMPLATES
  */
	var $XSL_TEMPLATES;
	
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $XSL_TEMPLATE_URI
  */
	var $XSL_TEMPLATE_URI;

  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $docArray  
  */
	var $XSL_TEMPLATE_LOCAL;
	
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $XSL 
  */
	var $XSL;
	
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $XSL_LOCAL_URI
  */
	var $XSL_LOCAL_URI;
	
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $XSL_LOCAL 
  */
	var $XSL_LOCAL;
	
	  
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
	function __construct( $vars ) {
		parent::__construct( $vars );
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
  * @name  IncludeModules
  * @param string $conf - FPO copy here
  * @param string $object
  */
	function IncludeModules( $conf, $object=false ) {
	  
		$this -> XML_CONF_OBJ = $conf;
		
    $this -> processConf();
		
		$this -> MODULES = $conf -> documentElement -> getElementsByTagName('module');
		$this -> XSL_TEMPLATE_URI = $conf -> getPathValue('xsltemplate');
		$this -> XSL_TEMPLATE_LOCAL = (boolean) $conf -> getPathAttribute('//xsltemplate','local');
		
		if ($object) {
		  $this -> XSL = $object;
    } else {
  		$this -> readXSLTemplate();
    }
		
		$this -> addLocalXSL();
		$this -> addModuleNodes();
		
		return $this -> XSL;
	}
  
	function processConf() {
      $this -> XML_CONF_OBJ = $this -> preProcessConf( $this -> XML_CONF_OBJ );
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
  * @name  processBase
  */
  function processBase() {
    //Close our Base Function
    if ($this -> SCOPE == "pages") {
      $this -> preProcessModules();
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
  * @name  addModuleToConf
  * @param string $name - FPO copy here
  * @param string $parse - FPO copy here
  * @param string $before - FPO copy here
  */
	function addModuleToConf($name,$parse,$before) {
    $TEMPELEMENT = $this -> XML_CONF_OBJ -> documentElement -> createElement("module");
    $TEMPPOS = $this -> XML_CONF_OBJ -> documentElement -> getElementsByTagName('modules');
    $XPATH = new DomXPath($this -> XML_CONF_OBJ -> documentElement); 
    $REFNODES = $XPATH -> query("//module[0]");
    $REFNODE = $REFNODES -> item(0);
		$TEMPPOS -> item(0) -> insertBefore($TEMPELEMENT, $REFNODE);
		$TEMPELEMENT->setAttribute("name",$name);
		$TEMPELEMENT->setAttribute("parse",$parse);
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
  * @name  readXSLTemplate
  */
	function readXSLTemplate() {
    $this -> XSL = new XML();
    if ($this -> XSL_TEMPLATE_LOCAL) {
      $this -> XSL -> loadXML($this -> rootdir.$this -> XSL_TEMPLATE_URI);
    } else {
      $this -> XSL -> loadXML($this -> libroot."wtvr/".$GLOBALS["wtvr_version"]."/".$this -> XSL_TEMPLATE_URI);
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
  * @name  addModuleNodes
  * @param string $modules
  */
	function addModuleNodes( $modules = true ) {
	  //Initialize the WTVR and STYROFORM Frameworks
    $attribs["href"] = "file:///".$this -> xslroot."xsl/includes.xsl";
  	$this -> XSL -> createSingleElement("include","stylesheet",$attribs);
    
    //Add project-level XSL includes, if there area any
    if (file_exists($this -> local_libroot."xsl/includes.xsl")) {
      $attribs["href"] = "file:///".$this -> local_libroot."xsl/includes.xsl";
  	  $this -> XSL -> createSingleElement("include","stylesheet",$attribs);
    }
    
    if ($modules) {
  		foreach ($this -> MODULES as $node) {
  			if ($node->getAttribute('parse') != "CLIENT") {
  			  $attribs["href"] = "file:///".$this -> docroot."/modules/".$node->getAttribute('name')."/xsl/index.xsl";
    	    $this -> XSL -> createSingleElement("include","stylesheet",$attribs);
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
  * @name  addLocalXSL
  */
	function addLocalXSL() {
		//echo $this -> XSL_LOCAL;
		$attribs["href"] ="file:///".$this -> docroot."/".$this -> SCOPE."/".$this -> ID."/xsl/index.xsl";
  	$this -> XSL -> createSingleElement("include","stylesheet",$attribs);
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
	function __destruct() {
  }
  
}
?>
