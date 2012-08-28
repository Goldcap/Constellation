<?php

/**
 * clsXMLFormUtils.php, Styroform XML Form Controller
 * XML Form Generator and Validator.
 * @author Andy Madsen <amadsen@operislabs.com>
 * @version 1.2
 * @package com.Operis.Styroform
 */

/**
 * Convenience methods for XML Form.
 */
class XMLFormUtils {
  
  /**
  * Constructor creates a new XML Document
  * 
  * @name constructor  
  */
	function __construct() {
    $this->objXMLDoc = new XML();
  }
  
	/**
  * Create a datetime value from a compound form element.
  *  
  *	@name sfDateTime
  * @param string $elementname - FPO copy here
  */
  function sfDateTime( $elementname ) {
    $month = ((isset($_POST[$elementname."|month|comp"])) && (strlen($_POST[$elementname."|month|comp"]) > 0)) ? $_POST[$elementname."|month|comp"] : "01";
    $day = ((isset($_POST[$elementname."|day|comp"])) && (strlen($_POST[$elementname."|day|comp"]) > 0)) ? $_POST[$elementname."|day|comp"] : "01";
    $year = ((isset($_POST[$elementname."|year|comp"])) && (strlen($_POST[$elementname."|year|comp"]) > 0)) ? $_POST[$elementname."|year|comp"] : "1900";
    $hour = ((isset($_POST[$elementname."|hour|comp"])) && (strlen($_POST[$elementname."|hour|comp"]) > 0)) ? $_POST[$elementname."|hour|comp"] : "00";
    $minute = ((isset($_POST[$elementname."|min|comp"])) && (strlen($_POST[$elementname."|min|comp"]) > 0)) ? $_POST[$elementname."|min|comp"] : "00";
    $second = ((isset($_POST[$elementname."|second|comp"])) && (strlen($_POST[$elementname."|second|comp"]) > 0)) ? $_POST[$elementname."|second|comp"] : "00";
    $thetime = ($month . "/" . $day . "/" . $year . " " . $hour . ":" . $minute . ":" . $second);
	  
	  if (strlen(preg_replace("[/\/:\s0/]","",$thetime)) == 0) {
	    return false;
	  } else {
      return $thetime;
    }
	}
	
  /**
  * Remove the querystring variables "page" and "sort" from the URL
  *  
  * @name stripQSPage
  */
	function stripQSPage() {
    return preg_replace("/&(page|sort_(.*[^=]))=([0-9])+/i","",$_SERVER["QUERY_STRING"]);
  }
  
  /**
  * Determine if a form element is a compound element
  *  
  * @name checkCompound
  * @param string $item
  * @return boolean  
  */
	function checkCompound( $item ) {
	  if (substr( $item ,-4) == "comp") {
	    return true;
  	} else {
  	  return false;
    }
	}
	
  /**
  * Return an array containing the parts for a compound element
  * 
  * Parts are Name and Type
  *  
  * @name getCompound
  * @param string $item
  * @return array  
  */
	function getCompound($item) {
	  if (explode("|",$item)) {
	    return explode("|",$item);
    }
	}
	
  /**
  * Adds an element to an array
  *  
  * @name array_insert  
  * @param array $array
  * @param int $position
  * @param array $elements
  */
	function array_insert($array,$position,$elements) {
	  if (($position > 0) && ($position < count($array))) {
	    
      $left = array_slice ($array, 0, $position);
      $right = array_slice ($array, $position,count($array));
      
      $insert[0] = $elements;
      
      $array = array_merge ($left, $insert, $right);
      unset($left, $right, $insert);
      
      unset ($position, $elements);
    } else if ($position >= count($array)){
      $array[count($array) + 1] = $elements;
    }else {
      array_unshift($array,$elements);
    }
    return $array ;
  }
  
   
  /**
  * Dumps the current document into an XML Output
  *  
  * @name showXML
  */
  function showXML() {
    $this -> _debug();
  }
  
  /**
  * Dumps the current document into an XML Output
  * 
  * @name _debug
  */
  function _debug(){
    echo $this->objXMLDoc->_saveXML();
  }
  
}

?>
