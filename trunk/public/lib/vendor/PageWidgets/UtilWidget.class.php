<?php

/**
 * Utils_PageWidget.class.php, Symfony PageWidgets
 * Symfony Page Widget Parser.
 * @author Andy Madsen <amadsen@operislabs.com>
 * @version 1.0
 * @package com.Operis.PageWidgets
 * 
 */

/**
 * Common Interface for PageWidget classes that refers back to Symfony
 */
 
class Utils_PageWidget
{
  
  /** 
  * Inbound Symfony Context
  *  
	* @access public
  * @var sfContext
  * @name $context  
  */
  public $context;
  
	/** 
  * Container for WTVR Data Object
  *  
	* @property  
  * @access public
  * @var WTVRData
  * @name $dataObj  
  */
  public $dataObj;
  
  
	/** 
  * Class Constructor
  *  
  * @access public
  * @name __construct
  * @param sfContext context	  
  */
  public function __construct( $context=false ){
    if (! $context ) {
      $this -> context = sfContext::getInstance();
    } else {
      $this -> context = $context;
    }
  }
  
  /**
  * Adds an IP Address to the Blocked Sites Directory, via SecurityPageWidget
  *  
  * @name blockIP
  */
  static function blockIP() {
    Security_PageWidget::blockIP();
  }
  
  /**
  * Removes an IP Address from the Blocked Sites Directory, via SecurityPageWidget
  * 
  * @name unblockIP
  */
  static function unblockIP() {
    Security_PageWidget::unblockIP();
  }
  
  /**
  * Adds a Javascript to the template output.
  *   
  * @name addJs
  * @param string $js  
  */
  public function addJs( $js ) {
    $this -> context->getResponse()->addJavascript($js."?v=".sfConfig::get("app_js_version"));
  }
  
  /**
  * Adds a CSS Link to the template output.
  *   
  * @name addCss
  * @param string $css  
  */
  public function addCss( $css ) {
    $this -> context->getResponse()->addStylesheet($css);
  }
  
  /**
  * Sets the Title of the Current Page
  *   
  *	@name setTitle
  *	@param string $title  
  */
  function setTitle( $title ) {
    $this->context->getResponse()->setTitle($title);
  }
  
  /**
  * Sets a Meta Property for the Current Page
  *   
  * @name setMeta
  * @param string $name
  * @param string $value	  
  */
  function setMeta( $name, $value ) {
    $this->context->getResponse()->addMeta($name, $value);
  }
  
  /**
  * Sets a Header Property for the Current Page
  *
  * @name setHeader 
  * @param string $name
  * @param string $value	
  */
  function setHeader( $name, $value ) {
    $this->context->getResponse()->setHttpHeader($name, $value);
  }
  
  /**
  * Sets the HTTP Status Property for the Current Page
  *
  * @name setStatus
  * @param string $status
  * @param string $message	
  */
  function setStatus( $status, $message="Sorry, there was an error." ) {
    $this->context->getResponse()->setStatusCode( $status, $message );
  }
  
  /**
  * Assigns the HTML Template to the current widget's output. Default for
  * all widgets is the widget name, ".template.php", but this would be 
  * modified to be $name.".template.php".
  *
	* @name setTemplate
	* @param string $name	
  */
  function setTemplate( $name ) {
    $this -> widget_vars['template'] = $name;
  }
  
  /**
  * Assigns the Symfony Layout to the current page's output. Default for
  * all pages would come from the YML Page Template, but this would be 
  * modified to be "/app/application/templates/".$name.".php".
  *
	* @name setLayout
	* @param string $name	
  */
  function setLayout( $name ) {
    $this -> layout = $name;
  }
  
  /**
  * Adds a name/value pair to the widget_var array. The actual value can
  * be any valid php object, string, number, array, etc...  
  *  
  * @name setWidgetVar
  * @param string $name
  * @param mixed $value	  
  */
  function setWidgetVar( $name, $value ) {
    $this -> widget_vars[$name] = $value;
  }
  
  /**
  * Merges an array value to the widget_var array. The "array" must conform to 
  * name/value format, but the value of the array can  be any valid php object, 
  * string, number, array, etc...  
  *  
  * @name assignWidgetVar
  * @param array $array	  
  */
  function assignWidgetVar( $array ) {
    $this -> widget_vars = array_merge($this -> widget_vars,$array);
  }
  
  /**
  * Assigns a WTVRData Item Array to the currently active XMLForm, using the 
  * currently active Crud Object. The Crud will be populated with the optional
  * $id parameter. Note both the XMLForm and Crud are generated with any 
  * PageWidget by default.			 
  *  
  * @name pushItem
  * @param int $id  
  */
  function pushItem( $id=false ) {
    if (! $id)
      $id=$this ->getId();
    $this -> XMLForm -> item = $this -> crud -> read( $id );
  }
  
  /**
  * Assigns an array of key/value pairs to an XMLForm. Use to apply custom
  * default values to a form.
  * 	  
  * <code>
  *   $item["key_1"] = "value1";
  *   $item["key_2"] = "value2";
  *   $this -> addItem( $item );  
  * </code>		 
  *	@name addItem
  * @param array $items
  * 	  
  */
  function addItem( $items ) {
    foreach ($items as $key=>$value) {
    	 $this -> XMLForm -> item[$key] = $value;
    }
  }
  
  /**
  * Takes an XMLForm and returns HTML using the appropriate form arrays for 
  * values, defaults, etc...  
  *  
  * @name returnForm
  */
  function returnForm() {
     return $this -> XMLForm -> drawForm();
  }
  
  /**
  * Returns an XMLForm snippet as an html fragment, great for parsing subsections
  * of a form via AJAX.  
  * 
  *	@name returnFragment  
  * @param string $widget 
  * @param string $filename
  * @param string $itemvar
  * @param array $array_item  
  * @param boolean $inline
  */
  function returnFragment( $widget, $filename, $itemvar="id", $array_item=null, $inline=false ) {
    if ($this -> getVar($itemvar) > 0) {
      $item = $this -> crud -> get();
    } elseif ($array_item != null) {
      $item = $array_item;
    } else {
      $item = null;
    }
    print ($this -> parseFormFragment(sfConfig::get("sf_lib_dir")."/widgets/".$widget."/xml/".$filename, $item, null, true, $inline ));
  }
  
  /**
  * Transforms an XMLForm Form Fragment into valid HTML.
  *   
  * @param string $xml - Full Path to XML Fragment
  * @param array $data - Default Values in Key/Value format
  * @param string $xsl_location - Full Path to XSL Document
  * @param boolean $lib - deprecated
  * @param boolean $inline - deprecated  
  */
  function parseFormFragment( $xml, $data=null, $xsl_location=null, $lib=true, $inline=false ) {
    
    if (is_string($xml)) {
      $thexml = new XML();
      $thexml -> loadXML($xml,false);
    } else {
      $thexml = $xml;
    }
    
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
    
    if ($xsl_location == null) {
      $xsl_location = sfConfig::get("sf_lib_dir")."/vendor/styroform/1.2/xsl/formfragment.xsl";
    }
    
    $thexsl = new XSL();
    $result = $thexsl -> convertDoc($xsl_location,$thexml -> documentElement,true);
    if ($inline) {
      return $result;
    } else {
      print($result);
      die();
    }
    
  }
  
  /**
  * Return an Styroform Form and a Styroform List as separate elements of an array.
  *  
  * @name returnFormAndList
  * @param string $conf
  * @param boolean $local
  * @param boolean $limit
  * @param string $type
  * @param object $util  
  * 		  
  */
  function returnFormAndList( $conf=false, $local=true, $limit=true, $type="standard", $util=null ) {
    $form = $this -> returnForm();
    $list = $this -> returnList( $conf, $local, $limit, $type, $util );
    $val = $form["form"] . $list["form"];
    return array("form"=>$val);
  }
  
  /**
  * Return an Styroform Form and a Styroform List as separate elements of an array.
  *  
  * @name returnListAndForm
  * @param string $conf
  * @param boolean $local
  * @param boolean $limit
  * @param string $type
  * @param object $util  
  */
  function returnListAndForm( $conf=false, $local=true, $limit=true, $type="standard", $util=null ) {
    $form = $this -> returnForm();
    $list = $this -> returnList( $conf, $local, $limit, $type, $util );
    $val = $list["form"] . $form["form"];
    return array("form"=>$val);
  }
  
  /**
  * Transforms a WTVRData XML Schema to a data object, of type "output_type", either
  * an Array or XML.
  *  
  * @name dataMap 
  * @param string $conf - Full Path to XML File
  * @param boolean $limit - Does Query Limit Results
  * @param string $output_type - "array,json"               
  * @param object $util - "array,
  */
  function dataMap( $conf, $limit=true, $output_type="array", $util=false ) {
    if ($conf instanceof XML) {
      $this -> logItem("Data Map File from Object","CONF");
    } else {
      $this -> logItem("Data Map File",$conf);
    }
    $this -> dataObj = new WTVRData( $this -> context );
    return $this -> dataObj -> dataMap ( $conf, $limit, $output_type, $util );
  }
  
  /**
  * Transforms a WTVRData XML Schema to a WTVRData Recordset. Generally better 
  * to use dataMap, but this does expose some internals of the WTVR Data class.  
  *  
  * @name buildResult 
  * @param string $conf - Full Path to XML File
  * @param string $limit - Does Query Limit Results
  */
  function buildResult( $conf, $limit=true ) {
    $this -> dataObj = new WTVRData( $this -> context );
    return $this -> dataObj -> buildResult ( $conf, $limit );
  }
  
  /**
  * Utility function for propel queries, without arguments.
  *   
  * @name propelQuery
  * @param string $sql - SQL Query
  * @return PDO Recordset  
  */
  function propelQuery($sql) {
    $d = new WTVRData( $this -> context );
    return $d -> propelQuery($sql);
  }
  
  /**
  * Utility function for propel queries, without arguments.
  *
	* @name propelInsert
  * @param string $sql - SQL Query
  */
  function propelInsert($sql) {
    $d = new WTVRData( $this -> context );
    $d -> propelQuery($sql);
  }
  
  /**
  * Utility function for propel queries, with arguments.
  *
	* @name propelArgs
  * @param string $sql - SQL Query  
  * @param array $args
  */
  function propelArgs($sql,$args) {
    $d = new WTVRData( $this -> context );
    return $d -> propelArgs($sql,$args);
  }
  
  
  /**
  * Utility function to parse Posted form values and return the Styroform methoc.
  * Useful for "Login" and "Pre-Widget" sleuthing of what form has been passed.  
  *  
  * @name getFormMethod
  */
  function getFormMethod() {
     $method = false;
     $method = ($this -> context -> getRequest() -> getPostParameter("SUBMIT_delete") != null) ? "delete" : $method;
     $method = ($this -> context -> getRequest() -> getPostParameter("SUBMIT_submit") != null) ? "submit" : $method;
     $method = ($this -> context -> getRequest() -> getPostParameter("SUBMIT_method") != null) ? "method" : $method;
     $method = ($this -> context -> getRequest() -> getParameter("styroaction") != null) ? $this -> context -> getRequest() -> getParameter("styroaction") : $method;
     return $method;
  }
  
  /**
  * Returns the "OP" value from the URL String.
  *  
  * @name getOp
  */
  function getOp() {
     return ($this -> context -> getRequest() ->getParameter("op") != null) ? $this -> context -> getRequest() ->getParameter("op") : false;
  }
  
  /**
  * Returns the "ID" value from the URL String.
  *  
  * @name getId
  */
  function getId() {
     return ($this -> context -> getRequest() ->getParameter("id") != null) ? $this -> context -> getRequest() ->getParameter("id") : false;
  }
  
  /**
  * Causes a browser redirect header with associated javascript redirect 
  * to be written to the browser.
  *  
  * @name redirectHeaders
  * @param string $url  - URL To Redirect
  */
  function redirectHeaders( $url ){

    session_write_close(); 
    
    header("Location: http://".$_SERVER["SERVER_NAME"]."/".$url);
    
    echo '<SCRIPT language="JavaScript">';
    echo '<!--';
    echo 'window.location="http://'.$_SERVER["SERVER_NAME"].'/'.urlencode($url);
    echo '//-->';
    echo '</SCRIPT>';
    
    //$this -> context-> getController()->redirect($url);
    die();
  }
  
  /**
  * Causes a browser redirect header to be written to the browser using 
  * symfony.
  *  
  * @name redirect
  * @param string $url  - URL To Redirect
  */
  function redirect( $url ){
    $this -> context-> getController()->redirect($url);
  }
  
  /**
  * Causes a forward request to be written to the browser using 
  * symfony.
  *  
  * @name forward
  * @param string $module  - Symfony module to forward
  * @param string $action - Symfony action to forward  
  */
  function forward( $module, $action ){
    $this -> context->getController()->forward($module, $action);
  }
  
  /**
  * Checks for the existence of a variable, using POST, GET, SESSION, and APP.
  *   
  * @name ifVar
  * @param string $name  - Name of variable.
  */
  function ifVar( $name ) {
    if ($this -> greedyVar( $name )) {
      return true;
    }
    return false;
  }
  
  /**
  * Returns the value of a variable, using POST, GET, SESSION, and APP.
  *
  * @name greedyVar
  * @param string $name - Name of Variable
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
  * Returns the value of a variable, using GET.
  *
  * @name getVar
  * @param string $name - Name of Variable
  */
  function getVar( $name ) {
    if ($this -> context -> getRequest() -> getParameter($name) !== null) {
      $var = $this -> context -> getRequest() -> getParameter($name);
      if (is_int($var)) {
        return (int) $var;
      }
      return $this -> context -> getRequest() -> getParameter($name);
    }
    return false;
  }
  
  /**
  * Sets the value of a GET variable.
  *
  * @name setgetVar
  * @param string $name - Name of Variable
  * @param string $value - Value of Variable  
  */
  function setgetVar( $name, $value ) {
    $_GET[$name] = $value;
    $this -> context -> getRequest() -> setParameter($name,$value);
    
    return true;
  }
  
  /**
  * Returns the value of a variable, using POST.
  *
  * @name postVar
  * @param string $name - Name of Variable
  */
  function postVar( $name ) {
    if ($this -> context -> getRequest() -> getPostParameter($name) !== null) {
      return $this -> context -> getRequest() -> getPostParameter($name);
    }
    if ($_POST[$name] !== null) {
      return $_POST[$name];
    }
    return false;
  }
  
  /**
  * Sets the value of a POST variable.
  *
  * @name setpostVar
  * @param string $name - Name of Variable
  * @param string $value - Value of Variable  
  */
  function setpostVar( $name, $value ) {
    $_POST[$name] = $value;
    $this -> context -> getRequest() -> setParameter($name,$value);
    
    return true;
  }
  
  /**
  * Returns the value of a variable, using SESSION.
  *
  * @name sessionVar
  * @param string $name - Name of Variable
  */
  function sessionVar( $name ) {
    if (($this -> context) && ($this -> context -> getUser()->getAttribute($name) !== null)) {
      return $this -> context -> getUser()->getAttribute($name);
    }
    return false;
  }
  
  /**
  * Sets the value of a SESSION variable.
  *
  * @name setsessionVar
  * @param string $name - Name of Variable
  * @param string $value - Value of Variable 
  */
  function setsessionVar( $name, $value ) {
    $this ->context->getUser()->setAttribute($name,$value);
    return true;
  }
  
  /**
  * Removes a value from SESSION.
  *
  * @name removesessionVar
  * @param string $name - Name of Variable
  */
  function removesessionVar( $name ) {
    $this ->context->getUser()->getAttributeHolder()->remove($name);
    return true;
  }
  
   /**
  * Sets the value of a FLASH variable.
  *
  * @name setflashVar
  * @param string $name - Name of Variable
  * @param string $value - Value of Variable 
  */
  function setflashVar( $name, $value ) {
    $this ->context->getUser()->setFlash($name,$value);
    return true;
  }
  
  
  /**
  * Gets the value of a COOKIE variable, returning the default if nothing is 
  * found.
  *
  * @name cookieVar
  * @param string $name - Name of Variable
  * @param string $default - Value of Variable 
  */
  function cookieVar( $name, $default="" ) {
    if ($this -> context -> getRequest() -> getCookie($name) != "") {
      return $this -> context -> getRequest() -> getCookie($name);
    //Just in case we haven't set the cookie yet
    } else {
      return $default;
    }
  }

  /**
  * Sets the value of a COOKIE variable.
  *
  * @name setcookieVar
  * @param string $name - Name of Variable
  * @param string $value - Value of Variable  
  * @param string $time - Lifetime of Cookie
  * @param string $path - Path of Cookie 
  * @param string $domain - Domain of Cookie 
  * @param string $secure - Only available in SSL
  * @param string $httponly - Only available in HTTP Requests
  */
  function setcookieVar( $name, $value, $time=0, $path="/", $domain=false, $secure=false, $httponly=false ) {
    if (! $domain) {
      $domain = ".constellation.tv";
    }
    
    //Just to be safe with PHP's notirious terrible typing
    ($secure) ? $secure = 1 : 0;
    //Time variable is in days, so do translation...
    $time = time()+60*60*24*$time;
    
    $this -> context -> getResponse() -> setCookie ($name, $value, $time, $path, $domain, $secure, $httponly);
    return true;
  }
  
  /**
  * Sets the value of a COOKIE variable.
  *
  * @name removecookieVar
  * @param string $name - Name of Cookie 
  * @param string $path - Path of Cookie 
  * @param string $domain - Domain of Cookie 
  * @param string $secure - Only available in SSL
  */
  function removecookieVar( $name, $path="/", $domain=false, $secure=false ) {
    if (! $domain) {
      $domain = sfConfig::get("app_domain");
    }
    $this -> context -> getResponse() -> setCookie ($name, "", time() - 3600, $path, $domain, $secure);
    return true;
  }
  
  /**
  * Set the Symfony User as authenticated.
  *  
  * @name setAuthenticated
  * @param boolean $val
  */
  function setAuthenticated( $val = true ) {
    $this ->context->getUser()->setAuthenticated($val);
    return true;
  }
  
  /**
  * Return if Symfony User as authenticated.
  *  
  * @name isAuthenticated
  * @return boolean  
  */
  function isAuthenticated() {
    return $this ->context->getUser()->isAuthenticated();
  }
  
  /**
  * Add a credential to the Symfony User
  *  
  * @name addcredential
  * @param string $name  - Name of Credential
  */
  function addcredential( $name ){
    $this -> context->getUser()->addCredential($name);
  }
  
  /**
  * Remove a credential to the Symfony User
  *  
  * @name removecredential
  * @param string $name  - Name of Credential
  */
  function removecredential( $name ){
    $this -> context->getUser()->removeCredential($name);
  }
  
   /**
  * Check if the Symfony User has a credential
  *  
  * @name hascredential
  * @param string $name  - Name of Credential
  */
  function hascredential( $name ){
    if ($this -> context->getUser()->hasCredential($name)) {
      return true;
    }
    return false;
  }
  
  /**
  * Returns the Symfony User Object
  *  
  * @name getUser
  * @return sfUser  
  */
  function getUser() {
    return $this ->context->getUser();
  }
  
  /**
  * Returns the value of a variable, using sfConfig.
  *
  * @name processVar
  * @param string $name - Name of Variable
  */
  function processVar( $name ) {
    return sfConfig::get($name);
  }
  
  /**
  * Sets the value of an sfConfig variable.
  *
  * @name setProcessVar
  * @param string $name - Name of Variable
  * @param string $value - Value of Variable 
  */
  function setProcessVar( $name, $value ) {
    return sfConfig::set($name,$value);
  }
  
  /**
  * Return a Styroform List as an HTML Element.
  *  
  * @name returnList
  * @param string $conf
  * @param boolean $local
  * @param boolean $limit
  * @param string $type
  * @param object $util
  * @return text	  
  */
  function returnList( $conf=false, $local=true, $limit=true, $type="standard", $util=null ) {
    if ($conf instanceof XML) {
      //
    } elseif (! $conf ) {
      $conf = $this -> baseloc. "query/".$this -> widget_name ."_list_datamap.xml" ;
    } elseif ($conf && $local) {
      $conf = $this -> baseloc."query/".$conf ;
    }
    
    return $this -> xmlList(  $this -> dataMap( $conf, $limit, "array", $util ), "css", $type );
  }
  
  /**
  * Create a Styroform List as an HTML Element from a PDO Result Set.
  *  
  * @name createList
  * @param PDO_Recordsest $rs
  * @param string $result_name
  * @param string $titlename
  * @param string $allowadd
  * @return text	  
  */
  function createList( $rs, $result_name, $titlename, $allowadd  ) {
   
    //$baseqs=substr($baseqs,0,strlen($thevars) - 1);
    $res["data"] = $rs;
    $res["hidden"] = null;
    $res["meta"]["name"] = $resultname;
    $res["meta"]["title"] = $titlename;
    $res["meta"]["allow_add"] = $allowadd;
    $res["meta"]["pk"] = null;
    $res["meta"]["totalresults"] = count($rs);
    $res["meta"]["page"] = 1;
    $res["meta"]["rpp"] = 0;
    $res["meta"]["ppp"] = 5;
    $res["meta"]["baseqs"] = null;
    $res["meta"]["sort"] = null;
    $res["meta"]["terms"] = null;
    $res["meta"]["group"] = null;
    $res["meta"]["result_id"] = null;
    $res["output"]["sizes"] = null;
    $res["output"]["format"] = null;
    
    return $this ->  xmlList( $res, "css", $type );
  }
  
  /**
  * Create an Excel Spreadsheet as a Binary Stream from a PDO Result Set. The 
  * "Args" param should include the filename, and the location.
  * 
  * <code>
  *   $args["filename"] = "SomeFile";
  *   $args["location"] = "/var/foo/bar";
  * </code>			  
  *  
  * @name createExcel
  * @param PDO_Recordsest $rs
  * @param string $result_name
  * @param string $titlename
  * @param string $args
  * @return BinaryStream	  
  */
  function createExcel( $rs, $result_name, $titlename, $args=false  ) {
   
    //$baseqs=substr($baseqs,0,strlen($thevars) - 1);
    $res["data"] = $rs;
    $res["hidden"] = null;
    $res["meta"]["name"] = $resultname;
    $res["meta"]["title"] = $titlename;
    $res["meta"]["allow_add"] = false;
    $res["meta"]["pk"] = null;
    $res["meta"]["totalresults"] = count($rs);
    $res["meta"]["page"] = 1;
    $res["meta"]["rpp"] = 0;
    $res["meta"]["ppp"] = 5;
    $res["meta"]["baseqs"] = null;
    $res["meta"]["sort"] = null;
    $res["meta"]["terms"] = null;
    $res["meta"]["group"] = null;
    $res["meta"]["result_id"] = null;
    $res["output"]["sizes"] = null;
    $res["output"]["format"] = null;
    
    return $this -> genExcel( $res, $args );
  }
  
  /**
  * XML Data List Generator, creates an XML Tree from a specific array.
  * 
  *	Type refers to the method in which a list is rendered in HTML, as CSS Divs
  *	or as a table with tr/td tags.  
  *	
  *	A "standard" ret results in an HTML List element, a "return" ret
  * will return the modified array, and the default is to add the list to the
  * current XML Tree, if using Styroform. Base refers to the base nodeelement
  * in a Styroform XML Tree that the list would be appended to.
  * 
  * @name xmlList  
  * @param array $data
  * @param string $type - "css,table"
  * @param string $ret - "standard,return"
  * @param string $base
  * @return mixed  
  */
  function xmlList( $data, $type = "css", $ret = "standard", $base="content" ) {
    
    $currentpath = explode("?",$_SERVER["REQUEST_URI"]);
    
    $listarray["name"] = "LIST";
    $listarray["attribs"] = array("type"=>$type,
                                  "name"=>$data["meta"]["name"],
                                  "links"=>$data["meta"]["links"],
                                  "title"=>$data["meta"]["title"],
                                  "allow_add"=>$data["meta"]["allow_add"],
                                  "pk"=>$data["meta"]["pk"],
                                  "url"=>$_SERVER["REQUEST_URI"],
                                  "script"=>$currentpath[0],
                                  "script_default"=>$data["meta"]["script_default"],
                                  "query_string"=>$data["meta"]["baseqs"],
                                  "docount"=>"true",
                                  "header"=>"true",
                                  "totalResults"=>$data["meta"]["totalresults"],
                                  "page"=>$data["meta"]["page"],
                                  "pagevar"=>$data["meta"]["pagevar"],
                                  "rpp"=>$data["meta"]["rpp"],
                                  "ppp"=>$data["meta"]["ppp"],
                                  "result_id"=>$data["meta"]["result_id"]);
    $listarray["itemname"] = "LISTITEM";
    $listarray["sort"] = $data["meta"]["sort"];
    $listarray["terms"] = $data["meta"]["terms"];
    $listarray["group"] = $data["meta"]["group"];                              
    $listarray["hidden"] = $data["hidden"];                              
    $listarray["textbody"] = $data["output"]["textbody"];   
    $listarray["textfooter"] = $data["output"]["textfooter"]; 
    $listarray["formwrapper"] = $data["output"]["formwrapper"]; 
    
    $i=0;
    if (count($data["data"]) > 0) {
      $keys = array_keys($data["data"][0]);
      
      foreach ($data["data"] as $datum) {
        foreach($keys as $key) {
          if (! is_array($datum[$key])) {
            $listarray["items"][$i][$key]=WTVRcleanString($datum[$key]);
          } else {
            $listarray["items"][$i][$key]=$datum[$key];
          }
        }
        $i++;
      }
    }
    
    if ($ret == "standard") {
      return $this -> addList($listarray, $base);
    } elseif ($ret == "return") {
      return $listarray;
    } else {
      return $this -> addNodeList($listarray, $base);
    }
    
    //return $listarray;
  }
  
  /**
  * Returns a delimited string from a WTVR XML Query. 
  *  
  * @name returnDelim
  * @param string $conf - Full Path to XML File
  * @param boolean $local - Return from standard widget layout
  * @param boolean $limit - Does Query Limit Results
  * @param string $type - String Delimiter               
  * @param object $util
  */
  function returnDelim( $conf=false, $local=true, $limit=true, $type=",", $util=null ) {
    if (! $conf ) {
      $conf = $this -> baseloc. "query/".$this -> widget_name ."_list_datamap.xml" ;
    } elseif ($conf && $local) {
      $conf = $this -> baseloc."query/".$conf ;
    }
    
    return $this -> delimList(  $this -> dataMap( $conf, $limit, "array", $util ), $type );
  }
  
  /**
  * Character Delimited Data List Generator, creates a Character Delimited
  * String from a specific array.
  * 
  * @name delimList  
  * @param array $data - Input Array
  * @param string $delim - Character to use as delimiter
  * @return string  
  */
  function delimList( $data, $delim = "," ) {
    
    $content = "";
    
    if (count($data["data"]) > 0) {
      $keys = array_keys($data["data"][0]);
    
      $i=0;
      foreach ($keys  as $key) {
        $content .= '"'.$key.'"';
        $i++;
        ($i != count($keys)) ? $content .= $delim : null;
      }
      $content .= "\n";
      
      $i=0;
      foreach ($data["data"] as $datum) {
        $k = 0;
        foreach($keys as $key) {
          $content .= '"'.WTVRcleanString($datum[$key]).'"';
          $k++;
          ($k != count($keys)) ? $content .= $delim : null;
        }
        $i++;
        $content .= "\n";
      }
    }
    
    return $content;
  }
  
  /**
  * Generates an HTML List from an XML Dom Tree using Styroform LISTELEMENT
  * and LIST nodes. (See Styroform XSL for specific format)  
  *   
  * @name addList
  * @param array $listarray
  * @return text  
  */
  function addList($listarray) {
    
    $this -> documentElement = new XML();
    $this -> documentElement -> loadXML( $this -> baseloc."xml/index.xml" );
    $this -> documentElement -> setPathAttribute('//widget',0, 'name',$this->widget_name);
    
    //Root List ELEMENT = $listarray["name"]
    //Root List ELEMENT Attribs = $listarray["attribs"]
    //List Element Name = $listarray["itemname"]
    //$listarray["items"][$i]["attribname"]="attribvalue"
    
    $this -> documentElement -> createSingleElement($listarray["name"],"content",$listarray["attribs"]);
    $this -> documentElement -> createSingleElement("TERMITEMS",$listarray["name"]);
    $this -> documentElement -> createSingleElement("SORTITEMS",$listarray["name"]);
    $this -> documentElement -> createSingleElement("GROUP",$listarray["name"]);
    $this -> documentElement -> createSingleElement("HIDDENITEMS",$listarray["name"]);
    
    //Added this to allow for simple formatting of output
    $this -> documentElement -> createSingleElement("textbody",$listarray["name"],null,$listarray["textbody"]);
    //Added this to allow for simple formatting of output
    $this -> documentElement -> createSingleElement("textfooter",$listarray["name"],null,$listarray["textfooter"]);
    //Added this to allow lists with form elements
    $this -> documentElement -> createSingleElement("formwrapper",$listarray["name"],null,$listarray["formwrapper"]);
    //Added this to allow alternate BASE HREF for internal links
    $this -> documentElement -> createSingleElement("script_base",$listarray["name"],null,$listarray["attribs"]["script_default"]);
   
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
  	
  	if (isset($listarray["terms"])) {
      foreach($listarray["terms"] as $termitem) {
        $termattrib["name"] = $termitem["name"];
        $termattrib["value"] = $termitem["value"];
        
        $this -> documentElement -> createSingleElement("TERMITEM","TERMITEMS",$termattrib,null);  
  		}
  	}
  	
  	if (isset($listarray["sort"])) {
      foreach($listarray["sort"] as $sortitem) {
        $sortattrib["value"] = $sortitem["name"];
        $sortattrib["description"] = ucwords(str_replace("_"," ",$sortitem["name"]));
        if ($sortitem["selected"]=="true") {
          $sortattrib["selected"] = "true";
        } else {
          $sortattrib["selected"] = "false";
        }
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
  	
    if(sfConfig::get("showXML")) {
      $this -> documentElement->saveXML();
      die();
    }
    
    $this -> objXSLDoc = new XSL();
    $xsl_location = $this -> baseloc. "xsl/index.xsl";
    
		return array("form"=>$this -> objXSLDoc -> convertDoc($xsl_location,$this -> documentElement->documentElement, true));
    
  }
  
  /**
  * Adds an XML List to an XML Dom Tree using Styroform LISTELEMENT
  * and LIST nodes. (See Styroform XSL for specific format)  
  *   
  * @name addNodeList
  * @param array $listarray
  * @param string $base - Name of node to attatch list to  
  * @return text  
  */
  function addNodeList($listarray,$base="content") {
    
    $this -> documentElement = new XML();
    $this -> documentElement -> loadXML( $this -> baseloc."xml/index.xml" );
    $this -> documentElement -> setPathAttribute('//widget',0, 'name',$this->widget_name);
    
    $basenode = $this -> documentElement -> createElement($listarray["name"]);
    foreach ($listarray["attribs"] as $key => $value) {
      $basenode -> setAttribute($key, $value);
    }
    
    //Added this to allow for simple formatting of output
    
    $thisval = null;
    if (isset($listarray["items"])) {
      $pos = 0;
      foreach($listarray["items"] as $item) {
        $childnode = $this -> documentElement -> createElement($listarray["itemname"]);
        $count = 0;
  		  foreach($item as $key => $attrib) {
  		    $thekey = str_replace(" ","",$key);
  		    if ((is_array($attrib)) && ($key == "attribs")) {
            foreach($attrib as $key => $attrib) {
             $attval = ($attrib != "") ? $attrib : "";
             $childnode -> setAttribute($thekey, $attval);
            }
      		}elseif (is_array($attrib)) {
      		  $childparent = $this -> documentElement -> createElement($thekey);
  			    foreach($attrib as $atkey => $atval) {
  			      if ((! is_array($atkey)) && (! is_array($atval))) {
  			        $setval = ($atval != "") ? $atval : "";
                $subchild = $this -> documentElement -> createElement($thekey."_child");
                
                $this -> documentElement -> appendCdata($subchild,$setval);
                $childparent -> appendChild($subchild);
                $childnode -> appendChild($childparent);
                
              }
            }
          } else {
            $setval = ($attrib != "") ? $attrib : "";
            $subchild = $this -> documentElement -> createElement($thekey);
            $attval = ($attrib != "") ? $attrib : "";
            $this -> documentElement ->  appendCdata($subchild,$setval);
            $childnode -> appendChild($subchild);
            //$this -> documentElement -> createSingleCdataElement($thekey,$listarray["itemname"],$tempattrib,$setval,$pos);
          }
          if (is_array($listarray["group"]) && (in_array($thekey,$listarray["group"]))) {
            $setval = ($attrib != "") ? $attrib : "";
            $subchild = $this -> documentElement -> createElement($thekey);
            foreach($tempattrib as $key => $attrib) {
             $attval = ($attrib != "") ? $attrib : "";
             $subchild -> setAttribute($thekey, $attval);
            }
            $this -> documentElement ->  appendCdata($subchild,$tempattrib[$thekey]);
            $childnode -> appendChild($subchild);
            //$this -> documentElement -> createSingleCdataElement($thekey,$listarray["itemname"],$tempattrib,$tempattrib[$thekey],$pos);
          }
          $count++;
  			}
  			$basenode -> appendchild($childnode);
  			$pos++;
  		}
  	}
    
    $sortnode = $this -> documentElement -> createElement("SORTITEMS");
  	if (isset($listarray["sort"])) {
      foreach($listarray["sort"] as $sortitem) {
        $asortnode = $this -> documentElement -> createElement("SORTITEM");
        $asortnode  -> setAttribute("value", $sortitem["name"]);
        $asortnode  -> setAttribute("description", ucwords(str_replace("_"," ",$sortitem["name"])));
        if ($sortitem["selected"]=="true") {
          $asortnode  -> setAttribute("selected", "true");
        }
        $sortnode -> appendChild($asortnode);
  		}
  	}
  	$basenode -> appendchild($sortnode);
  	
  	$termnode = $this -> documentElement -> createElement("TERMS");
  	if (isset($listarray["terms"])) {
      foreach($listarray["terms"] as $termitem) {
        $atermnode = $this -> documentElement -> createElement("TERMITEM");
        $atermnode  -> setAttribute("value", $termitem["value"]);
        $atermnode  -> setAttribute("name", $termitem["name"]);
        $termnode -> appendChild($atermnode);
  		}
  	}
  	$basenode -> appendchild($termnode);
  	
  	$groupnode = $this -> documentElement -> createElement("GROUP");
  	if (isset($listarray["group"])) {
      foreach($listarray["group"] as $groupitem) {
        $agroupnode = $this -> documentElement -> createElement("SORTITEM");
        $agroupnode  -> setAttribute("value", $groupitem);
        $groupnode -> appendChild($agroupnode); 
  		}
  	}
  	$basenode -> appendchild($groupnode);
  	
  	$hiddennode = $this -> documentElement -> createElement("HIDDENITEMS");
  	if (isset($listarray["hidden"])) {
      foreach($listarray["hidden"] as $key => $hiddenitem) {
        $ahiddennode = $this -> documentElement -> createElement("HIDDENITEM");
        $ahiddennode  -> setAttribute("value", $hiddenitem);
        $hiddennode -> appendChild($ahiddennode); 
  		}
  	}
  	$basenode -> appendchild($hiddennode);
  	//dump($this -> context -> getRequest() -> getRequestParameters());
  	
  	//If we've overloaded our Request Parameters, check in this array
    //$sets = $this -> context -> getRequest() -> getParameterHolder()->getAll();
  	
    $hiddennode = $this -> documentElement -> createElement("PARAMS");
  	  foreach($this -> context -> getRequest() -> getRequestParameters() as $key => $hiddenitem) {
        if (is_array($hiddenitem)) {
          foreach ($hiddenitem as $item){
            $ahiddennode = $this -> documentElement -> createElement("VAR");
            $ahiddennode  -> setAttribute("name", urldecode($key));
            $ahiddennode  -> setAttribute("value", $item);
            $hiddennode -> appendChild($ahiddennode);
          }
        } else {
          $ahiddennode = $this -> documentElement -> createElement("VAR");
          $ahiddennode  -> setAttribute("name", urldecode($key));
          $ahiddennode  -> setAttribute("value", $hiddenitem);
          $hiddennode -> appendChild($ahiddennode); 
        }
  		}
  	$basenode -> appendchild($hiddennode);
  	
    $hiddennode = $this -> documentElement -> createElement("GET");
  	  foreach($this -> context -> getRequest() -> getGetParameters() as $key => $hiddenitem) {
        if (is_array($hiddenitem)) {
          foreach ($hiddenitem as $item){
            $ahiddennode = $this -> documentElement -> createElement("VAR");
            $ahiddennode  -> setAttribute("name", $key);
            $ahiddennode  -> setAttribute("value", $item);
            $hiddennode -> appendChild($ahiddennode);
          }
        } else {
          $ahiddennode = $this -> documentElement -> createElement("VAR");
          $ahiddennode  -> setAttribute("name", $key);
          $ahiddennode  -> setAttribute("value", $hiddenitem);
          $hiddennode -> appendChild($ahiddennode); 
        }
  		}
  	$basenode -> appendchild($hiddennode);
  	
  	$hiddennode = $this -> documentElement -> createElement("POST");
  	  foreach($this -> context -> getRequest() -> getPostParameters() as $key => $hiddenitem) {
        if (is_array($hiddenitem)) {
          foreach ($hiddenitem as $item){
            $ahiddennode = $this -> documentElement -> createElement("VAR");
            $ahiddennode  -> setAttribute("name", $key);
            $ahiddennode  -> setAttribute("value", $item);
            $hiddennode -> appendChild($ahiddennode);
          }
        } else {
          $ahiddennode = $this -> documentElement -> createElement("VAR");
          $ahiddennode  -> setAttribute("name", $key);
          $ahiddennode  -> setAttribute("value", $hiddenitem);
          $hiddennode -> appendChild($ahiddennode); 
        }
  		}
  	$basenode -> appendchild($hiddennode);
  	
    $hiddennode = $this -> documentElement -> createElement("PROCESS");
  	  foreach(sfConfig::getAll() as $key => $hiddenitem) {
        if (is_array($hiddenitem)) {
          foreach ($hiddenitem as $item){
            if ((left($key,2) != "sf") && (left($key,3) != "app") && (left($key,3) != "mod")) {
            $ahiddennode = $this -> documentElement -> createElement("VAR");
            $ahiddennode  -> setAttribute("name", $key);
            if (! is_array($item))
            $ahiddennode  -> setAttribute("value", $item);
            $hiddennode -> appendChild($ahiddennode);
            }
          }
        } elseif ((left($key,2) != "sf") && (left($key,3) != "app") && (left($key,3) != "mod")) {
          $ahiddennode = $this -> documentElement -> createElement("VAR");
          $ahiddennode  -> setAttribute("name", $key);
          $ahiddennode  -> setAttribute("value", $hiddenitem);
          $hiddennode -> appendChild($ahiddennode); 
        }
  		}
  	$basenode -> appendchild($hiddennode);
  	
    $this -> documentElement -> appendByPath("//content",$basenode);
    //$this -> documentElement -> saveXML();
    
    $this -> documentElement -> createSingleElement("textbody",$listarray["name"],false,$listarray["textbody"]);
    $this -> documentElement -> createSingleElement("textfooter",$listarray["name"],false,$listarray["textfooter"]);
    $this -> documentElement -> createSingleElement("formwrapper",$listarray["name"],false,$listarray["formwrapper"]);
    
    $this -> objXSLDoc = new XSL();
    $xsl_location = $this -> baseloc. "xsl/index.xsl";
    
    if(sfConfig::get("showXML")) {
      $this -> documentElement->saveXML();
      die();
    }
    
    return array("form"=>$this -> objXSLDoc -> convertDoc($xsl_location,$this -> documentElement->documentElement,true));
  }
  
  /**
  * Returns an array from a WTVR Data Query.
  *   
  * @name  returnArray
  * @param string $conf - Location of Query
  * @param string $local - Is Query in standard Widget Location
  * @param string $limit - Should query limit results
  */
  function returnArray( $conf=false, $local=true, $limit=true ) {
    if (! $conf ) {
      $conf = $this -> baseloc. "query/".$this -> widget_name ."_list_datamap.xml" ;
    } elseif ($conf && $local) {
      $conf = $this -> baseloc."query/".$conf ;
    }
    return $this -> dataMap( $conf, $limit );
  }
  
  /**
  * Returns a json object in Dojo JSON Format (for use with Combo Boxes)
  * from a WTVR Data Query.
  *   
  * @name  returnDojoJson
  * @param string $conf - Location of Query
  * @param string $local - Is Query in standard Widget Location
  * @param string $limit - Should query limit results
  */
  function returnDojoJson( $conf=false, $local=true, $limit=true ) {
     if (! $conf ) {
      $conf = $this -> baseloc. "query/".$this -> widget_name ."_list_datamap.xml" ;
    } elseif ($conf && $local) {
      $conf = $this -> baseloc."query/".$conf ;
    }
    
    $res = $this -> dataMap( $conf, $limit );
    $result = '{identifier:"'.$res["meta"]["name"].'", items: ';
    $result = $result . json_encode($res["data"]);
    $result = $result . '}';
    return $result;
  }
  
  /**
  * Returns a json object from a WTVR Data Query.
  *   
  * @name  returnArray
  * @param string $conf - Location of Query
  * @param string $local - Is Query in standard Widget Location
  * @param string $limit - Should query limit results
  */
  function returnJson( $conf=false, $local=true, $limit=true ) {
     if (! $conf ) {
      $conf = $this -> baseloc. "query/".$this -> widget_name ."_list_datamap.xml" ;
    } elseif ($conf && $local) {
      $conf = $this -> baseloc."/query/".$conf ;
    }
    return $this -> dataMap( $conf, $limit, "json" );
  }
  
  /**
  * Returns an Excel Binary Stream from a WTVR Data Query.
  *   
  * @name  returnExcel
  * @param string $conf - Location of Query
  * @param string $local - Is Query in standard Widget Location
  * @param string $limit - Should query limit results             
  * @param array $args - ("filename,location")
  * @param object $util - WTVR Data Format Utility
  */
  function returnExcel( $conf=false, $local=true, $limit=true, $args=array(), $util=false ) {
    if (! $conf ) {
      $conf = $this -> baseloc. "query/".$this -> widget_name ."_list_datamap.xml" ;
    } elseif ($conf && $local) {
      $conf = $this -> baseloc."/query/".$conf ;
    }
    return $this -> genExcel($this -> dataMap( $conf, $limit, "array", $util ), $args );
  }
  
  /**
  * Generates an Excel Binary Stream from a specific array.
  *  
  * @name  genExcel
  * @param string $array - WTVR Data Array
  * @param string $args - ("filename,location")
  * @param string $util - WTVR Format Utility
  */
  function genExcel( $array, $args, $util=false ) {
    
    //dump($array);
    if (! isset($args["filename"])) {
      $args["filename"] = $array["meta"]["title"]."-".formatDate(false,"PRETTYMD").'.xls';
    }
    createDirectory($args["location"]);
    
    // We give the path to our file here
    $workbook = new Spreadsheet_Excel_Writer($args["location"]."/".$this -> encodeFilename($args["filename"]));
    $worksheet = $workbook->addWorksheet($array["meta"]["title"]);
    
    $row=0;
    $column=0;
    
    if ($array['data'] != null) {
      $colnames = (array_keys($array['data'][0]));
      
      foreach($colnames as $colname) {
        //echo($colname."<br/>");
        if (($colname != 'listtype') && ($colname != 'id')) {
          $worksheet->write($row,$column,$colname);
          $column++;
        }
      }
    
      $row=1;
      $column=0;
      
      $theitems = $array["data"];
      
      foreach($theitems as $item) {
        foreach($item as $key=>$value) {
          //echo($key ."=". $value."<br />");
          if (($key != 'listtype') && ($key != 'id')) {
            $worksheet->write($row,$column,(strlen(trim($value)) == 0)?"":$value);
            $column++;
          }
        }
        $column=0;
        $row++;
      }
      
      // We still need to explicitly close the workbook
      $workbook->close();
      
      return $args["location"]."/".$this -> encodeFilename($args["filename"]);
    }
    
  }
  
  /**
  * Generates a PDF from a specific array.
  *    
  * @name  returnPDF
  * @param string $conf - FPO copy here
  * @param string $local - FPO copy here
  * @param string $limit - FPO copy here 
  * @param string $args - (filename,location)
  */
  function returnPDF( $conf=false, $local=true, $limit=true, $args=array() ) {
    
    if (! $conf ) {
      $conf = $this -> baseloc. "query/".$this -> widget_name ."_list_datamap.xml" ;
    } elseif ($conf && $local) {
      $conf = $this -> baseloc."/query/".$conf ;
    }
    $this -> genPDF($this -> dataMap( $conf, $limit ), $args );
    
  }
  
  /**
  * Generates a PDF Binary Stream from a specific array.
  *  
  * @name  genPDF
  * @param string $array - WTVR Data Array
  * @param string $args - ("filename,location")
  */
  function genPDF( $array, $args ) { 
    
    if (count($array["data"]) == 0) {
      return false;
    }
    
    $colnames = (array_keys($array['data'][0]));
    $column=0;
    
    foreach($colnames as $colname) {
      if (($colname != 'listtype') && ($colname != 'id')) {
        $header[$column] = $colname;
        if (isset($array['output']['format'][$column])) {
          $widths[$colname] = $array['output']['format'][$column];
        } else {
          $widths[$colname] = 50;
        }
        $column++;
      }
    }
    
    $pdf=new FPDF();
    
    $pdf->SetFont('Times','',7);
    $this -> newPDFPage($pdf);
    $this -> setPDFHeader($pdf,$header,$widths);
    
    if (! isset($args["filename"])) {
      $args["filename"] = $array["meta"]["title"]."-".formatDate(false,"PRETTYMD").'.pdf';
    }
    createDirectory($args["location"]);
    
    //Data
  	$fill=0;
  	$row=1;
    $column=0;
    $theitems = $array["data"];
    
    foreach($theitems as $item) {
      if ($row > $array["meta"]["rpp"]) {
        $this -> newPDFPage($pdf);
        $this -> setPDFHeader($pdf,$header,$widths);
        $row = 0;
      }
      //Color and font restoration
    	$pdf->SetFillColor(224,235,255);
    	$pdf->SetTextColor(0);
    	$pdf->SetDrawColor(128,0,0);
    	$pdf->SetLineWidth(.1);
    	$pdf->SetFont('');
    	
      foreach($header as $key) {
        if (($key != 'listtype') && ($key != 'id')) {
          $thevalue = (strlen(trim($item[$key])) == 0)?"No Value":$item[$key];
          $maxchars = ($widths[$key] * .6);
          $thevalue = substr($thevalue,0,$maxchars);
          $pdf->Cell($widths[$key],6,$thevalue,'LR',0,'L',$fill);
          //$worksheet->write($row,$column,(strlen(trim($value)) == 0)?"No Value":$value);
          $column++;
        }
      }
      $column=0;
      $pdf->Ln();
      $row++;
      $fill=!$fill;
    }
    
    $pdf->Output($args["location"]."/".$this -> encodeFilename($args["filename"]));
    
  }
  
  /**
  * Add a Page to a PDF
  *  
  * @name  newPDFPage
  * @param FPDFObject $pdf
  */
  function newPDFPage($pdf) {
    $pdf->AddPage("L");
    $pdf->Image("/var/www/html/sites/dev.tattoojohnny.com/public/web/images/ttj_smallicon.jpg", 10, 10); 
    $pdf->SetXY(200,10);
    $pdf->Cell("300",7,formatDate(null,'pretty'));
    $pdf->SetXY(200,15);
    $pdf->Cell("300",7,"Page: ".$pdf->PageNo());
    $pdf->SetXY(10,40);
  }
  
  /**
  * Add a Header to a PDF
  *  
  * @name  setPDFHeader
  * @param FPDFObject $pdf 
  * @param string $header
  * @param int $widths
  */
	function setPDFHeader($pdf,$header,$widths) {
   //Colors, line width and bold font
  	$pdf->SetFillColor(177,236,252);
  	$pdf->SetTextColor(0);
  	$pdf->SetDrawColor(128,0,0);
  	$pdf->SetLineWidth(.1);
  	$pdf->SetFont('','B');
  	
    //Header
    foreach($header as $key=>$value) {
      //echo $key."=".$value."<br />";
  		$pdf->Cell($widths[$value],7,$value,1,0,'C',1);
  	}
  	$pdf->Ln();
  }
    
  /**
  * Construct a datetime element from Styroform Composite Form Elements
  *   
  * @name sfDateTime
  * @param string $elementname - Name of Styroform Composite Element
  */
  function sfDateTime( $elementname ) {
    if (is_valid_date($_POST[$elementname])) {
      return $_POST[$elementname];
    }
    
    $month = ((isset($_POST[$elementname."|month|comp"])) && (strlen($_POST[$elementname."|month|comp"]) > 0)) ? $_POST[$elementname."|month|comp"] : "01";
    $day = ((isset($_POST[$elementname."|day|comp"])) && (strlen($_POST[$elementname."|day|comp"]) > 0)) ? $_POST[$elementname."|day|comp"] : "01";
    $year = ((isset($_POST[$elementname."|year|comp"])) && (strlen($_POST[$elementname."|year|comp"]) > 0)) ? $_POST[$elementname."|year|comp"] : "1900";
    $hour = ((isset($_POST[$elementname."|hour|comp"])) && (strlen($_POST[$elementname."|hour|comp"]) > 0)) ? $_POST[$elementname."|hour|comp"] : "00";
    $minute = ((isset($_POST[$elementname."|min|comp"])) && (strlen($_POST[$elementname."|min|comp"]) > 0)) ? $_POST[$elementname."|min|comp"] : "00";
    $second = ((isset($_POST[$elementname."|second|comp"])) && (strlen($_POST[$elementname."|second|comp"]) > 0)) ? $_POST[$elementname."|second|comp"] : "00";
    $thetime = ($month . "/" . $day . "/" . $year . " " . $hour . ":" . $minute . ":" . $second);
	  
	  if (strlen(preg_replace("[/\/:\s0/]","",$thetime)) == 0) {
	    return false;
	  } else {
      return $thetime;
    }
	}
	
  /**
  * Export the "showXML" setting for debugging Styroform and WTVR Data.
  * Will dump the object (form or query) to browser.  
  *  
  * @name showXML
  */
  function showXML() {
    
    sfConfig::set("showXML",true);
    
  }
  
  /**
  * Export the "showData" setting for debugging WTVR Data.
  * Will dump the query results to browser.  
  *  
  * @name showData
  */
  function showData() {
    
    sfConfig::set("showData",true);
    
  }
  
  /**
  * Add a message to the Symfony Log
  *   
  * @name logItem  
  * @param string $logtype
  * @param string $message
  */
  function logItem($logtype,$message) {
    if ($this -> context != null)
    sfContext::getInstance()->getLogger()->info("{".$logtype."} \"".$message."\"");
    
  }
  
  /**
  * Loop over a directory and create an XML List, returning a Styroform XML 
  * Dom Element List.
  *  
  * @name listDirectory  
  * @param string $path - Base Path of Directory
  * @param string $url - Base HTTP URL of Directory
  */
  function listDirectory( $path, $url ) {
    if (! is_dir($path)) {
      createDirectory($path);
    }
    $dir = new DirectoryIterator( $path );
    
    $count = 0;
      foreach ($dir as $file) {
        if (isOK($file)) {
          $filename = str_replace("_"," ",$file);
          $filename = str_replace("-",", ",$filename);
          $afile = explode(".",$filename);
          $result[$count]["File"] = $url."/download/".urlEncode($file)."|".$afile[0];
          $result[$count]["Filetype"] = '<img src="http://ll.tattoojohnny.com/images/Neu/16x16/mimetypes/'.$afile[1].'.gif" />';
          $count++;
        }
      }
      
      $res["data"] = $result;
      $res["hidden"] = array();
      $res["meta"]["name"] = "statement";
      $res["meta"]["links"] = "js";
      $res["meta"]["title"] = "Affiliate Click Statements";
      $res["meta"]["allow_add"] = "false";
      $res["meta"]["totalresults"] = count($result);
      $res["meta"]["page"] = 1;
      $res["meta"]["rpp"] = 15;
      $res["meta"]["ppp"] = 5;
      return $this -> xmlList($res);
      
  }
  
  /**
  * Generate a Styroform List of Queries in a Widget Directory
  *  
  * @name listQueries  
  * @param string $path - Path to Widget Query Directory
  */
  function listQueries( $path ) {
    $dir = new DirectoryIterator( $path );
      
    foreach ($dir as $file) {
      if (isOK($file)) {
        $report = new XML();
        $report -> loadXML( $file->getPathname() );
        $reportname = $report -> getPathAttribute("//map","title");
        if ($reportname == urlDecode($this -> getVar("id"))) {
          $this -> returnList($file->getPathname());
          $this -> documentElement -> setPathAttribute("//module[@name='reports']/content/file", 0, "excelfile", "/services/reports/excel/0/?file=".$file->getFilename());
  
        }
      }
    }
  }
  
  /**
  * Display a file from a path using a filename. Better to use the ImageHelper
  * unless using an unusual mime type.
  *  
  * @name showFile  
  * @param string $path - Path to File
  * @param string $file - Name of Stream
  */
  function showFile( $path, $file ) {
      
      $filename = $this -> encodeFilename( $file );
      $afile = explode(".",$filename);
      
      switch ($afile[1]) {
        case "pdf":
          header("Content-type: application/octet-stream");
          break;
        case "xls":
          header("Content-type: application/octet-stream");
          break;
      }
      
      header("Content-Disposition: attachment; filename=\"".$filename."\"");
      $contents = file_get_contents($path."/".$file);
      echo $contents;
      die();
  }
  
  /**
  * Returns the location of a User Specific directory.
  *  
  * @name userDir  
  * @deprecated  
  */
  function userDir() {
    if ($this -> sessionVar("user_id")) {
      return sfConfig::get("app_shared_data_dir")."/usr/".sprintf("%010d",$this -> sessionVar("user_id"));
    }
    return false;
  }
  
  /**
  * Removes specific characters from a filename to make it Windows Safe.
  *  
  * @name encodeFilename  
  * @param string $file - Name of the file
  * @return string  
  */
  function encodeFilename( $file ) {
    
    $filename = str_replace(", ","-",$file);
    $filename = str_replace(" ","_",$filename);
    $filename = str_replace(",","-",$filename);
    return $filename;
  }
  
  /**
  * Return original filename from Windows Safe Filename
  *  
  * @name decodeFilename  
  * @param string $file - Windows Safe Name
  * @return string  
  */
  function decodeFilename( $file ) {
    $filename = str_replace("-",", ",$file);
    $filename = str_replace("_"," ",$filename);
    return $filename;
  }
  
  /**
  * Used to export date-specific data from tables.
  *  
  * @name dbBackup  
  * @param string $table - Name of Table
  * @param string $themonth - Month of Records to be Backed up
  * @param string $theyear - Year of Records to be Backed up
  */
  function dbBackup( $table, $themonth=false, $theyear=false ) {
    $doo = new MySQLAbstract();
    if (! $themonth) {
      $themonth = month();
    }
    if (! $theyear) {
      $theyear = year();
    }
    $backupDir = sfConfig::get("app_shared_data_dir")."/sql/backup/".$table."/".$theyear."-".sprintf("%02d",$themonth)."/";
    $startdate = $theyear . "-" . sprintf("%02d",$themonth)  . "-01 00:00:00";
    if ($this ->widget_vars["args"][0] == 12) {
      $enddate = $theyear . "-01-01 00:00:00" ;
    } else {
      $enddate = $theyear . "-" . sprintf("%02d",$themonth + 1)  . "-01 00:00:00" ;
    }
    $sql = "SELECT * 
     INTO OUTFILE '".$backupDir.$table.".sql'
     FROM ".$table."
     where ".$table."_date_created >= '".$startdate."'
     and ".$table."_date_created < '".$enddate."';";
   
    createDirectory($backupDir);
    if (file_exists($backupDir.$table.".sql")) {
      rename($backupDir.$table.".sql",$backupDir.$table.".".formatDate(null,"RAW").".sql");
    }
    $result = $doo->data_insert($sql);
    
    $this -> logItem("Archive Service","Generated ".$table." Backups for ". $theyear."-".sprintf("%02d",$themonth));
    
  }
  
  /**
  * Returns the contents of a file, using "baseloc", a widget-specific file location.
  *  
  * @name getLocalFile  
  * @param string $file - Name of file
  */
  function getLocalFile( $file ) {
      
      return file_get_contents($this -> baseloc.$file);
      
  }
  
  /**
  * Renders a partial view using an sfPartialView
  *  
  * @name renderPartial  
  * @param sfContext $context - Inbound SF Context
  * @param string $name - Name of Partial View (for logging)
  * @param string $template - Location of PHP Template
  * @param array $args - Passed to the view for rendering
  */
	static function renderPartial( $context, $name, $template, $args=null ) {
    $view = new sfPartialView( $context, 'widgets', $name, $name );
    $view->getAttributeHolder()->add($args);
    $templateloc = $template;
    $view->setTemplate($templateloc);
    return $view->render();
  }
}
