<?php

/**
 * WTVRUtils.php, Styroform XML Form Controller
 * 
 * Generic functions to extend framework
 * @author Andy Madsen <amadsen@motormeme.com>
 * @version 1.3
 * @package WTVR
 */
// WTVRUtils


/**
* XML Form Utilites for string manipulation and flow control
*/
require_once(dirname(__FILE__).'../../../utils.php');

  /**
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * <code> 
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  * </code>
  * @name putLog
  * @param string $message - FPO copy here
  */
function putLog( $message ) {
/**
* XML Form Utilites for string manipulation and flow control
*/
  $logreader = new WTVRLogReader();
  $logreader -> PutLog($message);
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
  * @name readLog
  */
function readLog() {

/**
* XML Form Utilites for string manipulation and flow control
*/
  $logreader = new WTVRLogReader();
  $logreader -> ReadLog();
  playSound();
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
  * @name playSound
  * @param string $sound  - FPO copy here
  */
function playSound( $sound = "CHINA_1.mp3" ) {
  echo("<script>soundManager.play('mySound0','".$GLOBALS["asseturl"]."/mp3/".$sound."');</script>");
}


function SET_REMOTE_ADDR() {
  if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
    return $_SERVER["HTTP_X_FORWARDED_FOR"];
  } else {
    return $_SERVER["REMOTE_ADDR"];
  }
}

function REMOTE_ADDR() {
  if (isset($_SESSION["REMOTE_ADDR"])) {
    return $_SESSION["REMOTE_ADDR"];
  } elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
    return $_SERVER["HTTP_X_FORWARDED_FOR"];
  } else {
    return $_SERVER["REMOTE_ADDR"];
  }
}

function forTesting() {

  if ((REMOTE_ADDR() == "67.244.88.70") || (REMOTE_ADDR() == "192.168.2.102")) {
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
  * @name createDirectory 
  * @param string $location - FPO copy here
  * @param string $mask - FPO copy here
  */
function createDirectory( $location, $mask = 0777,$group="webusers" ) {
  if (is_dir($location)) {
    return;
  }
  $loc = "";
  $dirtree = explode("/",$location);
  array_shift($dirtree);
  foreach($dirtree as $dir) {
    $loc .= "/".$dir;
    if (! is_dir($loc)) {
      try {
      if (! mkdir($loc,$mask,true)) {
        //die("<br />Directory ".$loc." could not be created by user 'apache'");
      }}catch (Exception $e) {
        //QAMail ("This directory couldn't be created: ".$loc." as part of ". $location, "Directory creation Error WTVRUtils createDirectory()");
      }
    } elseif (! @is_writable  ( $loc  )) {
      //die("<br />Directory ".$loc." is not writable for user 'apache'");
    }
    try {
      setFilesysParams($loc);
    } catch (Exception $e) {
    
    }
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
  * @name createFile  
  * @param string $location - FPO copy here
  * @param string $data - FPO copy here
  * @param string $force - FPO copy here
  * @param string $append - FPO copy here
  */
function createFile( $location, $data, $force=false,$append=false ) {
  if ((! $force) && (! file_exists($location))) {
    $data = "Initialized at " . now() . "\n" . $data;
    if ($append) {
    if (! @file_put_contents($location, $data, FILE_APPEND)) {
      die("File ".$location." could not be appended by user 'apache'");
    }
    } else {
    if (! @file_put_contents($location, $data)) {
      die("File ".$location." could not be created by user 'apache'");
    }}
  } else {
    if ($append) {
    if (! @file_put_contents($location, $data, FILE_APPEND)) {
      die("File ".$location." could not be appended by user 'apache'");
    }
    } else {
      try {
        file_put_contents($location, $data);
      } catch (Exception $e) {
        QAMail ("This file couldn't be created: ". $location, "File creation Error WTVRUtils createFile()");
      }
    }
  }
  try {
    setFilesysParams($loc);
  } catch (Exception $e) {
  
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
  * @name createFile  
  * @param string $location - FPO copy here
  * @param string $data - FPO copy here
  * @param string $force - FPO copy here
  * @param string $append - FPO copy here
  */
function setFilesysParams( $loc,$mask=0777,$user="nginx",$group="webusers" ) {
  //@chmod($loc,$mask);
  //@chown($loc,$user);
  //@chgrp($loc,$group);
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
  * @name createFile  
  * @param string $location - FPO copy here
  * @param string $data - FPO copy here
  * @param string $force - FPO copy here
  * @param string $append - FPO copy here
  */
function writeFile( $filename, $somecontent, $force=false,$append=false ) {
  if ((! $force) && (! file_exists($filename))) {
    $data = "Initialized at " . now() . "\n" .  $somecontent;
    if ($append) {
    if (!$handle = fopen($filename, 'a')) {
         echo "Cannot open file ($filename)";
         exit;
    }
    // Write $somecontent to our opened file.
    if (fwrite($handle, $somecontent) === FALSE) {
        echo "Cannot write to file ($filename)";
        exit;
    }} else {
    if (!$handle = fopen($filename, 'w')) {
         echo "Cannot open file ($filename)";
         exit;
    }
    // Write $somecontent to our opened file.
    if (fwrite($handle, $somecontent) === FALSE) {
        echo "Cannot write to file ($filename)";
        exit;
    }}
  } else {
   if ($append) {
    if (!$handle = fopen($filename, 'a')) {
         echo "Cannot open file ($filename)";
         exit;
    }
    // Write $somecontent to our opened file.
    if (fwrite($handle, $somecontent) === FALSE) {
        echo "Cannot write to file ($filename)";
        exit;
    }
   } else {
    if (!$handle = fopen($filename, 'w')) {
         echo "Cannot open file ($filename)";
         exit;
    }
    // Write $somecontent to our opened file.
    if (fwrite($handle, $somecontent) === FALSE) {
        echo "Cannot write to file ($filename)";
        exit;
    }}
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
  * @name full_copy 
  * @param string $source - FPO copy here
  * @param string $target - FPO copy here
  */
function full_copy( $source, $target ) {
    if ( is_dir( $source ) && (! preg_match("/\.svn/",$source))) {
        @mkdir( $target );
        $d = dir( $source );
        while ( FALSE !== ( $entry = $d->read() ) ) {
            if ( $entry == '.' || $entry == '..' )
            {  continue; }
           
            $Entry = $source . '/' . $entry;           
            if ( is_dir( $Entry ) && (! preg_match("/\.svn/",$source))) {
                full_copy( $Entry, $target . '/' . $entry );
                continue;
            }
            copy( $Entry, $target . '/' . $entry );
        }
        $d->close();
    } elseif (! preg_match("/\.svn/",$source)) {
        copy( $source, $target );
    }
}

  /**
  * http://us.php.net/manual/en/function.rmdir.php
  * This function removes all directories and files with a specific starting point
  * <code> 
  *  deltree("/var/www/html/sites/foo");
  * </code>
  * @f Linux Starting Path Directory;
  */
function deltree( $f ){
    if( is_dir( $f ) && !is_link($f) ){
        foreach( scandir( $f ) as $item ){
            if( !strcmp( $item, '.' ) || !strcmp( $item, '..' ) )
                continue;
            deltree( $f . "/" . $item );
        }
        rmdir( $f );
    } else {
        unlink( $f );
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
  * @name reLocate 
  * @param string $url
  */    
function reLocate( $url = false ) {
  if (isset($GLOBALS["deadpedal"])) {
    return true;
  }
  
  if (! $url) {
    $url = $_SERVER["HTTP_REFERER"];
  }
  preg_match(
'_^(?:([^:/?#]+):)?(?://([^/?#]*))?'.
'([^?#]*)(?:\?([^#]*))?(?:#(.*))?$_',
$url, $uri_parts);
  //preg_match('/rest=[^&]*(.*)/',$_SERVER["QUERY_STRING"],$qs);
  
  //.$qs[1]
  $uri_parts[3] = $uri_parts[3];
  
  header( "Location: ".$uri_parts[3] ) ;
  ?>
  <script type="text/javascript">
    window.location.href="<?php echo(urlencode($uri_parts[3]))?>";
  </script>
  <?php
}

function reDirect($url=false,$qs=false) {
  if (isset($GLOBALS["deadpedal"])) {
    return true;
  }
  
  ($url != "/") ? $url = "/".$url : null;
  ($qs) ? $qs = "&" . $qs : false;
  header( "Location: ".$url.$qs ) ;
  ?>
  <script type="text/javascript">
    window.location.href="<?php echo(urlencode($url.$qs))?>";
  </script>
  <?php
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
  * @name errorDirect
  * @param string $type - FPO copy here
  */
function errorDirect($type="not_access") {
  if ($type == "access") {
    getPageAndDump( "access.html" );
  }
  if ($type == "timeout") {
    getPageAndDump( "timeout.html" );
  }
  getPageAndDump( "error.html" );
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
  * @name getPageAndDump
  * @param string $page - FPO copy here
  */
function getPageAndDump( $page, $message=false ) {
  if (! isset($GLOBALS["deadpedal"])) {
    if (file_exists($page)) {
      $page = file_get_contents($page, true );
      echo ($page);
      if ($message) {
        echo ($message);
      }
      die();
    }
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
  * @name is_obj
  * @param string $object - FPO copy here
  * @param string $check - FPO copy here
  * @param string $strict - FPO copy here
  */
function is_obj( &$object,$check=null,$strict=true )
{
  if (is_object($object)) {
     if ($check == null) {
         return true;
     } elseif ($check == true) {
       return get_class($object);
     } else {
           $object_name = get_class($object);
           return ($strict === true)?
               ( $object_name == $check ):
               ( strtolower($object_name) == strtolower($check) );
     }   
  } else {
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
  * @name sendMail
  * @param string $subject - FPO copy here
  * @param string $body - FPO copy here
  * @param string $to - FPO copy here
  * @param string $text - FPO copy here
  * @param string $from - FPO copy here
  * @param string $fromfname - FPO copy here
  * @param string $fromlname - FPO copy here
  * @param string $tofname - FPO copy here
  * @param string $tolname - FPO copy here
  */
function sendMail($subject,$body,$to,$text=null,$from=null,$fromfname=null,$fromlname=null,$tofname=null,$tolname=null) {
  
  /**
  * XML Form Utilites for string manipulation and flow control
  */
  $mail = new WTVRMail( null );
  $mail -> sendMail($subject,$body,$to,$text,$from,$fromfname,$fromlname,$tofname,$tolname);
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
  * @name queueMail
  * @param string $subject - FPO copy here
  * @param string $body - FPO copy here
  * @param string $to - FPO copy here
  * @param string $text - FPO copy here
  * @param string $from - FPO copy here
  * @param string $fromfname - FPO copy here
  * @param string $fromlname - FPO copy here
  * @param string $tofname - FPO copy here
  * @param string $tolname - FPO copy here
  */
function queueMail($subject,$body,$to,$text=null,$from=null,$fromfname=null,$fromlname=null,$tofname=null,$tolname=null) {

/**
* XML Form Utilites for string manipulation and flow control
*/
  include_once("wtvr/".$GLOBALS["wtvr_version"]."/WTVRMail.php");
  $mail = new WTVRMail( null );
  $mail -> queueMail($subject,$body,$to,$text,$from,$fromfname,$fromlname,$tofname,$tolname);
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
  * @name QAMail  
  * @param string $message - FPO copy here
  * @param string $subject - FPO copy here
  */
function QAMail ( $message, $subject = false, $config=true, $user=false ) {
  
  /**
  * XML Form Utilites for string manipulation and flow control
  */
  $mail = new WTVRMail( null);
  $mail -> debug = false;
  
  if (! $subject)
    $subject = "QAMail on ".formatDate(now(),"pretty");
  
  if ($config) {
  foreach(sfConfig::get("app_site_admin") AS $admin_email){
    $mail -> sendMail($subject,$message,$admin_email,null,null,null,null,null,"Constellation.tv Site Admin");
  }
  }
  
  //$mail -> sendMail($subject,$message,"amadsen@gmail.com",null,null,null,null,null,"Constellation.tv Site Admin");
  if ($user)
    $mail -> sendMail($subject,$message,$user,null,null,null,null,null,"Constellation.tv Site Admin");
  
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
  * @name WTVRcleanString  
  * @param string $str - FPO copy here
  * @param string $encoded - FPO copy here
  */
function WTVRcleanString($str,$encoded=false,$debug=false) {
  
  
  $str = preg_replace('/([\xc0-\xdf].)/se', "'&#' . ((ord(substr('$1', 0, 1)) - 192) * 64 + (ord(substr('$1', 1, 1)) - 128)) . ';'", $str);
  $str = preg_replace('/([\xe0-\xef]..)/se', "'&#' . ((ord(substr('$1', 0, 1)) - 224) * 4096 + (ord(substr('$1', 1, 1)) - 128) * 64 + (ord(substr('$1', 2, 1)) - 128)) . ';'", $str); 
  
  $str = html_entity_decode($str);
  if ($debug)
    dump($str);
  
  $str = str_replace("&euro;&trade;", "'", $str);
  $str = str_replace("Äô", "'", $str);
  $str = str_replace("‚Äô", "'", $str);
  $str = str_replace("Äú", "\"", $str);
  $str = str_replace("‚Äú", "\"", $str);
  $str = str_replace("Äù", "\"", $str);
  $str = str_replace("‚Äù", "\"", $str);
  $newstr = "";
  
  for ($i=0;$i<strlen($str);$i++) {
    $chr = $str{$i};
    $ord = ord($chr);
    if (($ord<31 || $ord>126) && ($ord != 13)) {
      $chr = "";
    }
    $newstr .= $chr;
  }
  
  if ($encoded)
  $str = htmlentities($newstr);
  else
  $str = $newstr;
  
  if (mb_detect_encoding($str) != "ASCII") {
    $str = str_replace("?","",mb_convert_encoding($str,"ASCII"));
    //Sometimes the stuff just doesn't convert, so we'll go char by char
    if (mb_detect_encoding($str) != "ASCII") {
      $aval="";
      for ($st=0;$st<strlen($str);$st++) {
        if(mb_detect_encoding($str[$st])=="ASCII") {
          $aval=$aval.$str[$st];
        }
        //echo ord($this -> documents["item"]["cms_object_teaser"][$i])."\n";
      }
      $str = $aval;
    }
  }
  
  return $str;
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
  * @name isOK 
  * @param string $file
  */
function WTVRFlatString ( $str ) {
  $str = addslashes(str_replace(array('\r\n', '\r', '\n'), ' ', $str));
  return $str;  
}

function WTVRTags( $str ) {
  return strip_tags($str, '<p><a><span><i><strong><bold>');
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
  * @name isOK 
  * @param string $file
  */
function isOK($file) {
  if ($file->isDot()) {
      return false;
   } elseif (isSVN($file)) {
      return false;
   } elseif ($file ->getFilename() == ".DS_Store") {
      return false;
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
  * @name isSVN 
  * @param string $file
  */
  function isSVN($file) {
    if (substr($file ->getFilename(),0,4) == '.svn') {
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
  * @name isSVN 
  * @param string $file
  */
  function getBrowserInfo() {
    //I know it's nuts, but I'm adding the browser to the XML!
    $browserinfo = explode(" ", $_SERVER["HTTP_USER_AGENT"]);
    if (preg_match("/MSIE/",$_SERVER["HTTP_USER_AGENT"])) {
      $browser[0] = "Explorer";
      $browser[1] = str_replace(";","",$browserinfo[3]);
    } elseif (preg_match("/Firefox/",$_SERVER["HTTP_USER_AGENT"])) {
      $browser[0] = "Firefox";
      $browserparse = explode("/",$browserinfo[9]);
      $browser[1] = $browserparse[1];
    } elseif (preg_match("/Safari/",$_SERVER["HTTP_USER_AGENT"])) {
      $browser[0] = "Safari";
      preg_match("/Version\/([0-9].[0-9].[0-9])/",$_SERVER["HTTP_USER_AGENT"],$matches);
      $browser[1] = $matches[1];
    } else {
      $browser[0] = "Other";
      if (isset($browserinfo[12]))
      $browserparse = explode("/",$browserinfo[12]);
      if (isset($browserparse[1]))
      $browser[1] = $browserparse[1];
    }
    $browser[2] = $browserinfo;
    $browser[3] = round($browser[1]);
    //dump($_SERVER["HTTP_USER_AGENT"]);
    if (preg_match("/Windows NT/",$_SERVER["HTTP_USER_AGENT"])) {
      $browser[4] = "Windows XP";
    } elseif (preg_match("/Linux/",$_SERVER["HTTP_USER_AGENT"])) {
      $browser[4] = "Linux";
    } elseif (preg_match("/Win 9/",$_SERVER["HTTP_USER_AGENT"])) {
      $browser[4] = "Windows 9x";
    } elseif (preg_match("/Mac/",$_SERVER["HTTP_USER_AGENT"])) {
      $browser[4] = "mac";
    }
    
    return $browser;
  }

function getCountries() {
  return '
  <select size="1" name="country" value="" class="countryselect">					
          <option value="">Select One
          </option>			
          <option value="AL">Albania
          </option>
          <option value="DZ">Algeria
          </option>
          <option value="AD">Andorra
          </option>
          <option value="AO">Angola
          </option>
          <option value="AI">Anguilla
          </option>
          <option value="AG">Antigua and Barbuda
          </option>
          <option value="AR">Argentina
          </option>
          <option value="AM">Armenia
          </option>
          <option value="AW">Aruba
          </option>
          <option value="AU">Australia
          </option>
          <option value="AT">Austria
          </option>
          <option value="AZ">Azerbaijan
          </option>
          <option value="BS">Bahamas
          </option>
          <option value="BH">Bahrain
          </option>
          <option value="BD">Bangladesh
          </option>
          <option value="BB">Barbados
          </option>
          <option value="BY">Belarus
          </option>
          <option value="BE">Belgium
          </option>
          <option value="BZ">Belize
          </option>
          <option value="BJ">Benin
          </option>
          <option value="BM">Bermuda
          </option>
          <option value="BT">Bhutan
          </option>
          <option value="BO">Bolivia
          </option>
          <option value="BA">Bosnia Herzegovina
          </option>
          <option value="BW">Botswana
          </option>
          <option value="BR">Brazil
          </option>
          <option value="VG">British Virgin Islands
          </option>
          <option value="BN">Brunei Darussalam
          </option>
          <option value="BG">Bulgaria
          </option>
          <option value="BF">Burkina Faso
          </option>
          <option value="MM">Burma (Myanmar)
          </option>
          <option value="BI">Burundi
          </option>
          <option value="KH">Cambodia
          </option>
          <option value="CM">Cameroon
          </option>
          <option value="CA">Canada
          </option>
          <option value="CV">Cape Verde
          </option>
          <option value="KY">Cayman Islands
          </option>
          <option value="CF">Central African Rep.
          </option>
          <option value="TD">Chad
          </option>
          <option value="CL">Chile
          </option>
          <option value="CN">China
          </option>
          <option value="CO">Colombia
          </option>
          <option value="CG">Congo, Democratic Republic of
          </option>
          <option value="CD">Congo, Republic of the (Brazza
          </option>
          <option value="CK">Cook Islands (New Zealand)
          </option>
          <option value="CR">Costa Rica
          </option>
          <option value="CI">C&ocirc;te d\'Ivoire
          </option>
          <option value="HR">Croatia
          </option>
          <option value="CY">Cyprus
          </option>
          <option value="CZ">Czech Republic
          </option>
          <option value="DK">Denmark
          </option>
          <option value="DJ">Djibouti
          </option>
          <option value="DM">Dominica
          </option>
          <option value="DO">Dominican Republic
          </option>
          <option value="EC">Ecuador
          </option>
          <option value="EG">Egypt
          </option>
          <option value="SV">El Salvador
          </option>
          <option value="GQ">Elobey Islands (Equatorial Gui
          </option>
          <option value="ER">Eritrea
          </option>
          <option value="EE">Estonia
          </option>
          <option value="ET">Ethiopia
          </option>
          <option value="FO">Faroe Islands
          </option>
          <option value="FJ">Fiji
          </option>
          <option value="FI">Finland
          </option>
          <option value="FR">France
          </option>
          <option value="GF">French Guiana
          </option>
          <option value="PF">French Oceania (French Polynes
          </option>
          <option value="GA">Gabon
          </option>
          <option value="GM">Gambia
          </option>
          <option value="GE">Georgia, Republic of
          </option>
          <option value="DE">Germany
          </option>
          <option value="GH">Ghana
          </option>
          <option value="GI">Gibraltar
          </option>
          <option value="GR">Greece
          </option>
          <option value="GL">Greenland
          </option>
          <option value="GD">Grenada
          </option>
          <option value="GP">Guadeloupe
          </option>
          <option value="GU">Guam
          </option>
          <option value="GT">Guatemala
          </option>
          <option value="GN">Guinea
          </option>
          <option value="GW">Guinea-Bissau
          </option>
          <option value="GY">Guyana
          </option>
          <option value="HT">Haiti
          </option>
          <option value="HN">Honduras
          </option>
          <option value="HK">Hong Kong
          </option>
          <option value="HU">Hungary
          </option>
          <option value="IS">Iceland
          </option>
          <option value="IN">India
          </option>
          <option value="ID">Indonesia
          </option>
          <option value="IE">Ireland
          </option>
          <option value="IL">Israel
          </option>
          <option value="IT">Italy
          </option>
          <option value="JM">Jamaica
          </option>
          <option value="JP">Japan
          </option>
          <option value="JO">Jordan
          </option>
          <option value="KZ">Kazakhstan
          </option>
          <option value="KE">Kenya
          </option>
          <option value="KW">Kuwait
          </option>
          <option value="KG">Kyrgyzstan
          </option>
          <option value="LV">Latvia
          </option>
          <option value="LB">Lebanon
          </option>
          <option value="LS">Lesotho
          </option>
          <option value="LI">Liechtenstein
          </option>
          <option value="LT">Lithuania
          </option>
          <option value="LU">Luxembourg
          </option>
          <option value="MO">Macao
          </option>
          <option value="MK">Macedonia
          </option>
          <option value="MG">Madagascar
          </option>
          <option value="MW">Malawi
          </option>
          <option value="MY">Malaya (Malaysia)
          </option>
          <option value="MV">Maldives
          </option>
          <option value="ML">Mali
          </option>
          <option value="MT">Malta
          </option>
          <option value="MH">Marshall Islands, Republic of
          </option>
          <option value="MQ">Martinique
          </option>
          <option value="MR">Mauritania
          </option>
          <option value="MU">Mauritius
          </option>
          <option value="MX">Mexico
          </option>
          <option value="FM">Micronesia, Federated States o
          </option>
          <option value="MD">Moldova
          </option>
          <option value="MC">Monaco (France)
          </option>
          <option value="MN">Mongolia
          </option>
          <option value="MS">Montserrat
          </option>
          <option value="MA">Morocco
          </option>
          <option value="MZ">Mozambique
          </option>
          <option value="MM">Myanmar (Burma)
          </option>
          <option value="NA">Namibia
          </option>
          <option value="NP">Nepal
          </option>
          <option value="NL">Netherlands
          </option>
          <option value="AN">Netherlands Antilles
          </option>
          <option value="NC">New Caledonia
          </option>
          <option value="NZ">New Zealand
          </option>
          <option value="NI">Nicaragua
          </option>
          <option value="NE">Niger
          </option>
          <option value="NG">Nigeria
          </option>
          <option value="NO">Norway
          </option>
          <option value="OM">Oman
          </option>
          <option value="PK">Pakistan
          </option>
          <option value="PW">Palau
          </option>
          <option value="PA">Panama
          </option>
          <option value="PG">Papua New Guinea
          </option>
          <option value="PY">Paraguay
          </option>
          <option value="PE">Peru
          </option>
          <option value="PH">Philippines
          </option>
          <option value="PL">Poland
          </option>
          <option value="PT">Portugal
          </option>
          <option value="QA">Qatar
          </option>
          <option value="RE">Reunion
          </option>
          <option value="RO">Romania
          </option>
          <option value="RU">Russia
          </option>
          <option value="RW">Rwanda
          </option>
          <option value="KN">Saint Kitts (St. Christopher a
          </option>
          <option value="LC">Saint Lucia
          </option>
          <option value="VC">Saint Vincent and the Grenadin
          </option>
          <option value="MP">Saipan, Northern Mariana Islan
          </option>
          <option value="AS">Samoa, American
          </option>
          <option value="SM">San Marino
          </option>
          <option value="SA">Saudi Arabia
          </option>
          <option value="SN">Senegal
          </option>
          <option value="RS">Serbia
          </option>
          <option value="SC">Seychelles
          </option>
          <option value="SL">Sierra Leone
          </option>
          <option value="SG">Singapore
          </option>
          <option value="SK">Slovak Republic
          </option>
          <option value="SI">Slovenia
          </option>
          <option value="ZA">South Africa
          </option>
          <option value="KR">South Korea (Korea, Republic o
          </option>
          <option value="ES">Spain
          </option>
          <option value="LK">Sri Lanka
          </option>
          <option value="SR">Suriname
          </option>
          <option value="SZ">Swaziland
          </option>
          <option value="SE">Sweden
          </option>
          <option value="CH">Switzerland
          </option>
          <option value="SY">Syrian Arab Republic
          </option>
          <option value="TW">Taiwan
          </option>
          <option value="TZ">Tanzania
          </option>
          <option value="TH">Thailand
          </option>
          <option value="TG">Togo
          </option>
          <option value="TT">Trinidad and Tobago
          </option>
          <option value="TN">Tunisia
          </option>
          <option value="TR">Turkey
          </option>
          <option value="TM">Turkmenistan
          </option>
          <option value="TC">Turks and Caicos Islands
          </option>
          <option value="UG">Uganda
          </option>
          <option value="UA">Ukraine
          </option>
          <option value="AE">United Arab Emirates
          </option>
          <option value="GB">United Kingdom (Great Britain)
          </option>
          <option value="US" selected="selected">United States
          </option>
          <option value="UY">Uruguay
          </option>
          <option value="UZ">Uzbekistan
          </option>
          <option value="VU">Vanuatu
          </option>
          <option value="VA">Vatican City
          </option>
          <option value="VE">Venezuela
          </option>
          <option value="VN">Vietnam
          </option>
          <option value="VI">Virgin Islands U.S.
          </option>
          <option value="WF">Wallis and Futuna Islands
          </option>
          <option value="YE">Yemen
          </option>
          <option value="ZM">Zambia
          </option>
          <option value="ZW">Zimbabwe
          </option>				
        </select>';
}

/**
* Using styroform XSL, we can construct a datetime element
* Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
* <code> 
*  $fpo = something();
*  $fpo = something();
*  $fpo = something();
*  $fpo = something();
* </code>
* @name sfDateTime
* @param string $elementname - FPO copy here
*/
function validDateTime( $month, $day, $year, $hour=null, $minute=null, $second = null) {
  $month = (strlen($month) > 0) ? $month : "01";
  $day = (strlen($day) > 0) ? $day : "01";
  $year = (strlen($year) > 0) ? $year : "1900";
  $hour = (strlen($hour) > 0) ? $hour : "00";
  $minute = (strlen($minute) > 0) ? $minute : "00";
  $second =  (strlen($second) > 0) ? $second : "00";
  $thetime = ($month . "/" . $day . "/" . $year . " " . $hour . ":" . $minute . ":" . $second);
  
  if (strlen(preg_replace("[/\/:\s0/]","",$thetime)) == 0) {
    return false;
  } else {
    return $thetime;
  }
}
