<?php

/**
 *  WTVRItemCreator.php, Styroform XML Form Controller
 * 
 * XML Form Generator and Validator
 * in-code documentation through DocBlocks and tags.
 * @author Andy Madsen <amadsen@motormeme.com>
 * @version 1.2
 * @package XMLForm
 */
//  WTVRItemCreator

/**
* XML Form Utilites for string manipulation and flow control
*/
require_once(dirname(__FILE__).'/../WTVRUtils.php');


/**
 * The Form Controller class, responsible for drawing the form, calling the validator, adding "Items" and "ItemLists", and navigating through formsets
 * @package WTVRItemCreator
 * @subpackage classes
 */
class WTVRItemCreator {
  
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $name
  */
  var $name;
  
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $ual
  */
  var $ual;
  
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $group  
  */
  var $group;
  
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $loc
  */
  var $loc;
  
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $basetemplateloc
  */
  var $basetemplateloc;
  
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $templateloc
  */
  var $templateloc;
  
  /** 
  * Array of XML Forms in an ordered FormSet
  * If this is a page or module for scaffolding, pull configuration from elsewhere;
  * @access public
  * @var array
  * @name $scaffold
  */
  var $scaffold;
  
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $scaffloc
  */
  var $scaffloc;
  
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
  * @name $lock  
  */
  var $lock;
  
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $debug
  */
  var $debug;
  
  /** 
  * Array of XML Forms in an ordered FormSet
  * These are for optional arguments you can pass to the generator
  * @access public
  * @var array
  * @name $args 
  */
  var $args = array();
  
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
    $this -> forceloc = false;
    $this -> lock = false;
    $this -> scaffold = false;
    $this -> local_libroot = sfConfig::get('sf_config_dir');
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
  * @name doCreate
  */
  function doCreate() {
    $this -> setName();
    $this -> createTree();
    $this -> createClass();
    $this -> postProcess();
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
  * @name runCopy 
  * @param string $thismodule - FPO copy here
  * @param string $type - FPO copy here
  */
  function runCopy( $thismodule, $type="mod" ) {
    full_copy($thismodule["source"],$thismodule["target"]);
    //Rename the class
    @rename($thismodule["target"]."/classes/class_".$thismodule["name"]."_".$type.".php", $thismodule["target"]."/classes/class_".$thismodule["new_name"]."_".$type.".php");
    $this -> replaceStringInFile( $thismodule["target"]."/classes/class_".$thismodule["new_name"]."_".$type.".php", "class ".$thismodule["name"], "class ".$thismodule["new_name"] );
    
    //Rename the css
    @rename($thismodule["target"]."/html/css/".$thismodule["name"].".css", $thismodule["target"]."/html/css/".$thismodule["new_name"].".css");
    $this -> replaceStringInFile( $thismodule["target"]."/html/css/".$thismodule["new_name"].".css", $thismodule["name"], $thismodule["new_name"] );
    
    //Rename the js
    @rename($thismodule["target"]."/html/js/".$thismodule["name"].".js", $thismodule["target"]."/html/js/".$thismodule["new_name"].".js");
    $this -> replaceStringInFile( $thismodule["target"]."/html/js/".$thismodule["new_name"].".js", $thismodule["name"], $thismodule["new_name"] );
    
    //Change the CONF
    $this -> replaceStringInFile( $thismodule["target"]."/conf/index.xml", "<page name=\"".$thismodule["name"], "<page name=\"".$thismodule["new_name"] );
    //Change the FORMSETTINGS
    $this -> replaceStringInFile( $thismodule["target"]."/conf/formconf.php", $thismodule["name"], $thismodule["new_name"] );
    //Change the XML
    $this -> replaceStringInFile( $thismodule["target"]."/xml/index.xml", $thismodule["name"], $thismodule["new_name"] );
    //Change the FORM
    $this -> replaceStringInFile( $thismodule["target"]."/xml/form.xml", $thismodule["name"], $thismodule["new_name"] );
    //Change the FORMCONF
    $this -> replaceStringInFile( $thismodule["target"]."/xml/formconf.xml", $thismodule["name"], $thismodule["new_name"] );
    
    //Change the XSL
    $this -> replaceStringInFile( $thismodule["target"]."/xsl/index.xsl", $thismodule["name"], $thismodule["new_name"] );
    
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
  * @name deleteItem  
  * @param string $name - FPO copy here
  * @param string $type - FPO copy here
  */
  function deleteItem( $name, $type = "module" ) {
    if (is_array($name)) {
      foreach($name as $aname) {
        deltree( $this -> docroot.$type."s/".$aname );
      }
    } else {
      deltree( $this -> docroot.$type."s/".$name );
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
  * @name replaceStringInFile
  * @param string $file - FPO copy here
  * @param string $pattern - FPO copy here
  * @param string $string - FPO copy here
  */
  function replaceStringInFile( $file, $pattern, $string ) {
    if (file_exists($file)) {
    $class = file_get_contents($file);
    $class = str_replace($pattern,$string,$class);
    file_put_contents($file,$class);
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
  * @name setType  
  * @param string $str
  */
  function setType( $str ) {
    $this -> type = $str;
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
  * @name setName
  */
  function setName() {
    if (! $this -> scaffold) {
      $this -> loc = $this -> docroot.$this -> type."s/".$this -> name;
    } else {
      $this -> createDirectory($this -> loc."scaffold/");
      $this -> loc = $this -> loc."scaffold/".$this -> name;
    }
    if ($this -> debug) {echo "Setting Directory at " . $this -> loc . "<br />";}
    $this -> templateloc = $this -> templateloc;
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
  * @name setModules
  */
  function setModules() {
    if ((! $this -> modules) && ($_POST["modules"])) {
      foreach($_POST["modules"] as $module) {
      $this -> modules["name"] = $_POST["modules"];
      //dump($this -> modules);
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
  * @name createTree
  */
  function createTree() {
    if ($this -> loc) {
      //create page root directory
      $this -> createDirectory($this -> loc);
      $dir = new DirectoryIterator($this -> templateloc.$this -> type);
      foreach ($dir as $file) {
         if (($dir->isDir()) || ($dir->isDot()) || (substr($file -> getFilename(),0,3) == 'dyn')) {
             continue;
         }
         $this -> copyFile($file -> getFilename());
      }
    } else {
      die("Item Name Not Defined");
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
  * @name createClass
  */
  function createClass() {
    
    $thedoc = new DOMDocument();
    
    if ($this -> debug) {echo "Creating Root Element ...<br /><br />";}
    $docRoot = $thedoc -> createElement("ROOT");
    $thedoc -> appendChild($docRoot);
    
    if ($this -> debug) {echo "Creating class element ...<br /><br />";}
    $docclass = $thedoc -> createElement("class");
    $docclass -> setAttribute("name",$this -> name);
    $docRoot -> appendChild($docclass);
    
    if ($this -> debug) {echo "Transform Class to PHP ...<br /><br />";}
    //Transform to PHP File
    $transformer = new WTVRPHPCreator();
    
    $transformer -> xml = $thedoc;
    $transformer -> xsl_location = $this -> templateloc . $this -> type . "/dyn_class.xsl";
    $transformer -> location = $this -> loc."/". $this -> name.".class".".php";
    $transformer -> transformToPHP();
    
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
  * @param string $dir - FPO copy here
  */
  function createDirectory($dir) {
    createDirectory( $dir );
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
  * @name createFile 
  * @param string $name - FPO copy here
  * @param string $filepath - FPO copy here
  */
  function createFile($name,$filepath) {
    if (! file_exists($filepath)) {
      if (substr($filepath,-4,4) != ".svn") {
        if (!copy($this -> templateloc.$this->type."/".$name, $filepath)) {
           echo "failed to copy $filepath... from ".$this -> templateloc.$this->type."/".$name."\n";
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
  * @name copyFile  
  * @param string $name
  */
  function copyFile($name) {
    $filearray = explode(".",$name);
    $locarray = explode("_",$filearray[0]);
    $filename = str_replace("name",$this->name.".",$locarray[count($locarray)-1].".".$filearray[1]);
    $loc = $this -> loc."/";
    for ($i=0;$i<count($locarray)-1;$i++){
      $loc .= $locarray[$i]."/";
      $this -> createDirectory($loc);
    }
    $thefilepath = $loc.$filename;
    $this -> createFile($name,$thefilepath);
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
  * @name removeCrud
  */
  function removeCrud() {
    $classloc = $this -> loc."/classes/class_".$this -> name."_mod.php";
    $class = file_get_contents($classloc);
    $class = str_replace("\$this -> crud = new test_crud", "//\$this->crud = new test_crud",$class);
    $class = str_replace("require_once 'crud/","//require_once 'crud/",$class);
    file_put_contents($classloc,$class);
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
  * @name processQueryMap
  */
  function processQueryMap() {
    $queryloc = $this -> scaffloc."map/".$this -> name."_list_datamap.xml";
    $maploc = $this -> loc."/query/".$this -> name."_list_datamap.xml";
    $queryconf = file_get_contents($queryloc);
    file_put_contents($maploc,$queryconf);
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
  * @name processQueryMap
  */
  function postProcess() {
    //Change the FORMSETTINGS
    $this -> replaceStringInFile( $this -> loc."/conf/formconf.php", "\$name", $this -> name );
    //Change the FORMSETTINGS
    $this -> replaceStringInFile( $this -> loc."/xsl/index.xsl", "\$name", $this -> name );
  }
  
    
}
?>
