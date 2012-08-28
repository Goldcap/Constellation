<?php
       
   class WtvrMessageViewCrudBase extends Utils_PageWidget { 
   
    var $WtvrMessageView;
   
       var $wtvr_message_view_id;
   var $fk_wtvr_message_id;
   var $fk_message_recipient_id;
   var $wtvr_message_view_date_viewed;
   var $wtvr_message_view_ip_address;
   var $wtvr_message_view_browser;
   var $wtvr_message_view_data;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> getWtvrMessageViewId();
    }
    
    if (($id) && ($id != 0)) {
      $this ->WtvrMessageView = WtvrMessageViewPeer::retrieveByPK( $id );
    } else {
      $this ->WtvrMessageView = new WtvrMessageView;
    }
  }
  
  function hydrate( $id ) {
      $this ->WtvrMessageView = WtvrMessageViewPeer::retrieveByPK( $id );
  }
  
  function getWtvrMessageViewId() {
    if (($this ->postVar("wtvr_message_view_id")) || ($this ->postVar("wtvr_message_view_id") === "")) {
      return $this ->postVar("wtvr_message_view_id");
    } elseif (($this ->getVar("wtvr_message_view_id")) || ($this ->getVar("wtvr_message_view_id") === "")) {
      return $this ->getVar("wtvr_message_view_id");
    } elseif (($this ->WtvrMessageView) || ($this ->WtvrMessageView === "")){
      return $this ->WtvrMessageView -> getWtvrMessageViewId();
    } elseif (($this ->sessionVar("wtvr_message_view_id")) || ($this ->sessionVar("wtvr_message_view_id") == "")) {
      return $this ->sessionVar("wtvr_message_view_id");
    } else {
      return false;
    }
  }
  
  function setWtvrMessageViewId( $str ) {
    $this ->WtvrMessageView -> setWtvrMessageViewId( $str );
  }
  
  function getFkWtvrMessageId() {
    if (($this ->postVar("fk_wtvr_message_id")) || ($this ->postVar("fk_wtvr_message_id") === "")) {
      return $this ->postVar("fk_wtvr_message_id");
    } elseif (($this ->getVar("fk_wtvr_message_id")) || ($this ->getVar("fk_wtvr_message_id") === "")) {
      return $this ->getVar("fk_wtvr_message_id");
    } elseif (($this ->WtvrMessageView) || ($this ->WtvrMessageView === "")){
      return $this ->WtvrMessageView -> getFkWtvrMessageId();
    } elseif (($this ->sessionVar("fk_wtvr_message_id")) || ($this ->sessionVar("fk_wtvr_message_id") == "")) {
      return $this ->sessionVar("fk_wtvr_message_id");
    } else {
      return false;
    }
  }
  
  function setFkWtvrMessageId( $str ) {
    $this ->WtvrMessageView -> setFkWtvrMessageId( $str );
  }
  
  function getFkMessageRecipientId() {
    if (($this ->postVar("fk_message_recipient_id")) || ($this ->postVar("fk_message_recipient_id") === "")) {
      return $this ->postVar("fk_message_recipient_id");
    } elseif (($this ->getVar("fk_message_recipient_id")) || ($this ->getVar("fk_message_recipient_id") === "")) {
      return $this ->getVar("fk_message_recipient_id");
    } elseif (($this ->WtvrMessageView) || ($this ->WtvrMessageView === "")){
      return $this ->WtvrMessageView -> getFkMessageRecipientId();
    } elseif (($this ->sessionVar("fk_message_recipient_id")) || ($this ->sessionVar("fk_message_recipient_id") == "")) {
      return $this ->sessionVar("fk_message_recipient_id");
    } else {
      return false;
    }
  }
  
  function setFkMessageRecipientId( $str ) {
    $this ->WtvrMessageView -> setFkMessageRecipientId( $str );
  }
  
  function getWtvrMessageViewDateViewed() {
    if (($this ->postVar("wtvr_message_view_date_viewed")) || ($this ->postVar("wtvr_message_view_date_viewed") === "")) {
      return $this ->postVar("wtvr_message_view_date_viewed");
    } elseif (($this ->getVar("wtvr_message_view_date_viewed")) || ($this ->getVar("wtvr_message_view_date_viewed") === "")) {
      return $this ->getVar("wtvr_message_view_date_viewed");
    } elseif (($this ->WtvrMessageView) || ($this ->WtvrMessageView === "")){
      return $this ->WtvrMessageView -> getWtvrMessageViewDateViewed();
    } elseif (($this ->sessionVar("wtvr_message_view_date_viewed")) || ($this ->sessionVar("wtvr_message_view_date_viewed") == "")) {
      return $this ->sessionVar("wtvr_message_view_date_viewed");
    } else {
      return false;
    }
  }
  
  function setWtvrMessageViewDateViewed( $str ) {
    $this ->WtvrMessageView -> setWtvrMessageViewDateViewed( $str );
  }
  
  function getWtvrMessageViewIpAddress() {
    if (($this ->postVar("wtvr_message_view_ip_address")) || ($this ->postVar("wtvr_message_view_ip_address") === "")) {
      return $this ->postVar("wtvr_message_view_ip_address");
    } elseif (($this ->getVar("wtvr_message_view_ip_address")) || ($this ->getVar("wtvr_message_view_ip_address") === "")) {
      return $this ->getVar("wtvr_message_view_ip_address");
    } elseif (($this ->WtvrMessageView) || ($this ->WtvrMessageView === "")){
      return $this ->WtvrMessageView -> getWtvrMessageViewIpAddress();
    } elseif (($this ->sessionVar("wtvr_message_view_ip_address")) || ($this ->sessionVar("wtvr_message_view_ip_address") == "")) {
      return $this ->sessionVar("wtvr_message_view_ip_address");
    } else {
      return false;
    }
  }
  
  function setWtvrMessageViewIpAddress( $str ) {
    $this ->WtvrMessageView -> setWtvrMessageViewIpAddress( $str );
  }
  
  function getWtvrMessageViewBrowser() {
    if (($this ->postVar("wtvr_message_view_browser")) || ($this ->postVar("wtvr_message_view_browser") === "")) {
      return $this ->postVar("wtvr_message_view_browser");
    } elseif (($this ->getVar("wtvr_message_view_browser")) || ($this ->getVar("wtvr_message_view_browser") === "")) {
      return $this ->getVar("wtvr_message_view_browser");
    } elseif (($this ->WtvrMessageView) || ($this ->WtvrMessageView === "")){
      return $this ->WtvrMessageView -> getWtvrMessageViewBrowser();
    } elseif (($this ->sessionVar("wtvr_message_view_browser")) || ($this ->sessionVar("wtvr_message_view_browser") == "")) {
      return $this ->sessionVar("wtvr_message_view_browser");
    } else {
      return false;
    }
  }
  
  function setWtvrMessageViewBrowser( $str ) {
    $this ->WtvrMessageView -> setWtvrMessageViewBrowser( $str );
  }
  
  function getWtvrMessageViewData() {
    if (($this ->postVar("wtvr_message_view_data")) || ($this ->postVar("wtvr_message_view_data") === "")) {
      return $this ->postVar("wtvr_message_view_data");
    } elseif (($this ->getVar("wtvr_message_view_data")) || ($this ->getVar("wtvr_message_view_data") === "")) {
      return $this ->getVar("wtvr_message_view_data");
    } elseif (($this ->WtvrMessageView) || ($this ->WtvrMessageView === "")){
      return $this ->WtvrMessageView -> getWtvrMessageViewData();
    } elseif (($this ->sessionVar("wtvr_message_view_data")) || ($this ->sessionVar("wtvr_message_view_data") == "")) {
      return $this ->sessionVar("wtvr_message_view_data");
    } else {
      return false;
    }
  }
  
  function setWtvrMessageViewData( $str ) {
    $this ->WtvrMessageView -> setWtvrMessageViewData( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->WtvrMessageView = WtvrMessageViewPeer::retrieveByPK( $id );
    }
    
    if ($this ->WtvrMessageView ) {
       
    	       (is_numeric(WTVRcleanString($this ->WtvrMessageView->getWtvrMessageViewId()))) ? $itemarray["wtvr_message_view_id"] = WTVRcleanString($this ->WtvrMessageView->getWtvrMessageViewId()) : null;
          (is_numeric(WTVRcleanString($this ->WtvrMessageView->getFkWtvrMessageId()))) ? $itemarray["fk_wtvr_message_id"] = WTVRcleanString($this ->WtvrMessageView->getFkWtvrMessageId()) : null;
          (is_numeric(WTVRcleanString($this ->WtvrMessageView->getFkMessageRecipientId()))) ? $itemarray["fk_message_recipient_id"] = WTVRcleanString($this ->WtvrMessageView->getFkMessageRecipientId()) : null;
          //Be sure to format this date for the output type in Styroform, defaults to 'date'
      (WTVRcleanString($this ->WtvrMessageView->getWtvrMessageViewDateViewed())) ? $itemarray["wtvr_message_view_date_viewed"] = formatDate($this ->WtvrMessageView->getWtvrMessageViewDateViewed('%Y-%m-%d %T'),"TS") : null;
          (WTVRcleanString($this ->WtvrMessageView->getWtvrMessageViewIpAddress())) ? $itemarray["wtvr_message_view_ip_address"] = WTVRcleanString($this ->WtvrMessageView->getWtvrMessageViewIpAddress()) : null;
          (WTVRcleanString($this ->WtvrMessageView->getWtvrMessageViewBrowser())) ? $itemarray["wtvr_message_view_browser"] = WTVRcleanString($this ->WtvrMessageView->getWtvrMessageViewBrowser()) : null;
          (WTVRcleanString($this ->WtvrMessageView->getWtvrMessageViewData())) ? $itemarray["wtvr_message_view_data"] = WTVRcleanString($this ->WtvrMessageView->getWtvrMessageViewData()) : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->WtvrMessageView = WtvrMessageViewPeer::retrieveByPK( $id );
    } elseif (! $this ->WtvrMessageView) {
      $this ->WtvrMessageView = new WtvrMessageView;
    }
        
  	 ($this -> getWtvrMessageViewId())? $this ->WtvrMessageView->setWtvrMessageViewId( WTVRcleanString( $this -> getWtvrMessageViewId()) ) : null;
    ($this -> getFkWtvrMessageId())? $this ->WtvrMessageView->setFkWtvrMessageId( WTVRcleanString( $this -> getFkWtvrMessageId()) ) : null;
    ($this -> getFkMessageRecipientId())? $this ->WtvrMessageView->setFkMessageRecipientId( WTVRcleanString( $this -> getFkMessageRecipientId()) ) : null;
          if (is_valid_date( $this ->WtvrMessageView->getWtvrMessageViewDateViewed())) {
        $this ->WtvrMessageView->setWtvrMessageViewDateViewed( formatDate($this -> getWtvrMessageViewDateViewed(), "TS" ));
      } else {
      $WtvrMessageViewwtvr_message_view_date_viewed = $this -> sfDateTime( "wtvr_message_view_date_viewed" );
      ( $WtvrMessageViewwtvr_message_view_date_viewed != "01/01/1900 00:00:00" )? $this ->WtvrMessageView->setWtvrMessageViewDateViewed( formatDate($WtvrMessageViewwtvr_message_view_date_viewed, "TS" )) : $this ->WtvrMessageView->setWtvrMessageViewDateViewed( null );
      }
    ($this -> getWtvrMessageViewIpAddress())? $this ->WtvrMessageView->setWtvrMessageViewIpAddress( WTVRcleanString( $this -> getWtvrMessageViewIpAddress()) ) : null;
    ($this -> getWtvrMessageViewBrowser())? $this ->WtvrMessageView->setWtvrMessageViewBrowser( WTVRcleanString( $this -> getWtvrMessageViewBrowser()) ) : null;
    ($this -> getWtvrMessageViewData())? $this ->WtvrMessageView->setWtvrMessageViewData( WTVRcleanString( $this -> getWtvrMessageViewData()) ) : null;
          
    $this -> save();
     
  }
  
  function save() {
    $this ->WtvrMessageView ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->WtvrMessageView = WtvrMessageViewPeer::retrieveByPK($id);
    }
    
    if (! $this ->WtvrMessageView ) {
      return;
    }
    
    $this ->WtvrMessageView -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('WtvrMessageView_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "WtvrMessageViewPeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $WtvrMessageView = WtvrMessageViewPeer::doSelect($c);
    
    if (count($WtvrMessageView) >= 1) {
      $this ->WtvrMessageView = $WtvrMessageView[0];
      return true;
    } else {
      $this ->WtvrMessageView = new WtvrMessageView();
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
      $name = "WtvrMessageViewPeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $WtvrMessageView = WtvrMessageViewPeer::doSelect($c);
    
    if (count($WtvrMessageView) >= 1) {
      $this ->WtvrMessageView = $WtvrMessageView[0];
      return true;
    } else {
      $this ->WtvrMessageView = new WtvrMessageView();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>