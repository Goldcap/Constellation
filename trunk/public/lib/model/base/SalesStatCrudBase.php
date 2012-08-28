<?php
       
   class SalesStatCrudBase extends Utils_PageWidget { 
   
    var $SalesStat;
   
       var $sales_stat_id;
   var $sales_stat_date;
   var $sales_stat_sales_paypal;
   var $sales_stat_sales_paypal_wpp;
   var $sales_stat_visitors;
   var $sales_stat_pageviews;
   var $sales_stat_type;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> getSalesStatId();
    }
    
    if (($id) && ($id != 0)) {
      $this ->SalesStat = SalesStatPeer::retrieveByPK( $id );
    } else {
      $this ->SalesStat = new SalesStat;
    }
  }
  
  function hydrate( $id ) {
      $this ->SalesStat = SalesStatPeer::retrieveByPK( $id );
  }
  
  function getSalesStatId() {
    if (($this ->postVar("sales_stat_id")) || ($this ->postVar("sales_stat_id") === "")) {
      return $this ->postVar("sales_stat_id");
    } elseif (($this ->getVar("sales_stat_id")) || ($this ->getVar("sales_stat_id") === "")) {
      return $this ->getVar("sales_stat_id");
    } elseif (($this ->SalesStat) || ($this ->SalesStat === "")){
      return $this ->SalesStat -> getSalesStatId();
    } elseif (($this ->sessionVar("sales_stat_id")) || ($this ->sessionVar("sales_stat_id") == "")) {
      return $this ->sessionVar("sales_stat_id");
    } else {
      return false;
    }
  }
  
  function setSalesStatId( $str ) {
    $this ->SalesStat -> setSalesStatId( $str );
  }
  
  function getSalesStatDate() {
    if (($this ->postVar("sales_stat_date")) || ($this ->postVar("sales_stat_date") === "")) {
      return $this ->postVar("sales_stat_date");
    } elseif (($this ->getVar("sales_stat_date")) || ($this ->getVar("sales_stat_date") === "")) {
      return $this ->getVar("sales_stat_date");
    } elseif (($this ->SalesStat) || ($this ->SalesStat === "")){
      return $this ->SalesStat -> getSalesStatDate();
    } elseif (($this ->sessionVar("sales_stat_date")) || ($this ->sessionVar("sales_stat_date") == "")) {
      return $this ->sessionVar("sales_stat_date");
    } else {
      return false;
    }
  }
  
  function setSalesStatDate( $str ) {
    $this ->SalesStat -> setSalesStatDate( $str );
  }
  
  function getSalesStatSalesPaypal() {
    if (($this ->postVar("sales_stat_sales_paypal")) || ($this ->postVar("sales_stat_sales_paypal") === "")) {
      return $this ->postVar("sales_stat_sales_paypal");
    } elseif (($this ->getVar("sales_stat_sales_paypal")) || ($this ->getVar("sales_stat_sales_paypal") === "")) {
      return $this ->getVar("sales_stat_sales_paypal");
    } elseif (($this ->SalesStat) || ($this ->SalesStat === "")){
      return $this ->SalesStat -> getSalesStatSalesPaypal();
    } elseif (($this ->sessionVar("sales_stat_sales_paypal")) || ($this ->sessionVar("sales_stat_sales_paypal") == "")) {
      return $this ->sessionVar("sales_stat_sales_paypal");
    } else {
      return false;
    }
  }
  
  function setSalesStatSalesPaypal( $str ) {
    $this ->SalesStat -> setSalesStatSalesPaypal( $str );
  }
  
  function getSalesStatSalesPaypalWpp() {
    if (($this ->postVar("sales_stat_sales_paypal_wpp")) || ($this ->postVar("sales_stat_sales_paypal_wpp") === "")) {
      return $this ->postVar("sales_stat_sales_paypal_wpp");
    } elseif (($this ->getVar("sales_stat_sales_paypal_wpp")) || ($this ->getVar("sales_stat_sales_paypal_wpp") === "")) {
      return $this ->getVar("sales_stat_sales_paypal_wpp");
    } elseif (($this ->SalesStat) || ($this ->SalesStat === "")){
      return $this ->SalesStat -> getSalesStatSalesPaypalWpp();
    } elseif (($this ->sessionVar("sales_stat_sales_paypal_wpp")) || ($this ->sessionVar("sales_stat_sales_paypal_wpp") == "")) {
      return $this ->sessionVar("sales_stat_sales_paypal_wpp");
    } else {
      return false;
    }
  }
  
  function setSalesStatSalesPaypalWpp( $str ) {
    $this ->SalesStat -> setSalesStatSalesPaypalWpp( $str );
  }
  
  function getSalesStatVisitors() {
    if (($this ->postVar("sales_stat_visitors")) || ($this ->postVar("sales_stat_visitors") === "")) {
      return $this ->postVar("sales_stat_visitors");
    } elseif (($this ->getVar("sales_stat_visitors")) || ($this ->getVar("sales_stat_visitors") === "")) {
      return $this ->getVar("sales_stat_visitors");
    } elseif (($this ->SalesStat) || ($this ->SalesStat === "")){
      return $this ->SalesStat -> getSalesStatVisitors();
    } elseif (($this ->sessionVar("sales_stat_visitors")) || ($this ->sessionVar("sales_stat_visitors") == "")) {
      return $this ->sessionVar("sales_stat_visitors");
    } else {
      return false;
    }
  }
  
  function setSalesStatVisitors( $str ) {
    $this ->SalesStat -> setSalesStatVisitors( $str );
  }
  
  function getSalesStatPageviews() {
    if (($this ->postVar("sales_stat_pageviews")) || ($this ->postVar("sales_stat_pageviews") === "")) {
      return $this ->postVar("sales_stat_pageviews");
    } elseif (($this ->getVar("sales_stat_pageviews")) || ($this ->getVar("sales_stat_pageviews") === "")) {
      return $this ->getVar("sales_stat_pageviews");
    } elseif (($this ->SalesStat) || ($this ->SalesStat === "")){
      return $this ->SalesStat -> getSalesStatPageviews();
    } elseif (($this ->sessionVar("sales_stat_pageviews")) || ($this ->sessionVar("sales_stat_pageviews") == "")) {
      return $this ->sessionVar("sales_stat_pageviews");
    } else {
      return false;
    }
  }
  
  function setSalesStatPageviews( $str ) {
    $this ->SalesStat -> setSalesStatPageviews( $str );
  }
  
  function getSalesStatType() {
    if (($this ->postVar("sales_stat_type")) || ($this ->postVar("sales_stat_type") === "")) {
      return $this ->postVar("sales_stat_type");
    } elseif (($this ->getVar("sales_stat_type")) || ($this ->getVar("sales_stat_type") === "")) {
      return $this ->getVar("sales_stat_type");
    } elseif (($this ->SalesStat) || ($this ->SalesStat === "")){
      return $this ->SalesStat -> getSalesStatType();
    } elseif (($this ->sessionVar("sales_stat_type")) || ($this ->sessionVar("sales_stat_type") == "")) {
      return $this ->sessionVar("sales_stat_type");
    } else {
      return false;
    }
  }
  
  function setSalesStatType( $str ) {
    $this ->SalesStat -> setSalesStatType( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->SalesStat = SalesStatPeer::retrieveByPK( $id );
    }
    
    if ($this ->SalesStat ) {
       
    	       (is_numeric(WTVRcleanString($this ->SalesStat->getSalesStatId()))) ? $itemarray["sales_stat_id"] = WTVRcleanString($this ->SalesStat->getSalesStatId()) : null;
          //Be sure to format this date for the output type in Styroform, defaults to 'date'
      (WTVRcleanString($this ->SalesStat->getSalesStatDate())) ? $itemarray["sales_stat_date"] = formatDate($this ->SalesStat->getSalesStatDate('%Y-%m-%d %T'),"TS") : null;
          (WTVRcleanString($this ->SalesStat->getSalesStatSalesPaypal())) ? $itemarray["sales_stat_sales_paypal"] = WTVRcleanString($this ->SalesStat->getSalesStatSalesPaypal()) : null;
          (WTVRcleanString($this ->SalesStat->getSalesStatSalesPaypalWpp())) ? $itemarray["sales_stat_sales_paypal_wpp"] = WTVRcleanString($this ->SalesStat->getSalesStatSalesPaypalWpp()) : null;
          (is_numeric(WTVRcleanString($this ->SalesStat->getSalesStatVisitors()))) ? $itemarray["sales_stat_visitors"] = WTVRcleanString($this ->SalesStat->getSalesStatVisitors()) : null;
          (is_numeric(WTVRcleanString($this ->SalesStat->getSalesStatPageviews()))) ? $itemarray["sales_stat_pageviews"] = WTVRcleanString($this ->SalesStat->getSalesStatPageviews()) : null;
          (WTVRcleanString($this ->SalesStat->getSalesStatType())) ? $itemarray["sales_stat_type"] = WTVRcleanString($this ->SalesStat->getSalesStatType()) : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->SalesStat = SalesStatPeer::retrieveByPK( $id );
    } elseif (! $this ->SalesStat) {
      $this ->SalesStat = new SalesStat;
    }
        
  	 ($this -> getSalesStatId())? $this ->SalesStat->setSalesStatId( WTVRcleanString( $this -> getSalesStatId()) ) : null;
          if (is_valid_date( $this ->SalesStat->getSalesStatDate())) {
        $this ->SalesStat->setSalesStatDate( formatDate($this -> getSalesStatDate(), "TS" ));
      } else {
      $SalesStatsales_stat_date = $this -> sfDateTime( "sales_stat_date" );
      ( $SalesStatsales_stat_date != "01/01/1900 00:00:00" )? $this ->SalesStat->setSalesStatDate( formatDate($SalesStatsales_stat_date, "TS" )) : $this ->SalesStat->setSalesStatDate( null );
      }
    ($this -> getSalesStatSalesPaypal())? $this ->SalesStat->setSalesStatSalesPaypal( WTVRcleanString( $this -> getSalesStatSalesPaypal()) ) : null;
    ($this -> getSalesStatSalesPaypalWpp())? $this ->SalesStat->setSalesStatSalesPaypalWpp( WTVRcleanString( $this -> getSalesStatSalesPaypalWpp()) ) : null;
    ($this -> getSalesStatVisitors())? $this ->SalesStat->setSalesStatVisitors( WTVRcleanString( $this -> getSalesStatVisitors()) ) : null;
    ($this -> getSalesStatPageviews())? $this ->SalesStat->setSalesStatPageviews( WTVRcleanString( $this -> getSalesStatPageviews()) ) : null;
    ($this -> getSalesStatType())? $this ->SalesStat->setSalesStatType( WTVRcleanString( $this -> getSalesStatType()) ) : null;
          
    $this -> save();
     
  }
  
  function save() {
    $this ->SalesStat ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->SalesStat = SalesStatPeer::retrieveByPK($id);
    }
    
    if (! $this ->SalesStat ) {
      return;
    }
    
    $this ->SalesStat -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('SalesStat_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "SalesStatPeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $SalesStat = SalesStatPeer::doSelect($c);
    
    if (count($SalesStat) >= 1) {
      $this ->SalesStat = $SalesStat[0];
      return true;
    } else {
      $this ->SalesStat = new SalesStat();
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
      $name = "SalesStatPeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $SalesStat = SalesStatPeer::doSelect($c);
    
    if (count($SalesStat) >= 1) {
      $this ->SalesStat = $SalesStat[0];
      return true;
    } else {
      $this ->SalesStat = new SalesStat();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>