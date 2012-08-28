<?php

/**
 * default actions.
 *
 * @package    constellation.tv
 * @subpackage default
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class defaultActions extends sfActions
{
  var $page_widget;
  var $action;
  
  public function preExecute()
  {
    $this -> action = strtolower($this -> getRequestParameter("action"));
   
    //$this -> getResponse()->setHttpHeader('P3P', "policyref=\"/w3c/p3p.xml\", CP=\"IDC DSP COR CURa ADMa CONa OUR STP\"" );
    
    //This anticipates the SSL REMOTE_ADDR issue
    //Wherein the Load Balancer is returned as the REMOTE ADDR
    //All uses of $_SERVER["REMOTEADDR"] run through the function REMOTE_ADDR()
    //Which refers to this variable
    if (! isset($_SESSION["REMOTE_ADDR"])) {
      $_SESSION["REMOTE_ADDR"] = SET_REMOTE_ADDR();
    }
  }
  
  /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex($request) {
    
    //In case we did an indirect (non-styroform) login post
    if ((($_SERVER["REQUEST_METHOD"] == "POST") && (isset($_POST["styroname"])) && ($_POST["styroname"] == "Login")) ||
      (($_SERVER["REQUEST_METHOD"] == "POST") && isset($_POST["type"]) && ($_POST["type"] == 'login'))) {
        if ($this -> context -> getRequest() -> getAttribute("dest")) {
          $this->redirect($this -> context -> getRequest() -> getAttribute("dest"));
        } else {
          $this->redirect ('/');
        }
    } elseif (strtolower($this -> action) == "logout") {
       if ($this -> context -> getRequest() -> getAttribute("dest")) {
          $this->redirect($this -> context -> getRequest() -> getAttribute("dest"));
        } else {
          $this->redirect ('/');
        }
    } else {
      $this->getContext()->getLogger()->info("{Index Authentication} Authenticated:: ".$this -> getUser() -> isAuthenticated()."\"");
    	 
			switch ($this -> action) {
        case "s3":
          $this->setLayout('service');
          $this -> executeS3($request);
        	break;
        case "service":
        case "services":
          sfConfig::set('sf_web_debug', false);  
          $this->setLayout('service');
          $this -> executeService($request);
        	break;
        case "login":
          if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $this->setLayout('login');
          } else {
            $this -> executePageView($request);
        	}
          break;
        case "logout":
          $this->setLayout('logout');
        	break;
        case "21jumpstreet":
        case "about":
        case "account":
        case "browser":
        case "boxoffice": 
        case "canvas":
        case "contact":
        case "event":
        case "facebook":
        case "faq":
        case "forward":
        case "films":
        case "film-makers":
        case "host":
        case "hosting":
        case "help": 
        case "how":
        case "index":
        case "join":
        case "lobby":
        case "logout": 
        case "moderator":
        case "preuser":
        case "privacy":
        case "profile":
        case "purchase":
        case "screening":
        case "reset-password":
        case "signup":
        case "start":
        case "subscription":
        case "terms":
        case "theater":
        case "thevow":
        case "timezoneremote":
        case "upload":
          $this -> executePageView($request);
          break;
        case "film":
          $redirect = new TitleLookup_PageWidget( $this->getContext(), null, null );
          $this -> executePageView($request,$redirect -> findAlias());
          break;
        default:
          $redirect = new TitleLookup_PageWidget( $this->getContext(), null, null );
          $redirect -> parse();
          $this->redirect ('/');
          break;
      }
    }
  }
  
  public function executePageView($request,$name=null) {
  	
    $this->page_widget = new PageWidget($request,false,$this->getContext(),$name);
    if (is_null($name)) {
      $name = $this -> action;
    }
    $return = $this->page_widget->init($name);
    if ($return == '404') {
      $this->page_widget->init('404');
    }
    $return = $this -> page_widget -> execute();
     
    if ($return == '404') {
      $this->page_widget->init('404');
      $this -> page_widget -> execute();
    }
    
    $this->content = $this->page_widget->result;
    
    if ($this -> page_widget->layout != "")
      $this->setLayout($this -> page_widget->layout);
    
  }
  
  public function executeService($request) {
    $this->getContext()->getLogger()->info("{Service Authentication} Authenticated:: ".$this -> getUser() -> isAuthenticated()."\"");
    $this->page_widget = new PageWidget($request,false,$this->getContext());
    $this->page_widget->renderService();
    $this->content = $this->page_widget->result;
    if ($this -> page_widget->layout != "")
      $this->setLayout($this -> page_widget->layout);
  }
  
  public function executeS3($request) {
    $this->getContext()->getLogger()->info("{Service Authentication} Authenticated:: ".$this -> getUser() -> isAuthenticated()."\"");
    $this->page_widget = new PageWidget($request,false,$this->getContext());
    $this->page_widget->renderS3();
    $this->content = $this->page_widget->result;
    if ($this -> page_widget->layout != "")
      $this->setLayout($this -> page_widget->layout);
  }
  
}
