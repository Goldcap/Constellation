<?php

/**
 * WTVRPHPCreator.php, Styroform XML Form Controller
 * 
 * XML Form Generator and Validator
 * in-code documentation through DocBlocks and tags.
 * @author Andy Madsen <amadsen@motormeme.com>
 * @version 1.2
 * @package XMLForm
 */
// WTVRPHPCreator

/**
 * The Form Controller class, responsible for drawing the form, calling the validator, adding "Items" and "ItemLists", and navigating through formsets
 * @package WTVRPHPCreator
 * @subpackage classes
 */
class WTVRPHPCreator {
  
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $xml 
  */
  var $xml;
  
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $xsl_location  
  */
  var $xsl_location;
  
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $location
  */
  var $location;
  
  var $local_libroot;
  var $controlurl;
  
  /**
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * <code> 
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  * </code>
  * @name WTVRPHPCreator
  */
  function WTVRPHPCreator() {
    $this -> debug_local = true;
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
  * @name transformToPHP
  */
  function transformToPHP( $die=false ) {
    
    $libnode = $this -> xml -> createElement("local_libroot");
    $libnode -> nodeValue = $this -> local_libroot;
    $this -> xml -> appendChild($libnode);
    $libnode = $this -> xml -> createElement("controlurl");
    $libnode -> nodeValue = $this -> controlurl;
    $this -> xml  -> appendChild($libnode);
    $hostnode = $this -> xml -> createElement("hostname");
    if(array_key_exists("SERVER_NAME", $_SERVER))
    {
      $hostnode -> nodeValue = $_SERVER["SERVER_NAME"];
    }
    $this -> xml -> appendChild($hostnode);
    $hostnode = $this -> xml -> createElement("asseturl");
    if(array_key_exists("asseturl", $GLOBALS))
    {
      $hostnode -> nodeValue = $GLOBALS['asseturl'];
    }
    $this -> xml -> appendChild($hostnode);
    
    if ($this -> debug_local) {cli_text("[Reading XSL]","green");}
    $xslDoc = new DOMDocument();
    $xslDoc -> load($this -> xsl_location);
    
    if ($this -> debug_local) {cli_text("[Transforming to Conf]","cyan");}
    $xslt = new XSLTProcessor();
    $xslt -> importStylesheet($xslDoc); 
    $output = $xslt -> transformToDoc($this -> xml);
    
    if ($this -> debug_local) {cli_text("[DONE]","green");}
    
    $filename = $this -> location;
    
    if ($this -> debug_local) {cli_text("[Saving To File]","cyan");}
    if ($this -> debug_local) {cli_text($filename,"grey");}
    
    $output -> formatOutput = true;
    
    $string = $output -> saveXML();
    
    if ($this -> debug_local) {cli_text("[Updating Widget Variables]","cyan");}
    //Remove XML Declaration, if any
    $string = preg_replace("/\<\?xml(.*)\>/","", $string);  
    //Remove all Linebreaks
    $string = str_replace("\n","", $string); 
    //Force Linebreaks at silly delimiter
    $string = str_replace("//##","\n", $string); 
    
    $handle = fopen($filename, 'w');
    
    // Let's make sure the file exists and is writable first.
    if (is_writable($filename)) {
       // In our example we're opening $filename in append mode.
       // The file pointer is at the bottom of the file hence
       // that's where $somecontent will go when we fwrite() it.
       if (!$handle = fopen($filename, 'a')) {
             echo "Cannot open file ($filename)";
             exit;
       }
    
       // Write $somecontent to our opened file.
       if (fwrite($handle, $string) === FALSE) {
           echo "Cannot write to file ($filename)";
           exit;
       }
      
       if ($this -> debug_local) {cli_text($filename,"grey");}
       fclose($handle);
    
    } else {
       cli_text("The file $filename is not writable","red","white");
    }
    if ($this -> debug_local) {cli_text("[DONE]","green");}
    if ($die) {
      die("HERE");
    }
  }
}
?>
