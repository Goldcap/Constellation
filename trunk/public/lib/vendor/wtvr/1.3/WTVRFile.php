<?php

/**
 * WTVRFile.php, Styroform XML Form Controller
 * 
 * XML Form Generator and Validator
 * in-code documentation through DocBlocks and tags.
 * @author Andy Madsen <amadsen@motormeme.com>
 * @version 1.2
 * @package XMLForm
 */
    
/**
 * The Form Controller class, responsible for drawing the form, calling the validator, adding "Items" and "ItemLists", and navigating through formsets
 * @package WTVRFile
 * @subpackage classes
 */
class WTVRFile {
    
    /** 
    * Array of XML Forms in an ordered FormSet
    * @access public
    * @var array
    * @name $element_name 
    */
    var $element_name;
      
    /** 
    * Array of XML Forms in an ordered FormSet
    * @access public
    * @var array
    * @name $thefile 
    */
    var $thefile;
    //The FILE array has this in it:
    //["name"]=>
    //string(10) "bowtie.jpg"
    //["type"]=>
    //string(10) "image/jpeg"
    //["tmp_name"]=>
    //string(14) "/tmp/phpVd3XyX"
    //["error"]=>
    //int(0)
    //["size"]=>
    //int(11486)
    
    /** 
    * Array of XML Forms in an ordered FormSet
    * @access public
    * @var array
    * @name $source_type 
    */
    var $source_type;
    
    /** 
    * Array of XML Forms in an ordered FormSet
    * @access public
    * @var array
    * @name $source_type 
    */
    var $file_size;
    
    /** 
    * Array of XML Forms in an ordered FormSet
    * @access public
    * @var array
    * @name $source_type 
    */
    var $hash;
      
      
    /** 
    * Array of XML Forms in an ordered FormSet
    * @access public
    * @var array
    * @name $destination_name 
    */
    var $destination_name;
      
    /** 
    * Array of XML Forms in an ordered FormSet
    * @access public
    * @var array
    * @name $destination_type
    */
    var $destination_type;
      
    /** 
    * Array of XML Forms in an ordered FormSet
    * @access public
    * @var array
    * @name $destination_dir 
    */
    var $destination_dir;
      
    /** 
    * Array of XML Forms in an ordered FormSet
    * @access public
    * @var array
    * @name $destination_fullname
    */
    var $destination_fullname;
     
    /** 
    * Array of XML Forms in an ordered FormSet
    * @access public
    * @var array
    * @name $imagetypes 
    */
    var $imagetypes;
    
      
    /**
    * Form Constructor.
    * The formsettings array should look like this, either passed in the constructor or via WTVR:
    * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
      * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
    * <code> 
    *  $fpo = something();
    *  $fpo = something();
    *  $fpo = something();
    *  $fpo = something();
    * </code>
    * @name Constructor
    * @param string $element_name - FPO copy here
    * @param array $formsettings  - Array with both Formset, and XSL Doc
    */
    function __construct( $element_name='',$styro=true ) {
      
      $this -> element_name = $element_name;
      
      if ((! empty($_FILES)) && ($this -> element_name != '' )) {
        if($styro) {
           $this -> thefile = $_FILES["FILE_".$this -> element_name];
        } else {
           $this -> thefile = $_FILES[$this -> element_name];
        }
        $this -> setFileAttributes();
      }
      $this -> imagetypes = array('jpg','gif','jpeg');
    }
    
    /**
    * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
    * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
    * <code> 
    *  $fpo = something();
    *  $fpo = something();
    *  $fpo = something();
    *  $fpo = something();
    * </code>
    * @name isUploaded
    */
    function setFileAttributes() {
      if ($this -> thefile["tmp_name"] != "") {
      
        $source = explode(".",$this -> thefile['name']);
        $this -> source_type = strtolower(end($source));
        $this -> file_size = $this -> thefile['size'];
        
        $this -> hash = hash_file('md5', $this -> thefile['tmp_name']);
      }
      return true;
    }
    
    /**
    * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
    * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
    * <code> 
    *  $fpo = something();
    *  $fpo = something();
    *  $fpo = something();
    *  $fpo = something();
    * </code>
    * @name isUploaded
    */
    function setFile( $file ) {
      
      $filearray = explode("/",$file);
      $filename = end($filearray);
      //The FILE array has this in it:
      $this -> thefile["name"] = $filename;
      //string(10) "bowtie.jpg"
      //["type"]=>
      //string(10) "image/jpeg"
      $this -> thefile["tmp_name"] = $file;
      //string(14) "/tmp/phpVd3XyX"
      $this -> thefile["error"] = 0;
      //int(0)
      $this -> thefile["size"] = @filesize( $file  );
      //int(11486)
      $this -> setFileAttributes();
      
      $source = explode(".",$this -> thefile['name']);
      $this -> destination_name = strtolower($source[0]);
      
      return true;
    }
    
    /**
    * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
    * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
    * <code> 
    *  $fpo = something();
    *  $fpo = something();
    *  $fpo = something();
    *  $fpo = something();
    * </code>
    * @name isUploaded
    */
    function isUploaded() {
      
      if (strlen($this -> source_type) > 0) {
        return true;
      }
      return false;
    }
    
    /**
    * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
    * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
    * <code> 
    *  $fpo = something();
    *  $fpo = something();
    *  $fpo = something();
    *  $fpo = something();
    * </code>
    * @name isImage
    */
    function isImage() {
      switch (in_array($this -> source_type,$this -> imagetypes)) {
        case true :
        return true;
        break;
        
        case false:
        return false;
        break;
      }
    }
      
    /**
    * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
    * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
    * <code> 
    *  $fpo = something();
    *  $fpo = something();
    *  $fpo = something();
    *  $fpo = something();
    * </code>
    * @name move
    */
    function move() {
      
      if (! $this -> destination_name) {
        die("Need to specify destination name");
      }
      if (! $this -> destination_dir) {
        die("Need to specify destination directory");
      }
      $this -> destination_fullname = $this -> destination_dir."/".$this -> destination_name .".".$this -> source_type;
      
      $this -> createDestinationDir();
      if (! move_uploaded_file ( $this -> thefile["tmp_name"], $this -> destination_fullname )) {
        throw new Exception("File Not Moved To " .$this -> destination_fullname);
        return false;
      }
      
      //@chmod($this -> destination_fullname, 0777);
      return $this -> destination_fullname;
    }
    
    /**
    * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
    * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
    * <code> 
    *  $fpo = something();
    *  $fpo = something();
    *  $fpo = something();
    *  $fpo = something();
    * </code>
    * @name move
    */
    function moveFile($source,$destination) {
      
      if (! $destination) {
        die("Need to specify destination name");
      }
      $this -> destination_fullname = $destination;
      
      $this -> createDestinationDir();
      
      if (! rename( $source, $this -> destination_fullname )) {
        throw new Exception("File ".$source." Not Moved To " .$this -> destination_fullname);
        return false;
      }
      
      @chmod($this -> destination_fullname, 0777);
      return $this -> destination_fullname;
    }
    
    /**
    * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
    * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
    * <code> 
    *  $fpo = something();
    *  $fpo = something();
    *  $fpo = something();
    *  $fpo = something();
    * </code>
    * @name move
    */
    function relocate() {
      if (! $this -> destination_name) {
        die("Need to specify destination name");
      }
      if (! $this -> destination_dir) {
        die("Need to specify destination directory");
      }
      $this -> destination_fullname = $this -> destination_dir."/".$this -> destination_name .".".$this -> source_type;
      
      $this -> createDestinationDir();
      
      if (! rename ( $this -> thefile["tmp_name"], $this -> destination_fullname )) {
        die("File Not Moved To " .$this -> destination_fullname);
        return false;
      }
      @chmod($this -> destination_fullname, 0777);
      return $this -> destination_fullname;
    }
    
    /**
    * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
    * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
    * <code> 
    *  $fpo = something();
    *  $fpo = something();
    *  $fpo = something();
    *  $fpo = something();
    * </code>
    * @name copy 
    * @param string $destination_dir - FPO copy here
    * @param string $name - FPO copy here
    */
    function copy( $destination_dir=false, $name = false, $source=false ) {
      if (! $this -> destination_name) {
        die("Need to specify destination name");
      }
      if (! $this -> destination_dir) {
        die("Need to specify destination directory");
      }
      
      if (! $name) {
        $name = $this -> destination_name;
      }
      
      if (! $destination_dir) {
        $destination_dir=$this -> destination_dir;
      }
      
      $this -> createDestinationDir($destination_dir);
      
      $destination =  $destination_dir. "/". $name .".".$this -> source_type;
      
      
      if (! $source) {
        $source=$this -> destination_dir . $this -> destination_name . "." . $this -> source_type;
      }
      
      try {
        if (! copy ( $source, $destination )) {
          return false;
        }
        @chmod($destination, 0777);
        return true;
      } catch (Exception $e)  {
        return false;
      }
    }
      
    /**
    * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
    * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
    * <code> 
    *  $fpo = something();
    *  $fpo = something();
    *  $fpo = something();
    *  $fpo = something();
    * </code>
    * @name createDestinationDir
    */
    function createDestinationDir( $dir=false ) {
      if (! $dir ) {
        $dir = $this -> destination_dir;
      }
      if (strlen($dir) > 0) {
        createDirectory($dir);
        }
    }
    
  }
  
?>
