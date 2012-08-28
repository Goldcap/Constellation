<?php

/**
 *  WTVRStubCreator .php, Styroform XML Form Controller
 * 
 * XML Form Generator and Validator
 * in-code documentation through DocBlocks and tags.
 * @author Andy Madsen <amadsen@motormeme.com>
 * @version 1.2
 * @package XMLForm
 */
//  WTVRStubCreator 

/**
 * The Form Controller class, responsible for drawing the form, calling the validator, adding "Items" and "ItemLists", and navigating through formsets
 * @package WTVRStubCreator
 * @subpackage classes
 */
class WTVRStubCreator {
 
   /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $dirs
  */
  var $dirs;
  
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
  * @name $stubloc 
  */
  var $stubloc;
  
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $docroot
  */
  var $docroot;
  
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $type
  */
  var $type;
  
    
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
  function __construct() {
    $this -> libloc = str_replace("/src","",$_SERVER["DOCUMENT_ROOT"])."/lib/schema";
    $this -> docroot = $_SERVER["DOCUMENT_ROOT"];
    $this -> stubloc = "/var/www/html/lib/wtvr/admin/templates/mod_rewrite/index.php";
    
    $this -> dirs = new DOMDocument();
    $this -> dirs -> load($this -> libloc."/pages.xml");
    $this -> doConf("page");
    $this -> doConf("module");
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
  * @name  doConf 
  * @param string $type
  */
  function doConf($type) {
    $this -> type = $type;
    $this -> loopPages();
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
  * @name loopPages
  */
  function loopPages() {
    $nodes = $this -> dirs -> getElementsByTagname($this -> type);
    
    foreach ($nodes as $node) {
      $this -> createDirectory($node -> getAttribute("name"));
      $this -> copyIndex($node -> getAttribute("name"));
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
  * @name createDirectory
  * @param string $name - FPO copy here
  */
  function createDirectory($name) {
    if (! is_dir($this -> docroot."/".$name)) {
      mkdir($this -> docroot."/".$name, 0755);
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
  * @name copyIndex  
  * @param string $name
  */
  function copyIndex($name) {
    if (!copy($this -> stubloc,$this -> docroot."/".$name."/index.php")) {
       echo "failed to copy $this -> stubloc...\n";
    }
  }


}
?>
