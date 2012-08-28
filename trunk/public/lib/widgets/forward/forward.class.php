<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/forward_crud.php';
  
   class forward_PageWidget extends Widget_PageWidget {
	
  var $XMLForm;
	var $crud;
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    parent::__construct( $context );
  }

	function parse() {
	 $vars = explode("|",$this -> getVar("dest"));
	 if ((! $vars[0]) || ($vars[0] == '')) {
    $this -> redirect("/");
   }
   $sql = "select audience_short_url from audience where fk_user_id = ".$this -> sessionVar("user_id")." and audience_invite_code = '".$vars[0]."' and audience_paid_status = 2 limit 1";
   $res = $this -> propelQuery($sql);
   if ($res -> RowCount() > 0) {
    while ($row = $res -> fetch()) {
      $this -> context -> getRequest() -> setAttribute("dest",$row[0]);
    }
   } else {
   	if ($vars[1] == '') {
		 	$this -> context -> getRequest() -> setAttribute("dest","/theater/".$vars[0]);
		} else {
			$this -> context -> getRequest() -> setAttribute("dest","/film/".$vars[1]);
   	}
	 } 
   
   return $this -> widget_vars;
   
    
  }
	}

  ?>
