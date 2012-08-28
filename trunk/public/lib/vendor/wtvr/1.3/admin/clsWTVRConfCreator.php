<?php

/**
 * WTVRConfCreator.php, Styroform XML Form Controller
 * Use this to create the Pages.xml, Modules.xml, associated UAL Arrays
 * XML Form Generator and Validator
 * in-code documentation through DocBlocks and tags.
 * @author Andy Madsen <amadsen@motormeme.com>
 * @version 1.2
 * @package XMLForm
 */
// WTVRConfCreator

/**
* XML Form Utilites for string manipulation and flow control
*/
include_once "wtvr/".$GLOBALS["wtvr_version"]."/admin/clsWTVRPHPCreator.php";

/**
 * The Form Controller class, responsible for drawing the form, calling the validator, adding "Items" and "ItemLists", and navigating through formsets
 * @package WTVRConfCreator
 * @subpackage classes
 */
class WTVRConfCreator extends GlobalBase {
  
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
  * @name $debug_local 
  */
  var $debug_local;
  
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
    parent::__construct();
    $this -> debug_local = false;
    if ($this -> debug_local)
    print("writing conf docs...");
    
    $this -> libloc = str_replace("/src","",$_SERVER["DOCUMENT_ROOT"])."/lib/schema";
    $this -> confloc = str_replace("/src","",$_SERVER["DOCUMENT_ROOT"])."/lib/conf/ual.xml";
    
    $this -> doConf();
    $this -> setGlobalConf();
    $this -> doAccess();
   
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
  * @name doConf  
  * @param string $type
  */
  function doConf() {
    foreach($this -> scopes as $scope) {
      $this -> type = $scope;
      $this -> createConf();
      $this -> writeConf();
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
  * @name doConf  
  * @param string $type
  */
  function doAccess() {
    foreach($this -> scopes as $scope) {
      $this -> setAccess( $scope );
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
  * @name setSchemaDir
  */
  function setSchemaDir() {
    if (! is_dir($this -> libloc)) {
      mkdir($this -> libloc, 0755);
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
  * @name writeConf
  */
  function writeConf() {
    createFile ($this -> libloc."/".$this -> type.".xml", $this -> conf);
    if ($this -> debug_local) {
      echo("Just wrote this file: ".$this -> libloc."/".$this -> type.".xml<BR /><BR />");
    }
    $this -> conf = null;
    $this -> dir = null;
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
  * @name setGlobalConf
  */
  function setGlobalConf() {
    $theSchema = new DOMDocument();
    $theObj = new DOMDocument();

    if ($this -> debug_local) {echo "Creating Root Element ...<br /><br />";}
    $docRoot = $theSchema -> createElement("ROOT");
    $theSchema -> appendChild($docRoot);
    $modRoot = $theSchema -> createElement("modules");
    $docRoot -> appendChild($modRoot);
    
    if ($this -> debug_local) {echo "Reading and Joining schemas ...<br /><br />";}
    foreach($this -> scopes as $scope) {
      $objRoot = $theSchema -> createElement("itemgroup");
      $docRoot -> appendChild($objRoot);
      $objRoot -> setAttribute("scope",$scope);
      
      if (file_exists($this -> local_libroot."schema/".$scope.".xml")) {
        $xml_obj = file_get_contents("schema/".$scope.".xml",true);
        $theObj -> loadXML($xml_obj);
        $thechild = $theObj -> getElementsByTagname("theitems");
        $TEMPCHILD = $theSchema -> importNode( $thechild -> item(0) , true );
        $objRoot -> appendChild($TEMPCHILD);
      }
    }
    
    //Transform to PHP File
    $transformer = new WTVRPHPCreator();
   
    $transformer -> xml = $theSchema;
    $transformer -> xsl_location = $this -> libroot."wtvr/".$GLOBALS["wtvr_version"]."/admin/templates/conf/wtvrconf.xsl";
    $transformer -> location = $this -> local_libroot."conf/classes_conf.php";
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
  * @name setPageAccess
  */
  function setAccess( $type ) {
    
    $theSchema = new DOMDocument();
    $theObjects = new DOMDocument();
    
    if ($this -> debug_local) {echo "Creating Root Element ...<br /><br />";}
    $docRoot = $theSchema -> createElement("ROOT");
    $theSchema -> appendChild($docRoot);
    
    if ($this -> debug_local) {echo "Reading '".$type.".xml' ...<br /><br />";}
    $xml_objects = file_get_contents("schema/".$type.".xml",true);
    
    $theObjects -> loadXML($xml_objects);
    $theobjectchild = $theObjects -> getElementsByTagname("theitems");
    
    if ($this -> debug_local) {echo "Joining schemas ...<br /><br />";}
    $thisNode = $theSchema -> getElementsByTagname("ROOT") -> item(0);
    $TEMPCHILD = $theSchema -> importNode( $theobjectchild -> item(0) , true );
    $thisNode -> appendChild($TEMPCHILD);
    
    $theconfloc = $theSchema -> createElement("confloc");
    $theconfloc -> setAttribute("location", $this -> confloc);
    $theSchema -> appendChild($theconfloc);
    
    //Transform to PHP File
    $transformer = new WTVRPHPCreator();
    
    $transformer -> xml = $theSchema;
    $transformer -> xsl_location = $this -> libroot."wtvr/".$GLOBALS["wtvr_version"]."/admin/templates/conf/itemaccessconf.xsl";
    $transformer -> location = $this -> local_libroot."conf/".$type."_conf.php";
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
  * @name createConf
  */
  function createConf() {
    //$this -> conf = "<?xml version=\"1.0\" encoding=\"UTF-8\">";
    $this -> conf .= "<theitems>\n";
    
    $directorytype = ($this -> type == "modules") ? "modules" : "pages";
    $directory = $this -> docroot . $directorytype;
    
    if ($this -> debug_local) {
        echo("\n--> Loading item from ".$directory."<br />");
    }
    
    $this -> dir = new DirectoryIterator($directory);
    
    foreach ($this -> dir as $file) {
       $ual = '';
       $group = '';
       if (! isOK($file)) {
           continue;
       }
       
       $theconfdoc = new XML();
       if ($this -> debug_local) {
        echo("\n--> Loading configuration from " . $file->getFilename() . " in ".$directory."/".$file->getFilename()."/conf/index.xml<br />");
       }
       $theconfdoc -> loadXML($directory."/".$file->getFilename()."/conf/index.xml");
       $scope = $theconfdoc -> getPathValue("//scope");
       
       if ((strlen($scope)==0 && (($this -> type != "modules") && ($this -> type != "pages"))) || ((strlen($scope)>0) && (strtolower($scope) != $this -> type))) {
        if ($this -> debug_local) {
          echo("\n--> Skipping configuration for " . $file->getFilename() . " with scope ".strtolower($scope)." in type ".$this -> type."<br />");
        }
        continue;
       }
       
       $ual = $theconfdoc -> getAttributeValueByPath("/".substr($this->type,0,-1),"ual");
       $group = $theconfdoc -> getAttributeValueByPath("/".substr($this->type,0,-1),"group");
       if ($ual == '') {$ual = 'public';}
       if ($this -> debug_local) {
              echo("\n--> UAL is ".$ual."<br />");
              }
       if ($group == '') {$group = 'div02';}
       if ($this -> debug_local) {
              echo("\n--> GROUP is ".$group."<br />");
              }
       $this -> conf .= "<item name=\"".$file->getFilename()."\" ual=\"".$ual."\" group=\"".$group."\">";
       $thisname = ($this -> type == "modules") ? $file->getFilename()."_mod" : $file->getFilename()."_page";
       
       $basemethods = get_class_methods("GlobalBase");
       $modmethods = get_class_methods("ModuleBase");
       $pagemethods = get_class_methods("PageBase");
       
       if (file_exists($directory."/".$file->getFilename()."/classes/"."class_".$thisname.".php")) {
        if ($this -> debug_local) {
          echo($directory."/".$file->getFilename()."/classes/"."class_".$thisname.".php<br />");
        }
        
        include_once($directory."/".$file->getFilename()."/classes/"."class_".$thisname.".php");
			  $ClassMethods = get_class_methods($thisname);
			  
        if ($ClassMethods) {
        foreach($ClassMethods as $amethod) {
			    if ((! in_array($amethod,$basemethods)) && (! in_array($amethod,$pagemethods)) && (! in_array($amethod,$modmethods))) {
            if ($this -> debug_local) {
              echo("\n--> Adding Method ".$amethod."<br />");
            }
            $this -> conf .= "\n\t<method>".$amethod."</method>";
          }
        }
        }
        
        //echo ($directory."/".$file->getFilename()."/classes/".$thisfilename." does exist.");
       }
       $this -> conf .= "</item>\n";
       
    }
    
    $this -> conf .= "</theitems>\n";
    
  }
  
}
?>
