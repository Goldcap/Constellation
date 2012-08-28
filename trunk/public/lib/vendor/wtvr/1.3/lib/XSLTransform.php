<?php

/**
 * xsltransform.php, Styroform XML Form Controller
 * This class takes XML and XSL strings, fetched via CDXML
 * and transforms them into XHTML
 * XML Form Generator and Validator
 * in-code documentation through DocBlocks and tags.
 * @author Andy Madsen <amadsen@motormeme.com>
 * @version 1.2
 * @package XMLForm
 */
// xsltransform

/**
 * The Form Controller class, responsible for drawing the form, calling the validator, adding "Items" and "ItemLists", and navigating through formsets
 * Creates the Message Body from a specific template
 * And puts it in the mail class Property "body"
 * @package xsltransform
 * @subpackage classes
 */
class xsltransform extends WTVRBase {

/** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $XMLOBJ 
  */	
  var $XMLOBJ;
  
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
  * @name $p_object  
  */
	var $p_object;
	
	  
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
  * @name readXSL 
  */
	function readXSL() {  
		if($this -> SCOPE == "modules") {
		  
      $attribs["href"] = "file:///".$this -> xslroot."xsl/includes.xsl";
  		$this -> XSL -> createSingleElement("include","stylesheet",$attribs);
  		
      //Add project-level XSL includes, if there area any
      if (file_exists($this -> local_libroot."xsl/includes.xsl")) {
        $attribs["href"] = "file:///".$this -> local_libroot."xsl/includes.xsl";
  		  $this -> XSL -> createSingleElement("include","stylesheet",$attribs);
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
  * @name  drawpage
  * @param string $outputType - FPO copy here
  * @param string $cache - FPO copy here
  * @param string $object - FPO copy here
  */
	function drawpage ( $outputType='screen', $cache = null, $object = null ) {
		
    $this -> readXSL();
		
		$this -> p_object = ($object) ? $object : false;
    
		$this -> addGenericAttributes( $this -> XMLOBJ );
		
    if (($this -> dumpXML()) || ($this -> debug && $_GET["showXML"] == "true")) {
      $this -> showXML();
    }
    
    if (($this -> dumpXSL()) || ($this -> debug && $_GET["showXSL"] == "true")) {
      $this -> showXSL();
    }
    
    $xsl = new XsltProcessor();
		$this -> addXSLNamespace( $this -> XSL -> documentElement );
		
		if (! $xsl->importStylesheet( $this -> XSL -> documentElement )) {
      //errorDirect();
    }
    
    if (($cache -> TYPE == "SMARTY") && ($this -> SCOPE == "pages")) {
        $this -> createModulesCache( $xsl, "indexCache.tpl", $cache );
    }
     
    if ($cache -> TYPE == "GEN") {
      $this -> addGenericAttributes( $cache -> CACHEBASEOBJ );
	  
	    $baseresult = $xsl->transformToXML($cache -> CACHEBASEOBJ -> documentElement);
      
      $cache -> createSMARTYCache( $baseresult, $this -> SCOPE , $cache -> VARIABLES["ID"], "indexGen.tpl", true );
    }
    
    $result = $xsl->transformToXML($this -> XMLOBJ -> documentElement);
    
    switch ($outputType) {
		  case 'text':
		  break;
      
      case 'screen':
      //echo($result);
      break;
      
      default:
      //echo($result);
      break;
    }
    
    if ($cache -> TYPE == "HTML") {
      $cache -> createSMARTYCache( $result, $this -> SCOPE , $cache -> VARIABLES["ID"] );
    }
    
    //This really just creates your smarty skeleton
    //But once the template is created, you won't hit it again...
    if ($cache -> TYPE == "SMARTY") {
      $cache -> createSMARTYCache( $result, $this -> SCOPE , $cache -> VARIABLES["ID"], "indexCache.tpl", true );
    }
    
    return $result;
    
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
  * @name  addGenericAttributes
  * @param string $obj 
  */
	function addGenericAttributes( $obj ) {
    
    //FYI, this doesn't seem to do anything...
    if (! @$obj -> documentElement -> xinclude()) {
      //errorDirect();
    }
    
		switch ($this -> GENERATOR) {
      case "fragment":
      $rootname = "ROOT";
      break;
      default:
      $rootname = "PAGE";
      break;
    }
    
    switch ($this -> SCOPE) {
      case "modules":
      //Tell the XML what SCOPE it's being read from
		  $obj -> setPathAttribute("//module",0,"scope",$this -> SCOPE);
      break;
      default:
      $obj -> setPathAttribute("//page",0,"scope",$this -> SCOPE);
      break;
    }
		
    //Do some final cleanup for the kids:
		if (($obj -> getPathAttribute("//".$rootname,"baseurl") == "") && ($this -> SCOPE != "modules")) {
      if (strlen($this -> asseturl) > 0) {
        $base = $this -> hostname.$this -> asseturl."/";
      } else {
        $base = $this -> hostname."/";
      }
      $obj -> setPathAttribute("//".$rootname,0,"baseurl",$base);
    }
    
    //Do some final cleanup for the kids:
		if ($obj -> getPathAttribute("//".$rootname,"hostname") == "") {
      $obj -> setPathAttribute("//".$rootname,0,"hostname",$this -> hostname."/");
    }
    
    $obj -> setPathAttribute("//".$rootname,0,"generator",$this -> GENERATOR);
    $obj -> setPathAttribute("//".$rootname,0,"script",$_SERVER["REQUEST_URI"]);
    $obj -> setPathAttribute("//".$rootname,0,"docroot",$this -> docroot);
    
    //I know it's nuts, but I'm adding the browser to the XML!
    $browserinfo = explode(" ", $_SERVER["HTTP_USER_AGENT"]);
    if (preg_match("/MSIE/",$_SERVER["HTTP_USER_AGENT"])) {
      $browser[0] = "Explorer";
      $browser[1] = str_replace(";","",$browserinfo[3]);
    } elseif (preg_match("/Firefox/",$_SERVER["HTTP_USER_AGENT"])) {
      $browser[0] = "Firefox";
      $browserparse = explode("/",$browserinfo[9]);
      $browser[1] = $browserparse[1];
    } elseif (preg_match("/Safari/",$_SERVER["HTTP_USER_AGENT"])) {
      $browser[0] = "Safari";
      $browserparse = explode("/",$browserinfo[12]);
      $browser[1] = $browserparse[1];
    }
    
    $obj -> setPathAttribute("//".$rootname,0,"browsername",$browser[0] );
    $obj -> setPathAttribute("//".$rootname,0,"browserversion",$browser[1] );
    
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
  * @name  createModulesCache
  * @param string $xsl - FPO copy here
  * @param string $filename - FPO copy here
  * @param string $cache - FPO copy here
  */
	function createModulesCache( $xsl, $filename, $cache ) {
    $nodes = $this -> XMLOBJ -> getElementsByTagname("module");
  	
  	foreach ($nodes as $node) {
      $THISNAME=$node->getAttribute('name');
      $THISPARSE=$node->getAttribute('parse');
      
      switch ($THISPARSE) {
        case "HTML":
        if ($node->getAttribute("baseurl")) {
          $thebase = $node->getAttribute("baseurl");
        } else {
          $thebase = "modules/".$THISNAME."/html/index.html";
        }
        $modulehtml = file_get_contents ( $this -> docroot.$thebase );
        break;
        
        case "SMARTY":
        if ($node->getAttribute("baseurl")) {
          $thefile = $node->getAttribute("baseurl");
        } else {
          $thefile = "index.tpl";
        }
        $modulehtml = $cache->drawSmartyTemplate($THISNAME,"modules",$this -> p_object,$thefile);
        break;
        
        default:
        $xml = new XML();
        $anode = $xml -> documentElement -> importNode($node, true );
        $xml -> documentElement -> appendChild($anode);
        $modulehtml = $xsl->transformToXML($xml -> documentElement);
        break;
        
      }
      
      switch ($cache -> TYPE) {
    
        case "HTML":
        $cache -> createHTMLCache( $modulehtml, "modules" , $THISNAME );
        break;
        
        case "SMARTY":
        $cache -> createSmartyTemplate($THISNAME,"modules",$modulehtml,$filename,true);
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
  * @name  addXSLNamespace
  * @param string $XSL 
  */
	function addXSLNamespace( $XSL ) {
	  //This is thanks to PHP's terrible handling of Namespaces
    //Force every "include" to be an "xsl:include"
    $result = $XSL -> saveXML();
    $result = str_replace("<include","<xsl:include",$result);
    $XSL -> loadxml($result);
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
  * @name  showXML
  */
	function showXML () {
	  $this -> XMLOBJ -> saveXML();
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
  * @name  showXSL
  */
	function showXSL () {
	  $this -> XSL -> saveXML();
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
