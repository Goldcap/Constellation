<?php


/**
 * SecurityWidget.class.php, Symfony PageWidgets
 * Symfony Page Widget Parser.
 * @author Andy Madsen <amadsen@operislabs.com>
 * @version 1.0 
 * @package com.Operis.PageWidgets
 */

/**
 * Blocks users by IP, using a file-based system of IP and DateTime records.
 */
 
class Security_PageWidget extends Utils_PageWidget
{
  
  /** 
  * Use Mongo Datasource to record pageview frequency
  *   
  * @deprecated
  *	@access public
  * @name checkDOS  
  * @param sfContext $context  
  */
	static function checkDOS( $context ) {
    
    $opts = explode(":",$context -> getDatabaseManager() -> getDatabase( "ttj_metric" )->getParameter("dsn"));
    //$this -> dos = new Mongo();
    $dos = new Mongo( $opts[0] );
    $doscollection = $dos ->selectDB( "dosattack" )->selectCollection( "dosattack" );
    $format = "Y-m-d H:".sprintf("%02d",date("i")).":00.000000";
    $doc = array ("ip_address"=>REMOTE_ADDR(),
                  "hit_hour"=>date($format));
    $doscollection->update(  $doc,
                                    array(
                                    "\$inc" => array("hit_uniques"=>1)),
                                    true);
    $query = array ("ip_address"=>REMOTE_ADDR(),
                  "hit_hour"=>date($format),
                  "hit_uniques"=>array("\$gt"=>30));
                  
    $res = $doscollection-> findOne($query);
    if($res) {
      Security_PageWidget::blockIP();
      //getPageAndDump( "block.html" );
    }
    
  }
  
  /** 
  * Check "BLOCK" directory for text files matching inbound request, and block if found.
  *   
  * @access public
  * @name checkIP  
  */
	static function checkIP() {
    
    if (file_exists(sfConfig::get("app_block_dir") . "/blockIp/" . REMOTE_ADDR())) {
      header("Location: http://".$_SERVER["SERVER_NAME"]."/block.html");
      die();
    }
  }
  
  /** 
  * Add an inbound IP Address to the "BLOCK" directory files
  *   
  * @access public
  * @name blockIP  
  */
	static function blockIP() {
    
    if (left(REMOTE_ADDR(),9) == "69.16.180")
      return false;
    
    if (left(REMOTE_ADDR(),9) == "72.36.7.9")
      return false;

    if (left(REMOTE_ADDR(),9) == "69.175.63")
      return false;

    if (left(REMOTE_ADDR(),10) == "173.236.42")
      return false;
    
    if (left(REMOTE_ADDR(),5) == "74.95")
      return false;
      
    if (left(REMOTE_ADDR(),5) == "74.66")
      return false;
    
    if (left(REMOTE_ADDR(),9) == "10.32.165")
      return false;
      
    if (REMOTE_ADDR() == "138.210.38.107")
      return false;
    
    if (REMOTE_ADDR() == "68.173.230.218")
      return false; 
       
    if (REMOTE_ADDR() == "192.168.2.109")
      return false;
    
    createFile( sfConfig::get("app_block_dir") . "/blockIp/" . REMOTE_ADDR(), now(), true );
    QAMail ("Blocked ".REMOTE_ADDR()." at " . now(),"IP Blocked");
    
  }
  
  /** 
  * Remove an inbound IP Address to the "BLOCK" directory files
  *   
  * @access public
  * @name unblockIP  
  */
	static function unblockIP() {

    unlink( sfConfig::get("app_block_dir") . "/blockIp/" . REMOTE_ADDR() );
    
  }
  
}
?>
