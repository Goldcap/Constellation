<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/QA_crud.php';
  
   class QA_PageWidget extends Widget_PageWidget {
	
  var $XMLForm;
	var $crud;
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
    $this -> crud = new QandaCrud( $context );
    parent::__construct( $context );
  }

	function parse() {
	   
	   //This is moved to Python, so not used anymore...
	   if ($this -> as_service) {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
          $this -> crud -> setFkScreeningId( $this -> postVar("screening_id") );
          $this -> crud -> setFkUserId( $this -> postVar("user_id") );
          $this -> crud -> setQandaBody( WTVRFlatString(WTVRCleanString($this -> postVar("body"))) );
          $this -> crud -> setQandaAnswered( 0 );
          $this -> crud -> setQandaCurrent( null );
          $this -> crud -> setQandaCreatedAt( now() );
          $this -> crud -> setQandaUpdatedAt( null );
          $this -> crud -> save();
          die('{"questions":[{"success": "true","id": "'.$this -> crud -> Qanda -> getQandaId().'","html": "<div class=\"question\" id=\"q'.$this -> crud -> Qanda -> getQandaId().'\">'.WTVRTags(WTVRFlatString($this -> crud -> Qanda -> getQandaBody())).'</div>"}]}');
        }
     }
     
     if ($this -> getVar("id") == 'remove') {
	      $thid = str_replace("q","",$this -> getVar("qid"));
        $sql = "update qanda set qanda_answered = 0, qanda_sequence = null where qanda_id = ".$thid;
        $this -> propelQuery($sql);
        die("");
     }
	   
	   if ($this -> getVar("id") == 'add') {
	      $thid = str_replace("q","",$this -> getVar("qid"));
        $sql = "update qanda set qanda_answered = 1, qanda_sequence = 1 where qanda_id = ".$thid;
        $this -> propelQuery($sql);
        die("");
     }
     
     if ($this -> getVar("id") == 'order') {
      if ((is_array($this -> getVar("order"))) && (count($this ->getVar("order")) > 0)){
        foreach ($this -> getVar("order") as $key=>$value) {
          $sql = "update qanda set qanda_sequence = ".($key + 1)." where qanda_id = ".$value;
          $this -> propelQuery($sql);
        }
        die("");
      }
     }
     
    return $this -> widget_vars;
   
	  if ($this -> XMLForm -> isPosted()) {  
      $this -> doPost();
    }
    $this -> doGet();
    
    return $this -> drawPage();
    
  }

  function doPost(){
     
     if ($this -> XMLForm -> validateForm()) {
        switch ($this -> getFormMethod()) {
          case "submit":
          $this -> crud -> write();
          break;
          case "delete":
          $this -> crud -> remove();
          break;
        }
      }
    
  }

  function doGet(){
    
    if (($this ->getOp() == "detail") && ($this -> getId()>=0) && ($this -> crud)) {
      $this -> pushItem();
    }
    
  }

  function drawPage(){
    
    if (($this ->getOp() == "detail") && ($this -> getId()>=0) && ($this -> crud)) {
      return $this -> returnForm();
    } elseif ($this ->getOp() == "list" ) {
      return $this -> returnList();
    }
    
  }

	}

  ?>
