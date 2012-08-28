<?php

include_once("dadoo/clsMySQLAbstract.php");
include_once("PHPCLI/clsCLI.php");


/* TODO:: The postfix_admin platform doesn't actually ever create accounts, we'd need to extend this*/
/* TODO:: The mailtable platform doesn't actually ever do forwarding, we'd need to extend this*/

class MailUser {
  
  var $domain;
  var $database;
  var $platform;
  var $mailhome;
  var $createaccount;
  
  function __construct() {
    $this -> domain = $_SERVER["SERVER_NAME"];
    
    $this -> platform = (isset($GLOBALS["mailuser_platform"])) ? $GLOBALS["mailuser_platform"] : "mailtable";
    $this -> mailhome = (isset($GLOBALS["mailuser_home"])) ? $GLOBALS["mailuser_home"] : "/home/vmail";
    $this -> createaccount = (isset($GLOBALS["mailuser_create"])) ? $GLOBALS["mailuser_create"] : true;
    
    if ($this -> platform == "mailtable") {
      $this -> database = "mail";
    } elseif ($this -> platform == "postfix_admin") {
      $this -> database = "postfix";
    }
    
  }
  
  function addEmailUser( $username, $password , $forward = null ) {
    $connection = new MySQLAbstract();
    $connection -> database = $this -> database;
    
    if ($this -> platform != "mailtable") {
      $query = "Select address FROM `alias` WHERE `address` = '".$username."@".$this -> domain."';";
    } else {
      $query = "Select email FROM `users` WHERE `email` = '".$username."@".$this -> domain."';";
    }
    $result = $connection -> data_query($query);
    
    if ($result["dbvalues"] == "") {
      if ($this -> platform != "mailtable") {
        $query = "INSERT INTO `alias` (`address`, `goto`,  `domain`, `created`, `modified`, `active`) VALUES ('".$username."@".$this -> domain."','".$forward."', '".$this -> domain."','".now()."','".now()."',1);";
      } else {
        $query = "INSERT INTO `users` (`email`, `password`, `quota`, `maildir`) VALUES ('".$username."@".$this -> domain."', ENCRYPT('".$password."'), 10485760,'".$this -> domain."/".$username."/');";
      }
      $connection -> data_insert($query);
    }
    
    if ($this -> createaccount) {
      $this -> queueAddMailUser($username);
    }
  }
  
  function deleteEmailUser( $username ) {
    $connection = new MySQLAbstract();
    $connection -> database = $this -> database;
    if ($this -> platform != "mailtable") {
      $query = "delete `alias` where `address` = '".$username."@".$this -> domain."';";
    } else {
      $query = "delete FROM `users` WHERE `email` = '".$username."@".$this -> domain."';";
    }
    $result = $connection -> data_insert($query);
    if ($this -> createaccount) {
      $this -> queueDeleteAddMailUser($username);
    }
  }
  
  function moveEmailUser( $username, $newname, $forward = null ) {
    $connection = new MySQLAbstract();
    $connection -> database = $this -> database;
    //Check for old username
    if ($this -> platform != "mailtable") {
      $query = "Select address FROM `alias` WHERE `address` = '".$username."@".$this -> domain."';";
    } else {
      $query = "Select email FROM `users` WHERE `email` = '".$username."@".$this -> domain."';";
    }
    $result = $connection -> data_query($query);
    if (! $result["dbvalues"] == "") {
      //Check for new username
      if ($this -> platform != "mailtable") {
        $query = "Select address FROM `alias` WHERE `address` = '".$newname."@".$this -> domain."';";
      } else {
        $query = "Select email FROM `users` WHERE `email` = '".$newname."@".$this -> domain."';";
      }
      $result = $connection -> data_query($query);
      //Update Record
      if ($result["dbvalues"] == "") {
        if ($this -> platform != "mailtable") {
          $query = "UPDATE `alias` set `address` = '".$newname."@".$this -> domain."', `goto` = '".$forward."' WHERE address = '".$username."@".$this -> domain."';";
        } else {
          $query = "UPDATE `users` set `email` = '".$newname."@".$this -> domain."' WHERE email = '".$username."@".$this -> domain."';";
        }
        $connection -> data_insert($query);
      }
    }
    if ($this -> createaccount) {
      $this -> queueMoveMailUser($username, $newname);
    }
  }
  
  function changeEmailUser( $username, $forward = null ) {
    $connection = new MySQLAbstract();
    $connection -> database = $this -> database;
    //Check for old username
    if ($this -> platform != "mailtable") {
      $query = "Select address FROM `alias` WHERE `address` = '".$username."@".$this -> domain."';";
    } else {
      $query = "Select email FROM `users` WHERE `email` = '".$username."@".$this -> domain."';";
    }
    $result = $connection -> data_query($query);
    if (! $result["dbvalues"] == "") {
      if ($this -> platform != "mailtable") {
        $query = "UPDATE `alias` set `goto` = '".$forward."' WHERE address = '".$username."@".$this -> domain."';";
      }
      $connection -> data_insert($query);
    }
    if ($this -> createaccount) {
      $this -> queueMoveMailUser($username, $newname);
    }
  }
  
  function queueAddMailUser( $username ) {
    file_put_contents( $GLOBALS['binroot']."adduser/".$username, now() );
  }
  
  function queueDeleteMailUser( $username ) {
    file_put_contents( $GLOBALS['binroot']."deleteuser/".$username, now() );
  }
  
  function queueMoveMailUser( $username, $newname ) {
    file_put_contents( $GLOBALS['binroot']."moveuser/".$username, $newname."\n" );
  }
  
  function __destruct() {
  
  }
}
?>
