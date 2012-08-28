<?php

/**
 * TestUtil_PageWidget.class.php, Symfony PageWidgets
 * Symfony Page Widget Test Controller.
 * @author Andy Madsen <amadsen@operislabs.com>
 * @version 1.0
 * @package com.Operis.PageWidgets.test
 *	
 **/

include_once(sfConfig::get('sf_lib_dir')."/helper/UserHelper.php");
include_once(sfConfig::get('sf_lib_dir')."/helper/LoginHelper.php");
include_once(sfConfig::get('sf_lib_dir')."/helper/CryptHelper.php");

/**
 * Utils for Lime Unit Tests using YML
 *  
 * Used to control YAML-Based Tests from CLI
 * Be careful about Namespaces, as the PHPUnit_Extensions_SeleniumTestCase
 * Is FULL of methods that will conflict, such as runTest(), doRun(), run(), etc...
 * NOTE:: All Methods in class are Predicated with a Namespace Constraint
 * Except setUp and tearDown
 *		
 */
 
class TestUtil_PageWidget extends Utils_PageWidget {
  
  /** 
  * Inbound Symfony Context
  *   
  * @property  
  * @access public
  * @var sfContext
  * @name $context  
  */
	var $context;
  
	/** 
  * Lime Test 
  *   
  * @property  
  * @access public
  * @var Lime Test
  * @name $t  
  */
	var $t;
  
	/** 
  * Name of the Test Being Run
  *   
  * @property  
  * @access public
  * @var string
  * @name $test_name  
  */
	var $test_name;
  
	/** 
  * Object created from specific Widget
  *   
  * @property  
  * @access public
  * @var PageWidget
  * @name $widget  
  */
	var $widget;
  
	/** 
  * YAML File Representing a specific test
  *   
  * @property  
  * @access public
  * @var YAML
  * @name $yaml_data  
  */
	var $yaml_data;
  
	/** 
  * Array of Fixture Data for GET PARAMS
  *   
  * @property  
  * @access public
  * @var array
  * @name $get_array  
  */
	var $get_array;
  
	/** 
  * Array of Fixture Data for POST PARAMS
  *   
  * @property  
  * @access public
  * @var array
  * @name $post_array  
  */
	var $post_array;
  
	/** 
  * Array of Fixture Data for SESSION PARAMS
  *   
  * @property  
  * @access public
  * @var array
  * @name $session_array  
  */
	var $session_array;
  
	/** 
  * Array of Fixture Data for ACTION PARAMS
  *   
  * @property  
  * @access public
  * @var array
  * @name $action_array  
  */
	var $action_array;
  
	/** 
  * Array of Fixture Data from the Database
  *   
  * @property  
  * @access public
  * @var array
  * @name $dbo_array  
  */
	var $dbo_array;
  
	/** 
  * Widget Vars that are sent to the Widget
  *   
  * @property  
  * @access public
  * @var array
  * @name $wvar_array  
  */
	var $wvar_array;
  
	/** 
  * Page Vars that are sent to the Widget
  *   
  * @property  
  * @access public
  * @var array
  * @name $pvar_array  
  */
	var $pvar_array;
  
	/** 
  * Array of Fixture Data for the sfUser
  *   
  * @property  
  * @access public
  * @var array
  * @name $user_array  
  */
	var $user_array;
  
  
  /** 
  * Propel DB Objects defined in a property array
  *   
  * @property  
  * @access public
  * @var array
  * @name $dbc_objects  
  */
	var $dbc_objects;
  
	/** 
  * Propel DB Objects actually pulled using Propel
  *   
  * @property  
  * @access public
  * @var object
  * @name $dbo_objects  
  */
	var $dbo_objects;
  
  /** 
  * Methods for each Object, and expected vals
  *   
  * @property  
  * @access public
  * @var array
  * @name $dbo_methods  
  */
  var $dbo_methods;
  
  /** 
  * Total Count of tests that have run
  *   
  * @property  
  * @access public
  * @var int
  * @name $test_count  
  */
	var $test_count;
  
  /** 
  * The sfUser Object
  *   
  * @property  
  * @access public
  * @var sfUser
  * @name $user  
  */
	var $user;
  
	/** 
  * Object to hold a specific Lime Test
  *   
  * @property  
  * @access public
  * @var object
  * @name $test  
  */
	var $test;
  
  /** 
  * Should the test be run as a service, or as an embedded widget
  *   
  * @property  
  * @access public
  * @var boolean
  * @name $service_mode  
  */
	var $service_mode;
  
  /** 
  * Class Constructor
  * 
  *	@access public
  * @name __construct  
  * @param sfContext context
  * @param string $test_name
  * @param string $config	  
  */
	public function __construct( $context, $test_name, $config ) {
    
    $this -> context = $context;
    $this -> test_name = $test_name;
    $this -> get_array = array();
    $this -> post_array = array();
    $this -> session_array = array();
    $this -> action_array = array();
    $this -> dbo_array = array();
    $this -> wvar_array = array();
    $this -> pvar_array = array();
    
    $this -> dbo_objects = array();
    $this -> dbo_methods = array();
    
    $this -> yaml_data = null;
    
    $yml = sfConfig::get('sf_lib_dir').'/widgets/'.$this -> test_name.'/conf/'.$config;
    if (file_exists($yml)) {
      $this -> yaml_data = sfYaml::load($yml);
      if (array_key_exists("service",$this -> yaml_data)) {
        $this -> service_mode = true;
      }
    }
    
    parent::__construct( $context );
    
  }
  
  /** 
  * Simulates a User Login if Test Requires it
  * 
  *	@access public
  * @name login   
  */
	public function login() {
    if (is_null($this -> yaml_data)) {
      return;
    }
    
    $vars = $this -> yaml_data;
    if (! is_null($vars["user"])) {
      foreach(array_keys($vars["user"]) as $uservar) {
        $this -> user_array[$uservar] = $vars["user"][$uservar];
      }
      $user = $this->context->getUser();
      $auser = createUser( $this -> context,
                $this -> user_array["fname"],
                $this -> user_array["lname"],
                $this -> user_array["email"],
                $this -> user_array["username"],
                $this -> user_array["password"],
                null,
                $this -> user_array["birthday"] );
      
      cli_text("Test Login: Setting User ".$auser -> User -> getUserId(),"cyan");
      $userobj = $this -> context -> getUser();
      $userobj ->setAttribute("user_id",$auser -> User -> getUserId());
      $userobj ->setAuthenticated(true);
      $userobj ->setAttribute("user_id",$auser -> User -> getUserId());
      $userobj ->setAttribute("user_fullname",$auser -> User -> getUserFullname());
      $userobj ->setAttribute("user_email",$auser -> User -> getUserEmail());
      $userobj ->setAttribute("user_username",$auser -> User -> getUserUsername());
      $ual = unserialize($auser -> User -> getUserUal());
      
      if (($ual) && (count($ual) > 0)) {
        foreach($ual as $auth) {
          $userobj ->addCredential($auth);
        }
      }
          
      $this -> user = $auser -> User;
      
    }
    
  }
  
  /** 
  * Puts data in the database that's required for test. DBC = DataBase Create
  * 
  *	@access public
  * @name createDbFixtures    
  */
	public function createDbFixtures() {
    $vars = $this -> yaml_data;
    if (! is_null($vars["dbc"])) {
      foreach(array_keys($vars["dbc"]) as $dbcvar) {
      eval("\$this -> dbc_objects[\"".$dbcvar."\"] = new ".str_replace(" ","",ucwords(str_replace("_"," ",$dbcvar)))."();");
      foreach($vars["dbc"][$dbcvar]["vars"] as $key => $value) {
        if (substr($value,0,1) == "=") {
          $sval = $this -> getVarVal($value);
        } else {
          $sval = $value;
        }
        eval("\$this -> dbc_objects[\"".$dbcvar."\"] -> ".$key."('".$sval."');");
      }
      eval("\$this -> dbc_objects[\"".$dbcvar."\"] -> save();");
      
      //Update the SOLR Search Engine
      $solr = new SolrManager_PageWidget(null, null, $this -> context);
      $akey = $vars["dbc"][$dbcvar]["key"];
      eval("\$solr -> execute(\"add\",\"".$dbcvar."\",\$this -> dbc_objects[\"".$dbcvar."\"] -> get".str_replace(" ","",ucwords(str_replace("_"," ",$akey)))."());");
      
    }}
  }
  
  /** 
  * Puts specific data values in the Fixtures Array
  * 
  *	@access public
  * @name setFixtures    
  */
	public function setFixtures() {
    if (is_null($this -> yaml_data)) {
      return;
    }
    
    $vars = $this -> yaml_data;
    if (! is_null($vars["get"])) {
    foreach(array_keys($vars["get"]) as $getvar) {
      if ((! is_array($vars["get"][$getvar])) && (substr($vars["get"][$getvar],0,1) == "=")) {
        $this -> get_array[$getvar] = $this -> getVarVal($vars["get"][$getvar]);
      } else {
        $this -> get_array[$getvar] = $vars["get"][$getvar];
      }
    }}
    
    if (! is_null($vars["post"])) {
    foreach(array_keys($vars["post"]) as $postvar) {
      if ((! is_array($vars["post"][$postvar])) && (substr($vars["post"][$postvar],0,1) == "=")) {
        $this -> post_array[$postvar] = $this -> getVarVal($vars["post"][$postvar]);
      } else {
        $this -> post_array[$postvar] = $vars["post"][$postvar];
      }
    }}
    
    if (! is_null($vars["session"])) {
    foreach(array_keys($vars["session"]) as $sessionvar) {
      if ((! is_array($vars["session"][$sessionvar])) && (substr($vars["session"][$sessionvar],0,1) == "=")) {
        $this -> session_array[$sessionvar] = $this -> getVarVal($vars["session"][$sessionvar]);
      } else {
        $this -> session_array[$sessionvar] = $vars["session"][$sessionvar];
      }
    }}
    
    if (! is_null($vars["action"])) {
    foreach(array_keys($vars["action"]) as $actionvar) {
      if ((! is_array($vars["action"][$actionvar])) && (substr($vars["action"][$actionvar],0,1) == "=")) {
        $this -> action_array[$actionvar] = $this -> getVarVal($vars["action"][$actionvar]);
      } else {
        $this -> action_array[$actionvar] = $vars["action"][$actionvar];
      }
    }}
    
    if (! is_null($vars["wvar"])) {
    foreach(array_keys($vars["wvar"]) as $wvar) {
      if ((! is_array($vars["wvar"][$wvar])) && (substr($vars["wvar"][$wvar],0,1) == "=")) {
        $this -> wvar_array[$wvar] = $this -> getVarVal($vars["wvar"][$wvar]);
      } else {
        $this -> wvar_array[$wvar] = $vars["wvar"][$wvar];
      }
    }
    $this -> test_count = $this -> test_count + count($this -> wvar_array);
    }
    
    if (! is_null($vars["pvar"])) {
    foreach(array_keys($vars["pvar"]) as $pvar) {
       if ((! is_array($vars["pvar"][$pvar])) && (substr($vars["pvar"][$pvar],0,1) == "=")) {
        $this -> pvar_array[$pvar] = $this -> getVarVal($vars["pvar"][$pvar]);
      } else {
        $this -> pvar_array[$pvar] = $vars["pvar"][$pvar];
      }
    }
    $this -> test_count = $this -> test_count + count($this -> pvar_array);
    }
    
    if (! is_null($vars["dbo"])) {
    foreach(array_keys($vars["dbo"]) as $dbovar) {
      if ($dbovar != 'vars') {
        $this -> dbo_array[$dbovar] = $vars["dbo"][$dbovar];
        //Each Key will need it's own insert test
        $this -> test_count = $this -> test_count + 1;
        if (isset($vars["dbo"][$dbovar]["vars"])) {
          $this -> dbo_methods[$dbovar] = $vars["dbo"][$dbovar]["vars"];
          $this -> test_count = $this -> test_count + count($vars["dbo"][$dbovar]["vars"]);
        }
      }
    }}
  }
  
  /** 
  * Sets a GET Value for the Fixtures
  * 
  *	@access public
  * @name setGetArrayVar  
  * @param string $name
  * @param string $value
  */
	public function setGetArrayVar( $name, $value ) {
    $this -> get_array[$name] = $value;
  }
  
  /** 
  * Sets a POST Value for the Fixtures
  * 
  *	@access public
  * @name setPostArrayVar  
  * @param string $name
  * @param string $value
  */
	public function setPostArrayVar( $name, $value ) {
    $this -> post_array[$name] = $value;
  }
  
  /** 
  * Sets a SESSION Value for the Fixtures
  * 
  *	@access public
  * @name setSessionArrayVar  
  * @param string $name
  * @param string $value 
  */
	public function setSessionArrayVar( $name, $value ) {
    $this -> session_array[$name] = $value;
  }
  
  /** 
  * Takes Values from the Fixtures array and assigns them to the Widget
  * 
  *	@access public
  * @name assignVars  
  */
	public function assignVars() {
    foreach($this -> get_array as $key => $value) {
      $this -> setgetVar($key,$value);
    }
    foreach($this -> post_array as $key => $value) {
      $this -> setPostVar($key,$value);
    }
    foreach($this -> session_array as $key => $value) {
      $this -> setSessionVar($key,$value);
    }
    foreach($this -> action_array as $key => $value) {
      $this -> setPostVar($key,$value);
    }
  }
  
  /** 
  * Pulls Data Objects from the configuration and puts them in the Fixtures array
  * 
  *	@access public
  * @name createDbObjects  
  */
	public function createDbObjects() {
    if (is_null($this -> yaml_data)) {
      return;
    }
    foreach($this -> dbo_array as $key => $value) {
        
      //Get the film out of the DB
      $sql = "select max(".$value["key"].") from ".$key.";";
      $result = $this -> propelQuery($sql);
      $row = $result->fetch();
      $max = $row[0];
      
      eval("\$this -> dbo_objects[\$key] = ".str_replace(" ","",ucwords(str_replace("_"," ",$key)))."Peer::retrieveByPK(\$max);");
      
    }
  }
  
  /** 
  * Generates a specific value using a "type" nomenclature.
  * 
  *	@access public
  * @name getVarVal  
  * @param string $value
  */
	public function getVarVal( $value ) {
    $arr = explode(":",$value);
    
    switch ($arr[0]) {
      case "=date":
        if ((isset($arr[2])) && (left($arr[2],1) == "+") || (left($arr[2],1) == "-")) {
          $ts = time();
          $mult = (left($arr[2],1)=="-") ? -1 : 1;
          
          switch(substr($arr[2],1,1)) {
            case "H":
              $ts = $ts + ($mult * substr($arr[2],2) * 3600);
              break;
            case "i":
              $ts = $ts + ($mult * substr($arr[2],2) * 60);
              break;
            case "s":
              $ts = $ts + ($mult * substr($arr[2],2));
              break;
            case "D":
              $ts = $ts + ($mult * substr($arr[2],2) * 86400);
            	break;
            case "M":
              $ts = $ts + ($mult * substr($arr[2],2) * 86400 * 30);
              break;
            case "Y":
              $ts = $ts + ($mult * substr($arr[2],2) * 86400 * 365);
              break;
          }
          $arr[2] = $ts;
        
        }
        $tval = formatDate($arr[2],$arr[1]);
        break;
      case "=post":
        $tval = $this -> post_array[$arr[1]];
        break;
      case "=get":
        $tval = $this -> get_array[$arr[1]];
        break;
      case "=session":
        $tval = $this -> session_array[$arr[1]];
        break;
      case "=user":
        eval('$tval = $this -> user -> '.$arr[1].'();');
        return $tval;
        break;
      case "=dbo":
        eval('$tval = $this -> dbo_objects[$arr[2]] -> '.$arr[1].'();');
        return $tval;
        break;
      case "=dbc":
        eval('$tval = $this -> dbc_objects[$arr[2]] -> '.$arr[1].'();');
        return $tval;
        break;
      default;
        throw new Exception("Value not in scope");
        break;
    }
    
    if (isset($arr[3])) {
      cli_text("Applying function '".$arr[3]."' to value '".$tval."'.","cyan");
      eval('$tval = '.$arr[3].'($tval);');
    }
    return $tval;
    
  }
  
  /** 
  * Attempts to insert a value in the database
  * 
  *	@access public
  * @name testInsert  
  */
	public function testInsert() {
    
    foreach( array_keys($this -> dbo_objects) as $key ) {
      //Test the object insert
      $this -> t -> ok($this -> dbo_objects[$key], ucwords($key)." Insert");
      foreach( $this -> dbo_methods[$key] as $vkey => $vvalue) {
        if (substr($vkey,0,1) == "=") {
          eval('$tval = $this -> test -> '.substr($vkey,1).'($this -> dbo_objects[$key]);');
        } else {
          eval('$tval = $this -> dbo_objects[$key] -> '.$vkey.'();');
        }
        if (substr($vvalue,0,1) == "=") {
          $sval = $this -> getVarVal($vvalue);
        } else {
          $sval = $vvalue;
        }
        $this -> t -> is($tval,$sval,$vkey." Insert");
      }
      //Remove the Test Object
      $this -> dbo_objects[$key] -> delete();
    }
  }
  
  /** 
  * Asserts the existence of a specific Widget Var
  * 
  *	@access public
  * @name testWvars  
  */
	public function testWvars() {
    
    foreach( $this -> wvar_array as $vkey => $vvalue) {
      if (substr($vkey,0,1) == "=") {
        eval('$tval = $this -> test -> '.substr($vkey,1).'($this -> widget -> widget_vars);');
      } else {
        eval('$tval = $this -> widget -> widget_vars['.$vkey.'];');
      }
      if (substr($vvalue,0,1) == "=") {
        $sval = $this -> getVarVal($vvalue);
      } else {
        $sval = $vvalue;
      }
      $this -> t -> is($tval,$sval,"'".$vkey."' Widget Var");
    }
  }
  
  /** 
  * Asserts the existence of a specific Page Var
  * 
  *	@access public
  * @name testPvars  
  */
	public function testPvars() {
    
    foreach( $this -> pvar_array as $vkey => $vvalue) {
      if (substr($vkey,0,1) == "=") {
        eval('$tval = $this -> test -> '.substr($vkey,1).'($this -> widget -> widget_vars);');
      } else {
        eval('$tval = $this -> widget -> widget_vars['.$vkey.'];');
      }
      if (substr($vvalue,0,1) == "=") {
        $sval = $this -> getVarVal($vvalue);
      } else {
        $sval = $vvalue;
      }
      $this -> t -> is($tval,$sval,"'".$vkey."' Page Var");
    }
  }
  
  /** 
  * Ends the test and removes test data
  * 
  *	@access public
  * @name end  
  */
	public function end() {
    
    $user = $this->widget->context->getUser();
    $user -> setAuthenticated( false );
    $user -> getAttributeHolder()->clear();
    $user -> shutdown();
    
    if ($this -> user) {
      $this -> user -> delete();
    }
    
    $vars = $this -> yaml_data;
    
    if (count($vars["dbc"]) > 0) {
    foreach(array_keys($vars["dbc"]) as $dbcvar) {
    	//Update the SOLR Search Engine
      $solr = new SolrManager_PageWidget(null, null, $this -> context);
      $key = $vars["dbc"][$dbcvar]["key"];
      eval("\$solr -> execute(\"delete\",\"".$dbcvar."\",\$this -> dbc_objects[\"".$dbcvar."\"] -> get".str_replace(" ","",ucwords(str_replace("_"," ",$key)))."());");
      
      $this -> dbc_objects[$dbcvar] -> delete();
    	
    }}
    
    cli_text("Finished Test for " . $this -> test_name."\n","cyan");
    
  }
  
}
?>
