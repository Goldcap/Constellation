<?php

/**
 * PageWidget.class.php, Symfony PageWidgets
 * Symfony Page Widget Parser.
 * @author Andy Madsen <amadsen@operislabs.com>
 * @version 1.0
 * @package com.Operis.PageWidgets
 */

include_once(sfConfig::get("sf_lib_dir")."/helper/BeaconHelper.php");

/**
 * Parses Widgets from YML and outputs HTML to Default Symfony Module
 * Note, this class is embedded into
 * the lib/symfony/filter/sfRenderingFilter.class.php
 * Line 53 - 56 
 */
 
class PageWidget extends Utils_PageWidget
{
  /** 
  * Name of the widget being accessed
  *   
  * @property  
  * @access public
  * @var string
  * @name $module  
  */
	private $module;
  
	/** 
  * Name of the page being accessed 
  *   
  * @property  
  * @access public
  * @var string
  * @name $name  
  */
	private $name;
  
	/** 
  * Page object parsed from Page YML
  * @property  
  * @access public
  * @var string
  * @name $page  
  */
	private $page;
  
	/** 
  * Name of PageWidget Template used to render widgets
  *   
  * @property  
  * @access public
  * @var string
  * @name $template  
  */
	private $template;
  
	/** 
  * sfRequest Object Passed from Symfony
  *  
  * @property  
  * @access public
  * @var sfWebRequest
  * @name $request  
  */
	private $request;
  
	/** 
  * The operation parsed from the url /$action/$op
  * 
  *	@property  
  * @access public
  * @var string
  * @name $op  
  */
	public $op;
  
	/** 
  * Register for storing widget vars passed from each widget
  *   
  * @property  
  * @access public
  * @var array
  * @name $variables  
  */
	public $variables;
  
	/** 
  * Content returned from parsed widgets, returned to symfony as "content"
  *   
  * @property  
  * @access public
  * @var string
  * @name $result  
  */
	public $result;
  
	/** 
  * Registers errors from the widget parsing, and returns to symfony as "error" 
  *  
  * @property  
  * @access public
  * @var array
  * @name $error  
  */
	public $error;
  
	/** 
  * Register to serialize variables coming from widgits with identical names
  *   
  * @property  
  * @access public
  * @var int
  * @name $varcount  
  */
	public $varcount;
  
	/** 
  * Cache length of a particular page
  *   
  * @property  
  * @access public
  * @var string
  * @name $cachetime  
  */
	public $cachetime;
  
  
  /** 
  * Widgets can override any parsing of any other widgets, and "force" a specific 
  * content result to be displayed. Good for errors.
  *   
  * @property  
  * @access public
  * @var string
  * @name $forced_content  
  */
	public $forced_content;
  
	/** 
  * Buffer for storing Forced Content results
  *   
  * @property  
  * @access public
  * @var string
  * @name $forced_output  
  */
	public $forced_output;
  
	/** 
  * Symfony layout to be used in rendering the page
  *   
  * @property  
  * @access public
  * @var string
  * @name $layout  
  */
	public $layout;
  
	/** 
  * Register used in AB Testing
  *   
  * @property  
  * @access public
  * @var string
  * @name $abcommit  
  */
	public $abcommit;
  
  
  /** 
  * Class Constructor
  *    
  * @access public
  * @name __construct
  * @param sfWebRequest request
  * @param boolean init - If true, load Page YML  
  * @param sfContext context
  * @param string name	  
  */
	public function __construct( $request, $init=true, $context=false, $name=null )
  {
    if ($request) {
      $this -> context = (! $context)? sfContext::getInstance() : $context;
      $this->module = $request->getParameter("module");
      $this->name = $request->getParameter("action");
      $this->op = $request->getParameter("op");
      $this -> request = $request;
    }
    
    $this -> error = null;
    $this -> variables = array();
    $this -> layout = "";
    if ($init)
    $this -> init();
    
  }
  
  /** 
  * Loads YML for the page, and assignes page variables 
  *  
  * @access public
  * @name init
  * @param string name - Optional name of the page	  
  */
	public function init( $name=false ) {
    
    if (! $name) {
      $name = $this -> name;
    }
    
    $this->page = $this->loadYml($name);
    
    //If this page isn't GLOBALLY cached, and there are cache values in the yml
    //Use those to determine this page lifecycle
    //::Note, these will never be hit if the GLOBAL cache is longer,
    //::And this isn't in the "Cache Skip List" (i.e. this is five)
    if ((isset($this->page['cache'])) && (sfConfig::get("app_pagewidget_cache"))) {
      $cache = new Cache_PageWidget( $this -> context );
      $cache -> forceCache();
      $cache -> cachetime = $this->page['cache'];
      $cache -> readCache();
    }
    if ($this->page['template'] == '/') {
      return "404";
    }
    if (isset($this->page['ual'])) {
      $got=false;
      foreach($this -> page['ual'] as $ual) {
        if ($this -> context -> getUser() -> hasCredential($ual)) { $got=true; break; }
      }
      if (!$got) { 
        if ($_SERVER["REQUEST_URI"] != "login") {
          if ($this -> page["ual_auth"]) {
            preg_match_all("/:([^\/]+)\//",$this -> page["ual_auth"],$matches);
            if ($matches) {
              foreach($matches[1] as $key => $item) {
                $patterns[] = "/".str_replace("/","\/",$matches[0][$key])."/";
                $replacements[] = $this -> getVar($item)."/";
              }
              $this -> page["ual_auth"] = preg_replace($patterns,$replacements,$this-> page["ual_auth"]);
            }
            $this -> page["ual_auth"] = preg_replace("/\/$/","",$this -> page["ual_auth"]);
            $this -> page["ual_auth"] = preg_replace("/^\//","",$this -> page["ual_auth"]);
            redirect($this -> page["ual_auth"]);
          } else {
            redirect('/?destination='.$_SERVER["REQUEST_URI"]); 
          }
          die(); 
        } else {
          redirect('/'); 
          die(); 
        }
      }
    }
    if (isset($this->page['ip'])) {
      $got=false;
      foreach($this -> page['ip'] as $ip) {
        if (REMOTE_ADDR() == $ip) { $got=true; break; }
      }
      if (!$got) { redirect('error'); die(); }
    }
    $this->template = $this->loadYml("pagetemplates/" . $this->page['template']);
    
    if ((isset($this->page['beacon'])) && (is_array($this->page['beacon']))){
      foreach($this -> page['beacon'] as $beacon){
        beacon( $beacon );
      }
    }
    
    if (! isset($this -> page['title'])) {
      $this -> page['title'] = sfConfig::get('app_default_site_title');
    } elseif (left($this -> page['title'],2) == "::") {
      //Moved this to the widget_vars...
			die("TODO, Add Dynamic Title Feature: PageWidget::init");
    }
    $this->context->getResponse()->setTitle($this->page['title']);
    
    if (isset($this->page['javascripts'])) {
    foreach ($this->page['javascripts'] as $js) {
      $this -> addJs($js);
    }}
    if (isset($this->page['css'])) {
    foreach ($this->page['css'] as $css) {
      $this -> addCss($css);
    }}
    if (isset($this->page['layout'])) {
      $this -> layout = $this->page['layout'];
    }
  }
  
  /** 
  * Parses Widget Classes, Renders Widget Templates, and returns content
  *   
  * @access public
  * @name execute  
  */
	public function execute() {
    
    $this->parseWidgets();
    
    if ($this -> error == '404') {
      return '404';
    }
    
    $this->renderWidgets();
    
    if ($this -> forced_content != "") {
      $this -> result = $this -> forced_output;
    }
    
    return 'execute';
  }
  
  /** 
  * Loops over widgets in page YML, and Parses the Widget Class, returning any 
  * widget vars into the main variables array
  *   
  * @access public
  * @name parseWidgets
  * @param array divs - Container divs for parsed widget content
  * @param boolean stop - Debug Var   
  */
	public function parseWidgets( $divs=null, $stop=false )
  {
    if($divs === null)
    {
      $divs = $this->template['divs'];
    }
    if ($stop) {
      dump($divs);
    }
    
    foreach($divs as $id => $divVars)
    {
      
      // run controller for this widget (defined in page yml)
      if(array_key_exists($id, $this->page['widgets']))
      {
        $widgets = $this->page['widgets'][$id];
        if($widgets)
        {
          foreach($widgets as $type => $widgetVars)
          {
            
            $this -> execWidget( $type, $widgetVars, $id );
            
          }
        }
      }
      
      // now render child divs
      if($divVars && array_key_exists('divs', $divVars))
      {
        $childDivs = $divVars['divs'];
        $this->parseWidgets($childDivs);
      }
      
      // now render second level child divs
      if($divVars && array_key_exists('child_divs', $divVars))
      {
        $cdivs = Array();
        foreach($divVars['child_divs'] as $cd => $cv) {
          $widg = $this->page["widgets"][$id][$cv];
          if (isset($widg)) {
            foreach($widg as $key=>$value) {
              $this -> execWidget( $key, $value, $id );
            }
          }
        }
      }
    }
    
  }
  
  /** 
  * Executes each widget class, determines if AB Testing should occur, and assigns
  * widget vars to the variables array.  
  *   
  * @access public
  * @name execWidget
  * @param string type - AB or not AB
  * @param array widgetVars - From prior widgets, passed in array
  * @param @id - deprecated 			  
  */
	public function execWidget( $type, $widgetVars, $id ) {
  
    $this -> context ->getLogger()->info("{PageWidgetCache} Parsing Widget \"".$type."\"");
    
    if ($type == "AB") {
      
      //If we've set a specific AB before
      if ($this -> request -> getcookie("AB".$this -> name) != "") {
        $classname = $this -> request -> getcookie("AB".$this -> name). "_PageWidget";
      } else {
        $int = round(rand(0,1000)/100);
        if ($int == 3) {
          $classname = $widgetVars[1]. "_PageWidget";
        } else {
          $classname = $widgetVars[0]. "_PageWidget";
        }
      }
      $this -> abcommit = $classname;
    } else {
      $classname = $type . "_PageWidget";
    }
    if ($type == "html") {
      $this -> variables[$type][$id][] = $widgetVars;
      return;
    }
    
    if (! class_exists($classname)) {
      $this -> context ->getLogger()->info("{PageWidgetCache} Skipped Parsing Widget \"".$type."\"");
      return;
    }
    
    if( !$widgetVars)
      $widgetVars = array();
    
    $widget = new $classname($widgetVars,$this -> variables,$this -> context);
    $widget -> as_service = false;
    $widget -> as_cli = false;
    $result = $widget->parse();
    
    if ($widget->layout != "") {
      $this -> layout = $widget -> layout;
    }
    
    if ($result == '404') {
      $this -> error = '404';
    } else {
      if (is_array($result)) {
        $widgetVars = array_merge($widgetVars,$result);
      } else {
        $widgetVars[] = $result;
      }
      //This excludes all other output
      //Last In with it's own name wins
      if (isset($widgetVars["force_content"])) {
      if (preg_match("/".$classname."/",$widgetVars["force_content"]."_PageWidget")) {
        $this -> forced_content = $classname;
      }}
      if (isset($widgetVars["title"])) {
      	$this->context->getResponse()->setTitle($widgetVars["title"]);
			}
      $this -> variables[$type][$id][] = $widgetVars;
    }
    
  }
  
  /** 
  * Passes all parsed variables from Exec Widget to the Display Decorators, and 
  * places parsed content in the appropriate page wrapper div  
  *   
  * @access public
  * @name renderWidgets
  * @param array divs 	  
  */
	public function renderWidgets( $divs=null )
  {
  
    if($divs === null)
    {
      $divs = $this->template['divs'];
    }
    
    $content = array();
    foreach($divs as $id => $divVars)
    {
      if($divVars && array_key_exists('attributes', $divVars))
      {
        $attributes = $divVars['attributes'];
      }
      else
      {
        $attributes = "";
      }
      
      $this -> result .= sprintf('<div id="%s" %s>', $id, $attributes);
      
      // content of this div   
      $divContent = array();
      
      // render widgets in this div (defined in page yml)
      if(array_key_exists($id, $this->page['widgets']))
      {
        $widgets = $this->page['widgets'][$id];
        if($widgets)
        {
          foreach($widgets as $type => $widgetVars)
          {
            $divContent[] = $this -> drawWidget( $type, $widgetVars, $id, $divContent, $this -> varcount);
            
          }
        }
      }
      
      // now render child divs
      if($divVars && array_key_exists('divs', $divVars))
      {
        $childDivs = $divVars['divs'];
        
        $divContent[] = $this->renderWidgets($childDivs)."</div>";
      }
      
      // now render second level child divs
      if($divVars && array_key_exists('child_divs', $divVars))
      {
        $cdivs = Array();
        $childDivContent = "";
        foreach($divVars['child_divs'] as $cd => $cv) {
          $widg = $this->page["widgets"][$id][$cv];
          if (isset($widg)) {
            foreach($widg as $key=>$value) {
              //echo("Adding Content for ".$cv."<br />");
              $divdata = $this -> drawWidget( $key, $value, $id );
              ($divdata) ? $childDivContent = $childDivContent . sprintf('<div id="%s">', $cv) . $divdata . "</div>" : null;
            }
          }
        }
        $divContent[] = $childDivContent;
      }
      
      // finally write out the div opening
      $this -> result .= join(PHP_EOL, $divContent);
      
      if((! $divVars) || ($divVars && !array_key_exists('divs', $divVars))) {
        
        $this -> result .= "</div>";
      }
    }
  }
  
  /** 
  * Creates HTML from passed Widget Variables
  *   
  * @access public
  * @name drawWidget
  * @param string type - AB or not AB
  * @param array widgetVars - From Prior parsed widgets
  * @param string id - The integer occurence of this Widget
  * @param string $divContent - Prior content, new results appended to this 
  */
	public function drawWidget( $type, $widgetVars, $id, $divContent=null ) {
    
    if (($type == "AB") && ($this -> abcommit != "")) {
      $classname = $this -> abcommit;
    } else {
      $classname = $type . "_PageWidget";
    }
    
    if ($type == "html") {
      return file_get_contents(sfConfig::get("sf_app_dir")."/templates/".$widgetVars["html"]);
    }
    
    if (! class_exists($classname)) {
      return;
    }
    
    if (! isset($this -> varcount[$type][$id])){
      $this -> varcount[$type][$id] = 0;
    }
    
    if( !$widgetVars) {
      $widgetVars = null;
    }
    
    if( ! isset($this -> variables[$type][$id][$this -> varcount[$type][$id]]))
      $this -> variables[$type][$id][$this -> varcount[$type][$id]] = $widgetVars;
    
    $widget = new $classname($this -> variables[$type][$id][$this -> varcount[$type][$id]],$this -> variables,$this -> context);
    $divContent = $widget->render( $type.":".$id.":".$this -> varcount[$type][$id] );
    
    if ($classname == $this -> forced_content) {
      $this -> forced_output = $divContent;
    }
    $this -> varcount[$type][$id]++;
    
    return $divContent;
    
  }
  
  /** 
  * Returns a single widget rendered without the page context
  *   
  * @access public
  * @name renderService
  * @return string  
  */
	public function renderService() {
    $classname = $this -> op . "_PageWidget";
    $widget = new $classname(null,$this -> variables,$this -> context);
    $widget -> as_service = true;
    $result = $widget->parse();
    if ($result == '404') {
      $this -> error = '404';
    } else {
      $widget -> widget_vars = $widgetVars[] = $result;
      $this -> variables[$this -> op]["service"][] = $widgetVars;
    }
    
    $this -> result = $widget->render( $this -> op.":service:0" );
    
  }
  
  /** 
  * Returns an Asset pulled from S3
  *   
  *	@access public
  * @name renderS3
  * @return string  
  */
	public function renderS3() {
    $widget = new S3Source_PageWidget(null,$this -> variables,$this -> context);
    $widget -> as_service = true;
    $result = $widget->parse();
    if ($result == '404') {
      $this -> error = '404';
    } else {
      $widget -> widget_vars = $widgetVars[] = $result;
      $this -> variables[$this -> op]["service"][] = $widgetVars;
    }
    
    $this -> result = $widget->render( $this -> op.":service:0" );
    
  }
  
  /** 
  * Returns a single widget rendered from CLI
  *   
  * @access public
  * @name renderCli
  * @return string	  
  */
	public function renderCli() {
    
    $classname = $this -> op . "_PageWidget";
    $widget = new $classname($this -> variables[$this -> op][0],$this -> variables,$this -> context);
    $widget -> as_cli = true;
    $result = $widget->parse();
    if ($result == '404') {
      $this -> error = '404';
    } else {
      $widget -> widget_vars = $widgetVars[] = $result;
      $this -> variables[$this -> op]["service"][] = $widgetVars;
    }
    
    $this -> result = $widget->render( $this -> op.":service:0" );
    
  }
  
  /** 
  * Loads a Page YML File
  *   
  * @access public
  * @name loadYml
  * @param string name	  
  */
	private function loadYml($name)
  {
    $dir = sfConfig::get('sf_app_dir').'/lib/pages/';
    return sfYaml::load($dir.$name.".yml");
  }
  
}
