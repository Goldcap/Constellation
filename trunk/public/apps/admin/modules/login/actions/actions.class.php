<?php

/**
 * login actions.
 *
 * @package    sf_sandbox
 * @subpackage login
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 9301 2008-05-27 01:08:46Z dwhittle $
 */
class loginActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex($request)
  {
   
    //In case we did an indirect (non-styroform) login post
    if (($_SERVER["REQUEST_METHOD"] == "POST") && (isset($_POST["styroname"])) && ($_POST["styroname"] == "Login") && ($this -> getContext() -> getUser() -> isAuthenticated())) {
        $this->redirect ('/');
    } elseif (strtolower($this -> action) == "logout") {
        $this->redirect ('/');
    } else {
      if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $this->setLayout('login');
      } else {
        $this->page_widget = new PageWidget($request, false);
        $this->page_widget->init("login");
        if ($this -> page_widget->layout != "")
          $this->setLayout($this -> page_widget->layout);
    
        $this->page_widget -> execute();
    	  $this->content = $this->page_widget->result;
      }
    }
  }
  
}
