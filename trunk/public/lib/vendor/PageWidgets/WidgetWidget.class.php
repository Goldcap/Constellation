<?php


/**
 * Widget_PageWidget.php, Symfony PageWidgets
 * Symfony Page Widget Parser.
 * @author Andy Madsen <amadsen@operislabs.com>
 * @version 1.0 
 * @package com.Operis.PageWidgets
 */

/**
 * Renders Widget Output using a Symfony View
 */

class Widget_PageWidget extends Utils_PageWidget
{
  /** 
  * Array for variables generated within a specific widget
  *   
  * @property  
  * @access public
  * @var array
  * @name $widget_vars  
  */
	public $widget_vars;
  
	/** 
  * Array for variables generated within a specific page
  * 
  *	@property  
  * @access public
  * @var array
  * @name $page_vars  
  */
	public $page_vars;
  
  /** 
  * The name of a specific widget, parsed from a page YML
  * 
  *	@property  
  * @access public
  * @var string
  * @name $widget_name  
  */
	public $widget_name;
  
  /** 
  * Base location of all widgets
  * 
  *	@property  
  * @access public
  * @var string
  * @name $baseloc  
  */
	public $baseloc;
  
	/** 
  * Location of Pagewiget Template used in rendering
  *  
  * @property  
  * @access public
  * @var boolean
  * @name $templateloc  
  */
	public $templateloc;
  
	/** 
  * Location of Symfony Layout used in rendering
  *   
  * @property  
  * @access public
  * @var boolean
  * @name $layout  
  */
	public $layout;
  
  /** 
  * Is the widget being accessed via the /services URL
  *   
  * @property  
  * @access public
  * @var boolean
  * @name $as_service  
  */
	public $as_service;
  
	/** 
  * Is the widget being accessed via PHP CLI
  *   
  * @property  
  * @access public
  * @var boolean
  * @name $as_cli  
  */
	public $as_cli;
  
	/** 
  * Is the widget being accessed as a Stream Resource
  *   
  * @deprecated 
  *	@property  
  * @access public
  * @var boolean
  * @name $as_file  
  */
	public $as_file;
  
  /** 
  * Class Constructor
  * 
  * @access public
  * @name __construct  
  * @param sfContext $context
  */
	public function __construct( $context ){
    parent::__construct( $context );
    $this -> init ( $this -> widget_name );
    $this -> layout = "";
    //$this -> baseloc = sfConfig::get('sf_lib_dir').'/widgets/'. $this -> widget_name ."/";
    //$this -> templateloc = $this -> baseloc . $this -> widget_name . ".template.php";
  }
  
  /** 
  * Starts the parsing process, and initializes the widget baseloc
  *   
  * @property  
  * @access public
  * @name init  
  * @param string widget_name - Name of Widget being parsed
	*/
	public function init( $widget_name ) {
    $this -> widget_name = $widget_name;
    $this -> baseloc = sfConfig::get('sf_lib_dir').'/widgets/'. $this -> widget_name ."/";
    
    switch (sfConfig::get("app_site_id")) {
    //Some Bad Example
    case 666:
      $this -> templateloc = $this -> baseloc . $this -> widget_name . ".some.template.php";
    	break;
    //Constellation
    default:
    	$this -> templateloc = $this -> baseloc . $this -> widget_name . ".template.php";
    	break;
    }
  }
  
  /** 
  * Passes widget vars to a Symfony PartialView, and renders
  
	* @access public
  * @name render 
  * @param string view - Logging property for this View
  */
  public function render( $view )
  {
    
    // render the widget    
    $view = new sfPartialView($this -> context, 'widgets', $this -> widget_name, $view );
    if (isset($this -> widget_vars['template'])) {
      if (right($this -> widget_vars['template'],3) == "php") {
        $this -> templateloc = $this -> widget_vars['template'];
      } else {
        switch (sfConfig::get("app_site_id")) {
        //Another Site
        case 666:
          $this -> templateloc = $this -> baseloc . $this -> widget_vars['template'] . ".some.template.php";
          break;
        //Constellation
        default:
          $this -> templateloc = $this -> baseloc . $this -> widget_vars['template'] . ".template.php";
        	break;
        }
      }
    }
    
    if (! file_exists($this -> templateloc)) {
      return "";
    }
    
    $view->setTemplate($this -> templateloc);
    
    if ($retval = $view->getCache())
    {
      return $retval;
    }
    
    // add vars set by execute method
    $view->getAttributeHolder()->add($this->widget_vars);
    $view->getAttributeHolder()->add($this->page_vars);
    
    // render
    return $view->render();
    
  }
  
}
