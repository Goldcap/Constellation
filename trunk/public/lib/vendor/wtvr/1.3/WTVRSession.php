<?php

/**
 * WTVRSession.php, Styroform XML Form Controller
 * 
 * XML Form Generator and Validator
 * in-code documentation through DocBlocks and tags.
 * @author Andy Madsen <amadsen@motormeme.com>
 * @version 1.2
 * @package XMLForm
 */
// WTVRSession

/**
* XML Form Utilites for string manipulation and flow control
*/
include_once("globals/globals.php");

/**
* XML Form Utilites for string manipulation and flow control
*/
include_once("utils.php");

/**
 * The Form Controller class, responsible for drawing the form, calling the validator, adding "Items" and "ItemLists", and navigating through formsets
 * Creates the Message Body from a specific template
 * And puts it in the mail class Property "body"
 * @package WTVRSession
 * @subpackage classes
 */
class WTVRSession {
    
   /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $duration  
  */
    var $duration;
   
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $host 
  */
    var $host;
    
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $username 
  */
    var $username;
    
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $password
  */
    var $password;
    
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $database
  */
    var $database;
    
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $life_time
  */
    var $life_time;
    
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $connection_id
  */
    var $connection_id;
    
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $result  
  */
    var $result;
    
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $htmlentities
  */
    var $htmlentities;
  	
  	  
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
	  
    $this->duration = get_cfg_var("session.gc_maxlifetime");
    
    $this -> host = $GLOBALS["dbip"];
		$this -> username = $GLOBALS["user"];
		$this -> password = $GLOBALS["pass"];
		$this -> database = $GLOBALS["database"]; 
    
    $this -> connection_id = mysql_connect($GLOBALS["dbip"], $GLOBALS["user"], $GLOBALS["pass"]);
    
    if (! $this -> connection_id) {
      die("Connect incorrect, check your dadoo settings.");
    }
    
		mysql_select_db($GLOBALS["database"], $this -> connection_id);
    
    /*
    $this -> connection_id = mysql_connect("localhost", "amadsen", "1hsvy5qb");
		mysql_select_db("whitman", $this -> connection_id);
    */
    
    // Register this object as the session handler
      session_set_save_handler( 
        array( &$this, "open" ), 
        array( &$this, "close" ),
        array( &$this, "read" ),
        array( &$this, "write"),
        array( &$this, "destroy"),
        array( &$this, "gc" )
      );
    
   }
   
  /**
  * Don't need to do anything. Just return TRUE.
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * <code> 
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  * </code>
  * @name open 
  * @param string $save_path - FPO copy here
  * @param string $session_name - FPO copy here
  */
   function open( $save_path, $session_name ) {
      global $sess_save_path;

      $sess_save_path = $save_path;


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
  * @name close
  */
   function close() {
   
     return true;
   
   }
   
  /**
  * Set empty result
  * Fetch session data from the selected database
  * <code> 
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  * </code>
  * @name read
  * @param string $id - FPO copy here
  */
   function read($id) {
      // Set empty result
      $data = '';

      $time = time();

      $newid = mysql_real_escape_string($id);
      
      if ($newid == '') {
        die("'mysql_real_escape_string' returned null in the session manager... Session is dead.");
      }
      
      $sql = "SELECT `session_data` FROM `wtvr_session` WHERE
             `session_id` = '".$newid."' AND `session_expires` > ".$time;
      
      $rs = mysql_query($sql);                           
      $a = mysql_num_rows($rs);
      
      if($a > 0) {
        $row = mysql_fetch_assoc($rs);
        $data = $row['session_data'];
      }
      
      return $data;
   }
   
  /**
  * Build query    
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * <code> 
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  * </code>
  * @name write 
  * @param string $id - FPO copy here
  * @param string $data - FPO copy here
  */
   function write($id,$data) {
                  
      $time = time() + $this->duration;
      $mod = formatDate(null,'TS');
      
      $newid = mysql_real_escape_string($id);
      $newdata = mysql_real_escape_string($data);

      $sql = "REPLACE `wtvr_session`
              (`session_id`,`session_data`,`session_expires`,`ip_address`, `date_modified`) VALUES('".$newid."', '".$newdata."', ".$time.", '".REMOTE_ADDR()."', '".$mod."')";

      $rs = mysql_query($sql);

      return true;
      
   }
   
  /**
  * Build query
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * <code> 
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  * </code>
  * @name destroy
  * @param string $sessID - FPO copy here
  */
   function destroy($sessID) {
      $newid = mysql_real_escape_string($id);
      $mod = formatDate(null,'TS');
      
      $sql = "UPDATE wtvr_session set date_ended = '".$mod."' WHERE session_id = '".$newid."'";

      mysql_query($sql);

     return true;
   }
   
  /**
  * delete old sessions
  * $this -> data_insert("DELETE FROM sessions WHERE session_expires < ".time());
  * <code> 
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  * </code>
  * @name gc
  */
   function gc() {
   
   }
}
?>
