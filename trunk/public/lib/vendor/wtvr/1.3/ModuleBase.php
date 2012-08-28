<?php

/**
 * ModuleBase.php, Styroform XML Form Controller
 * 
 * XML Form Generator and Validator
 * in-code documentation through DocBlocks and tags.
 * @author Andy Madsen <amadsen@motormeme.com>
 * @version 1.2
 * @package XMLForm
 */
// ModuleBase

/**
* ModuleBase Utilites for string manipulation and flow control
*/
include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLAbstract.php");

/**
 * The Form Controller class, responsible for drawing the form, calling the validator, adding "Items" and "ItemLists", and navigating through formsets
 * @package  ModuleBase 
 * @subpackage classes
 */
class ModuleBase extends WTVRBase {
 
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $documentElement
  */
  var $documentElement;
  
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $XSLDocument
  */
  var $XSLDocument;
  
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $XMLForm
  */
  var $XMLForm;
  
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $XMLFormArrays
  */
  var $XMLFormArrays;
  
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
  * @name loadXML
  * @param string $doc
  */
  function loadXML( $doc = "index.xml" ) {
    return $this -> loadXMLFile( "modules", $doc );
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
  * @name returnXML
  */
  function returnXML() {
    return $this -> documentElement;
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
  * @name loadXSL
  */
  function loadXSL( $doc = "index.xsl" ) {
    return $this -> loadXMLFile( "modules", $doc, "xsl" );
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
  * @name returnXSL
  */
  function returnXSL() {
    return $this -> XSLDocument;
  }
  
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
  * @name Destructor  
  * @param string $nodevalue - FPO copy here
  * @param string $nodevalue - FPO copy here
  * @param array $formsettings  - Array with both Formset, and XSL Doc
  */
  function __destruct() {
	  parent::__destruct();
  }
}
?>
