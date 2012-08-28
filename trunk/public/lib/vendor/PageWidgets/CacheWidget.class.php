<?php

/**
 * CacheWidget.class.php, Symfony PageWidgets
 * Symfony Page Widget Parser.
 * @author Andy Madsen <amadsen@operislabs.com>
 * @version 1.0
 * @package com.Operis.PageWidgets 
 */

/**
 * Generates Memcached Output from Symfony Page Widgets
 * 
	//Note, this class is embedded into
	//the lib/symfony/filter/sfRenderingFilter.class.php
	//Line 53 - 56 
 */
 
class Cache_PageWidget extends Utils_PageWidget
{
  
  /** 
  * Inbound Symfony Context
  *   
  * @property  
  * @access public
  * @var sfContext
  * @name $context  
  */
	public $context; 
  
	/** 
  * If caching HTML documents, root location of cache folder
  *   
  * @property  
  * @access public
  * @var string
  * @name $cacheDir  
  */
	public $cacheDir;
  
	/** 
  * Inbound Browser URL
  *   
  * @property  
  * @access public
  * @var string
  * @name $raw_path  
  */
	public $raw_path;
  
	/** 
  * Browser URL with HTML Entities and Special Chars removed
  *   
  * @property  
  * @access public
  * @var string
  * @name $path  
  */
	public $path;
  
	/** 
  * Browser URL in MD4 Format
  *  
  * @property  
  * @access public
  * @var string
  * @name $path_md4  
  */
	public $path_md4;
  
	/** 
  * If caching HTML documents, name of cache file
  *  
  * @property  
  * @access public
  * @var string
  * @name $file  
  */
	public $file;
  
	/** 
  * If caching HTML documents, location of cache folder after parameters added
  *   
  * @property  
  * @access public
  * @var string
  * @name $fileloc  
  */
	public $fileloc;
  
  /** 
  * When was the cache object created
  *   
  * @property  
  * @access public
  * @var timestamp
  * @name $filecreatetime  
  */
	public $filecreatetime;
  
	/** 
  * Server Browser object with specifics about client
  *  
  * @property  
  * @access public
  * @var array
  * @name $browser  
  */
	public $browser;
  
	/** 
  * List of User Agents to match against Browser Requirements
  *  
  * @property  
  * @access public
  * @var array
  * @name $agents  
  */
	public $agents;
  
	/** 
  * Which websites are available for caching
  *  
  * @deprecated
  *	@property  
  * @access public
  * @var boolean
  * @name $sites  
  */
	public $sites;
  
	/** 
  * Should cache resources be indexed on a per-browser basis.
  *   
  * @property  
  * @access public
  * @var boolean
  * @name $browsersafe  
  */
	public $browsersafe;
  
	/** 
  * YML Object with application cache parameters
  *  
  * @property  
  * @access public
  * @var boolean
  * @name $cacheinfo  
  */
	public $cacheinfo;
  
	/** 
  * Should the requested resource be cached
  *   
  * @property  
  * @access public
  * @var boolean
  * @name $docache  
  */
	public $docache;
  
	/** 
  * How long should the cache content persist
  *   
  * @property  
  * @access public
  * @var string
  * @name $cachetime  
  */
	public $cachetime;
  
	/** 
  * Force page to cache regardless of prior cache results or params
  *   
  * @property  
  * @access public
  * @var boolean
  * @name $forcecache  
  */
	public $forcecache;
  
  
  /** 
  * Should Cache actions be in debug mode
  *   
  * @property  
  * @access public
  * @var boolean
  * @name $debug  
  */
	public $debug;
  
	/** 
  * Should Cache actions be logged
  *   
  * @property  
  * @access public
  * @var boolean
  * @name $log  
  */
	public $log;
  
  /** 
  * Class Constructor
  * 
  *	@access public
  * @name __construct  
  * @param sfContext context  
  */
	public function __construct( $context=false ){
    
    if (! $context ) {
      $this -> context = sfContext::getInstance();
    } else {
      $this -> context = $context;
    }
    
    $this -> browsersafe = sfConfig::get("app_pagewidget_cache_browsersafe");
    if ($this -> browsersafe) {
      //$this -> cacheDir = sfConfig::get("app_pagewidget_cache_dir");
      $this -> browser = getBrowserInfo();
      
      $agents = sfConfig::get('sf_config_dir').'/cache_agents.yml';
      $this -> agents = sfYaml::load($agents);
      //$this -> sites = sfConfig::get("app_server_farm");
    }
    
    $this -> debug = false;
    //$this -> log = true;
    $this -> forcecreate = false;
    
    $dir = sfConfig::get('sf_app_dir').'/config/pageWidgetCache.yml';
    $this -> cacheinfo = sfYaml::load($dir);
    
    //Default Value
    //Can be overridden in the page YML
    $this -> cachetime = sfConfig::get("app_pagewidget_cache_lifetime");
    if (sfConfig::get("force_cache")) {
      $this -> forcecache = true;
    }
    
    //If we come from the Master Server, we're building cache
    //So force this
    if ((REMOTE_ADDR()=="10.32.165.132") || (REMOTE_ADDR()=="173.236.42.242")) {
      $this -> forcecreate = true;
    }
  }
  
  /** 
  * If set, the cache of this page will be written regardless of other factors.
  * Usefull to remove stale records during administrative content updates. 
  *  
  * @access public
  * @name forceCache  
  */
	public function forceCache() {
    $this -> forcecache = true;
    sfConfig::set("force_cache",true);
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
  * "Getter" for the path attribute, sets also the MD4 hash of a specific path.
  * Input the REQUEST_URI and return the filesystem path and hash.  
  *  
  * @access public
  * @name getPath
  * @param string $raw_path  
  */
  function getPath( $raw_path=false ) {
    
    if ($this -> raw_path != "") {
      $this -> raw_path = str_replace("%2520","%20",$this -> raw_path);
    } elseif (! $raw_path ) {;
      $this -> raw_path = $_SERVER["REQUEST_URI"];
      $this -> raw_path = str_replace("%2520","%20",$this -> raw_path);
    } else {
      $this -> raw_path = $raw_path;
    }
    
    $this -> file =  $this -> raw_path;
    $this -> path = $this -> encodePath( $this -> raw_path );
    $this -> path_md4 = hash("md4",$this -> path);
    
    /*
    $apath = hash("md4",$this -> path);
    $apathloc = left($apath,1) . "/".left($apath,2)."/".left($apath,3)."/";
    
    if ($this -> browsersafe) {
      $this -> fileloc = $this -> cacheDir."/PageWidgetCache/".$this -> browser[0]."/".$this -> browser[3]."/".$apathloc;
    } else {
      $this -> fileloc = $this -> cacheDir."/PageWidgetCache/All/".$apathloc;
    }
    
    $this -> file = $this ->fileloc.$apath;
    */
    
  }
  
  /** 
  * Takes a given path and places the result output into the cache object, be
  * that Memcached or a Filesystem Object.  
  *
	* @access public
  * @name createCache  
  */
  function createCache() {
    
    $docache = false;
    
    if (($_SERVER["REQUEST_METHOD"] == "POST") && (isset($_POST["styroname"])) && ($_POST["styroname"] != "Login")) {
      return;
    }
    
    $this -> getPath();
    
    if (! $this -> forcecreate) {
      if (! $this -> isCachable())
        return;
    }
    
    $cachetime=explode(":",$this -> cachetime);
    
    if ($this -> debug) {
      //dump($cachetime);
    }
    switch ($cachetime[1]) {
      case "MONTH":
        $thecurrenttime=$cachetime[0]*29*86400;
      	break;
      case "WEEK":
        $thecurrenttime=$cachetime[0]*7*86400;
      	break;
      case "DAY":
        $thecurrenttime=$cachetime[0]*86400;
      	break;
      case "HOUR":
        $thecurrenttime=$cachetime[0]*3600;
      	break;
      case "MINUTE":
        $thecurrenttime=$cachetime[0]*60;
      	break;
      default:
        $thecurrenttime=0;
        break;
    }
    
    if ($thecurrenttime==0) {
      return;
    }
    
    $memcache = new Memcached();
    $memcache->setOption(Memcached::OPT_COMPRESSION, false);
    $memcache->addServer(sfConfig::get("app_mongo_cache"), 11211);
    $result1 = $memcache->set( $this -> file, $this->context->getResponse()->getContent()."\n<!--  PATH: (".$this -> raw_path.") XMEMCACHE CREATE: ". now() . "; EXPIRES: ".$this -> cachetime."; -->", $thecurrenttime );
    //Just in case our retarded NGINX Memcached URL is too long, we have MD4
    $result2 = $memcache->set( $this -> path_md4, $this->context->getResponse()->getContent()."\n<!--  PATH: (".$this -> raw_path.") XMEMCACHE CREATE: ". now() . "; EXPIRES: ".$this -> cachetime."; -->", $thecurrenttime );
    /*
    $vars = sfConfig::get("app_mongo_cache_dsn");
    $this -> hit = new Mongo( $vars[0] );
    $coll = $this -> hit -> selectDB( $vars[1] )
                 -> selectCollection( "cache_registry" );
         
    $coll -> update(  array ("url"=>$this -> file), 
                      array("url"=>$this -> file,
                      "date" => time()),
                      true);
    
    sfContext::getInstance()->getLogger()->info("{PageWidgetCache} Created Cache file at \"".$this -> file."\"");
    */
  }
  
  /** 
  * Determines if a URL is available for caching based on the YML configuration
  * of the current application. (See /apps/application/config/cacheWidget.yml) 
  * This can be a single URL, a Regex Pattern, and can either declare the result
  * as "cachable" or "skippable".	  
  * 
  * @access public
  * @name isCachable  
  */
	function isCachable() {
    
    if (isset($_GET["pid"])) {
      return false;
    }
    
    if ($this -> forcecache) {
      return true;
    }
    
    if (! isset($this -> cacheinfo)) {
      return false;
    }
    
    //Check to see if there's a direct match for cache skips
    if (in_array($this -> path, $this -> cacheinfo["direct_skips"] )) {
      $this -> docache=false;
      return false;
    }
    
    //Do regex pattern matching for cache skips... 
    //Process last, essentially like an allow,deny
    foreach($this -> cacheinfo["pattern_skips"] as $pat) {
      if (preg_match("/".$pat."/",$this -> path,$matches)) {
        $this -> docache=false;
        return false;
      }
    }
    
    //Check to see if there's a direct match for cache allows
    if (in_array($this -> path, $this -> cacheinfo["direct"] )) {
      $this -> docache=true;
      return true;
    }
    
    //Do regex pattern matching for cache allows... More expensive, but more flexible
    foreach($this -> cacheinfo["pattern"] as $pat) {
      if (preg_match("/".$pat."/",$this -> path,$matches)) {
        $this -> docache=true;
        return true;
      }
    }
    return false;
    
  }
  
  /** 
  * Reads a specific cache file using a specific User Agent, indexed by Name and Version
  *   
	* @access public
  * @name readCache  
  */
  function readCache() {
  
    if (($_SERVER["REQUEST_METHOD"] == "POST") && (isset($_POST["styroname"])) && ($_POST["styroname"] != "Login")) {
      return;
    }
    
    $this -> getPath();
    
    if (! $this -> isCachable())
      return;
      
    $memcache = new Memcached();
    $memcache->setOption(Memcached::OPT_COMPRESSION, false);
    $memcache->addServer(sfConfig::get("app_mongo_cache"), 11211);
    $res = $memcache->get( $this -> path_md4 );
    
    /*
    $this -> hit = new Mongo(  sfConfig::get("app_mongo_cache")  );
    $grid = $this -> hit -> selectDB( "ttj_cache_".sfConfig::get("sf_environment") ) -> selectCollection( "cache_index" );
    $res = $grid -> findOne( array("path_encoded"=>$this -> file) );
    */
    
    //$grid -> remove( array("path_encoded"=>$this -> file) );
    //die();
    if ($res) {
      
      //Cache Control Headers
      $this -> filecreatetime = (time() - strtotime($res["date"]));
      $this->context->getResponse()->addCacheControlHttpHeader('max_age='.$this -> cachetime);
      echo $res;
      echo ("\n<!-- PATH: (".$this -> file.") NMEMCACHE AGE: ". $this -> filecreatetime . ":SECONDS; EXPIRES: ".$this -> cachetime."; -->" );
      die();
      
      /*Don't need this with memcached
      $this -> filecreatetime = (time() - strtotime($res["date"]));
      sfContext::getInstance()->getLogger()->info("{PageWidgetCache} Output Cache file from \"".$this -> file."\"");
      
      $cachetime=explode(":",$this -> cachetime);
      if ($this -> debug) {
        //dump($cachetime);
      }
      switch ($cachetime[1]) {
        case "WEEK":
          $thecurrenttime=$cachetime[0]*7*86400;
        	break;
        case "DAY":
          $thecurrenttime=$cachetime[0]*86400;
        	break;
        case "HOUR":
          $thecurrenttime=$cachetime[0]*3600;
        	break;
        case "MINUTE":
          $thecurrenttime=$cachetime[0]*60;
        	break;
        default:
          $thecurrenttime=0;
          break;
      }
      
      if ($thecurrenttime==0) {
        return;
      }
      
      if ((! sfConfig::get("app_pagewidget_cache_expires")) || ($this -> filecreatetime < $thecurrenttime)) {
        //Cache Control Headers
        $this->context->getResponse()->addCacheControlHttpHeader('max_age='.$thecurrenttime);
        echo $res["content"];
        echo ("\n<!-- MCACHE AGE: ". $this -> filecreatetime . ":SECONDS; EXPIRES: ".$this -> cachetime."; -->" );
        die();
      } elseif ((sfConfig::get("app_pagewidget_cache_expires")) &&  ($this -> filecreatetime  > $thecurrenttime)){
        try {
          $memcache -> delete( $this -> file );
          //$grid -> remove( array("path_encoded"=>$this -> file) );
        } catch (Exception $e) {
          
        }
      }*/
    }
    
  }
  
  /** 
  * Reads a specific cache file or resource using a specific User Agent, 
  * indexed by Name and Version. Generally cache is handled at the HTTP Server,
  * but in the case that it's application driven, this feature is useful.  
  *   
	* @access public
  * @name checkCache
  * @param string $url  
  */
	function checkCache( $url ) {
    
    $this -> getPath( $url );
    
    if (! $this -> isCachable())
      return false;
      
    $memcache = new Memcached();
    $memcache->setOption(Memcached::OPT_COMPRESSION, false);
    $memcache->addServer(sfConfig::get("app_mongo_cache"), 11211);
    $res = $memcache->get( $this -> path_md4 );
    
    //$grid -> remove( array("path_encoded"=>$this -> file) );
    //die();
    if ($res) {
    
      return true;
      
    }
    
    return false;
    
  }
  
  /** 
  * Clears the entire cache directory, and the cache database table.
  *   
	* @access public
  * @name clearAllCache 
  * @param string $server 
  */
	function clearAllCache( $server ) {
    $this -> flushCache( $server );
  }
    
  
  /** 
  * Clears a specific set of cache files and objects, using a server URI.
  * Traverses the cache directory regardless of User Agent, if User Agent
  * is used in creating cache files.  
  *   
  * @access public
  * @name clearCacheFile
  * @param string $url
  * @param string $server	  
  */
	function clearCacheFile( $url = false, $server=false ) {
    
    if ($url) {
      $this -> setgetVar("cache_url",$url);
    }
    
    if (! $server) {
      $server = sfConfig::get("app_mongo_cache");
    }
    
    if (preg_match("/\*/",$this -> getVar("cache_url"))) {
      die("Not yet implemented");
      die($this -> getVar("cache_url"));
    }
    $this -> getPath( $this -> getVar("cache_url") );
    
    try {
        $memcache = new Memcached;
        $memcache->addServer($server, 11211);
        $memcache -> delete( $this -> file );
        $memcache -> delete( $this -> path_md4 );
        /*
        $this -> hit = new Mongo(  sfConfig::get("app_mongo_cache")  );
        $grid = $this -> hit -> selectDB( "ttj_cache_".sfConfig::get("sf_environment") ) -> selectCollection( "cache_index" );
        $grid -> remove( array("path_encoded"=>$this -> file) );
        */
    } catch (Exception $e) {
        
    }
    
    
  }
  
  /** 
  * Clears a specific set of cache files, using a server URI
  * Traverses the cache directory regardless of User Agent
  *   
	* @access public
  * @name flushCache 
  * @param string $server 
  */
	function flushCache( $server ) {
    $memcache_obj = new Memcached();
    $memcache_obj->connect( $server, 11211 );
    $memcache_obj->flush();
    
  }
  
  /** 
  * Returns Memcache status from a specific server.
  *   
	* @access public
  * @name getCacheStats
  * @param string $server  
  */
	function getCacheStats( $server ) {
      $memcache_obj = new Memcached();
      $memcache_obj->addServer( $server, 11211);
      $status = $memcache_obj->getStats();
      $status = $status[$server.":11211"];
      
      $str =  "<table class='stats_table'>";

      $str .= "<tr><td class='item'>Memcache Server version:</td><td> ".$status ["version"]."</td></tr>";
      $str .= "<tr><td class='item'>Process id of this server process </td><td>".$status ["pid"]."</td></tr>";
      $str .= "<tr><td class='item'>Number of seconds this server has been running </td><td>".$status ["uptime"]."</td></tr>";
      
      $str .= "<tr><td class='item' colspan='2'><br /><br /></td></tr>";
      $str .= "<tr><td class='item'>Accumulated user time for this process </td><td>".$status ["rusage_user"]." seconds</td></tr>";
      $str .= "<tr><td class='item'>Accumulated system time for this process </td><td>".$status ["rusage_system"]." seconds</td></tr>";
      $str .= "<tr><td class='item'>Total number of items stored by this server ever since it started </td><td>".$status ["total_items"]."</td></tr>";
      
      $str .= "<tr><td class='item' colspan='2'><br /><br /></td></tr>";
      $str .= "<tr><td class='item'>Number of open connections </td><td>".$status ["curr_connections"]."</td></tr>";
      $str .= "<tr><td class='item'>Total number of connections opened since the server started running </td><td>".$status ["total_connections"]."</td></tr>";
      $str .= "<tr><td class='item'>Number of connection structures allocated by the server </td><td>".$status ["connection_structures"]."</td></tr>";
      $str .= "<tr><td class='item'>Cumulative number of retrieval requests </td><td>".$status ["cmd_get"]."</td></tr>";
      $str .= "<tr><td class='item'> Cumulative number of storage requests </td><td>".$status ["cmd_set"]."</td></tr>";
      
      $percCacheHit=((real)$status ["get_hits"]/ (real)$status ["cmd_get"] *100);
      $percCacheHit=round($percCacheHit,3);
      $percCacheMiss=100-$percCacheHit;
      $str .= "<tr><td class='item' colspan='2'><br /><br /></td></tr>";

      $str .= "<tr><td class='item'>Number of keys that have been requested and found present </td><td>".$status ["get_hits"]." ($percCacheHit%)</td></tr>";
      $str .= "<tr><td class='item'>Number of items that have been requested and not found </td><td>".$status ["get_misses"]."($percCacheMiss%)</td></tr>";

      $MBRead= (real)$status["bytes_read"]/(1024*1024);
      $str .= "<tr><td class='item' colspan='2'><br /><br /></td></tr>";

      $str .= "<tr><td class='item'>Total number of bytes read by this server from network </td><td>".$MBRead." Mega Bytes</td></tr>";
      $MBWrite=(real) $status["bytes_written"]/(1024*1024) ;
      $str .= "<tr><td class='item'>Total number of bytes sent by this server to network </td><td>".$MBWrite." Mega Bytes</td></tr>";
      $MBSize=(real) $status["limit_maxbytes"]/(1024*1024) ;
      
      $str .= "<tr><td class='item' colspan='2'><br /><br /></td></tr>";
      $str .= "<tr><td class='item'>Number of bytes this server is allowed to use for storage.</td><td>".$MBSize." Mega Bytes</td></tr>";
      $str .= "<tr><td class='item'>Number of valid items removed from cache to free memory for new items.</td><td>".$status ["evictions"]."</td></tr>";

      $str .= "</table>";
      
      return $str;

  }
  
}
