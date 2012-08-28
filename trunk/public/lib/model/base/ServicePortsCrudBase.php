<?php
       
   class ServicePortsCrudBase extends Utils_PageWidget { 
   
    var $ServicePorts;
   
       var $service_ports_id;
   var $service_ports_service_host;
   var $service_ports_service_port_base;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> getServicePortsId();
    }
    
    if (($id) && ($id != 0)) {
      $this ->ServicePorts = ServicePortsPeer::retrieveByPK( $id );
    } else {
      $this ->ServicePorts = new ServicePorts;
    }
  }
  
  function hydrate( $id ) {
      $this ->ServicePorts = ServicePortsPeer::retrieveByPK( $id );
  }
  
  function getServicePortsId() {
    if (($this ->postVar("service_ports_id")) || ($this ->postVar("service_ports_id") === "")) {
      return $this ->postVar("service_ports_id");
    } elseif (($this ->getVar("service_ports_id")) || ($this ->getVar("service_ports_id") === "")) {
      return $this ->getVar("service_ports_id");
    } elseif (($this ->ServicePorts) || ($this ->ServicePorts === "")){
      return $this ->ServicePorts -> getServicePortsId();
    } elseif (($this ->sessionVar("service_ports_id")) || ($this ->sessionVar("service_ports_id") == "")) {
      return $this ->sessionVar("service_ports_id");
    } else {
      return false;
    }
  }
  
  function setServicePortsId( $str ) {
    $this ->ServicePorts -> setServicePortsId( $str );
  }
  
  function getServicePortsServiceHost() {
    if (($this ->postVar("service_ports_service_host")) || ($this ->postVar("service_ports_service_host") === "")) {
      return $this ->postVar("service_ports_service_host");
    } elseif (($this ->getVar("service_ports_service_host")) || ($this ->getVar("service_ports_service_host") === "")) {
      return $this ->getVar("service_ports_service_host");
    } elseif (($this ->ServicePorts) || ($this ->ServicePorts === "")){
      return $this ->ServicePorts -> getServicePortsServiceHost();
    } elseif (($this ->sessionVar("service_ports_service_host")) || ($this ->sessionVar("service_ports_service_host") == "")) {
      return $this ->sessionVar("service_ports_service_host");
    } else {
      return false;
    }
  }
  
  function setServicePortsServiceHost( $str ) {
    $this ->ServicePorts -> setServicePortsServiceHost( $str );
  }
  
  function getServicePortsServicePortBase() {
    if (($this ->postVar("service_ports_service_port_base")) || ($this ->postVar("service_ports_service_port_base") === "")) {
      return $this ->postVar("service_ports_service_port_base");
    } elseif (($this ->getVar("service_ports_service_port_base")) || ($this ->getVar("service_ports_service_port_base") === "")) {
      return $this ->getVar("service_ports_service_port_base");
    } elseif (($this ->ServicePorts) || ($this ->ServicePorts === "")){
      return $this ->ServicePorts -> getServicePortsServicePortBase();
    } elseif (($this ->sessionVar("service_ports_service_port_base")) || ($this ->sessionVar("service_ports_service_port_base") == "")) {
      return $this ->sessionVar("service_ports_service_port_base");
    } else {
      return false;
    }
  }
  
  function setServicePortsServicePortBase( $str ) {
    $this ->ServicePorts -> setServicePortsServicePortBase( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->ServicePorts = ServicePortsPeer::retrieveByPK( $id );
    }
    
    if ($this ->ServicePorts ) {
       
    	       (is_numeric(WTVRcleanString($this ->ServicePorts->getServicePortsId()))) ? $itemarray["service_ports_id"] = WTVRcleanString($this ->ServicePorts->getServicePortsId()) : null;
          (WTVRcleanString($this ->ServicePorts->getServicePortsServiceHost())) ? $itemarray["service_ports_service_host"] = WTVRcleanString($this ->ServicePorts->getServicePortsServiceHost()) : null;
          (is_numeric(WTVRcleanString($this ->ServicePorts->getServicePortsServicePortBase()))) ? $itemarray["service_ports_service_port_base"] = WTVRcleanString($this ->ServicePorts->getServicePortsServicePortBase()) : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->ServicePorts = ServicePortsPeer::retrieveByPK( $id );
    } elseif (! $this ->ServicePorts) {
      $this ->ServicePorts = new ServicePorts;
    }
        
  	 ($this -> getServicePortsId())? $this ->ServicePorts->setServicePortsId( WTVRcleanString( $this -> getServicePortsId()) ) : null;
    ($this -> getServicePortsServiceHost())? $this ->ServicePorts->setServicePortsServiceHost( WTVRcleanString( $this -> getServicePortsServiceHost()) ) : null;
    ($this -> getServicePortsServicePortBase())? $this ->ServicePorts->setServicePortsServicePortBase( WTVRcleanString( $this -> getServicePortsServicePortBase()) ) : null;
          
    $this -> save();
     
  }
  
  function save() {
    $this ->ServicePorts ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->ServicePorts = ServicePortsPeer::retrieveByPK($id);
    }
    
    if (! $this ->ServicePorts ) {
      return;
    }
    
    $this ->ServicePorts -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('ServicePorts_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "ServicePortsPeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $ServicePorts = ServicePortsPeer::doSelect($c);
    
    if (count($ServicePorts) >= 1) {
      $this ->ServicePorts = $ServicePorts[0];
      return true;
    } else {
      $this ->ServicePorts = new ServicePorts();
      return false;
    }
  }
  
    //Pass an array of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function checkUnique( $vals ) {
    $c = new Criteria();
    
    foreach ($vals as $key =>$value) {
      $name = "ServicePortsPeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $ServicePorts = ServicePortsPeer::doSelect($c);
    
    if (count($ServicePorts) >= 1) {
      $this ->ServicePorts = $ServicePorts[0];
      return true;
    } else {
      $this ->ServicePorts = new ServicePorts();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>