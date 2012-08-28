<?php


/**
 * OutputWidget.class.php, Symfony PageWidgets
 * Symfony Page Widget Parser.
 * @author Andy Madsen <amadsen@operislabs.com>
 * @version 1.0 
 * @package com.Operis.PageWidgets
 */

/**
 * Modifies output to enable auto-generated CDN Paths for assets
 * <code> 
 * Note, this class is embedded into
 * the lib/symfony/filter/sfRenderingFilter.class.php
 * Line 53 - 56
 * AND
 * the plugins/sfErrorHandlerPlugin/lib/sfHardenedRenderingFilter.class.php
 * </code>   
 */
 

class Output_PageWidget extends Utils_PageWidget
{
  
  /** 
  * Inbound Symfony Context Object
  * 
  *	@property  
  * @access public
  * @var	sfContext
  * @name $context  
  */
	var $context;
  
	/** 
  * Inbound HTML Content
  *   
  * @property  
  * @access public
  * @var string
  * @name $content  
  */
	var $content;
  
  /** 
  * Class Constructor
  * 
  *	@access public
  * @name __construct
  * @param sfContext context	  
  */
	function __construct( $context ) {
    $this -> context = $context;
  }
  
  /** 
  * Function that establishes internal content param, and passes through to the
  * override function cleanImages  
  *   
  * @access public
  * @name cleanImages
  * @param sfResponse response  
  * return sfResponse	  
  */
	function cleanResponse( $response ) {
    $this -> content = $response -> getContent();
    $this -> cleanImages();
    $response -> setContent( $this -> content );
    return $response;
  }
  
  /** 
  * Regex match inbound content, and redirect to an image server, or CDN. Reads
  * port assignment to redirect to SSL if required.  
  *   
  * @access public
  * @name cleanImages  
  */
	function cleanImages() {
    if (sfConfig::get("app_image_server") != "http://www.constellation.tv" ) {
      $pt = "http://";
      if (($_SERVER["SERVER_PORT"] == "443") || ($_SERVER["SERVER_PORT"] == "8443")) {
        $pt = "https://";
      }
      $this -> content = str_replace("http://www.constellation.tv/images",$pt.sfConfig::get("app_image_server")."/images",$this -> content);
      $this -> content = str_replace("'/images","'".$pt.sfConfig::get("app_image_server")."/images",$this -> content);
      $this -> content = str_replace("\"/images","\"".$pt.sfConfig::get("app_image_server")."/images",$this -> content);
      // $this -> content = str_replace("http://www.constellation.tv/uploads",$pt.sfConfig::get("app_image_server")."/uploads",$this -> content);
      // $this -> content = str_replace("http://constellation.tv/uploads",$pt.sfConfig::get("app_image_server")."/uploads",$this -> content);
      // $this -> content = str_replace("//constellation.tv/uploads",$pt.sfConfig::get("app_image_server")."/uploads",$this -> content);
      // $this -> content = str_replace("'/uploads","'".$pt.sfConfig::get("app_image_server")."/uploads",$this -> content);
      // $this -> content = str_replace("\"/uploads","\"".$pt.sfConfig::get("app_image_server")."/uploads",$this -> content);
      $this -> content = str_replace("\"/css","\"".$pt.sfConfig::get("app_image_server")."/css",$this -> content);
      $this -> content = str_replace("\"/js","\"".$pt.sfConfig::get("app_image_server")."/js",$this -> content);
    }
  }
  
  
}
?>
