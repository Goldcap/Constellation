<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/ContactGrabber_crud.php';
  
 class ContactGrabber_PageWidget extends Widget_PageWidget {
	
  var $XMLForm;
	var $crud;
	var $inviter;
	var $oi_services;
	var $contacts;
	var $import_ok;
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
    $this -> inviter= new OpenInviter();
    $this -> oi_services = $this -> inviter->getPlugins();
    $this -> plugType='email';
    
    parent::__construct( $context );
  }

	function parse() {
    
    if ($this -> as_service) {
      if($this -> getVar("id") == "config") {
        include_once(sfConfig::get("sf_lib_dir")."/vendor/OpenInviter/postinstall.php");
        die();
      }
      $this -> doPost($this -> getVar('provider'), $this -> getVar("email"),$this -> getVar('password'),$this -> getVar('provider-alt'));
    }
    if ($_SERVER['REQUEST_METHOD']=='POST') {
      $this -> doPost($this -> getVar('provider_box'), $this -> getVar("email"),$this -> getVar('password'));
    }
    $this -> widget_vars["oi_services"] = $this -> oi_services;
    return $this -> widget_vars;
   
  }

  function doPost( $provider, $email, $password, $alternate=null){
    $this ->inviter->getPlugins();
    
    if ($provider == "other") {
      $res = $this -> inviter -> startPlugin($alternate);
    } else {
      $res = $this -> inviter -> startPlugin($provider);
    }
    
    if ($email=='') {
      print json_encode(array("result"=>"failure","message"=>"Please enter your email.","emails"=>""));
      die();
    }
    if ($password=='') {
      print json_encode(array("result"=>"failure","message"=>"Please enter your password.","emails"=>""));
      die();
    }
    //if( ! $this -> inviter -> doesPlugin) {
    //  print json_encode(array("result"=>"failure","message"=>"No Provider Found","emails"=>""));
    //  die();
    //}
    
    $res = $this -> inviter -> login($email,$password);
    
    if ($res) {
      if (false===$this -> contacts = $this -> inviter -> getMyContacts()) {
				$ers['contacts']="Unable to get contacts!";
				//dump($ers);
			} else {
				$this -> import_ok = true;
				$i=1;
				if (count($this -> contacts) == 0) {
          print json_encode(array("result"=>"failure","message"=>"No emails available","emails"=>""));
  				die();
        }
				foreach ($this -> contacts as $key => $value) {
				  if ($provider == "twitter") {
            $results[] = array("id"=>$value,"name"=>$value,"email"=>$value);
            $session = $this->inviter->plugin->getSessionID();
          } elseif ($provider == "facebook") {
            $vals = explode("|",$value);
            $results[] = array("id"=>$key,"name"=>$vals[1],"email"=>$vals[0]);
            $session = $this->inviter->plugin->getSessionID();
          } else {
            $results[] = array("id"=>$i,"name"=>$value,"email"=>$key);
            $session = false;
          }
          $i++;
        }
        print json_encode(array("result"=>"success","emails"=>$results,"session"=>$session));
				die();
			}
    } else {
      print json_encode(array("result"=>"failure","message"=>"Unable to login, ".strtolower($this -> inviter -> internalError).".","emails"=>""));
      die();
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
