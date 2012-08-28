<?php

/**
 * XMLInclude.php, Styroform XML Form Controller
 * This class takes a module ID from the WTVR database and transforms the
 * xml and xsl into an xhtml code fragment via xsltransform
 * Then dumps the result
 * XML Form Generator and Validator
 * in-code documentation through DocBlocks and tags.
 * @author Andy Madsen <amadsen@motormeme.com>
 * @version 1.2
 * @package XMLInclude
 */
// WTVRUtils

/**
 * The Form Controller class, responsible for drawing the form, calling the validator, adding "Items" and "ItemLists", and navigating through formsets
 * Creates the Message Body from a specific template
 * And puts it in the mail class Property "body"
 * @package XMLInclude
 * @subpackage classes
 */
class XMLInclude extends WTVRBase {
	
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
  * @name $conf  
  */
	var $conf;
	
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $XML_CONF  
  */
	var $XML_CONF;
	
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $XML_TEMPLATE  
  */
	var $XML_TEMPLATE;
	
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $BASEURL 
  */
	var $BASEURL;
	
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $TITLE  
  */
	var $TITLE;
	
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $VARIABLES 
  */
	var $VARIABLES;
	
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
  * @name $CACHETYPE
  */
  var $CACHETYPE;
  
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $CACHEBASEOBJ
  */
	var $CACHEBASEOBJ;
	
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $XML_SOURCE 
  */
	var $XML_SOURCE;
	
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $XML_SOURCE_LOC 
  */
	var $XML_SOURCE_LOC;
	
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
  * @name $MODULEELEMENT 
  */
  var $MODULEELEMENT;
  
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $MODULENODE  
  */
  var $MODULENODE;
  
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $JS 
  */
  var $JS;
  
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $CSS  
  */
  var $CSS;
  
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $CACHEMODULES  
  */
  var $CACHEMODULES;
  
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $CACHEMODULEELEMENT 
  */
  var $CACHEMODULEELEMENT;
  
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $CACHEMODULENODE 
  */
  var $CACHEMODULENODE;
  
  /** 
  * Array of XML Forms in an ordered FormSet
  * A Generic Pass-Through Object for Modules
  * @access public
  * @var array
  * @name $p_object  
  */
	var $p_object;
	
  /**
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * <code> 
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  * </code>
  * @name  XMLInclude
  */
	function XMLInclude( $vars ) {
	  parent::__construct( $vars );
	  $this -> debug = false;
	}

  /**
  * Source is the XML Template of a Page. 
  * If none provided, get it from the basic index.xml
  * <code> 
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  * </code>
  * @name  IncludeModules
  * @param string $conf - FPO copy here
  * @param string $source - FPO copy here
  * @param string $object - FPO copy here
  * @param string $vars - FPO copy here
  * @param string $classes - FPO copy here
  */
	function IncludeModules($conf,$source=false,$object=false,$vars,$classes) {
	  $this -> VARIABLES = $vars;
    $this -> classes = $classes; 
	  
    $this -> p_object = ($object) ? $object : false;
    
    //Pass this to the local application Controller for PreProcessing, if any
    $this -> conf = $this -> preProcessConf( $conf );
    if ($this -> conf -> getPathValue('documentConf')) {
      $this -> documentConf = $this -> conf -> getPathValue('documentConf');
    }
    
    $this -> TITLE = $this -> conf -> getPathValue('title');
    $this -> MODULES = $this -> conf -> documentElement -> getElementsByTagName('module');
		$this -> CSS = $this -> conf -> documentElement -> getElementsByTagName('CSSCRIPT');
		$this -> JS = $this -> conf -> documentElement -> getElementsByTagName('JSCRIPT');
		
		
    $this -> XML_TEMPLATE = $this -> conf -> getPathValue('xmltemplate');
		$this -> BASEURL = $this -> conf -> getAttributeValueByPath("//page","baseurl");
    
    if ($this -> conf -> getPathValue("//cache") == "SMARTY") {
  	 $this -> CACHETYPE = "SMARTY";	
    }
    
    if ($this -> CACHE == "SMARTY" || $this -> CACHE == "GEN") {
      $this -> CACHETYPE = $this -> CACHE;	
    }
    
  	$this -> readXMLSource();
		
    $this -> addBaseID();
		
		$this -> addDocumentConf();
		
		$this -> addTitle();
		
		$this -> addCss();
		
    $this -> addJs();
		
		$this -> XML_SOURCE_LOC = $this -> XML_SOURCE -> documentElement -> getElementsByTagName('BODY');
		
		$this -> addModuleNode();
		
		$this -> createCacheObj();
		
		$this -> processModules();
		
		return $this -> XML_SOURCE;
		
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
  * @name  readXMLSource
  */
	function readXMLSource() {
	  //Try to Parse the Page Template we were passed
    $this -> XML_SOURCE = new XML();
    
    if (! $source ) {
  	
  		if (! @$this -> XML_SOURCE -> loadXML($this -> docroot.$this -> XML_TEMPLATE)) {
  			errorDirect();
        die ("Error while parsing the xml document in ".$this -> docroot.$this -> XML_TEMPLATE."\n");
  		}
  	
  	} else {
      
  		if (! $this -> XML_SOURCE = $source ) {
  			die ("Error while parsing the xml page template\n");
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
  * @name  createCacheObj
  */
	function createCacheObj() {
    $this -> CACHEBASEOBJ = new XML();
  	if (($this -> CACHETYPE == "SMARTY") || ($this -> CACHETYPE == "GEN")) {
      $root = $this -> CACHEBASEOBJ -> documentElement -> importNode ( $this -> XML_SOURCE -> documentElement -> firstChild, true );
  		if (! $this -> CACHEBASEOBJ -> documentElement -> appendChild($root)) {
      	errorDirect();
        die ("Error while createing xml cache document in ".$this -> docroot.$this -> XML_TEMPLATE."\n");
  		}
  		$this -> CACHEMODULEELEMENT = $this -> CACHEBASEOBJ -> documentElement -> getElementsByTagName("MODULES") -> item(0);
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
  * @name  addBaseID
  */
	function addBaseID() {
	   $this -> XML_SOURCE -> setPathAttribute("//PAGE",0,"name", $this -> ID );
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
  * @name  addBaseID
  */
	function addDocumentConf() {
	   $this -> XML_SOURCE -> setPathAttribute("//PAGE",0,"docconf", $this -> documentConf );
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
  * @name  addTitle
  */
  function addTitle() {
    $this -> TITLESPACE = $this -> XML_SOURCE -> documentElement -> getElementsByTagName('TITLE');
    if ($this -> TITLE -> length > 0) {
      $this -> TITLESPACE -> item(0) -> nodeValue = $this -> TITLE -> item(0) -> nodeValue;
    }
    
    $localtitle = $this -> XML_SOURCE -> documentElement -> getElementsByTagName('TITLE');
    
    if (($localtitle -> length > 0) && (strlen($localtitle -> item(0) -> nodeValue))) {
      $this -> TITLESPACE -> item(0) -> nodeValue = $localtitle -> item(0) -> nodeValue;
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
  * @name  addCss
  */
  function addCss() {
    $this -> DESTINATION = $this -> XML_SOURCE -> documentElement -> getElementsByTagName('HEAD');
    foreach ($this -> CSS as $node) {
      $TEMPCSSBASE = $this -> XML_SOURCE -> documentElement -> createElement("CSSCRIPT");
		  $TEMPCSSBASE -> nodeValue = "/pages/".$this -> ID."/html/css/".$node -> nodeValue;
		  $TEMPCHILDBASE = $this -> DESTINATION -> item(0) -> appendChild($TEMPCSSBASE);
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
  * @name  addJs
  */
  function addJs() {
    $this -> DESTINATION = $this -> XML_SOURCE -> documentElement -> getElementsByTagName('HEAD');
    foreach ($this -> JS as $node) {
      $TEMPCSSBASE = $this -> XML_SOURCE -> documentElement -> createElement("JSCRIPT");
		  $TEMPCSSBASE -> nodeValue = "/pages/".$this -> ID."/html/js/".$node -> nodeValue;
		  $TEMPCHILDBASE = $this -> DESTINATION -> item(0) -> appendChild($TEMPCSSBASE);
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
  * @name  addModuleNode
  */
	function addModuleNode() {
	  $this -> MODULEELEMENT = $this -> XML_SOURCE -> documentElement -> createElement("MODULES");
    $this -> MODULENODE = $this -> XML_SOURCE_LOC -> item(0) -> appendChild($this -> MODULEELEMENT);
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
  * @name  processModules
  */
	function processModules() {
	  foreach ($this -> MODULES as $node) {
      $THISNAME=$node->getAttribute('name');
      $THISPARSE=$node->getAttribute('parse');
      $THISBASE=$node->getAttribute('baseurl');
      $THISUAL=$node->getAttribute('ual');
      $THISGROUP= (strlen($node->getAttribute('group'))) ? $node->getAttribute('group') : "zzdiv" ;
      if ($this -> debug) { echo "Adding ".$THISNAME." as ".$THISPARSE ." to ".$THISGROUP."<br />";}
      //Pass along the node just in case;
      $this -> addInclude($THISPARSE,$THISNAME,$THISGROUP,$node);
      
      if ($this -> CACHETYPE == "GEN") {
        
        if ($this -> debug) { echo "Adding ".$THISNAME." as SMARTY_INCLUDE to ".$THISGROUP." via the GEN OBJECT<br />";}
        
        $this -> addSmartyCacheInclude($THISPARSE,$THISNAME,$THISGROUP,$node);
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
  * @name  addInclude
  * @param string $THISPARSE - FPO copy here
  * @param string $THISNAME - FPO copy here
  * @param string $THISGROUP - FPO copy here
  * @param string $node - FPO copy here
  */
  function addInclude($THISPARSE,$THISNAME,$THISGROUP,$node) {
    if ($this -> debug) {echo("Access level for ".$THISNAME."=".$this -> module_ual[$THISNAME]."<br />");}
    
    if ($this -> debug) {echo("Your access is:". $this -> access."<br />");}
    if ($this -> isUsable($THISNAME)) {
      if ($this -> debug) { echo "Added ".$THISNAME."<br />";}
      switch ($THISPARSE) {
        case 'ASYNC' :
        $this -> addAsyncInclude($THISPARSE,$THISNAME,$THISGROUP,$node);
        break;
  		  case 'SMARTY' :
        $this -> addSmartyInclude($THISPARSE,$THISNAME,$THISGROUP,$node);
        break;
        case  'ACTIVE' :
        $this -> addActiveInclude($THISPARSE,$THISNAME,$THISGROUP);
        break;
        case 'HTML':
        $this -> addHTMLInclude($THISPARSE,$THISNAME,$THISGROUP,$node);
        break;
        default:
        $this -> addPassiveInclude($THISPARSE,$THISNAME,$THISGROUP);
  		  break;
      }
    } else {
      if ($this -> debug) { echo "Didn't Add ".$THISNAME."<br />";}
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
  * @name  addAsyncInclude
  * @param string $parse - FPO copy here
  * @param string $name - FPO copy here
  * @param string $group - FPO copy here
  * @param string $node - FPO copy here
  */
  function addAsyncInclude($parse,$name,$group,$node) {
    
    $TEMPELEMENTBASE = $this -> XML_SOURCE -> documentElement -> createElement("AMODULE");
		$TEMPCHILDBASE = $this -> MODULEELEMENT -> appendChild($TEMPELEMENTBASE);
		$this -> extendGroups($this -> XML_SOURCE, $this -> MODULEELEMENT, $group,$TEMPCHILDBASE);
		$TEMPCHILDBASE -> setAttribute("parse", $parse);
		
    $TEMPELEMENT = $this -> XML_SOURCE -> documentElement -> createElement("module");
		$TEMPCHILD = $TEMPCHILDBASE -> appendChild($TEMPELEMENT);
		
    //$TEMPCHILD -> setAttribute("xmlns:xi", "http://www.w3.org/2001/XInclude");
		$TEMPCHILD -> setAttribute("name", $name);
		//Due to a messy HTTPD CONF, you need to append "/" to the end of the service name
		if ($node->getAttribute("baseurl")) {
      $thebase = "http://".$_SERVER["SERVER_NAME"].$this -> asseturl.$node->getAttribute("baseurl");
    } else {
      $thebase = "http://".$_SERVER["SERVER_NAME"].$this -> asseturl."/services/".$name."/";
    }
    $TEMPCHILD -> setAttribute("baseurl", $thebase );
    
    if ($node->getAttribute("querystring")) {
      $thebase .= "&".str_replace("|","&",$node->getAttribute("querystring"));
    }
    
    $TEMPCHILD -> setAttribute("basehref", $thebase );

    //$TEMPCHILD -> setAttribute("baseurl", "/services/".$name."/");
		
    if ($node->getAttribute("onLoadEnd")) {
      $TEMPCHILD -> setAttribute("onLoadEnd", $node->getAttribute("onLoadEnd"));
    }
    
		//Read the CONF and add any assets to an "Attributes" Node
		$TEMPATTRIBS = $this -> XML_SOURCE -> documentElement -> createElement("attributes");
		$TEMPELEMENT -> appendChild($TEMPATTRIBS);
		$thisdom = new DOMDocument();
		$thisdom -> load($this -> docroot."modules/".$name."/conf/index.xml");
		$importNode = $thisdom -> getElementsByTagname("assets") -> item(0);
    
    //Likewise Get Any Attributes and Port As Well
    $RBVAL = $thisdom -> childNodes -> item(0) -> getAttribute("resourcebase");
    if ($RBVAL) {
      $TEMPCHILD -> setAttribute("resourcebase", $RBVAL);
    }
    
    $TEMPIMPORTCHILD = $this -> XML_SOURCE -> documentElement -> importNode( $importNode , true );
    $TEMPATTRIBS -> appendChild($TEMPIMPORTCHILD);
    
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
  * @name  addPassiveInclude
  * @param string $parse - FPO copy here
  * @param string $name - FPO copy here
  * @param string $group - FPO copy here
  */
	function addPassiveInclude($parse,$name,$group) {
    $TEMPELEMENTBASE = $this -> XML_SOURCE -> documentElement -> createElement("AMODULE");
		$TEMPCHILDBASE = $this -> MODULEELEMENT -> appendChild($TEMPELEMENTBASE);
		$this -> extendGroups($this -> XML_SOURCE, $this -> MODULEELEMENT,$group,$TEMPCHILDBASE);
		$TEMPCHILDBASE -> setAttribute("parse", $parse);
		
		$thisdom = new DOMDocument();
		$thisdom -> load($this -> docroot.$this -> BASEURL ."modules/".$name."/xml/index.xml");
		$TEMPELEMENT = $thisdom -> getElementsByTagname("module") -> item(0);
    
    //$TEMPELEMENT = $this -> XML_SOURCE -> documentElement -> createElement("xi:include");
		$TEMPIMPORTCHILD = $this -> XML_SOURCE -> documentElement -> importNode( $TEMPELEMENT , true );
    $TEMPCHILD = $TEMPCHILDBASE -> appendChild($TEMPIMPORTCHILD);
		
		//Do we need this?
    //$TEMPCHILD -> setAttribute("baseurl", $this -> BASEURL . "/modules/".$name."/xml/index.xml");
		
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
  * @name  addSmartyInclude
  * @param string $parse - FPO copy here
  * @param string $name - FPO copy here
  * @param string $group - FPO copy here
  * @param string $node - FPO copy here
  */
  function addSmartyInclude($parse,$name,$group,$node) {
    $TEMPELEMENTBASE = $this -> XML_SOURCE -> documentElement -> createElement("AMODULE");
		$TEMPCHILDBASE = $this -> MODULEELEMENT -> appendChild($TEMPELEMENTBASE);
		$this -> extendGroups($this -> XML_SOURCE, $this -> MODULEELEMENT,$group,$TEMPCHILDBASE);
		
    $TEMPCHILDBASE -> setAttribute("parse", $parse);
		$smartyelement = $this -> XML_SOURCE -> documentElement -> createElement("module");
		$smartyelement -> setAttribute("parse", $parse);
		$smartyelement -> setAttribute("name", $name);
		
		if ($node->getAttribute("baseurl")) {
      $thefile = $node->getAttribute("baseurl");
    } else {
      $thefile = "index.tpl";
    }
    
    $object = new WTVRCache( $this -> VARIABLES );
    
    $output = $object->drawSmartyTemplate($name,"modules",$this -> p_object,$thefile);
    $outputelement = $this -> XML_SOURCE -> documentElement -> createCDATASection( $output );
    $smartyelement -> appendChild($outputelement);
    $smartybase = $TEMPELEMENTBASE -> appendChild($smartyelement);
		
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
  * @name  addActiveInclude
  * @param string $parse - FPO copy here
  * @param string $name - FPO copy here
  * @param string $group - FPO copy here
  */
  function addActiveInclude($parse,$name,$group) {
    if ($this -> checkClass( "xml", "modules", $name )) {
      
      eval("\$theinclude = new ".$name."_mod(\$this -> p_object, \$this -> VARIABLES);");
      $domdoc = $theinclude -> xml();
      
      if ($domdoc) {
        $TEMPCHILD = $this -> XML_SOURCE -> documentElement -> importNode( $domdoc -> documentElement -> getElementsByTagname("module") -> item(0), true );
  		  $TEMPELEMENTBASE = $this -> XML_SOURCE -> documentElement -> createElement("AMODULE");
    		$TEMPCHILDBASE = $this -> MODULEELEMENT -> appendChild($TEMPELEMENTBASE);
    		$this -> extendGroups($this -> XML_SOURCE, $this -> MODULEELEMENT,$group,$TEMPCHILDBASE);
    		$TEMPCHILDBASE -> setAttribute("parse", $parse);
    		
        $TEMPCHILDBASE -> appendChild($TEMPCHILD);
  		  $TEMPCHILD -> removeAttribute("baseurl");
  		  $TEMPCHILD -> setAttribute("baseurl", $this -> BASEURL . "/modules/".$name."/");
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
  * @name  addHTMLInclude
  * @param string $parse - FPO copy here
  * @param string $name - FPO copy here
  * @param string $group - FPO copy here
  * @param string $node - FPO copy here
  */
  function addHTMLInclude($parse,$name,$group,$node) {
    $TEMPELEMENTBASE = $this -> XML_SOURCE -> documentElement -> createElement("AMODULE");
		$TEMPCHILDBASE = $this -> MODULEELEMENT -> appendChild($TEMPELEMENTBASE);
		$this -> extendGroups($this -> XML_SOURCE, $this -> MODULEELEMENT,$group,$TEMPCHILDBASE);
		
    $TEMPCHILDBASE -> setAttribute("parse", $parse);
		$htmlelement = $this -> XML_SOURCE -> documentElement -> createElement("module");
		$htmlelement -> setAttribute("name", $name);
		$htmlelement -> setAttribute("parse", $parse);
		$htmlelement -> setAttribute("disable-output-escaping", "yes");
		
    if ($node->getAttribute("baseurl")) {
      $thebase = $node->getAttribute("baseurl");
    } else {
      $thebase = "modules/".$name."/html/index.html";
    }
    
		$output = file_get_contents  ( $this -> docroot.$thebase );  
		
		$htmlelement -> nodeValue = $output;
    //$outputelement = $this -> XML_SOURCE -> documentElement -> createTextNode( $output );
    //$htmlelement -> appendChild($outputelement);
    $htmlbase = $TEMPELEMENTBASE -> appendChild($htmlelement);
		
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
  * @name  addSmartyCacheInclude
  * @param string $parse - FPO copy here
  * @param string $name - FPO copy here
  * @param string $group - FPO copy here
  * @param string $node - FPO copy here
  */
	function  addSmartyCacheInclude($parse,$name,$group,$node) {
        
    if ($this -> module_ual[$THISNAME] <= $this -> access) {
      //NEED TO APPEND SMARTY LINES TO THIS XML FILE FOR EACH MODULE
  		//THEN ADD XSL TO TRANSFORM IT TO INCLUDES
  		//THEN TRANSFORM AND SAVE USING THIS
		  $TEMPELEMENTBASE = $this -> CACHEBASEOBJ -> documentElement -> createElement("AMODULE");
       
      $TEMPCHILDBASE = $this -> CACHEMODULEELEMENT -> appendChild($TEMPELEMENTBASE);
      $TEMPCHILDBASE -> setAttribute("parse", "SMARTY_INCLUDE");
      $this -> extendGroups($this -> CACHEBASEOBJ, $this -> CACHEMODULEELEMENT,$group,$TEMPCHILDBASE);
		  
      $smartyelement = $this -> CACHEBASEOBJ -> documentElement -> createElement("module");
		  $smartyelement -> setAttribute("parse", "SMARTY_INCLUDE");
		  $smartyelement -> setAttribute("location",$this -> docroot."modules/".$name."/smarty/templates/index.tpl");
      $smartybase = $TEMPELEMENTBASE -> appendChild($smartyelement);
		  
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
  * @name  extendGroups
  * @param string $source - FPO copy here
  * @param string $destination - FPO copy here
  * @param string $groups - FPO copy here
  * @param string $baseelement - FPO copy here
  */
  function extendGroups($source,$destination,$groups,$baseelement) {
    $THEGROUPS = explode(",",$groups);
		
		$baseelement -> setAttribute("group", $THEGROUPS[0]);
    foreach($THEGROUPS as $agroup) {
      $TEMPGROUPBASE = $source -> documentElement -> createElement("GROUP");
		  $TEMPGROUPCHILD = $destination -> appendChild($TEMPGROUPBASE);
      $TEMPGROUPCHILD -> setAttribute("name", $agroup);
      $baseelement -> appendChild($TEMPGROUPCHILD);
    }
  }
  
  
}
?>
