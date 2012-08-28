<?php

 /**
 * WTVRCache.php, Styroform XML Form Controller
 * 
 * XML Form Generator and Validator
 * in-code documentation through DocBlocks and tags.
 * @author Andy Madsen <amadsen@motormeme.com>
 * @version 1.2
 * @package XMLForm
 */
// WTVRCache     
class WTVRCache extends GlobalBase {
  
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $TYPE  
  */
  var $TYPE;
  
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $VARIABLES 
  */
  var $VARIABLES;
  
  /** 
  * Array of XML Forms in an ordered FormSet
  * This is the XML Template pulled from the WTVR Parser
  * @access public
  * @var array
  * @name $CACHEBASEOBJ
  */
  var $CACHEBASEOBJ;
  
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $TEMPLATE  
  */
  var $TEMPLATE;
  
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
* @param string $vars - FPO copy here
* @param array $formsettings  - Array with both Formset, and XSL Doc
*/
  function __construct( $vars ) {
    parent::__construct();
    $this -> VARIABLES = $vars;
    $this -> TEMPLATE = new Smarty();
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
  
/**
* Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
* Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
* <code> 
*  $fpo = something();
*  $fpo = something();
*  $fpo = something();
*  $fpo = something();
* </code>
* @name outputCache  
* @param string $type - FPO copy here
* @param string $id - FPO copy here
* @param string $scope - FPO copy here
* @param string $filename - FPO copy here
* @param string $p_object - FPO copy here
* @param string $conf - FPO copy here
*/
  function outputCache( $type, $id, $scope, $filename="indexCache.tpl", $p_object = null, $conf = null ) {
    switch ($type) {
      case "SMARTY":
      $this -> drawSmartyTemplate( $id, $scope, $p_object, $filename, $conf );
      break;
      
      case "HTML":
      if (file_exists($this -> docroot.$scope."/".$id."/cache/index.html")) {
        getPageAndDump( $scope."/".$id."/cache/index.html" );
      }
      break;
      
      default: 
        $this -> errorDirect();
        die();
      break;
    }
  }
  
/**
* Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
* Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
* <code>
* <code> 
*  $fpo = something();
*  $fpo = something();
*  $fpo = something();
*  $fpo = something();
* </code>
* @name createHTMLCache  
* @param string $result - FPO copy here
* @param string $scope - FPO copy here
* @param string $id - FPO copy here
*/
  function createHTMLCache( $result, $scope = null, $id = null ) {
    $path = $scope . "/" . $id;
    
    switch ($scope) {
      case "modules":
      createDirectory( $this -> docroot.$path."/cache/" );
      createFile( $this -> docroot.$path."/cache/index.html", $result );
      break;
      
      case "pages":
      createDirectory( $this -> docroot.$path."/cache/" );
      createFile( $this -> docroot.$path."/cache/index.html", $result ); 
      break;
      
      default: 
        $this -> errorDirect();
        die();
      break;
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
  * @name createSMARTYCache  
  * @param string $result - FPO copy here
  * @param string $scope - FPO copy here
  * @param string $id - FPO copy here
  * @param string $thefilename - FPO copy here
  */
  function createSMARTYCache( $result, $scope, $id, $thefilename="index.tpl" ) {
    switch ($scope) {
      case "modules":
      $this -> createSmartyTemplate($id,$scope,$result,$thefilename,true);
      break;
      
      case "pages":
      $this -> createSmartyTemplate($id,$scope,$result,$thefilename,true); 
      break;
      
      default: 
      $this -> errorDirect();
      die();
      break;
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
  * @name createSmartyTemplate 
  * @param string $id - FPO copy here
  * @param string $scope - FPO copy here
  * @param string $content - FPO copy here
  * @param string $thefilename - FPO copy here
  * @param string $force - FPO copy here
  */
  function createSmartyTemplate($id,$scope,$content,$content,$force=null) {
    if ($content == null) {
      $content = "<div><span>smarty goes here</span></div>";
    } else {
      $content = preg_replace("/<script/i","{literal}<script",$content);
      $content = preg_replace("/<\/script>/i","</script>{/literal}",$content);
    }
    
    if ($scope == "modules") {
      $content = preg_replace("/<[^>]DOCTYPE[^<]*>/","",$content);
    }
    
    $this -> createSmartyDirs( $id, $scope );
    
    createFile( $this -> docroot.$scope."/".$id.$this -> smarty_templates_dir.$thefilename, $content, $force);
    
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
  * @name setSmartyVars
  * @param string $id - FPO copy here
  * @param string $scope - FPO copy here
  */
  function setSmartyVars($id,$scope) {
    $this -> TEMPLATE -> template_dir = $this -> docroot.$scope."/".$id.$this -> smarty_templates_dir;
    $this -> TEMPLATE -> config_dir = $this -> docroot.$scope."/".$id.$this -> smarty_configs_dir;
    $this -> TEMPLATE -> compile_dir = $this -> smarty_compile_dir.$scope."/".$id;
    $this -> TEMPLATE -> cache_dir = $this -> smarty_cache_dir.$scope."/".$name;  
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
  * @name setSmartyVars
  * @param string $id - FPO copy here
  * @param string $scope - FPO copy here
  */
  function createSmartyDirs($id,$scope) {
    createDirectory( $this -> docroot.$scope."/".$id."/smarty" );
    createDirectory( $this -> docroot.$scope."/".$id.$this -> smarty_templates_dir );
    createDirectory( $this -> docroot.$scope."/".$id.$this -> smarty_configs_dir );
    createDirectory( $this -> smarty_compile_dir.$scope."/".$id );
    createDirectory(  $this -> smarty_cache_dir.$scope."/".$id );  
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
  * @name drawSmartyTemplate 
  * @param string $id - FPO copy here
  * @param string $scope - FPO copy here
  * @param string $p_object - FPO copy here
  * @param string $thefilename - FPO copy here
  * @param string $conf - FPO copy here
  */
  function drawSmartyTemplate($id,$scope,$p_object,$filename="index.tpl",$conf=null) {
    
    $path = $scope . "/" . $id;
    
    switch ($scope) {
      case "modules":
        $this -> setSmartyVars($id,$scope);
        $this -> createSmartyDirs($id,$scope);
        $this -> doMagicMethod ( $id );
        
        if (file_exists($this -> docroot.$path."/smarty/templates/".$filename)) {
          return $this -> TEMPLATE ->fetch($filename);
        }
      break;
      
      case "pages":
        $this -> setSmartyVars($id,$scope);
        $this -> createSmartyDirs($id,$scope);
        if ($conf) {
          $nodes = $conf -> documentElement -> getElementsByTagName("module");
          
          foreach ($nodes as $node) {
            $thid = $node -> getAttribute("name");
            $thparse = $node -> getAttribute("parse");
            ($thparse == "SMARTY") ? $this -> doMagicMethod ( $thid ) : null;
          }
        }
        
        $this -> doMagicMethod ( $id, "pages" );
        
        if (file_exists($this -> docroot.$path."/smarty/templates/".$filename)) {
          $this -> TEMPLATE -> display($filename);
        }
        
      break;
      
      default: 
        $this -> errorDirect();
        die();
      break;
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
  * @name doMagicMethod  
  * @param string $id - FPO copy here
  * @param string $scope - FPO copy here
  */
  function doMagicMethod ( $id, $scope="modules" ) {
    if ($this -> checkClass("smarty","modules",$id)) {
      eval("\$include = new ".$id.$this -> getScopeSig( "modules" )."( \$p_object, \$this -> VARIABLES );");
      $include -> smarty( $this -> TEMPLATE );
    
    }
    
  }
  
}

?>
