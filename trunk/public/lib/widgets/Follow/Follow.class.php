<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/Follow_crud.php';
  
   class Follow_PageWidget extends Widget_PageWidget {
	
  var $XMLForm;
	var $crud;
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
    $this -> XMLForm = new XMLForm($this -> widget_name);
	  $this -> XMLForm -> item_forcehidden = true;
    $this -> crud = new FollowingCrud( $context );
    parent::__construct( $context );
  }

	function parse() {
	 
	  if ($this -> as_service) {
			$vars = array("fk_followed_id"=>$this -> getVar("user_id"),"fk_follower_id"=>$this->sessionVar("user_id"));
			$this -> crud -> checkUnique($vars);
			if ($this -> getVar('type') == "true") {
				 $this -> crud -> setFkFollowedId($this -> getVar("user_id"));
				 $this -> crud -> setFkFollowerId($this->sessionVar("user_id")); 
				 $this -> crud -> setFollowingDateCreated(now());
				 $this -> crud -> save();
				 $res = new stdClass;
		     $res -> followResponse = array("status"=>"success",
																	"result"=>"followed",
																	"userId"=>$this -> getVar("user_id"));
		     print(json_encode($res));
		     die();
			} elseif ($this -> getVar('type') == "false") {
			   if ($this -> crud -> getFollowingId() > 0) { 
          $this -> crud -> remove();
				 }
				 $res = new stdClass;
		     $res -> followResponse = array("status"=>"success",
																	"result"=>"unfollowed",
																	"userId"=>$this -> getVar("user_id"));
		     print(json_encode($res));
		     die();
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