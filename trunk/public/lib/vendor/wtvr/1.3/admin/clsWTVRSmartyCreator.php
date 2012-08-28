<?php

/**
 * WTVRSmartyCreator.php, Styroform XML Form Controller
 * Use this to create the Pages.xml, Modules.xml, associated UAL Arrays
 * XML Form Generator and Validator
 * in-code documentation through DocBlocks and tags.
 * @author Andy Madsen <amadsen@motormeme.com>
 * @version 1.2
 * @package XMLForm
 */
// WTVRSmartyCreator

/**
* XML Form Utilites for string manipulation and flow control
*/
include_once "wtvr/".$GLOBALS["wtvr_version"]."/WTVRUtils.php";

/**
* XML Form Utilites for string manipulation and flow control
*/
include_once "wtvr/".$GLOBALS["wtvr_version"]."/WTVRCache.php";

/**
 * The Form Controller class, responsible for drawing the form, calling the validator, adding "Items" and "ItemLists", and navigating through formsets
 * @package WTVRSmartyCreator
 * @subpackage classes
 */
class WTVRSmartyCreator extends GlobalBase {
  
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $dir
  */
  var $dir;
  
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $pageloc 
  */
  var $pageloc;
  
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $moduleloc
  */
  var $moduleloc;
  
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $libloc 
  */
  var $libloc;
  
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
  * @name $type
  */
  var $type;
  
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $debug
  */
  var $debug;
  
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $SMARTY
  */
  var $SMARTY;
  
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $the_output
  */
  var $the_output;
  
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $force_overwrite
  */
  var $force_overwrite;
  
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
* @param string $vars
* @param array $formsettings  - Array with both Formset, and XSL Doc
*/
  function __construct( $vars ) {
    parent::__construct();
    $this -> debug = true;
    $this -> SMARTY = new WTVRCache();
    $this -> VARIABLES = $vars;
    $this -> SMARTY -> VARIABLES = $this -> VARIABLES;
    if ($this -> debug) print("writing smarty templates...<br />");
    
    $this -> pagesloc = $this -> docroot."/pages";
    $this -> modulesloc = $this -> docroot."/modules";
    $this -> doSmarty("modules");
    $this -> doSmarty("pages");
  }
  
  function doSmarty($type) {
    $this -> type = $type;
    $this -> createSmarty();
  }
  
    
  /**
  * This will take each page and module and output SMARTY Data of two types
  * 1) Just a flat HTML Dump of output
  * 2) Smarty with includes for each module
  * <code> 
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  * </code>
  * @name createSmarty
  */
  function createSmarty() {

    eval("\$directory = \$this -> ".$this -> type."loc;");
    
    $this -> dir = new DirectoryIterator($directory);
    if ($this -> debug) echo("==================================<br />");
    if ($this -> debug) echo($this -> type."<br />");
    if ($this -> debug)  echo("==================================<br />");
    
    foreach ($this -> dir as $file) {
      
      if (! isOK($file)) {
        continue;
      }
      
      $GLOBALS["deadpedal"] = true;
      if ($this -> debug) echo("Creating templates for ".$this -> type."/".$file -> getFilename()."<br />");
      $this -> the_output = new WTVR();
      $this -> the_output -> SCOPE = $this -> type;
      $this -> the_output -> ID = $file -> getFilename();
      $this -> the_output -> p_object = null;
      $this -> the_output -> outputType = 'text';
      $this -> the_output -> genVars(true);
      
      //This tells the Cache Engine to create the "GENERATED" smarty templates.
      $this -> the_output -> CACHE = "GEN";
      
      $this -> body = $this -> the_output -> initWTVR();
      
    }
    
  }
  
}
?>
