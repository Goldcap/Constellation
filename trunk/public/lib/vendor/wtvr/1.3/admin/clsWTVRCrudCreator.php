<?php

/**
 * WTVRCrudCreator.php, Styroform XML Form Controller
 * 
 * XML Form Generator and Validator
 * in-code documentation through DocBlocks and tags.
 * @author Andy Madsen <amadsen@motormeme.com>
 * @version 1.2
 * @package XMLForm
 */
// WTVRCrudCreator

/**
* XML Form Utilites for string manipulation and flow control
*/
require_once(dirname(__FILE__).'/../WTVRUtils.php');


/**
 * The Form Controller class, responsible for drawing the form, calling the validator, adding "Items" and "ItemLists", and navigating through formsets
 * @package WTVRCrudCreato
 * @subpackage classes
 */
class WTVRCrudCreator {
  
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
  * @name $theconf
  */
  var $theconf;
  
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $tables
  */
  var $tables;
  
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
  function __construct(){
    $this -> theconf = new DOMDocument();
    $this -> schemaroot = sfConfig::get('sf_config_dir');
    $this -> local_libroot = sfConfig::get('sf_lib_dir');
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
  * @name doCrud
  */
  function doCrud() {
    $this -> init();
    $this -> createCruds();
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
  * @name init
  */
  function init() {
    $this -> theconf -> load($this -> schemaroot."/schema.xml");
    $this -> tables = $this -> theconf -> getElementsByTagname("table");
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
* @name Destructo
* @param array $formsettings  - Array with both Formset, and XSL Doc
*/
  function __destruct(){
    
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
  * @name createCruds
  */
  function createCruds() {
   foreach ($this -> tables as $table) {
      if ($this ->debug) {
        echo "Creating " . $table -> getAttribute("name")."<BR />";
      }
      
      createDirectory($this -> local_libroot."/model/base");
      
      $tablename = $table -> getAttribute("name");
      
      //Create the Base Class for Each CRUD
      $theTable = new DOMDocument();
      $docRoot = $theTable -> createElement("ROOT");
      //$docRoot -> setAttribute("project",$GLOBALS["propelproject"]);
      
      $theTable -> appendChild($docRoot);
      //$thisNode = $theSchema -> getElementsByTagname("ROOT") -> item(0);
        
      $TEMPCHILD = $theTable -> importNode( $table , true );
      $docRoot -> appendChild($TEMPCHILD);
      
      //Transform to PHP File
      $transformer = new WTVRPHPCreator();
      $name = str_replace(" ","",ucwords(str_replace("_"," ",$tablename)));
      
      $transformer -> xml = $theTable;
      $transformer -> xsl_location = $this -> local_libroot."/vendor/wtvr/1.3/admin/templates/conf/wtvrcrudbase.xsl";
      if ($this ->debug) {
        echo "Writing " . $this -> local_libroot."base/".$name."CrudBase.php"."<BR />";
      }
      $transformer -> location = $this -> local_libroot."/model/base/".$name."CrudBase.php";
      $transformer -> transformToPHP();
      
      //Create the wrapper class, if there isn't one...
      if (! file_exists($this -> local_libroot."/model/".$name."_crud.php")) {
        $transformer -> xml = $theTable;
        $transformer -> xsl_location = $this -> local_libroot."/vendor/wtvr/1.3/admin/templates/conf/wtvrcrud.xsl";
        if ($this ->debug) {
          echo "Writing " . $this -> local_libroot."/".$name."Crud.php"."<BR />";
        }
        $transformer -> location = $this -> local_libroot."/model/".$name."Crud.php";
        $transformer -> transformToPHP();
        
        //echo $theTable -> saveXML();
      }
    }
  }
}

?>
