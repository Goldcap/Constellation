<?php


/**
 * SSLWidget.class.php, Symfony PageWidgets
 * Symfony Page Widget Parser.
 * @author Andy Madsen <amadsen@operislabs.com>
 * @version 1.0
 * @package com.Operis.PageWidgets
 */

/**
 * Moves a user between SSL and non-SSL Connections using the SSL Config
 */
 
class SSL_PageWidget extends Utils_PageWidget
{
  
  /** 
  * Inbound Symfony Context.
  * 	 
  *	@property	
  *	@access public
  * @var sfContext
  * @name $context  
  */
	public $context; 
  
	/** 
  * REQUEST URI of the Page
  *  
  *	@property  
  * @access public
  * @var string
  * @name $raw_path  
  */
	public $raw_path;
  
	/** 
  * Transformed REQUEST URI, using encode and decode
  *   
  * @property  
  * @access public
  * @var string
  * @name $path  
  */
	public $path;
  
  /** 
  * Array of parsed SSL Options from YML
  * 
  *	@property  
  * @access public
  * @var array
  * @name $sslinfo  
  */
	public $sslinfo;
  
	/** 
  * Flag to determine if page should be forced to SSL regardless of configuration
  *   
  * @property  
  * @access public
  * @var boolean
  * @name $forcessl  
  */
	public $forcessl;
  
	/** 
  * Flag to determine if a page should be drawn in SSL, based on configuration
  *  
  * @property  
  * @access public
  * @var boolean
  * @name $dossl  
  */
	public $dossl;
  
	/** 
  * Flag to determine if a URL is meant to be drawn in SSL
  * 
  *	@property  
  * @access public
  * @var boolean
  * @name $issecure  
  */
	public $issecure;
  
  /** 
  * Run SSLWidget Logic in Debug Mode
  *   
  * @property  
  * @access public
  * @var boolean
  * @name $debug  
  */
	public $debug;
  
	/** 
  * Should logging be enabled for this application.
  * 
  *	@property  
  * @access public
  * @var boolean
  * @name $log  
  */
	public $log;
  
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
    
    $this -> debug = false;
    $this -> log = true;
    
    $dir = sfConfig::get('sf_app_dir').'/config/sslWidget.yml';
    $this -> sslinfo = sfYaml::load($dir);
    $this -> issecure = (($_SERVER["SERVER_PORT"] == "443") || ($_SERVER["SERVER_PORT"] == "8443"))? true : false;
    //$this -> issecure = ($_SERVER["SERVER_PORT"] == "443") ? true : false;
    
    //Cache Control Headers
    //if ($this -> issecure)
      //$this->context->getResponse()->addCacheControlHttpHeader('private=True');
    
  }
  
  /** 
  * Makes a URL Safe to Place in the Filesystem
  *   
  * @access public
  * @name encodePath
  * @param string $path	  
  */
	function encodePath( $path ) {
    $path = str_replace("/","_",$path);
    $path = str_replace("?","|",$path);
    $path= str_replace("&",".",$path);
    return $path;
  }
  
  /** 
  * Takes a URL and returns it from filesafe to standard RFC 4248 Compliant
  *   
  * @access public
  * @name decodePath
  * @param string $path  
  */
	function decodePath( $path ) {
    $path = str_replace("_","/",$path);
    $path = str_replace("|","?",$path);
    $path= str_replace(".","&",$path);
    return $path;
  }
  
  /** 
  * "Setter" for the forcessl property. Overrides YML SSL Configuration.
  *   
  * @access public
  * @name forceSSL  
  */
	public function forceSSL() {
    $this -> forcessl = true;
    sfConfig::set("force_ssl",true);
  }
  
  /** 
  * "Getter" for the path attribute, sets also the MD4 hash of a specific path.
  * Input the REQUEST_URI and return the filesystem path and hash.  
  *  
  * @access public
  * @name getPath
  * @param string $path  
  */
  function getPath( $path=false ) {
    if (! $path ) {
      $this -> path = $_SERVER["REQUEST_URI"];
    } else {
      $this -> path = $path;
    }
    $this -> raw_path = $this -> path;
    $this -> path = $this -> encodePath( $this -> path );
    
  }
  
  /** 
  * Switches the Browser from Port 80 to Port 443, or vice-versa, depending on
  * determination from configuration options. Likewise uses "blindspots" from 
  * the configuration if some URL's aren't required to be evaluated at all.			  
  * 
  * @access public
  * @name checkSSL  
  */
	function checkSSL() {
    
    if (preg_match("/service/",$_SERVER["REQUEST_URI"])) {
      return true;
    }
    
    $this -> getPath();
    
    //Check to see if there's a direct match for cache skips
    //Do regex pattern matching for cache skips... 
    //Process last, essentially like an allow,deny
    foreach($this -> sslinfo["blindspots"] as $pat) {
    	if (preg_match("/".$pat."/",$this -> path,$matches)) {
        return true;
      }
    }
    
    if ((! $this -> isSecure()) && ($this -> issecure)) {
      //putLog("USER:: ".$this -> sessionVar("user_id")." | MESSAGE:: Swapping to Insecure");
      $this -> set80();
    }
    
    if (($this -> isSecure()) && (! $this -> issecure)) {
      //putLog("USER:: ".$this -> sessionVar("user_id")." | MESSAGE:: Swapping to Secure");
      $this -> set443();
    }
    
    return true;
    
  }
  
  /** 
  * Determines if a URL is available for SSL based on the YML configuration
  * of the current application. (See /apps/application/config/SSLWidget.yml) 
  * This can be a single URL, a Regex Pattern, and can either declare the result
  * as "cachable" or "skippable".	  
  * 
  * @access public
  * @name isSecure  
  */
	function isSecure() {
    
    if ($this -> forcessl) {
      return true;
    }
    
    if (! isset($this -> sslinfo)) {
      return false;
    }
    
    //Check to see if there's a direct match for cache skips
    if (in_array($this -> path, $this -> sslinfo["direct_skips"] )) {
      $this -> dossl=false;
      return false;
    }
    
    //Do regex pattern matching for cache skips... 
    //Process last, essentially like an allow,deny
    foreach($this -> sslinfo["pattern_skips"] as $pat) {
      if (preg_match("/".$pat."/",$this -> path,$matches)) {
        $this -> dossl=false;
        return false;
      }
    }
    
    //Check to see if there's a direct match for cache allows
    if (in_array($this -> path, $this -> sslinfo["direct"] )) {
      $this -> dossl=true;
      return true;
    }
    
    //Do regex pattern matching for cache allows... More expensive, but more flexible
    foreach($this -> sslinfo["pattern"] as $pat) {
      if (preg_match("/".$pat."/",$this -> path,$matches)) {
        $this -> dossl=true;
        return true;
      }
    }
    
    return false;
    
  }
  
  /** 
  * Replaces non SSL Image Paths with SSL Image Paths.
  *  
	* @access public
  * @name clearNonSSLImages
  * @param sfResponse response
  * return sfResponse	  
  */
  function clearNonSSLImages( $response ) {
    
    $content = str_replace("http://www.constellation.tv/prod","",$response->getContent());
    $content = str_replace("http://","https://",$content);
    $response -> setContent($content);
    return $response;
  }
  
  /** 
  * Switches the browser from Port 80 to Port 443.
  *  
	* @access public
  * @name set443  
  */
  function set443() {
		if (! sfConfig::get("app_enforce_ssl")) return;
    if ($this -> issecure) return;
		//putLog("Swapping to Secure");
    $uri = "https://".$this->getDomain().$_SERVER["REQUEST_URI"];
    $uri = preg_replace("/[a-zA-Z0-9]{1}\/\//","/",$uri);
    header( "Location: ".$uri ) ;
    die();
	}
	
	/** 
  * Switches the browser from Port 443 to Port 80.
  *  
	* @access public
  * @name set443  
  */
  function set80() {
		if (! $this -> issecure) return;
		$uri = "http://".$this->getDomain().$_SERVER["REQUEST_URI"];
    $uri = preg_replace("/[a-zA-Z0-9]{1}\/\//","/",$uri);
    header( "Location: ".$uri ) ;
    die();
	}
  
  /** 
  * Looks for a specific cookie to determine if this request should be served
  * from an alternate Domain Name. Certain URL's are meant to be served from
  * these alternates.	(i.e. this page is meant to be from X.constellation.tv, not
  * www.constellation.tv	)
  *  
  * @access public
  * @name getDomain  
  */
  function getDomain() {
    $thishost = explode(":",$_SERVER["HTTP_HOST"]);
    if ($thishost[0] == sfConfig::get("app_domain")) {
  	 return sfConfig::get("app_domain");
    }
    if (($this -> context ->getRequest()->getCookie("partner") != "") || ($this -> context -> getRequest() -> getAttribute("partner") != "")) {
//die("HERE");
	    if ($this -> context -> getRequest() -> getAttribute("partner") != "") {
          return $this -> context ->getRequest()-> getAttribute("partner").".constellation.tv";
      } else {
          return $this -> context ->getRequest()->getCookie("partner").".constellation.tv";
      }   
    } else {
      return sfConfig::get("app_domain");
    }     
  }       
}         
          
