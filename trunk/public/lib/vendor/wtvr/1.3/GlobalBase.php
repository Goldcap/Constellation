<?php

/**
 * GlobalBase.php, Styroform XML Form Controller
 * 
 * XML Form Generator and Validator
 * in-code documentation through DocBlocks and tags.
 * @author Andy Madsen <amadsen@motormeme.com>
 * @version 1.2
 * @package XMLForm
 */
// GlobalBase

/**
* XML Form Utilites for string manipulation and flow control
*/
//if (! $GLOBALS["skiperrors"])
include_once("WTVRUtils.php");

/**
* XML Form Utilites for string manipulation and flow control
*/
//if (! $GLOBALS["skiperrors"])
//include_once("WTVRError.php");

 /**
 * The Form Controller class, responsible for drawing the form, calling the validator, adding "Items" and "ItemLists", and navigating through formsets
 * @package GlobalBase
 * @subpackage classes
 */
class GlobalBase {
  
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
* @name Constructors  
* @param string $nodevalue - FPO copy here
* @param string $nodevalue - FPO copy here
* @param array $formsettings  - Array with both Formset, and XSL Doc
*/
  function __construct( $vars = false ) {
    parent::__construct( $vars );
    $this -> initSessions();
  }
  
  /***************************************************************************/
  /* Access Utilities                                                        */
  /***************************************************************************/
  /**
   *  
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * <code> 
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  * </code>
  * @name checkForSession 
  */
  public function isPublic( $id, $type = "module" ) {
    
    $aval = $type . "s_ual";
    $ual = $this -> $aval;
    
    if ($ual[$id] == 0) {
      return true;
    } else {
      return false;
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
  * @name checkForSession 
  */
  public function isUsable( $id, $type = "modules" ) {
    $aval = $type . "_ual";
    $ual = $this -> $aval;
    
    if ($ual[$id] <= $this -> access) {
      return true;
    } else {
      return false;
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
  * @name checkForSession 
  */
  public function isAdminModule() {
    if ( $this -> SCOPE == "modules" && $this -> ID == "admin" ) {
      return true;
    }
    return false;
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
  * @name checkForSession 
  */
  function checkForSession() {
    if ($this -> sessionVar($this -> user_session_id)) {
      return true;
    }
    return false;
  }
  
  function sessionRedirect() {
    if ($this -> checkForSession()) {
      reDirect($this -> access_page_id);
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
  * @name getUal
  */
  function getUal() {
    $this -> dirs = new DOMDocument();
    $this -> dirs -> load($this -> local_libroot."schema/ual.xml");
    $nodes = $this -> dirs -> getElementsByTagname("ual");
    $uals = array();
    
    foreach ($nodes as $node) {
      array_push($uals, $node -> getAttribute("name"));
    }
    sort($uals);
    return $uals;
  }
  
  /***************************************************************************/
  /* Site Structure Utilities                                                */
  /***************************************************************************/
  
  /**
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * <code> 
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  * </code>
  * @name getModules
  */
  function getModules() {
    $this -> dirs = new DOMDocument();
    $this -> dirs -> load($this -> local_libroot."schema/modules.xml");
    $nodes = $this -> dirs -> getElementsByTagname("item");
    $modules = array();
    
    foreach ($nodes as $node) {
      array_push($modules, $node -> getAttribute("name"));
    }
    sort($modules);
    return $modules;
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
  * @name getPages
  */
  function getPages() {
    $this -> dirs = new DOMDocument();
    $this -> dirs -> load($this -> local_libroot."schema/pages.xml");
    $nodes = $this -> dirs -> getElementsByTagname("item");
    $pages = array();
    
    foreach ($nodes as $node) {
      array_push($pages, $node -> getAttribute("name"));
    }
    sort($pages);
    return $pages;
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
  * @name getPages
  */
  function getScopeObjects( $type = "modules" ) {
    $this -> dirs = new DOMDocument();
    $this -> dirs -> load($this -> local_libroot."schema/".$type.".xml");
    
    $nodes = $this -> dirs -> getElementsByTagname("item");
    $object = array();
    
    foreach ($nodes as $node) {
      array_push($object, $node -> getAttribute("name"));
    }
    sort($object);
    
    return $object;
  }
  
  /***************************************************************************/
  /* Variable Retrieval                                                      */
  /***************************************************************************/
  
  /**
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * <code> 
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  * </code>
  * @name genVars  
  * @param string $force_default
  */
  function genVars( $file = "var.xml", $force_default = false ) {
    $this -> VARIABLES = array();
	  $this -> VARIABLES["GET"] = array();
	  $this -> VARIABLES["POST"] = array();
	  
	  if (! $this -> request) {
	    $the_request = $_SERVER["REQUEST_URI"];
	  } else {
      $the_request = $this -> request;
    }
    
    //We're not in the normal Base Directory ("SRC")
    if (strlen($this -> controlurl) > 0) {
      //We're in another framework, but not using that framework
      //HACK ALERT:: Added Smarty
      if (left($_SERVER["REQUEST_URI"],strlen($this -> controlurl)) == $this -> controlurl) {
        include_once ("Smarty/libs/Smarty.class.php");
        $the_request = substr($the_request,strlen($this -> controlurl));
      } else {
        $the_request = substr($the_request,strlen($this -> controlurl));
      }
    }
    
    $raw_request = explode("?",$the_request);
	  
    $request = explode("/",$raw_request[0]);
    (end($request) == "") ? array_pop($request) : null;
    array_shift($request);
    
    if (! in_array($request[0],$this -> scopes)) {
      $this -> SCOPE = "pages";
      $this -> SCOPELOC = $this -> SCOPE;
    } elseif ( $request[0] == "services" ) {
      $this -> SCOPE = "modules";
      $this -> SCOPELOC = $this -> SCOPE;
      array_shift($request);
    } else {
      $this -> SCOPE = $request[0];
      $this -> SCOPELOC = "pages";
      array_shift($request);
    }
    
    if (strlen($request[0]) > 0) {
      $this -> ID = $request[0];
      array_shift($request);
    } else {
      $this -> ID = $this -> home_page_id;
    }
    
    $this -> PATH = $this -> SCOPELOC . "/" . $this -> ID;
    
    if ((strlen($this -> basedir) > 0) && (left($_SERVER["REQUEST_URI"],5) != "/wtvr")) {
      $this -> PATH = $this -> basedir . $this -> SCOPELOC . "/" . $this -> ID;
    }
    
    //Pull the SCOPE Variables if they exist
    if (file_exists($this -> local_libroot."conf/". $this -> SCOPE . "_var.xml")) {
      $vardoc = new DOMDocument();
      $vardoc -> load($this -> local_libroot."conf/". $this -> SCOPE . "_var.xml");
      $vars = $vardoc -> getElementsByTagName("var");
      foreach($vars as $var) {
        if (! $force_default)
        $val = (count($request) > 0 && strlen($request[0]) > 0) ? urldecode($request[0]) : $var -> getAttribute("default");
        else
        $val = $var -> getAttribute("default");
        $this -> VARIABLES["GET"][urldecode($var -> getAttribute("name"))] = $val;
        array_shift($request);
      }
    }
    
    //Pull the module or page variables, if they exist
    if (file_exists($this -> docroot.$this -> PATH."/conf/" . $file)) {
      $vardoc = new DOMDocument();
      $vardoc -> load($this -> docroot.$this -> PATH."/conf/" . $file);
      $vars = $vardoc -> getElementsByTagName("var");
      foreach($vars as $var) {
        if (! $force_default)
        $val = (count($request) > 0 && strlen($request[0]) > 0) ? urldecode($request[0]) : $var -> getAttribute("default");
        else
        $val = $var -> getAttribute("default");
        $this -> VARIABLES["GET"][urldecode($var -> getAttribute("name"))] = urldecode($val);
        array_shift($request);
      }
    }
    
    if ($this -> allow_extended_vars && count($request) > 0 && strlen($request[0]) > 0){
    foreach($request as $var) {
        $this -> VARIABLES["GET"][count($this->VARIABLES)] = $var;
        array_shift($request);
    }}
    
    if (($this -> allow_extended_vars) && (count($raw_request) == 2)) {
    $var2 = explode("&",$raw_request[1]);
    foreach($var2 as $var) {
      $val = explode("=",$var);
      if (is_array($var)) {
        $this -> VARIABLES["GET"][urldecode($key)] = $value;
      } else {
        $this -> VARIABLES["GET"][urldecode($val[0])] = urldecode($val[1]);
      }
      array_shift($var2);
    }}
    
    foreach($_POST as $key=>$value) {
      if (is_array($value)) {
        $this -> VARIABLES["POST"][urldecode($key)] = $value;
      } else {
        $this -> VARIABLES["POST"][urldecode($key)] = urldecode($value);
      }
    }
    
    $this -> VARIABLES["PROCESS"]= array(null);
    $this -> VARIABLES["ID"]= $this -> ID;
    $this -> VARIABLES["SCOPE"]= $this -> SCOPE;
    
    //dump($this -> VARIABLES);
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
  * @name getScopeSig
  * @param string $scope - FPO copy here
  */
  function getScopeSig( $scope ) {
    return ($scope == 'modules') ? "_mod" : "_page";
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
  * @name getId
  */
  function getId() {
     return (isset($this -> VARIABLES["GET"]["id"])) ? $this -> VARIABLES["GET"]["id"] : false;
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
  * @name getAction
  */
  function getAction() {
     return (isset($this -> VARIABLES["GET"]["action"])) ? $this -> VARIABLES["GET"]["action"] : false;
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
  * @name getFormMethod
  */
  function getFormMethod() {
     $method = false;
     $method = (isset($this -> VARIABLES["POST"]["SUBMIT_delete"])) ? "delete" : $method;
     $method = (isset($this -> VARIABLES["POST"]["SUBMIT_submit"])) ? "submit" : $method;
     $method = (isset($this -> VARIABLES["POST"]["styroaction"])) ? $this -> VARIABLES["POST"]["styroaction"] : $method;
     return $method;
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
  * @name ifVar
  * @param string $name  - FPO copy here
  */
  function ifVar( $name ) {
    if (isset($this -> VARIABLES["GET"]) || isset($this -> VARIABLES["POST"])) {
      if ((array_key_exists($name,$this -> VARIABLES["GET"])) || (array_key_exists($name,$this -> VARIABLES["POST"]))) {
        return true;
      }
    } else {
      return false;
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
  * @name greedyVar
  * @param string $name - FPO copy here
  */
  function greedyVar( $name ) {
    if ($this -> postVar( $name )) {
      return $this -> postVar( $name );
    } elseif ($this -> getVar( $name )) {
      return $this -> getVar( $name );
    } elseif ($this -> sessionVar( $name )) {
      return $this -> sessionVar( $name );
    } elseif ($this -> processVar( $name )) {
      return $this -> processVar( $name );
    }
    return false;
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
  * @name getVar
  * @param string $name - FPO copy here
  */
  function getVar( $name ) {
    if (array_key_exists($name,$this -> VARIABLES["GET"])) {
      return $this -> VARIABLES["GET"][$name];
    }
    return false;
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
  * @name postVar
  * @param string $name - FPO copy here
  */
  function postVar( $name ) {
    if (array_key_exists($name,$this -> VARIABLES["POST"])) {
      return $this -> VARIABLES["POST"][$name];
    }
    return false;
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
  * @name sessionVar
  * @param string $name - FPO copy here
  */
  function sessionVar( $name ) {
    if (array_key_exists($name,$_SESSION)) {
      return $_SESSION[$name];
    }
    return false;
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
  * @name sessionVar
  * @param string $name - FPO copy here
  */
  function processVar( $name ) {
    if (array_key_exists($name,$this -> VARIABLES["PROCESS"])) {
      return $this -> VARIABLES["PROCESS"][$name];
    }
    return false;
  }
  
  /**
  * Session Management Classes 
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * <code> 
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  * </code>
  * @name initSessions
  */
  function initSessions() {
    
    if (! session_id() ) {
      
      if ($this -> sessions == "WTVR") {
        $session = new WTVRSession();
      }
      
      if ((isset($this -> sessions)) && ($this -> sessions != false)) {
        /*
        if (isset($_SERVER["SERVER_NAME"])) {
          $domain = '.'. preg_replace('`^www.`', '', $_SERVER["SERVER_NAME"]);
          // Per RFC 2109, cookie domains must contain at least one dot other than the
          // first. For hosts such as 'localhost', we don't set a cookie domain.
          if (count(explode('.', $domain)) > 2) {
            
          }
        }
        */
        //ini_set('session.cookie_domain', "");
        //ini_set('session.cookie_lifetime' ,'60');
        //header('P3P: CP="NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM"');
        if (! isset($GLOBALS["deadpedal"])) {
          session_start();
        }
      }
      
    }
    
    $this -> access = ($this -> sessionVar($this -> ual_session_id)) ? $this -> sessionVar($this -> ual_session_id) : 0;
    
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
  * @name endSession
  */
  function endSession() {
    
    if ((! $this -> locked) || (isset($_GET['kill']))) {
      // Unset all of the session variables.
      $_SESSION = array();
      
      // If it's desired to kill the session, also delete the session cookie.
      // Note: This will destroy the session, and not just the session data!
      if (! isset($GLOBALS["deadpedal"])) {
        if (isset($_COOKIE[session_name()])) {
           setcookie(session_name(), 'session_name()', time()-42000, '/');
        }
      }
      //Why does this kill a redirect?
      session_destroy();
    
    } else {
    
      //This is less safe, but for development will server our purposes
      foreach ($_SESSION as $key => $value) {
        if ($key != 'devel_key') {
          //echo($key."=".$value);
          unset($_SESSION[$key]);
        }
      }
    }
  }
  
  /**
  * Log Function
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * <code> 
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  * </code>
  * @name logItem  
  * @param string $logtype - FPO copy here
  * @param string $message - FPO copy here
  */
  function logItem($logtype,$message) {
    $location = eval("return \$this -> ".$logtype."log;");
    
    // Let's make sure the file exists and is writable first.
    createFile( $location, $message . "\n", false, true );
    
  }
  
  /**
  * Utility function for propel queries    
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * <code> 
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  * </code>
  * @name propelQuery
  * @param string $sql - FPO copy here
  */
  static function propelQuery($sql) {
    return WTVRData::propelQuery($sql);
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
  * @name dataMap 
  * @param string $conf - FPO copy here
  * @param string $limit - FPO copy here
  */
  function dataMap( $conf, $limit=true, $format="array" ) {
    $d = new WTVRData( $this -> VARIABLES );
    $d -> output_type = $format;
    return $d -> dataMap ( $conf, $limit );
  }
  
  /**
  * To determine Active Methods per class, and include it if necessary 
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * <code> 
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  * </code>
  * @name checkClass  
  * @param string $type - FPO copy here
  * @param string $scope - FPO copy here
  * @param string $id - FPO copy here
  */
  function checkClass($type=false,$scope=false,$id=false) {
    
    $this -> scopesig = ($scope == 'modules') ? "_mod" : "_page";
    
    if (@ $this -> classes[$scope][$id] != null) {
      require_once($scope."/".$id."/classes/class_".$id.$this -> scopesig.".php");
		  if (@ $this -> classes[$scope][$id][strtolower($type)]) {
        //Include the module and page base classes
        return true;
      } else {
				return false;   
			}
		}
		
	}
  
 /**
  * These two functions are used by Pages to control structure
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * <code> 
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  * </code>
  * @name loadConf
  */
  function loadConf( $id = false, $scope = "pages" ) {
    if (! $id) {
      $id = $this -> VARIABLES["ID"];
    }
    $this -> documentConf = new XML();
    $this -> documentConf -> loadXML( $scope . "/".$id."/conf/index.xml");
    
  }
  
  /**
  * These two functions are used by Pages to control structure
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * <code> 
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  * </code>
  * @name loadConf
  */
  function returnConf() {
    return $this -> documentConf;
    
  }
  
  /**
  * Conf should be a WideXML DocumentElement
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * <code> 
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  * </code>
  * @name addModuleToConf 
  * @param string $name - FPO copy here
  * @param string $parse - FPO copy here
  * @param string $group - FPO copy here
  * @param string $position - FPO copy here
  * @param string $conf - FPO copy here
  */
	function addModuleToConf($name,$parse,$group,$position,$conf) {
	  $attribs["name"] = $name;
	  $attribs["parse"] = $parse;
	  $attribs["group"] = $group;
	  
	  $conf -> createSingleElementByPath("module","//module[@name='".$position."']",$attribs,false,"//modules");

  }

  /**
  *XML management functions
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * <code> 
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  * </code>
  * @name loadXMLFile 
  * @param string $scope - FPO copy here
  * @param string $doc - FPO copy here
  */
  function loadXMLFile( $scope="modules", $doc = "index.xml", $type = "xml" ) {
    if ($type == "xsl") {
      $this -> XSLDocument = new XML();
    } else {
      $this -> documentElement = new XML();
    }
    
    if (($scope == "modules") && (! $this -> isUsable( $this -> module_name))) {
      die("access to this resource is restricted.");
    } elseif ($scope == "modules") {
      $id = $this -> module_name;
    } else {
      $id = $this -> page_name;
    }
    if ($type == "xsl") {
      $this -> XSLDocument -> loadXML($scope."/".$id."/".$type."/".$doc); 
    } else {
      $this -> documentElement -> loadXML($scope."/".$id."/".$type."/".$doc); 
    }
  }

  /**
  * Constructor and controller function for XMLIncludes
  * The "Modulator" takes our page conf and adds all the appropriate modules
  * Returning an XML Doc Tree
  * <code> 
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  * </code>
  * @name fetchXML  
  * @param string $type - FPO copy here
  * @param string $conf - FPO copy here
  * @param string $source - FPO copy here
  * @param string $conf - FPO copy here
  * @param string $object- FPO copy here
  * @param string $vars - FPO copy here
  */
  function fetchXML($type=null,$conf=false,$source=false,$object=false,$vars) {
    
    if($type == null) {
			$type = $this -> TYPE;
		}
	  
    //If requesting the entire page, return after parsing XML
	  //We cache the page from here as well
    if (($this -> SCOPE != 'modules') && ($type != 'conf')) {
      switch (strtoupper($type)) {
        case "XML":
        $this -> thecompiler = new XMLInclude( $vars );
        break;
        case "XSL":
        $this -> thecompiler = new XSLInclude( $vars );
        break;
      }
      
      $this -> thecompiler -> SCOPE = $this -> SCOPELOC;
			$this -> thecompiler -> ID = $this -> ID;
			$this -> thecompiler -> CACHE = $this -> CACHE;
			
			//If no source specified, the Modulator will read the default file
      $source = $this -> thecompiler -> IncludeModules($conf,$source,$object,$vars,$this->classes);
      
      $this -> CACHEBASEOBJ = $this -> thecompiler -> CACHEBASEOBJ;
		  
      return $source;
		
		//If source supplied, return the source
		} elseif ($source) { 
      
      $this -> createCacheFromSource( $source );
      
      return $source;
    
    //Otherwise, if source not supplied, get and return the source
    } else {
      
      $source = $this -> initXML($this -> PATH, $type);
      
      $this -> createCacheFromSource( $source );
      
      return $source;
      //die("No Source in Line 550 of Global Base");
      //return $this -> initXML($this -> PATH,$type,$this -> SCOPE.".xml");
    
    }
		
	}
	
  /**
  * Constructor and controller function for XMLIncludes
  * The "Modulator" takes our page conf and adds all the appropriate modules
  * Returning an XML Doc Tree
  * <code> 
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  * </code>
  * @name parseFormFragment  
  * @param string $xml - FPO copy here
  * @param string $data - FPO copy here
  * @param string $xsl - FPO copy here
  * @param string $lib - FPO copy here
  */
  function parseFormFragment( $xml, $data=null, $xsl=null, $lib=true ) {
    
    $thetrans = new XSLTransform( $this -> VARIABLES );
  
    if (is_string($xml)) {
      $thexml = new XML();
      $thexml -> loadXML($xml,false);
    } else {
      $thexml = $xml;
    }
    
    if ($data != null) {
      $aform = new XMLForm();
      $aform -> objXMLDoc = $thexml;
      $aform -> item = $data;
      $aform -> addItem();
      $thexml = $aform -> objXMLDoc;
    }
    
    //Pass the object to the Transformer
    $thetrans -> XMLOBJ = $thexml;
    $thetrans -> GENERATOR = "fragment";
    $thetrans -> SCOPE = "modules";
    
    if ($xsl == null) {
      $xsl = "wtvr/".$GLOBALS["wtvr_version"]."/xsl/formfragment.xsl";
    }
    $thexsl = new XML();
    $thexsl -> loadXML($xsl,$lib);
    
    //Generate the XSL
    $thetrans -> XSL = $thexsl;
    
    return $thetrans -> drawpage();
    
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
  * @name createFormElementFromPost
  * @param string $parse - FPO copy here
  */
  function createFormElementFromPost ($parse=true) {
    $elements_array = array(
			    "name" => $this -> greedyVar("name"),
			    "display" => $this -> greedyVar("display"),
			    "atype" => $this -> greedyVar("type"),
			    "default" => $this -> greedyVar("default"),
			    "aclass" => $this -> greedyVar("class"),
			    "vtip" => $this -> greedyVar("vtip"),
			    "required" => $this -> greedyVar("required"),
			    "displayclass" => $this -> greedyVar("displayclass"),
			    "size" => $this -> greedyVar("size"),
			    "pos" => $this -> greedyVar("pos")
			    );
		if (! $parse) {
		  return $this -> createFormElement( $this -> XMLForm, $elements_array );
		} else {
      return $this -> parseFormFragment( $this -> createFormElement( $this -> XMLForm, $elements_array ), $xsl="wtvr/".$GLOBALS["wtvr_version"]."/xsl/formelementfragment.xsl" );
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
  * @name createFormElement 
  * @param string $XMLForm - FPO copy here
  * @param string $array - FPO copy here
  */
  function createFormElement( $XMLForm, $array ) {
    $doc = new XML();
    $XMLForm -> createStyroformElement( $doc, $array );
    return $doc;
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
  * @name createCacheFromSource
  * @param string $source - FPO copy here
  */
	function createCacheFromSource( $source ) {
	  
    $this -> CACHEBASEOBJ = new XML();
    $root = $this -> CACHEBASEOBJ -> documentElement -> importNode ( $source -> documentElement -> firstChild, true );
		if (! $this -> CACHEBASEOBJ -> documentElement -> appendChild($root)) {
    	errorDirect();
      die ("Error while createing xml cache document in ".$this -> docroot.$this -> XML_TEMPLATE."\n");
		}
  }

  /**
  *Generic XML File Reader for our filesystem
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * <code> 
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  * </code>
  * @name initXML  
  * @param string $path - FPO copy here
  * @param string $type - FPO copy here
  * @param string $file - FPO copy here
  */
	function initXML($path, $type=null,$file=false) {
    $this -> thecompiler = new XML();
    
    if (! $file) {
      $filetype = ($type == 'conf') ? 'xml' : $type;
  		$file = "index.".$filetype;
    }
		
    if (! $this -> thecompiler -> loadXML( $path."/".$type."/" . $file )) {
      die("Error loading ".$path."/".$type."/" . $file." from GlobalBase");
      //$this -> thecompiler -> loadXML( "/xml/".$type."/".$file );
		}
		return $this -> thecompiler;
  }

  /**
  * Generic Data List Generators
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * <code> 
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  * </code>
  * @name xmlList  
  * @param string $data - FPO copy here
  * @param string $type - FPO copy here
  */
  function xmlList( $data, $type = "css", $ret = "standard" ) {
    
    $listarray["name"] = "LIST";
    $listarray["attribs"] = array("type"=>$type,
                                  "name"=>$data["meta"]["name"],
                                  "title"=>$data["meta"]["title"],
                                  "allow_add"=>$data["meta"]["allow_add"],
                                  "pk"=>$data["meta"]["pk"],
                                  "url"=>$_SERVER["SCRIPT_URL"],
                                  "query_string"=>$data["meta"]["baseqs"],
                                  "docount"=>"true",
                                  "header"=>"true",
                                  "totalResults"=>$data["meta"]["totalresults"],
                                  "page"=>$data["meta"]["page"],
                                  "rpp"=>$data["meta"]["rpp"],
                                  "ppp"=>$data["meta"]["ppp"]);
    $listarray["itemname"] = "LISTITEM";
    $listarray["sort"] = $data["meta"]["sort"];
    $listarray["group"] = $data["meta"]["group"];                              
    $listarray["hidden"] = $data["hidden"];                              
    
    $i=0;
    if (count($data["data"]) > 0) {
      $keys = array_keys($data["data"][0]);
      
      foreach ($data["data"] as $datum) {
        foreach($keys as $key) {
          $listarray["items"][$i][$key]=WTVRcleanString($datum[$key]);
        }
        $i++;
      }
    }
    
    if ($ret == "standard") {
      $this -> addList($listarray);
    } else {
      $this -> addNodeList($listarray);
    }
    
    return $listarray;
  }

  /**
  * Styroform Connectors
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * <code> 
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  * </code>
  * @name registerStyroModules
  */
  function registerStyroModules() {
    $modules = array();
    foreach($this -> getModules() as $module) {
      array_push($modules,array("sel_key" => $module, "sel_value" => $module));
    }
    $this -> XMLForm -> registerArray( "modules",$modules );
  }

  /**
  * Can the following be deprecated?
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * <code> 
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  * </code>
  * @name registerStyroPages
  */
  function registerStyroPages() {
    $pages = array();
    foreach($this -> getPages() as $page) {
      array_push($pages,array("sel_key" => $page, "sel_value" => $page));
    }
    $this -> XMLForm -> registerArray( "pages",$pages );
  }
  
  /**
  * Can the following be deprecated?
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * <code> 
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  * </code>
  * @name registerStyroPages
  */
  function registerScopeElements( $type ) {
    $objects = array();
    foreach($this -> getScopeObjects( $type ) as $object) {
      array_push($objects,array("sel_key" => $object, "sel_value" => $object));
    }
    
    $this -> XMLForm -> registerArray( $type,$objects );
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
  * @name addList
  * @param string $listarray - FPO copy here
  */
  function addList($listarray) {
    
    //Root List ELEMENT = $listarray["name"]
    //Root List ELEMENT Attribs = $listarray["attribs"]
    //List Element Name = $listarray["itemname"]
    //$listarray["items"][$i]["attribname"]="attribvalue"
    
    $this -> documentElement -> createSingleElement($listarray["name"],"content",$listarray["attribs"]);      
    $this -> documentElement -> createSingleElement("SORTITEMS",$listarray["name"]);
    $this -> documentElement -> createSingleElement("GROUP",$listarray["name"]);
    $this -> documentElement -> createSingleElement("HIDDENITEMS",$listarray["name"]);
   
    $thisval = null;
    if (isset($listarray["items"])) {
      foreach($listarray["items"] as $item) {
  		  $count = 0;
  		  foreach($item as $key => $attrib) {
          if ($key == "value") {
            $thisval = $attrib;
  			  } elseif (is_array($attrib)) {
  			    foreach($attrib as $atkey => $atval) {
  			      if ((! is_array($atkey)) && (! is_array($atval))) {
                $tempattrib[$key."__".$atkey] = ($atval != "") ? $atval : "";
              }
            }
          } else {
            $tempattrib[$key] = ($attrib != "") ? $attrib : "";
          }
          if (is_array($listarray["group"]) && (in_array($key,$listarray["group"]))) {
            $tempattrib["styrogroup_val"] = $tempattrib[$key];
          }
          $count++;
  			}
  			$this -> documentElement -> createSingleElement($listarray["itemname"],$listarray["name"],$tempattrib,$thisval);
  		}
  	}
  	if (isset($listarray["sort"])) {
      foreach($listarray["sort"] as $sortitem) {
        $sortattrib["value"] = $sortitem;
  		  $this -> documentElement -> createSingleElement("SORTITEM","SORTITEMS",$sortattrib,null);  
  		}
  	}
  	if (isset($listarray["group"])) {
      foreach($listarray["group"] as $groupitem) {
        $groupattrib["value"] = $groupitem;
  		  $this -> documentElement -> createSingleElement("GROUPITEM","GROUP",$groupattrib,null);  
  		}
  	}
  	if (isset($listarray["hidden"])) {
      foreach($listarray["hidden"] as $key => $hiddenitem) {
        $hiddenattrib["value"] = $hiddenitem;
  		  $this -> documentElement -> createSingleElement("HIDDENITEM","HIDDENITEMS",$hiddenattrib,null);  
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
  * @name addList
  * @param string $listarray - FPO copy here
  */
  function addNodeList($listarray,$base="content") {
    
    //Root List ELEMENT = $listarray["name"]
    //Root List ELEMENT Attribs = $listarray["attribs"]
    //List Element Name = $listarray["itemname"]
    //$listarray["items"][$i]["attribname"]="attribvalue"
    
    $this -> documentElement -> createSingleElement($listarray["name"],$base,$listarray["attribs"]);      
    $this -> documentElement -> createSingleElement("SORTITEMS",$listarray["name"]);
    $this -> documentElement -> createSingleElement("GROUP",$listarray["name"]);
    $this -> documentElement -> createSingleElement("HIDDENITEMS",$listarray["name"]);
    
    $thisval = null;
    if (isset($listarray["items"])) {
      $pos = 0;
      foreach($listarray["items"] as $item) {
  		  $this -> documentElement -> createSingleElement($listarray["itemname"],$listarray["name"]);
        $count = 0;
  		  foreach($item as $key => $attrib) {
  		    $thekey = str_replace(" ","",$key);
  		    if ((is_array($attrib)) && ($key == "attribs")) {
            foreach($attrib as $key => $attrib) {
             $attval = ($attrib != "") ? $attrib : "";
             $this -> documentElement -> setAttribute($listarray["itemname"], $pos, $thekey, $attval);
            }
      		}elseif (is_array($attrib)) {
  			    foreach($attrib as $atkey => $atval) {
  			      if ((! is_array($atkey)) && (! is_array($atval))) {
  			        $setval = ($atval != "") ? $atval : "";
                $this -> documentElement -> createSingleCdataElement($thekey."__".$atkey,$listarray["itemname"],$tempattrib,$setval,$pos);
              }
            }
          } else {
            $setval = ($attrib != "") ? $attrib : "";
            $this -> documentElement -> createSingleCdataElement($thekey,$listarray["itemname"],$tempattrib,$setval,$pos);
          }
          if (is_array($listarray["group"]) && (in_array($thekey,$listarray["group"]))) {
            $this -> documentElement -> createSingleCdataElement($thekey,$listarray["itemname"],$tempattrib,$tempattrib[$thekey],$pos);
          }
          $count++;
  			}
  			$pos++;
  		}
  	}
    
  	if (isset($listarray["sort"])) {
      foreach($listarray["sort"] as $sortitem) {
        $sortattrib["value"] = $sortitem;
  		  $this -> documentElement -> createSingleElement("SORTITEM","SORTITEMS",$sortattrib,null);  
  		}
  	}
  	if (isset($listarray["group"])) {
      foreach($listarray["group"] as $groupitem) {
        $groupattrib["value"] = $groupitem;
  		  $this -> documentElement -> createSingleElement("GROUPITEM","GROUP",$groupattrib,null);  
  		}
  	}
  	if (isset($listarray["hidden"])) {
      foreach($listarray["hidden"] as $key => $hiddenitem) {
        $hiddenattrib["value"] = $hiddenitem;
  		  $this -> documentElement -> createSingleElement("HIDDENITEM","HIDDENITEMS",$hiddenattrib,null);  
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
  * @name pushItem  
  */
  function pushItem() {
    $this -> XMLForm -> item = $this -> crud -> get();
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
  * @name returnFragment  
  * @param string $filename
  */
  function returnFragment( $filename, $itemvar="id", $array_item=null ) {
    if ($this -> getVar($itemvar) > 0) {
      $item = $this -> crud -> get();
    } elseif ($array_item != null) {
      $item = $array_item;
    } else {
      $item = null;
    }
    print ($this -> parseFormFragment($this -> docroot."/modules/".$this->module_name."/xml/".$filename, $item ));
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
  * @name returnForm
  */
  function returnForm() {
     $this -> documentElement = $this -> XMLForm -> drawForm();
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
  * @name  returnArray
  * @param string $conf - FPO copy here
  * @param string $local - FPO copy here
  * @param string $limit - FPO copy here
  */
  function returnArray( $conf=false, $local=false, $limit=true ) {
    if (! $conf ) {
      $conf = $this -> docroot. "modules/".$this -> module_name."/query/".$this -> module_name ."_list_datamap.xml" ;
    } elseif ($conf && $local) {
      $conf = $this -> docroot. "modules/".$this -> module_name."/query/".$conf ;
    }
    return $this -> dataMap( $conf, $limit );
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
  * @name  returnArray
  * @param string $conf - FPO copy here
  * @param string $local - FPO copy here
  * @param string $limit - FPO copy here
  */
  function returnJson( $conf=false, $local=false, $limit=true ) {
    if (! $conf ) {
      $conf = $this -> docroot. "modules/".$this -> module_name."/query/".$this -> module_name ."_list_datamap.xml" ;
    } elseif ($conf && $local) {
      $conf = $this -> docroot. "modules/".$this -> module_name."/query/".$conf ;
    }
    return $this -> dataMap( $conf, $limit, "json" );
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
  * @name  returnList
  * @param string $conf - FPO copy here
  * @param string $local - FPO copy here
  * @param string $limit - FPO copy here
  */
  function returnList( $conf=false, $local=false, $limit=true, $type="standard" ) {
    if (! $conf ) {
      $conf = $this -> docroot. "modules/".$this -> module_name."/query/".$this -> module_name ."_list_datamap.xml" ;
    } elseif ($conf && $local) {
      $conf = $this -> docroot. "modules/".$this -> module_name."/query/".$conf."_list_datamap.xml" ;
    }
    
    return $this -> xmlList(  $this -> dataMap( $conf, $limit ), "css", $type );
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
  * @name transformTemplate
  * @param string $scope - FPO copy here
  * @param string $object - FPO copy here
  * @param string $series - FPO copy here
  */
  function cliRequest ( $request, $generator="cli"  ) {
    
    $this -> obj = new WTVR();
    
    $this -> obj -> request = $request;
    
    $this -> obj -> unlockWTVR();
    
    $this -> obj -> genVars();
    
    $this -> obj -> outputType = 'text';
    $this -> obj -> generator = $generator;
      
    $body = $this -> obj -> initWTVR();
    
    $this -> obj = null;
    
    return $body;
  }	
}
?>
