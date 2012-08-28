<?php

/**
 * WTVRScaffolding.php, Styroform XML Form Controller
 * Use this to create pages and modules for your data schema
 * It will expose all of your tables to the webserver though
 * So use caution
 * XML Form Generator and Validator
 * in-code documentation through DocBlocks and tags.
 * @author Andy Madsen <amadsen@motormeme.com>
 * @version 1.2
 * @package XMLForm
 */

/**
* XML Form Utilites for string manipulation and flow control
*/
require_once(dirname(__FILE__).'/../WTVRUtils.php');


/**
 * The Form Controller class, responsible for drawing the form, calling the validator, adding "Items" and "ItemLists", and navigating through formsets
 * @package WTVRScaffolding 
 * @subpackage classes
 */
class WTVRScaffolding {
  
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
  * @name $libloc
  */
  var $libloc;
  
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $outloc
  */
  var $outloc;
  
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $schema
  */
  var $schema;
  
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $tables  
  */
  var $tables;
  
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $columns  
  */
  var $columns;
  
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
  * @name $xslobj
  */
  var $xslobj;
  
    /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $docArray  
  */
  var $basetemplateloc;
  
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $pageargs
  */
  var $pageargs;
  
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $modargs  
  */
  var $modargs;
  
  var $controlurl;
  
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
    $this -> theconf = new DOMDocument();
    $this -> schemaroot = sfConfig::get('sf_config_dir');
    $this -> local_libroot = sfConfig::get('sf_lib_dir');
    
    $this -> libloc = $this -> schemaroot;
    $this -> buildloc = $this -> local_libroot . "/model/wtvr_scaffolding/";
    if (! file_exists($this -> local_libroot . "/vendor/PageWidgets/templates/scaffold_mod_args.php"))
    die("scaffold_mod_args.php missing, please check again");
    $this -> modargs = include_once("conf/scaffold_mod_args.php");
    
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
  * @name wipeScaffoldByType
  * @param string $type - FPO copy here
  */
  function wipeScaffoldByType( $type ) {
    //$com = new CLI();
    //$com -> exec("cd ".$this -> local_libroot ."/model/wtvr_scaffolding/build/".$type."s/; rm -rf *;");  
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
  * @name doScaffold
  */
  function doScaffold() {
    $this -> wipeScaffoldByType("widget");
    $this -> setOutputDir();
    $this -> readSchema();
    $this -> createForms();
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
  * @name setOutputDir
  */
  function setOutputDir() {
    createDirectory( $this -> buildloc );
    createDirectory( $this -> buildloc."/map/" );
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
  * @name readSchema
  */
  function readSchema() {
    $this -> schema = new XML();
    $this -> schema -> loadXML($this -> libloc."/schema.xml");
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
  * @name createForms  
  */
  function createForms() {
    $this -> tables = $this -> schema -> getElementsByTagname("table");
    foreach ($this -> tables as $table) {
      $tempdoc = new DOMDocument();
      $node = $tempdoc -> importNode($table,true);
      $tempdoc -> appendChild($node);
      $this -> createModule( $table -> getAttribute("name") );
      $this -> createForm($tempdoc, $table -> getAttribute("name"));
      $this -> createForm($tempdoc, $table -> getAttribute("name"),true);
      $this -> createMap($tempdoc, $table -> getAttribute("name"));
      //$this -> createPage( $table -> getAttribute("name") );
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
  * @name createForm
  * @param string $xml_obj - FPO copy here
  * @param string $name - FPO copy here
  * @param string $conf - FPO copy here
  */
  function createForm( $xml_obj, $name, $conf=false ) {
    if ($conf) {
      $thetemplate = "formconf_generator.xsl";
      $thename = $name."conf.xml";
    } else {
      $thetemplate = "form_generator.xsl";
      $thename = $name.".xml";
    }
    $this -> xslobj = new XSL();
    $this -> basetemplateloc = $this -> local_libroot."/vendor/wtvr/1.3/admin/templates";
    $this -> xslobj -> saveDoc($this -> basetemplateloc."/conf/".$thetemplate, $xml_obj, $this -> buildloc.$thename );
    
    $moduleloc = sfConfig::get('sf_lib_dir').'/widgets/scaffold/'.$name.'/xml/';
    if ($conf) {
      cli_text("Writing formconf to ".$moduleloc."formconf.xml","green");
      $this -> xslobj -> saveDoc($this -> basetemplateloc."/conf/".$thetemplate, $xml_obj, $moduleloc."formconf.xml" );
    } else {
      cli_text("Writing form to ".$moduleloc."form.xml","green");
      $this -> xslobj -> saveDoc($this -> basetemplateloc."/conf/".$thetemplate, $xml_obj, $moduleloc."form.xml" );
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
  * @name createMap 
  * @param string $xml_obj - FPO copy here
  * @param string $name - FPO copy here
  */
  function createMap( $xml_obj, $name ) {
    $libnode = $xml_obj -> createElement("local_libroot");
    $libnode -> nodeValue = $this -> schemaroot;
    //cli_text($this -> schemaroot,"magenta");
    
    $xml_obj -> appendChild($libnode);
    $libnode = $xml_obj -> createElement("controlurl");
    $libnode -> nodeValue = $this -> controlurl;
    $xml_obj -> appendChild($libnode);
    
    $thetemplate = "datamap_generator.xsl";
    $thename = $name."_list_datamap.xml";
    cli_text("Writing map to ".$this -> buildloc."map/".$thename,"green");

    $this -> xslobj = new XSL();
    $this -> basetemplateloc = $this -> local_libroot."/vendor/wtvr/1.3/admin/templates";
    $this -> xslobj -> saveDoc($this -> basetemplateloc."/conf/".$thetemplate, $xml_obj, $this -> buildloc."map/".$thename );

    $moduleloc = sfConfig::get('sf_lib_dir').'/widgets/scaffold/'.$name.'/query/';
    cli_text("Writing query to ".$moduleloc.$thename,"green");
    $this -> xslobj -> saveDoc($this -> basetemplateloc."/conf/".$thetemplate, $xml_obj, $moduleloc.$thename );
    
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
  * @name createModule
  * @param string $name - FPO copy here
  */
  function createModule( $name ) {
    $pcr = new WTVRItemCreator();
    if (count($this->modargs) > 0) {
      $pcr -> args["mod"]= $this->modargs;
    }
    $pcr -> templateloc = sfConfig::get('sf_lib_dir').'/vendor/PageWidgets/templates/';
    $pcr -> loc = sfConfig::get('sf_lib_dir').'/widgets/';
    $pcr -> type = "widget";
    $pcr -> name = $name;
    $pcr -> scaffold = true;
    $pcr -> doCreate();
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
  * @name createPage
  * @param string $name- FPO copy here
  */
  function createPage( $name ) {
    $pcr = new WTVRItemCreator();
    
    if (count($this->pageargs) > 0) {
      $pcr -> args["page"] = $this->pageargs;
    }
    $pcr -> type = "page";
    $pcr -> name = $name;
    $pcr -> ual = "Administrators";
    $pcr -> modules = array(
                    array("name"=>"header","ual"=>"public","parse"=>"SERVER","group"=>"div01"),
                    array("name"=>$name,"ual"=>"public","parse"=>"ACTIVE","group"=>"centerdiv,div02"),
                    array("name"=>"menu","ual"=>"public","parse"=>"ACTIVE","group"=>"centerdiv,div03"),
                    array("name"=>"footer","ual"=>"public","parse"=>"SERVER","group"=>"div05")
                    );
    $pcr -> scaffold = true;
    $pcr -> doCreate();
  }
  
}
?>
