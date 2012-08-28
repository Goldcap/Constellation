<?php
 
  class TwentyOneJumpStreet_PageWidget extends Widget_PageWidget {
	
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
	  if($this -> getVar("op") == 'unlocked' || $this -> getVar("op") == '119' ){
        $this -> redirect('/21jumpstreet');
    }
    $this -> setTemplate('TwentyOneJumpStreetPost');

/*
    $this -> setTitle('Constellation | The Social Movie Theater');

		$this -> setMeta( "og:title", "Constellation | The Social Movie Theater" );
		$this -> setMeta( "og:type", "Movie" );
		$this -> setMeta( "og:url", "http://www.constellation.tv/21jumpstreet");
		$this -> setMeta( "og:image", "http://www.constellation.tv/images/pages/21jumpstreet/poster.png" );
		$this -> setMeta( "og:site_name", "Constellation.tv" );
		$this -> setMeta( "og:description", 'Join Jonah Hill and Channing Tatum LIVE in a special online event. Chat with the stars of 21 Jump Street and see them present YOUR craziest high school stories that you never told your parents.' );
 */
	  return $this -> widget_vars;    
  }

}
?>