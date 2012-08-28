<?php
       
   class AdminUserCrudBase extends Utils_PageWidget { 
   
    var $AdminUser;
   
       var $admin_user_id;
   var $admin_user_fname;
   var $admin_user_lname;
   var $admin_user_email;
   var $admin_user_password;
   var $admin_user_ual;
   var $admin_user_phone;
   var $admin_user_cell;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> getAdminUserId();
    }
    
    if (($id) && ($id != 0)) {
      $this ->AdminUser = AdminUserPeer::retrieveByPK( $id );
    } else {
      $this ->AdminUser = new AdminUser;
    }
  }
  
  function hydrate( $id ) {
      $this ->AdminUser = AdminUserPeer::retrieveByPK( $id );
  }
  
  function getAdminUserId() {
    if (($this ->postVar("admin_user_id")) || ($this ->postVar("admin_user_id") === "")) {
      return $this ->postVar("admin_user_id");
    } elseif (($this ->getVar("admin_user_id")) || ($this ->getVar("admin_user_id") === "")) {
      return $this ->getVar("admin_user_id");
    } elseif (($this ->AdminUser) || ($this ->AdminUser === "")){
      return $this ->AdminUser -> getAdminUserId();
    } elseif (($this ->sessionVar("admin_user_id")) || ($this ->sessionVar("admin_user_id") == "")) {
      return $this ->sessionVar("admin_user_id");
    } else {
      return false;
    }
  }
  
  function setAdminUserId( $str ) {
    $this ->AdminUser -> setAdminUserId( $str );
  }
  
  function getAdminUserFname() {
    if (($this ->postVar("admin_user_fname")) || ($this ->postVar("admin_user_fname") === "")) {
      return $this ->postVar("admin_user_fname");
    } elseif (($this ->getVar("admin_user_fname")) || ($this ->getVar("admin_user_fname") === "")) {
      return $this ->getVar("admin_user_fname");
    } elseif (($this ->AdminUser) || ($this ->AdminUser === "")){
      return $this ->AdminUser -> getAdminUserFname();
    } elseif (($this ->sessionVar("admin_user_fname")) || ($this ->sessionVar("admin_user_fname") == "")) {
      return $this ->sessionVar("admin_user_fname");
    } else {
      return false;
    }
  }
  
  function setAdminUserFname( $str ) {
    $this ->AdminUser -> setAdminUserFname( $str );
  }
  
  function getAdminUserLname() {
    if (($this ->postVar("admin_user_lname")) || ($this ->postVar("admin_user_lname") === "")) {
      return $this ->postVar("admin_user_lname");
    } elseif (($this ->getVar("admin_user_lname")) || ($this ->getVar("admin_user_lname") === "")) {
      return $this ->getVar("admin_user_lname");
    } elseif (($this ->AdminUser) || ($this ->AdminUser === "")){
      return $this ->AdminUser -> getAdminUserLname();
    } elseif (($this ->sessionVar("admin_user_lname")) || ($this ->sessionVar("admin_user_lname") == "")) {
      return $this ->sessionVar("admin_user_lname");
    } else {
      return false;
    }
  }
  
  function setAdminUserLname( $str ) {
    $this ->AdminUser -> setAdminUserLname( $str );
  }
  
  function getAdminUserEmail() {
    if (($this ->postVar("admin_user_email")) || ($this ->postVar("admin_user_email") === "")) {
      return $this ->postVar("admin_user_email");
    } elseif (($this ->getVar("admin_user_email")) || ($this ->getVar("admin_user_email") === "")) {
      return $this ->getVar("admin_user_email");
    } elseif (($this ->AdminUser) || ($this ->AdminUser === "")){
      return $this ->AdminUser -> getAdminUserEmail();
    } elseif (($this ->sessionVar("admin_user_email")) || ($this ->sessionVar("admin_user_email") == "")) {
      return $this ->sessionVar("admin_user_email");
    } else {
      return false;
    }
  }
  
  function setAdminUserEmail( $str ) {
    $this ->AdminUser -> setAdminUserEmail( $str );
  }
  
  function getAdminUserPassword() {
    if (($this ->postVar("admin_user_password")) || ($this ->postVar("admin_user_password") === "")) {
      return $this ->postVar("admin_user_password");
    } elseif (($this ->getVar("admin_user_password")) || ($this ->getVar("admin_user_password") === "")) {
      return $this ->getVar("admin_user_password");
    } elseif (($this ->AdminUser) || ($this ->AdminUser === "")){
      return $this ->AdminUser -> getAdminUserPassword();
    } elseif (($this ->sessionVar("admin_user_password")) || ($this ->sessionVar("admin_user_password") == "")) {
      return $this ->sessionVar("admin_user_password");
    } else {
      return false;
    }
  }
  
  function setAdminUserPassword( $str ) {
    $this ->AdminUser -> setAdminUserPassword( $str );
  }
  
  function getAdminUserUal() {
    if (($this ->postVar("admin_user_ual")) || ($this ->postVar("admin_user_ual") === "")) {
      return $this ->postVar("admin_user_ual");
    } elseif (($this ->getVar("admin_user_ual")) || ($this ->getVar("admin_user_ual") === "")) {
      return $this ->getVar("admin_user_ual");
    } elseif (($this ->AdminUser) || ($this ->AdminUser === "")){
      return $this ->AdminUser -> getAdminUserUal();
    } elseif (($this ->sessionVar("admin_user_ual")) || ($this ->sessionVar("admin_user_ual") == "")) {
      return $this ->sessionVar("admin_user_ual");
    } else {
      return false;
    }
  }
  
  function setAdminUserUal( $str ) {
    $this ->AdminUser -> setAdminUserUal( $str );
  }
  
  function getAdminUserPhone() {
    if (($this ->postVar("admin_user_phone")) || ($this ->postVar("admin_user_phone") === "")) {
      return $this ->postVar("admin_user_phone");
    } elseif (($this ->getVar("admin_user_phone")) || ($this ->getVar("admin_user_phone") === "")) {
      return $this ->getVar("admin_user_phone");
    } elseif (($this ->AdminUser) || ($this ->AdminUser === "")){
      return $this ->AdminUser -> getAdminUserPhone();
    } elseif (($this ->sessionVar("admin_user_phone")) || ($this ->sessionVar("admin_user_phone") == "")) {
      return $this ->sessionVar("admin_user_phone");
    } else {
      return false;
    }
  }
  
  function setAdminUserPhone( $str ) {
    $this ->AdminUser -> setAdminUserPhone( $str );
  }
  
  function getAdminUserCell() {
    if (($this ->postVar("admin_user_cell")) || ($this ->postVar("admin_user_cell") === "")) {
      return $this ->postVar("admin_user_cell");
    } elseif (($this ->getVar("admin_user_cell")) || ($this ->getVar("admin_user_cell") === "")) {
      return $this ->getVar("admin_user_cell");
    } elseif (($this ->AdminUser) || ($this ->AdminUser === "")){
      return $this ->AdminUser -> getAdminUserCell();
    } elseif (($this ->sessionVar("admin_user_cell")) || ($this ->sessionVar("admin_user_cell") == "")) {
      return $this ->sessionVar("admin_user_cell");
    } else {
      return false;
    }
  }
  
  function setAdminUserCell( $str ) {
    $this ->AdminUser -> setAdminUserCell( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->AdminUser = AdminUserPeer::retrieveByPK( $id );
    }
    
    if ($this ->AdminUser ) {
       
    	       (is_numeric(WTVRcleanString($this ->AdminUser->getAdminUserId()))) ? $itemarray["admin_user_id"] = WTVRcleanString($this ->AdminUser->getAdminUserId()) : null;
          (WTVRcleanString($this ->AdminUser->getAdminUserFname())) ? $itemarray["admin_user_fname"] = WTVRcleanString($this ->AdminUser->getAdminUserFname()) : null;
          (WTVRcleanString($this ->AdminUser->getAdminUserLname())) ? $itemarray["admin_user_lname"] = WTVRcleanString($this ->AdminUser->getAdminUserLname()) : null;
          (WTVRcleanString($this ->AdminUser->getAdminUserEmail())) ? $itemarray["admin_user_email"] = WTVRcleanString($this ->AdminUser->getAdminUserEmail()) : null;
          (WTVRcleanString($this ->AdminUser->getAdminUserPassword())) ? $itemarray["admin_user_password"] = WTVRcleanString($this ->AdminUser->getAdminUserPassword()) : null;
          (WTVRcleanString($this ->AdminUser->getAdminUserUal())) ? $itemarray["admin_user_ual"] = WTVRcleanString($this ->AdminUser->getAdminUserUal()) : null;
          (WTVRcleanString($this ->AdminUser->getAdminUserPhone())) ? $itemarray["admin_user_phone"] = WTVRcleanString($this ->AdminUser->getAdminUserPhone()) : null;
          (WTVRcleanString($this ->AdminUser->getAdminUserCell())) ? $itemarray["admin_user_cell"] = WTVRcleanString($this ->AdminUser->getAdminUserCell()) : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->AdminUser = AdminUserPeer::retrieveByPK( $id );
    } elseif (! $this ->AdminUser) {
      $this ->AdminUser = new AdminUser;
    }
        
  	 ($this -> getAdminUserId())? $this ->AdminUser->setAdminUserId( WTVRcleanString( $this -> getAdminUserId()) ) : null;
    ($this -> getAdminUserFname())? $this ->AdminUser->setAdminUserFname( WTVRcleanString( $this -> getAdminUserFname()) ) : null;
    ($this -> getAdminUserLname())? $this ->AdminUser->setAdminUserLname( WTVRcleanString( $this -> getAdminUserLname()) ) : null;
    ($this -> getAdminUserEmail())? $this ->AdminUser->setAdminUserEmail( WTVRcleanString( $this -> getAdminUserEmail()) ) : null;
    ($this -> getAdminUserPassword())? $this ->AdminUser->setAdminUserPassword( WTVRcleanString( $this -> getAdminUserPassword()) ) : null;
    ($this -> getAdminUserUal())? $this ->AdminUser->setAdminUserUal( WTVRcleanString( $this -> getAdminUserUal()) ) : null;
    ($this -> getAdminUserPhone())? $this ->AdminUser->setAdminUserPhone( WTVRcleanString( $this -> getAdminUserPhone()) ) : null;
    ($this -> getAdminUserCell())? $this ->AdminUser->setAdminUserCell( WTVRcleanString( $this -> getAdminUserCell()) ) : null;
          
    $this -> save();
     
  }
  
  function save() {
    $this ->AdminUser ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->AdminUser = AdminUserPeer::retrieveByPK($id);
    }
    
    if (! $this ->AdminUser ) {
      return;
    }
    
    $this ->AdminUser -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('AdminUser_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "AdminUserPeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $AdminUser = AdminUserPeer::doSelect($c);
    
    if (count($AdminUser) >= 1) {
      $this ->AdminUser = $AdminUser[0];
      return true;
    } else {
      $this ->AdminUser = new AdminUser();
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
      $name = "AdminUserPeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $AdminUser = AdminUserPeer::doSelect($c);
    
    if (count($AdminUser) >= 1) {
      $this ->AdminUser = $AdminUser[0];
      return true;
    } else {
      $this ->AdminUser = new AdminUser();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>