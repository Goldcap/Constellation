<?php

/**
 * WTVRItemIterator.php, Styroform XML Form Controller
 * 
 * XML Form Generator and Validator
 * in-code documentation through DocBlocks and tags.
 * @author Andy Madsen <amadsen@motormeme.com>
 * @version 1.2
 * @package XMLForm
 */
// WTVRItemIterator

/**
* XML Form Utilites for string manipulation and flow control
*/
include_once("clsWTVRItemCreator.php");

/**
 * The Form Controller class, responsible for drawing the form, calling the validator, adding "Items" and "ItemLists", and navigating through formsets
 * @package WTVRItemIterator
 * @subpackage classes
 */
class WTVRItemIterator extends WTVRItemCreator {

  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $modules
  */
    var $modules;
    
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $namearray 
  */
    var $namearray;
    
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $allmodules
  */
    var $allmodules;
    
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $pagemodules 
  */
    var $pagemodules;
   
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
      $this -> debug = false;
      $this -> loc = false;
      $this -> names = array();
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
  * @name iterateCopyFromPost
  */
    function iterateCopyFromPost() {
      $this -> setModules();
      $this -> doIteration();
    }
    
    //Use this to pull module attributes?
    /*
    function pullModules() {
      foreach($this -> namearray as $anarray) {
        array_push($this -> names,array("name"=>$anarray["name"],"ual"=>$anarray["ual"],"group"=>$anarray["group"]));
      }
    }
    
    function pullPages() {
      foreach($this -> namearray as $anarray) {
        array_push($this -> names,array("name"=>$anarray["page"]["name"],"ual"=>$anarray["page"]["ual"]));
        foreach($anarray["modules"] as $module) {
          $this -> pagemodules[$anarray["page"]["name"]][$module] = $this -> allmodules[$module];
        }
      }
    }
    */
    
  /**
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * <code> 
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  * </code>
  * @name setModules
  */
    function setModules() {
      $this -> modules = explode(",",$_POST["names"]);
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
  * @name doIteration
  */
    function doIteration() {
      foreach ($this -> modules as $item ) {
        echo("Creating ".$value."<br />");
        
        $this -> name = $item["name"];
        $this -> ual = $item["ual"];
        $this -> modules = $this -> pagemodules[$value];
        $this -> doCreate();
        
      }
    }
    
    /*
    function iterateModules( $name, $ual ) {
      $pcr = new WTVRItemCreator();
      $pcr -> type = "module";
      $pcr -> name = $name;
      $pcr -> ual = "admin";
      $pcr -> scaffold = true;
      $pcr -> doCopy();
    }
    */
}
?>
