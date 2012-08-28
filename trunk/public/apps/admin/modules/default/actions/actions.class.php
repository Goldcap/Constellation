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
    $this -> action = $this -> getRequestParameter("action");
    
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
    if ((($_SERVER["REQUEST_METHOD"] == "POST") && (isset($_POST["login"]))) && ($this -> getContext() -> getUser() -> isAuthenticated())) {
        $this->redirect ('/');
    } elseif (strtolower($this -> action) == "logout") {
        $this->redirect ('/');
    } else {
    
      switch ($this -> action) {
        case "service":
        case "services":
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
        default:
          $this -> executePageView($request);
          break;
      }
    }
  }
  
  public function executePageView($request) {
    $this->page_widget = new PageWidget($request,false,$this->getContext());
    $return = $this->page_widget->init($this -> action);
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
    $this->page_widget = new PageWidget($request,false,$this->getContext());
    $this->page_widget->renderService();
    $this->content = $this->page_widget->result;
    if ($this -> page_widget->layout != "")
      $this->setLayout($this -> page_widget->layout);
  }
  
}
