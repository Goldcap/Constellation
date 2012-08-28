<?php
  
class ForwardTheater_PageWidget extends Widget_PageWidget {

	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
    parent::__construct( $context );
  }

	function parse() {

    $event = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Event/query/Event_datamap.xml");

    if($event["meta"]["totalresults"] > 0){
      $this -> widget_vars["event"] = $event['data'][0];
    } else {
      $this -> redirect("/");
    }
    return $this -> widget_vars;      
  }
}
