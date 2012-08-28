<?php
       
   class MetricCrudBase extends Utils_PageWidget { 
   
    var $Metric;
   
       var $metric_id;
   var $metric_date;
   var $metric_users;
   var $metric_fb_users;
   var $metric_t_users;
   var $metric_o_users;
   var $metric_tickets;
   var $metric_total_tickets;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> get();
    }
    
    if (($id) && ($id != 0)) {
      $this ->Metric = MetricPeer::retrieveByPK( $id );
    } else {
      $this ->Metric = new Metric;
    }
  }
  
  function hydrate( $id ) {
      $this ->Metric = MetricPeer::retrieveByPK( $id );
  }
  
  function getMetricId() {
    if (($this ->postVar("metric_id")) || ($this ->postVar("metric_id") === "")) {
      return $this ->postVar("metric_id");
    } elseif (($this ->getVar("metric_id")) || ($this ->getVar("metric_id") === "")) {
      return $this ->getVar("metric_id");
    } elseif (($this ->Metric) || ($this ->Metric === "")){
      return $this ->Metric -> getMetricId();
    } elseif (($this ->sessionVar("metric_id")) || ($this ->sessionVar("metric_id") == "")) {
      return $this ->sessionVar("metric_id");
    } else {
      return false;
    }
  }
  
  function setMetricId( $str ) {
    $this ->Metric -> setMetricId( $str );
  }
  
  function getMetricDate() {
    if (($this ->postVar("metric_date")) || ($this ->postVar("metric_date") === "")) {
      return $this ->postVar("metric_date");
    } elseif (($this ->getVar("metric_date")) || ($this ->getVar("metric_date") === "")) {
      return $this ->getVar("metric_date");
    } elseif (($this ->Metric) || ($this ->Metric === "")){
      return $this ->Metric -> getMetricDate();
    } elseif (($this ->sessionVar("metric_date")) || ($this ->sessionVar("metric_date") == "")) {
      return $this ->sessionVar("metric_date");
    } else {
      return false;
    }
  }
  
  function setMetricDate( $str ) {
    $this ->Metric -> setMetricDate( $str );
  }
  
  function getMetricUsers() {
    if (($this ->postVar("metric_users")) || ($this ->postVar("metric_users") === "")) {
      return $this ->postVar("metric_users");
    } elseif (($this ->getVar("metric_users")) || ($this ->getVar("metric_users") === "")) {
      return $this ->getVar("metric_users");
    } elseif (($this ->Metric) || ($this ->Metric === "")){
      return $this ->Metric -> getMetricUsers();
    } elseif (($this ->sessionVar("metric_users")) || ($this ->sessionVar("metric_users") == "")) {
      return $this ->sessionVar("metric_users");
    } else {
      return false;
    }
  }
  
  function setMetricUsers( $str ) {
    $this ->Metric -> setMetricUsers( $str );
  }
  
  function getMetricFbUsers() {
    if (($this ->postVar("metric_fb_users")) || ($this ->postVar("metric_fb_users") === "")) {
      return $this ->postVar("metric_fb_users");
    } elseif (($this ->getVar("metric_fb_users")) || ($this ->getVar("metric_fb_users") === "")) {
      return $this ->getVar("metric_fb_users");
    } elseif (($this ->Metric) || ($this ->Metric === "")){
      return $this ->Metric -> getMetricFbUsers();
    } elseif (($this ->sessionVar("metric_fb_users")) || ($this ->sessionVar("metric_fb_users") == "")) {
      return $this ->sessionVar("metric_fb_users");
    } else {
      return false;
    }
  }
  
  function setMetricFbUsers( $str ) {
    $this ->Metric -> setMetricFbUsers( $str );
  }
  
  function getMetricTUsers() {
    if (($this ->postVar("metric_t_users")) || ($this ->postVar("metric_t_users") === "")) {
      return $this ->postVar("metric_t_users");
    } elseif (($this ->getVar("metric_t_users")) || ($this ->getVar("metric_t_users") === "")) {
      return $this ->getVar("metric_t_users");
    } elseif (($this ->Metric) || ($this ->Metric === "")){
      return $this ->Metric -> getMetricTUsers();
    } elseif (($this ->sessionVar("metric_t_users")) || ($this ->sessionVar("metric_t_users") == "")) {
      return $this ->sessionVar("metric_t_users");
    } else {
      return false;
    }
  }
  
  function setMetricTUsers( $str ) {
    $this ->Metric -> setMetricTUsers( $str );
  }
  
  function getMetricOUsers() {
    if (($this ->postVar("metric_o_users")) || ($this ->postVar("metric_o_users") === "")) {
      return $this ->postVar("metric_o_users");
    } elseif (($this ->getVar("metric_o_users")) || ($this ->getVar("metric_o_users") === "")) {
      return $this ->getVar("metric_o_users");
    } elseif (($this ->Metric) || ($this ->Metric === "")){
      return $this ->Metric -> getMetricOUsers();
    } elseif (($this ->sessionVar("metric_o_users")) || ($this ->sessionVar("metric_o_users") == "")) {
      return $this ->sessionVar("metric_o_users");
    } else {
      return false;
    }
  }
  
  function setMetricOUsers( $str ) {
    $this ->Metric -> setMetricOUsers( $str );
  }
  
  function getMetricTickets() {
    if (($this ->postVar("metric_tickets")) || ($this ->postVar("metric_tickets") === "")) {
      return $this ->postVar("metric_tickets");
    } elseif (($this ->getVar("metric_tickets")) || ($this ->getVar("metric_tickets") === "")) {
      return $this ->getVar("metric_tickets");
    } elseif (($this ->Metric) || ($this ->Metric === "")){
      return $this ->Metric -> getMetricTickets();
    } elseif (($this ->sessionVar("metric_tickets")) || ($this ->sessionVar("metric_tickets") == "")) {
      return $this ->sessionVar("metric_tickets");
    } else {
      return false;
    }
  }
  
  function setMetricTickets( $str ) {
    $this ->Metric -> setMetricTickets( $str );
  }
  
  function getMetricTotalTickets() {
    if (($this ->postVar("metric_total_tickets")) || ($this ->postVar("metric_total_tickets") === "")) {
      return $this ->postVar("metric_total_tickets");
    } elseif (($this ->getVar("metric_total_tickets")) || ($this ->getVar("metric_total_tickets") === "")) {
      return $this ->getVar("metric_total_tickets");
    } elseif (($this ->Metric) || ($this ->Metric === "")){
      return $this ->Metric -> getMetricTotalTickets();
    } elseif (($this ->sessionVar("metric_total_tickets")) || ($this ->sessionVar("metric_total_tickets") == "")) {
      return $this ->sessionVar("metric_total_tickets");
    } else {
      return false;
    }
  }
  
  function setMetricTotalTickets( $str ) {
    $this ->Metric -> setMetricTotalTickets( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->Metric = MetricPeer::retrieveByPK( $id );
    }
    
    if ($this ->Metric ) {
       
    	       (is_numeric(WTVRcleanString($this ->Metric->getMetricId()))) ? $itemarray["metric_id"] = WTVRcleanString($this ->Metric->getMetricId()) : null;
          (WTVRcleanString($this ->Metric->getMetricDate())) ? $itemarray["metric_date"] = WTVRcleanString($this ->Metric->getMetricDate()) : null;
          (is_numeric(WTVRcleanString($this ->Metric->getMetricUsers()))) ? $itemarray["metric_users"] = WTVRcleanString($this ->Metric->getMetricUsers()) : null;
          (is_numeric(WTVRcleanString($this ->Metric->getMetricFbUsers()))) ? $itemarray["metric_fb_users"] = WTVRcleanString($this ->Metric->getMetricFbUsers()) : null;
          (is_numeric(WTVRcleanString($this ->Metric->getMetricTUsers()))) ? $itemarray["metric_t_users"] = WTVRcleanString($this ->Metric->getMetricTUsers()) : null;
          (is_numeric(WTVRcleanString($this ->Metric->getMetricOUsers()))) ? $itemarray["metric_o_users"] = WTVRcleanString($this ->Metric->getMetricOUsers()) : null;
          (is_numeric(WTVRcleanString($this ->Metric->getMetricTickets()))) ? $itemarray["metric_tickets"] = WTVRcleanString($this ->Metric->getMetricTickets()) : null;
          (is_numeric(WTVRcleanString($this ->Metric->getMetricTotalTickets()))) ? $itemarray["metric_total_tickets"] = WTVRcleanString($this ->Metric->getMetricTotalTickets()) : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->Metric = MetricPeer::retrieveByPK( $id );
    } elseif (! $this ->Metric) {
      $this ->Metric = new Metric;
    }
        
  	 ($this -> getMetricId())? $this ->Metric->setMetricId( WTVRcleanString( $this -> getMetricId()) ) : null;
    ($this -> getMetricDate())? $this ->Metric->setMetricDate( WTVRcleanString( $this -> getMetricDate()) ) : null;
    ($this -> getMetricUsers())? $this ->Metric->setMetricUsers( WTVRcleanString( $this -> getMetricUsers()) ) : null;
    ($this -> getMetricFbUsers())? $this ->Metric->setMetricFbUsers( WTVRcleanString( $this -> getMetricFbUsers()) ) : null;
    ($this -> getMetricTUsers())? $this ->Metric->setMetricTUsers( WTVRcleanString( $this -> getMetricTUsers()) ) : null;
    ($this -> getMetricOUsers())? $this ->Metric->setMetricOUsers( WTVRcleanString( $this -> getMetricOUsers()) ) : null;
    ($this -> getMetricTickets())? $this ->Metric->setMetricTickets( WTVRcleanString( $this -> getMetricTickets()) ) : null;
    ($this -> getMetricTotalTickets())? $this ->Metric->setMetricTotalTickets( WTVRcleanString( $this -> getMetricTotalTickets()) ) : null;
          
    $this -> save();
     
  }
  
  function save() {
    $this ->Metric ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->Metric = MetricPeer::retrieveByPK($id);
    }
    
    if (! $this ->Metric ) {
      return;
    }
    
    $this ->Metric -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('Metric_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "MetricPeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $Metric = MetricPeer::doSelect($c);
    
    if (count($Metric) >= 1) {
      $this ->Metric = $Metric[0];
      return true;
    } else {
      $this ->Metric = new Metric();
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
      $name = "MetricPeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $Metric = MetricPeer::doSelect($c);
    
    if (count($Metric) >= 1) {
      $this ->Metric = $Metric[0];
      return true;
    } else {
      $this ->Metric = new Metric();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>