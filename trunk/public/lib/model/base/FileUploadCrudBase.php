<?php
       
   class FileUploadCrudBase extends Utils_PageWidget { 
   
    var $FileUpload;
   
       var $file_upload_id;
   var $file_upload_user;
   var $file_upload_filename;
   var $file_upload_date_discovery;
   var $file_upload_date;
   var $file_upload_status;
   var $file_upload_film;
   var $file_upload_client;
   var $file_upload_size;

   
   function __construct( $context, $id ) {
    parent::__construct( $context );
    //If no ID, try to match from session or get/post Scope
    if (! is_numeric($id)) {
      $id = $this -> getFileUploadId();
    }
    
    if (($id) && ($id != 0)) {
      $this ->FileUpload = FileUploadPeer::retrieveByPK( $id );
    } else {
      $this ->FileUpload = new FileUpload;
    }
  }
  
  function hydrate( $id ) {
      $this ->FileUpload = FileUploadPeer::retrieveByPK( $id );
  }
  
  function getFileUploadId() {
    if (($this ->postVar("file_upload_id")) || ($this ->postVar("file_upload_id") === "")) {
      return $this ->postVar("file_upload_id");
    } elseif (($this ->getVar("file_upload_id")) || ($this ->getVar("file_upload_id") === "")) {
      return $this ->getVar("file_upload_id");
    } elseif (($this ->FileUpload) || ($this ->FileUpload === "")){
      return $this ->FileUpload -> getFileUploadId();
    } elseif (($this ->sessionVar("file_upload_id")) || ($this ->sessionVar("file_upload_id") == "")) {
      return $this ->sessionVar("file_upload_id");
    } else {
      return false;
    }
  }
  
  function setFileUploadId( $str ) {
    $this ->FileUpload -> setFileUploadId( $str );
  }
  
  function getFileUploadUser() {
    if (($this ->postVar("file_upload_user")) || ($this ->postVar("file_upload_user") === "")) {
      return $this ->postVar("file_upload_user");
    } elseif (($this ->getVar("file_upload_user")) || ($this ->getVar("file_upload_user") === "")) {
      return $this ->getVar("file_upload_user");
    } elseif (($this ->FileUpload) || ($this ->FileUpload === "")){
      return $this ->FileUpload -> getFileUploadUser();
    } elseif (($this ->sessionVar("file_upload_user")) || ($this ->sessionVar("file_upload_user") == "")) {
      return $this ->sessionVar("file_upload_user");
    } else {
      return false;
    }
  }
  
  function setFileUploadUser( $str ) {
    $this ->FileUpload -> setFileUploadUser( $str );
  }
  
  function getFileUploadFilename() {
    if (($this ->postVar("file_upload_filename")) || ($this ->postVar("file_upload_filename") === "")) {
      return $this ->postVar("file_upload_filename");
    } elseif (($this ->getVar("file_upload_filename")) || ($this ->getVar("file_upload_filename") === "")) {
      return $this ->getVar("file_upload_filename");
    } elseif (($this ->FileUpload) || ($this ->FileUpload === "")){
      return $this ->FileUpload -> getFileUploadFilename();
    } elseif (($this ->sessionVar("file_upload_filename")) || ($this ->sessionVar("file_upload_filename") == "")) {
      return $this ->sessionVar("file_upload_filename");
    } else {
      return false;
    }
  }
  
  function setFileUploadFilename( $str ) {
    $this ->FileUpload -> setFileUploadFilename( $str );
  }
  
  function getFileUploadDateDiscovery() {
    if (($this ->postVar("file_upload_date_discovery")) || ($this ->postVar("file_upload_date_discovery") === "")) {
      return $this ->postVar("file_upload_date_discovery");
    } elseif (($this ->getVar("file_upload_date_discovery")) || ($this ->getVar("file_upload_date_discovery") === "")) {
      return $this ->getVar("file_upload_date_discovery");
    } elseif (($this ->FileUpload) || ($this ->FileUpload === "")){
      return $this ->FileUpload -> getFileUploadDateDiscovery();
    } elseif (($this ->sessionVar("file_upload_date_discovery")) || ($this ->sessionVar("file_upload_date_discovery") == "")) {
      return $this ->sessionVar("file_upload_date_discovery");
    } else {
      return false;
    }
  }
  
  function setFileUploadDateDiscovery( $str ) {
    $this ->FileUpload -> setFileUploadDateDiscovery( $str );
  }
  
  function getFileUploadDate() {
    if (($this ->postVar("file_upload_date")) || ($this ->postVar("file_upload_date") === "")) {
      return $this ->postVar("file_upload_date");
    } elseif (($this ->getVar("file_upload_date")) || ($this ->getVar("file_upload_date") === "")) {
      return $this ->getVar("file_upload_date");
    } elseif (($this ->FileUpload) || ($this ->FileUpload === "")){
      return $this ->FileUpload -> getFileUploadDate();
    } elseif (($this ->sessionVar("file_upload_date")) || ($this ->sessionVar("file_upload_date") == "")) {
      return $this ->sessionVar("file_upload_date");
    } else {
      return false;
    }
  }
  
  function setFileUploadDate( $str ) {
    $this ->FileUpload -> setFileUploadDate( $str );
  }
  
  function getFileUploadStatus() {
    if (($this ->postVar("file_upload_status")) || ($this ->postVar("file_upload_status") === "")) {
      return $this ->postVar("file_upload_status");
    } elseif (($this ->getVar("file_upload_status")) || ($this ->getVar("file_upload_status") === "")) {
      return $this ->getVar("file_upload_status");
    } elseif (($this ->FileUpload) || ($this ->FileUpload === "")){
      return $this ->FileUpload -> getFileUploadStatus();
    } elseif (($this ->sessionVar("file_upload_status")) || ($this ->sessionVar("file_upload_status") == "")) {
      return $this ->sessionVar("file_upload_status");
    } else {
      return false;
    }
  }
  
  function setFileUploadStatus( $str ) {
    $this ->FileUpload -> setFileUploadStatus( $str );
  }
  
  function getFileUploadFilm() {
    if (($this ->postVar("file_upload_film")) || ($this ->postVar("file_upload_film") === "")) {
      return $this ->postVar("file_upload_film");
    } elseif (($this ->getVar("file_upload_film")) || ($this ->getVar("file_upload_film") === "")) {
      return $this ->getVar("file_upload_film");
    } elseif (($this ->FileUpload) || ($this ->FileUpload === "")){
      return $this ->FileUpload -> getFileUploadFilm();
    } elseif (($this ->sessionVar("file_upload_film")) || ($this ->sessionVar("file_upload_film") == "")) {
      return $this ->sessionVar("file_upload_film");
    } else {
      return false;
    }
  }
  
  function setFileUploadFilm( $str ) {
    $this ->FileUpload -> setFileUploadFilm( $str );
  }
  
  function getFileUploadClient() {
    if (($this ->postVar("file_upload_client")) || ($this ->postVar("file_upload_client") === "")) {
      return $this ->postVar("file_upload_client");
    } elseif (($this ->getVar("file_upload_client")) || ($this ->getVar("file_upload_client") === "")) {
      return $this ->getVar("file_upload_client");
    } elseif (($this ->FileUpload) || ($this ->FileUpload === "")){
      return $this ->FileUpload -> getFileUploadClient();
    } elseif (($this ->sessionVar("file_upload_client")) || ($this ->sessionVar("file_upload_client") == "")) {
      return $this ->sessionVar("file_upload_client");
    } else {
      return false;
    }
  }
  
  function setFileUploadClient( $str ) {
    $this ->FileUpload -> setFileUploadClient( $str );
  }
  
  function getFileUploadSize() {
    if (($this ->postVar("file_upload_size")) || ($this ->postVar("file_upload_size") === "")) {
      return $this ->postVar("file_upload_size");
    } elseif (($this ->getVar("file_upload_size")) || ($this ->getVar("file_upload_size") === "")) {
      return $this ->getVar("file_upload_size");
    } elseif (($this ->FileUpload) || ($this ->FileUpload === "")){
      return $this ->FileUpload -> getFileUploadSize();
    } elseif (($this ->sessionVar("file_upload_size")) || ($this ->sessionVar("file_upload_size") == "")) {
      return $this ->sessionVar("file_upload_size");
    } else {
      return false;
    }
  }
  
  function setFileUploadSize( $str ) {
    $this ->FileUpload -> setFileUploadSize( $str );
  }
  

     function read( $id = false ) {
    $itemarray = array();
    
    if ($id) {
    $this ->FileUpload = FileUploadPeer::retrieveByPK( $id );
    }
    
    if ($this ->FileUpload ) {
       
    	       (is_numeric(WTVRcleanString($this ->FileUpload->getFileUploadId()))) ? $itemarray["file_upload_id"] = WTVRcleanString($this ->FileUpload->getFileUploadId()) : null;
          (WTVRcleanString($this ->FileUpload->getFileUploadUser())) ? $itemarray["file_upload_user"] = WTVRcleanString($this ->FileUpload->getFileUploadUser()) : null;
          (WTVRcleanString($this ->FileUpload->getFileUploadFilename())) ? $itemarray["file_upload_filename"] = WTVRcleanString($this ->FileUpload->getFileUploadFilename()) : null;
          //Be sure to format this date for the output type in Styroform, defaults to 'date'
      (WTVRcleanString($this ->FileUpload->getFileUploadDateDiscovery())) ? $itemarray["file_upload_date_discovery"] = formatDate($this ->FileUpload->getFileUploadDateDiscovery('%Y-%m-%d %T'),"TS") : null;
          (WTVRcleanString($this ->FileUpload->getFileUploadDate())) ? $itemarray["file_upload_date"] = WTVRcleanString($this ->FileUpload->getFileUploadDate()) : null;
          (WTVRcleanString($this ->FileUpload->getFileUploadStatus())) ? $itemarray["file_upload_status"] = WTVRcleanString($this ->FileUpload->getFileUploadStatus()) : null;
          (is_numeric(WTVRcleanString($this ->FileUpload->getFileUploadFilm()))) ? $itemarray["file_upload_film"] = WTVRcleanString($this ->FileUpload->getFileUploadFilm()) : null;
          (WTVRcleanString($this ->FileUpload->getFileUploadClient())) ? $itemarray["file_upload_client"] = WTVRcleanString($this ->FileUpload->getFileUploadClient()) : null;
          (WTVRcleanString($this ->FileUpload->getFileUploadSize())) ? $itemarray["file_upload_size"] = WTVRcleanString($this ->FileUpload->getFileUploadSize()) : null;
            
      return $itemarray;
      }
      return false;
    }
  
  function write( $id = false ) {
    if ($id) {
      $this ->FileUpload = FileUploadPeer::retrieveByPK( $id );
    } elseif (! $this ->FileUpload) {
      $this ->FileUpload = new FileUpload;
    }
        
  	 ($this -> getFileUploadId())? $this ->FileUpload->setFileUploadId( WTVRcleanString( $this -> getFileUploadId()) ) : null;
    ($this -> getFileUploadUser())? $this ->FileUpload->setFileUploadUser( WTVRcleanString( $this -> getFileUploadUser()) ) : null;
    ($this -> getFileUploadFilename())? $this ->FileUpload->setFileUploadFilename( WTVRcleanString( $this -> getFileUploadFilename()) ) : null;
          if (is_valid_date( $this ->FileUpload->getFileUploadDateDiscovery())) {
        $this ->FileUpload->setFileUploadDateDiscovery( formatDate($this -> getFileUploadDateDiscovery(), "TS" ));
      } else {
      $FileUploadfile_upload_date_discovery = $this -> sfDateTime( "file_upload_date_discovery" );
      ( $FileUploadfile_upload_date_discovery != "01/01/1900 00:00:00" )? $this ->FileUpload->setFileUploadDateDiscovery( formatDate($FileUploadfile_upload_date_discovery, "TS" )) : $this ->FileUpload->setFileUploadDateDiscovery( null );
      }
    ($this -> getFileUploadDate())? $this ->FileUpload->setFileUploadDate( WTVRcleanString( $this -> getFileUploadDate()) ) : null;
    ($this -> getFileUploadStatus())? $this ->FileUpload->setFileUploadStatus( WTVRcleanString( $this -> getFileUploadStatus()) ) : null;
    ($this -> getFileUploadFilm())? $this ->FileUpload->setFileUploadFilm( WTVRcleanString( $this -> getFileUploadFilm()) ) : null;
    ($this -> getFileUploadClient())? $this ->FileUpload->setFileUploadClient( WTVRcleanString( $this -> getFileUploadClient()) ) : null;
    ($this -> getFileUploadSize())? $this ->FileUpload->setFileUploadSize( WTVRcleanString( $this -> getFileUploadSize()) ) : null;
          
    $this -> save();
     
  }
  
  function save() {
    $this ->FileUpload ->save();
   }
  
  function remove( $id = false) {
    
    if ($id) {
      $this ->FileUpload = FileUploadPeer::retrieveByPK($id);
    }
    
    if (! $this ->FileUpload ) {
      return;
    }
    
    $this ->FileUpload -> delete();
    
    
    if ($this ->sessionVar("NaN")){
       $this ->context -> getUser()->getAttributeHolder()->remove('FileUpload_id');
    }
    
    return true;
    
  }
  
    //Pass an key value pair of $key-&gt;$value pairs
  //equivalent to colnames and check values
  //if ALL the array is matched in a data column
  //this will return true
  function populate( $key, $value ) {
    $c = new Criteria();
    
    $name = "FileUploadPeer::".strtoupper($key);
    eval("\$c->add(".$name.",\$value);");
    
    $c->setDistinct();
    $FileUpload = FileUploadPeer::doSelect($c);
    
    if (count($FileUpload) >= 1) {
      $this ->FileUpload = $FileUpload[0];
      return true;
    } else {
      $this ->FileUpload = new FileUpload();
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
      $name = "FileUploadPeer::".strtoupper($key);
      eval("\$c->add(".$name.",\$value);");
    }
    
    $c->setDistinct();
    $FileUpload = FileUploadPeer::doSelect($c);
    
    if (count($FileUpload) >= 1) {
      $this ->FileUpload = $FileUpload[0];
      return true;
    } else {
      $this ->FileUpload = new FileUpload();
      return false;
    }
  }
  
  function __destruct() {
  
  }
  
   }?>