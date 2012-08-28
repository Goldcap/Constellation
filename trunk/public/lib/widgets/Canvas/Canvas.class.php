<?php

  class Canvas_PageWidget extends Widget_PageWidget {
	
  var $XMLForm;
  var $crud;
  
  function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
    $this -> XMLForm = new XMLForm($this -> widget_name);
    $this -> XMLForm -> item_forcehidden = true;
    parent::__construct( $context );
  }

	function parse() {

    switch ($this -> getVar("op")) {
      case '21jumpstreet':
        $template = "21JumpStreet";

        $ageCookie = $_COOKIE['ctv_21js_age']!='' ? $_COOKIE['ctv_21js_age'] : 0;
        $this -> widget_vars['cookie'] = $ageCookie;
        $this -> widget_vars['hasTicket'] = $this->userHasTicket('21jumpstlive') > 0;

        break;
      case '21jumpstreetmini':
        $template = "21JumpStreetMini";
        break;
      case '21jumpstreetredirect':
        $template = "21JumpStreetRedirect";
        break;
      case 'films':
        $template = "Embed";
        break;
      default:
        $template = "Canvas";
    }
      $this -> setTemplate($template);

	  return $this -> widget_vars;
  }

  function userHasTicket($screening = null){
    if ($this -> sessionVar("user_id")) {
      $sql = "select audience_id
              from audience
              inner join screening
              on audience.fk_screening_id = screening.screening_id
              where fk_user_id = ".$this -> sessionVar("user_id")."
              and screening.screening_unique_key = '". $screening ."';";
      $res = $this -> propelQuery($sql);
      $screenings = $res -> fetchall();
      $audience = $screenings[0];
    } else {
      $audience = 0;
    }
    return $audience;
  }



}
