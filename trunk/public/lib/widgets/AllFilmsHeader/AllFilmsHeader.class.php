<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/AllFilmsHeader_crud.php';
  
   class AllFilmsHeader_PageWidget extends Widget_PageWidget {
	
  var $XMLForm;
	var $crud;
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
    parent::__construct( $context );
  }

	function parse() {
	 
    $sql = "select genre_name, genre_id 
						from genre 
						join film_genre on genre_id = fk_genre_id
						group by genre_name";
    $grs = $this -> propelQuery($sql);
    $this -> widget_vars["genres"] = $grs -> fetchAll();
    
	 return $this -> widget_vars;
   
  }

	}

  ?>
