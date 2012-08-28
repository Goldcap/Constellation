<?php

/**
 * WTVRLogReader.php, Styroform XML Form Controller
 * 
 * XML Form Generator and Validator
 * in-code documentation through DocBlocks and tags.
 * @author Andy Madsen <amadsen@motormeme.com>
 * @version 1.2
 * @package XMLForm
 */
// WTVRLogReader

/**
 * The Form Controller class, responsible for drawing the form, calling the validator, adding "Items" and "ItemLists", and navigating through formsets
 * @package WTVRLogReader
 * @subpackage classes
 */
class WTVRLogReader {
 
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $logloc 
  */
  var $logloc;
  
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $logdata 
  */
  var $logdata;
  
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
* @param array $formsettings  - Array with both Formset, and XSL Doc
*/
  function __construct() {
    $this -> logloc = sfConfig::get("sf_log_dir")."/wtvrlog.log";
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
  * @name ReadLog
  */
  function ReadLog() {
    $this -> logdata = file_get_contents($this -> logloc);
    echo(str_replace("|"," | ",$this -> logdata));
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
  * @name PutLog
  * @param string $message - FPO copy here
  */
  function PutLog( $message ) {
    $message = $message ."| DATE:: ".now()."| SERVER:: ".$_SERVER["SERVER_NAME"];
    createFile( $this -> logloc, $message."\n",false,true );
    //echo($this -> logdata);
  }

}
?>
