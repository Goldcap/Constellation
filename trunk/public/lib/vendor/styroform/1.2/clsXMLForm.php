<?php

/**
 * clsXMLForm.php, Styroform XML Form Controller
 * XML Form Generator and Validator.
 * @author Andy Madsen <amadsen@operislabs.com>
 * @version 1.2
 * @package com.Operis.Styroform 
 */

/**
 * Responsible for controlling the form, drawing, calling the validator, adding "Items" and "ItemLists", and navigating through formsets.
 */
class XMLForm extends XMLFormUtils {
  
  /** 
  * Flag to return transformed forms as XML Objects
  * @property  
  * @access public
  * @var boolean
  * @name $returnxml  
  */
	var $returnxml;
  
  /** 
  * Array of XML Forms in an ordered FormSet
  * @property  
  * @access public
  * @var array
  * @name $docArray  
  */
  var $docArray;
  
	/** 
  * Retains the number of the currently displayed form in the Formset
  * Starts at zero  
  * @access public
  * @var integer
  * @name $page  
  */
  var $page;
  
	/** 
  * Retains the name of the current form controlled by this object
  * Allows for multiple form controllers on a specific page. 
  * All forms within a formset should have the same name  
  * @access public
  * @var string
  * @name $name 
  */
  var $name;
  
  /** 
  * Object container for the XML Session configuration
  * @access public
  * @var XMLAbstract Object
  * @name $objSessionDoc
  */
  var $objSessionDoc;
  
	/** 
  * File location of the XML Session configuration
  * @access public
  * @var string
  * @name $theSessionDoc
  */
  var $theSessionDoc;
  
	/** 
  * Array of Session variables that are allowed to translate
  * to form variables, parsed from @link $objSessionDoc
  * @access public
  * @var array
  * @name $session_vars
  */
  var $session_vars = array();
  
   /** 
  * Object container for the current Form, as an XML Object
  * @access public
  * @var XMLAbstract Object
  * @name $objXMLDoc
  */
  var $objXMLDoc;
  
	/** 
  * Object container for the current Form XSL Transformation Document
  * @access public
  * @var XSLAbstract Object
  * @name $objXMLDoc
  */
  var $objXSLDoc;
  
	/** 
  * Object container for the XML Validators configuration
  * @access public
  * @var XMLAbstract Object
  * @name $objValidateDoc
  */
  var $objValidateDoc;
  
	/** 
  * File location of the current XML Form
  * @access public
  * @var string
  * @name $theXMLDoc
  */
  var $theXMLDoc;
  
	/** 
  * File location of the current XSL Transformation Document
  * @access public
  * @var string
  * @name $theXSLDoc
  */
  var $theXSLDoc;
  
	/** 
  * Status of the current XML Form Document
  * @access public
  * @var boolean
  * @name $isloaded
  */
  var $isloaded;
  
  /** 
  * Status of the current XML Form Validation
  * @deprecated Since v.8
  * @access public
  * @var string
  * @name $cookiename
  */
  var $cookiename;
  
	/** 
  * Status of the current XML Form Validation
  * @access public
  * @var boolean
  * @name $isvalidated
  */
  var $isvalidated;
  
	/** 
  * Control Variable
  * allowing form controllers to explicitly state that the form
  * is not validated. used for error generation    
  * @access public
  * @var boolean
  * @name $isvalidated
  */
  var $validated;
  
	/** 
  * Indicator for FormSet status, true means form is not at position 0 or end position
  * @access public
  * @var boolean
  * @name $isprocess_step
  */
  var $isprocess_step;
  
	/** 
  * Configuration variable read from current form, declaring whether to add HTTP GET variables to form as hidden variables
  * @access public
  * @var boolean
  * @name $addget_values
  */
  var $addget_values;
  
	/** 
  * Configuration variable read from current form, declaring whether to add HTTP POST variables to form as hidden variables
  * @access public
  * @var boolean
  * @name $addpost_values
  */
  var $addpost_values;
  
	/** 
  * Configuration variable read from current form, declaring whether to force population of hidden values from an "Item", even if the form element isn't already in the form
  * @access public
  * @var boolean
  * @name $appenditem_values
  */
  var $appenditem_values;
  
  /** 
  * Append Timestamp to XML Form Objext
  * @deprecated Timestamps are appended now by default
  * @access public
  * @var string
  * @name $settimestamp
  */
  var $settimestamp;
  
	/** 
  * Object List of element types that require more than one form element, i.e. day/month/year (3)
  * @deprecated Compounded form elements are defined in StyroformTypes
  * @access public
  * @var array
  * @name $theCompounds
  */
  var $theCompounds;
  
	/** 
  * Configuration variable read from current form, declaring whether to output the form as formelements or formvariables
  * Not currently supported  
  * @access public
  * @var string
  * @name $showform
  */
  var $showform;
  
	/** 
  * Optional Array containing the name and XMLDocument List for the Formset, read generally from either $GLOBALS or from within the WTVR Module, if WTVR
  * @access public
  * @var array
  * @name $formsettings
  */
  var $formsettings;
  
  /** 
  * Optional XML Root Node Variable, for forms inside of existing xml schemas
  * @access public
  * @var string
  * @name $ROOTNODE
  */
  var $ROOTNODE;
  
  /** 
  * Removes the doctype declaration from a returned XML Object
  * @access public
  * @var boolean
  * @name $omit_doctype
  */
  var $omit_doctype;
  
  /** 
  * Puts execution in debug mode
  * @access public
  * @var boolean
  * @name $debug
  */
  var $debug;
  
  /** 
  * Puts the validator in Debug mode
  * @access public
  * @var boolean
  * @name $validate_debug
  */
  var $validate_debug;
  
  /** 
  * N-Dimensional Array for adding Lists to the XML Tree,
  * used in CRUD operations.
  *   
  * @access public
  * @var array
  * @name $itemlist
  */
  var $itemlist;
  
  /** 
  * 2-Dimensional Array for adding values to the Form
  * The first element being the item name, 
  * the second being the value
  
  * @access public
  * @var array
  * @name $item
  */
  var $item;
  
  /** 
  * Modifications to specific elements that are performed after the dom has been procesed.
  * For instance, if you need to remove an element, or add an attribute, rather than adding it to the Object
  * Queue that in the "Mods" and it'll be processed after the various other args are applied	   
  
  * @access public
  * @var array
  * @name $mods
  */
  var $mods;
  
  /** 
  * A generic array in which to place other form elements.
  * @deprecated  
  * @access public
  * @var boolean
  * @name $container
  */
  var $container;
  
  /** 
  * Hidden Values that match form element names aren't added by default, this boolean will force that
  * @access public
  * @var boolean
  * @name $item_forcehidden
  */
  var $item_forcehidden;
  
  /** 
  * List of elements to skip in validation from the Post Vars
  * @deprecated  
  * @access public
  * @var array
  * @name $skip_missing_post
  */
  var $skip_missing_post;
  
  /** 
  * An array of elements that should be removed from the XML Form Object
  * @access public
  * @var array
  * @name $items_to_remove
  */
  var $items_to_remove;
  
  /** 
  * An Array of "lookup" arrays for Select Lists to pull from, if not in XML or Database Lookup  
  * @access public
  * @var array
  * @name $dynarray
  */
  var $dynarray = array();
  
  /** 
  * A list of element types that will have array options generated for them
  * @access public
  * @var array
  * @name $types_array
  */
  var $types_array = array();
  
  /** 
  * Configuration variable for XML Return Object
  * @access public
  * @var boolean
  * @name $isxml
  */
  var $isxml;
  /** 
  * Configuration variable declaring the parent module name from within parent Framework
  * @access public
  * @var string
  * @name $module
  */
  var $module;
  
  /** 
  * Configuration variable declaring the base location of XML Assets
  * @access public
  * @var string
  * @name $assetlocation
  */
  var $assetlocation;
  
  /** 
  * Unclear what this is, deprecated
  * @deprecated  
  * @access public
  * @var string
  * @name $styroform_result
  */
  var $styroform_result;
  
  /** 
  * Determines the form page increment
  * @deprecated 
  *	@access public
  * @var int
  * @name $step
  */
  var $step;
  
  
  /**
  * Form Constructor, determines the Formset and other globals using the $formsettings or from the formconf.php file,
  * which is discovered using the conf as it's location.
  
	* @name constructor  
  * @param array $formsettings  - Array with both Formset, and XSL Doc
  * @param string $conf - Name of the WTVR Module, defaults to null
  */
  function __construct($formsettings=null,$conf="formconf.php"){
    
    if ($formsettings != null) {
      $this -> widget = $formsettings;
      $this -> formsettings = include("lib/widgets/".$formsettings."/conf/".$conf);
    } else {
			$this -> formsettings = include($conf);	
		}
    
    $this -> isxml = false;
    
    $this -> page = 0;
    $this -> step = 1;
    $this -> debug = 0;
    $this -> validate_debug = 0;
    $this -> isloaded = false;
    $this -> show_form = true;
    $this -> styroform_result = false;
    $this -> item_forcehidden = false;
    $this -> skip_missing_post = false;
    $this -> items_to_remove = array();
    $this -> omit_doctype = true;
    $this -> assetlocation = "../lib/vendor/styroform/1.2/";  
    if ($this -> formsettings) {
      $this -> name = $this -> formsettings['name'];
      $this -> docArray = $this -> formsettings['XMLDocs'];
      $this -> theXSLDoc = $this -> formsettings['XSLDoc'];
      $this -> ROOTNODE = (isset($this -> formsettings['ROOTNODE'])) ? $this -> formsettings['ROOTNODE'] : null;
      $this -> page = ((isset($_POST["styropage"])) && ($this ->name == $_POST["styroname"])) ? $_POST["styropage"] : 0;
      $this -> theXMLDoc = $this -> docArray[$this -> page];
    	
		} else {
      //die("No Form Settings Found ");
    }
    
    //default multiple-value element types
    $this -> types_array = array("selectdb","checkdb","displaydb","selectquery","radiodb","displayselect","selectdisplay");
 
    $this -> setVars();
    
    $this -> objXMLDoc = new XML();
    $this -> objXSLDoc = new XSL();
    
    $this -> isvalidated = false;
    $this -> validated = true;
    $this -> isprocess_step = false;
    
    if ((! $this -> formsettings) && (isset($_POST["styroname"]))) {
      $this -> page = (($this ->name == $_POST["styroname"]) && (isset($_POST["styropage"]))) ? $_POST["styropage"] : 0;
    }
  }
  
  /**
  * Reads the Session Variable Configuration Document, and assigns them to an array
  * The format of this file is below, and it should be located in a secure location along the php include path ("xml/StyroformSessionVars.xml"). 
  * 	Any session variables named in this document will be added to the form by default.
  * @name setVars
  */
  function setVars() {
  	$this -> theSessionDoc = dirname(__FILE__)."/xml/StyroformSessionVars.xml";
    $this -> objSessionDoc = new XML();
  	$this -> objSessionDoc -> loadXML($this -> theSessionDoc );
  	$this -> setSessionVars();
	}
	
	/**
  * Serialize the Session Variable Configuration Document
  * @name setSessionVars  
  */
  function setSessionVars() {
    $i = 0;
  	foreach ($this -> objSessionDoc -> query("//sessionvars/variable/name") as $node ) {
      $this -> session_vars[$i] = $this -> objSessionDoc -> getNodeValue ($node);
      $i++;
    }
  }
  
  /**
  * Get the action, which is equivalent to the user's click. Buttons and image clicks
  * are transformed using this method. Reads through all post data looking for a submit.
  * 
  * This method has been replaced by getFormMethod()	  
  * @name getAction
  * @return string	  
  */
  function getAction() {
     if ($this -> isPosted()) {
      // Using Javascript the action was available
      if (isset($_POST["styroaction"])) {
        return ($_POST["styroaction"]);
      } else {
        foreach($_POST as $key => $value) {
          if (left($key,6)=="SUBMIT") {
            return $value;
          }
        }
      }
     } else {
      return null;
     }
  }
  
  /**
  * Main controller method, used to render a form into either XML or a string.
  * @name drawForm 
  * @return mixed (text/XML)
  */
  function drawForm(){
  
    $this -> moveForm();
    $this -> loadFormXML();
    
    return $this -> transformDocument();
  }
  
  /**
  * Increments the current form, with an optional param to skip form validation.
  * @name moveForm  
  * @param string $validate - FPO copy here
  * @return boolean  
  */
  function moveForm($validate=true) {
    
    if ($this -> isPosted()) {
      
      $this -> page = $_POST['styropage'];
      
      if ($_POST["styroaction"] == "back") {
        $this -> isvalidated = "TRUE";
			  $this -> isprocess_step = "TRUE";
			  $this -> page --;
			  $this ->theXMLDoc = $this -> docArray[$this -> page];
        $this -> loadFormXML();
      } elseif ($validate) {
        $this ->theXMLDoc = $this -> docArray[$this -> page];
        $this -> loadFormXML();
        
        if (($this -> validated) && ($this -> validateForm())) {
          $this -> isvalidated = "TRUE";
    			if (! $this -> page == count($this -> docArray )- 1) {
            $this -> isprocess_step = "TRUE";
          }
    			$this -> page += $this -> step;
    			$this ->theXMLDoc = $this -> docArray[$this -> page];
    			return true;
    		} else {
          $this ->theXMLDoc = $this -> docArray[$this -> page];
          $this -> loadFormXML();
          return false;
        }
      } else {
        //$this -> isvalidated = "TRUE";
  			//$this -> isprocess_step = "TRUE";
  			//$this -> page += $count;
  			//$this ->theXMLDoc = $this -> docArray[$this -> page];
  			die("moveForm without Validation");
        return true;
      }
    } else {
      $this ->theXMLDoc = $this -> docArray[$this -> page];
      $this -> loadFormXML();
    }
  }
  
  /**
  * Tests whether the form was posted or not from a normal form view.
  * @name isPosted
  * @return boolean  
  */
  function isPosted() {

    if (($_SERVER['REQUEST_METHOD'] == "POST") && (isset($_POST["styroname"])) && ($_POST["styroname"] == $this -> name)) {
      return true;
    }
    return false;
    
  }
  
  /**
  * Tests whether the form was posted or not from the CRUD List view. If a list
  * has a form wrapper, elements in the list can contain checkboxes for multi-item deletion.  
  * @name isListPosted
  * @param string $name - The Name of the For
  * @return boolean  
  */
  function isListPosted( $name ) {

    if (($_SERVER['REQUEST_METHOD'] == "POST") && (isset($_POST["listform"])) && ($_POST["listform"] == $name)) {
      return true;
    }
    return false;
    
  }
  
  /**
  * Similar to getAction(), but only looks for specific keys, which is equivalent to the user's click. 
  * Buttons and image clicks, are deduced using this method.
  * 
  *	@name getFormMethod
  * @return string  
  */
  function getFormMethod() {
     $method = false;
     $method = (isset($this -> VARIABLES["POST"]["SUBMIT_delete"])) ? "delete" : $method;
     $method = (isset($this -> VARIABLES["POST"]["SUBMIT_submit"])) ? "submit" : $method;
     if (method_exists($this -> context,"getRequest")) {
      $method = ($this -> context -> getRequest() -> getPostParameter("SUBMIT_method") != null) ? "method" : $method;
     }
     $method = (isset($this -> VARIABLES["POST"]["styroaction"])) ? $this -> VARIABLES["POST"]["styroaction"] : $method;
     return $method;
  }
  
   /**
  * Boolean to test which page is currently active
  * @name isPage
  * @param int $index
  * @return boolean  
  */
    function isPage( $index ) {
    if ($this -> page == $index) {
      return true;
    } else {
      return false;
    }
  }
  
  /**
  * Creates the objXMLDoc from an XML Form, looking in the following order for, 
  * Widget Name/Doc, ROOTNODE (from another XML File)  
  * @name loadFormXML 
  */
  function loadFormXML() {
    
    if (strlen($this -> theXMLDoc) == 0 ) {
      print("\$this -> theXMLDoc is empty for ".$this->widget );
      die();
    }
    
    if ($this->widget != null) {
      $rootelement = "widget";
      $this -> objXMLDoc -> loadXML("lib/widgets/".$this->widget."/".$this -> theXMLDoc);
      
    } elseif ( $this -> ROOTNODE != null ) {
      
      $rootelement = $this -> ROOTNODE;
      $this -> objXMLDoc -> loadXML($this -> theXMLDoc);
      
    } else { 
      $rootelement = "PAGE";
      
      $this -> objXMLDoc -> loadXML($this -> theXMLDoc);
      
    }

    $action = $this->objXMLDoc->getPathValue("AFORM/FORMACTION");
    if(startsWith($action, "php:"))
    {
      $code = substr($action, strlen("php:"));
      $action = eval($code);
      echo $action;
      die;
    }

    $this -> isloaded = $this -> page;
    if (strlen($this -> objXMLDoc -> getAttributeValueByPath ("//widget", "baseurl")) == 0) {
      $this -> objXMLDoc -> setAttribute($rootelement, 0, "baseurl", "http://" . $_SERVER["SERVER_NAME"]);
    }
    $this -> objXMLDoc -> setAttribute($rootelement, 0, "timestamp", microtime());
    $raw = $this->objXMLDoc->getPathAttribute("//AFORM/FORMACTION","raw");
    if ($raw == "true") {
			$URI = $_SERVER["REQUEST_URI"];
			$URI = preg_replace("/&SUBMIT_[^&]*/","",$URI);
			$URI = preg_replace("/&styropage[^&]*/","",$URI);
			$URI = preg_replace("/&styroname[^&]*/","",$URI);
			$URI = preg_replace("/&showerrors[^&]*/","",$URI);
			$this -> objXMLDoc -> setAttribute($rootelement, 0, "script", $URI);
    
		} else {
			$this -> objXMLDoc -> setAttribute($rootelement, 0, "script", $_SERVER["REQUEST_URI"]);
		}
		$this -> objXMLDoc -> setAttribute($rootelement, 0, "hostname", $_SERVER["SERVER_NAME"]);
    
    $this -> objXMLDoc -> setAttribute($rootelement, 0, "session", session_id());
    if (method_exists($this -> context,"getRequest")) {
      $this -> objXMLDoc -> setAttribute($rootelement, 0, "session_name", $this->getContext()->getStorage()->getParameter( "sf_session_name" ));
    } else {
      $this -> objXMLDoc -> setAttribute($rootelement, 0, "session_name", session_name());
    }
    
    $this -> name = $this -> objXMLDoc -> getNodeContent("//FORMNAME");
    $this -> addpost_values = ($this -> objXMLDoc -> getNodeContent("//PASSTHROUGHVALUES")) ? $this -> objXMLDoc -> getNodeContent("//PASSTHROUGHVALUES") : "FALSE";
    $this -> addget_values = ($this -> objXMLDoc -> getNodeContent("//QUERYVALUES")) ? $this -> objXMLDoc -> getNodeContent("//QUERYVALUES") : "FALSE";
    $this -> appenditem_values = ($this -> objXMLDoc -> getNodeContent("//HIDDENVALUES") == "TRUE") ? true : false;
    
  }
  
  /**
  * Processes all the items in the process queue, in the order of ERROR, SELECT OPTS,
  * POST, GET, SESSION, LIST, MODS, ITEM, HMAC, CONTAINER, DATES, then removes any items,
  * and finally does an XSL Transformation if needed, and then returns either a string, or an XML Object
  * @name transformDocument 
  * @return mixed (text/XML)  
  */
 
  function transformDocument(){
    
		$this -> ErrorMod();
    
    foreach($this -> types_array as $type) {
      $this -> generateOptions($type);
    }
    
    //add post values?
    if (($this -> addpost_values == "TRUE") || ( $this -> addget_values == "SELF" )) {
      $this -> postMod();
    }
    
    //add get values?
    if ($this -> addget_values == "TRUE") {
      $this -> getMod();
    }
    
    $this -> sessionMod();
  	
  	//Pulls items from the "List" and inserts them into the form
  	$this -> addList();
  	
    //Modifies Basenodes prior to display
    $this -> runMod();
    
  	//Pulls values from the "Item" and inserts them into the form
  	$this -> addItem();
    
    //Add an HMAC Signature using a Datestamp
    //And some random junk
  	$this -> addSig();
    
    //Add JSON Containers prior to display
    $this -> addContainer();
    
    $this ->  setDefaultDates();
    
    if (isset($_GET['debug']) && ($_GET['debug'] == 'amadsen')) {
      $this -> debug = true;
    }
    
    $this -> navMod();
    $this -> ErrorNoticeMod();
  	$this -> removeStyroformElements();
  	
  	if(sfConfig::get("showXML")) {
      $this -> showXML();
    }
  	//$this -> showXML();
    //Below is for HARDCORE Debuggers -- You Buggers
    if ($this -> name == "login") {
      //$this -> showXML();
    }
    
    if ($this -> returnxml) {
      return $this->objXMLDoc;
    } else {
      $xsl_location = $this -> assetlocation.$this -> theXSLDoc;
      $result = array("form"=>$this -> objXSLDoc -> convertDoc($xsl_location,$this->objXMLDoc->documentElement,$this -> omit_doctype));
      
      return $result;
    }
    
  }


  /**
  * Creates an XMLFormValidator, tests the form against the available Validators,
  * and returns the results of the Validation.
  * @name validateForm  
  * @return boolean  
  */
  function validateForm(){
    //instantiate object
    $this -> objValidateDoc = new XMLFormValidator();
    //run validation
    $this -> objValidateDoc -> debug = $this -> validate_debug;
    
    if (! $this -> isloaded) {
      $this -> loadFormXML();
    }
    
    $this -> objValidateDoc -> initValidation( $this->objXMLDoc );
    
    //Pass the dynarray for selectDB Arrays (if any)
    $this -> objValidateDoc -> dynarray = $this -> dynarray; 
    
    $this -> isvalidated = $this -> objValidateDoc -> checkForm();
    
    //return status
    return $this -> isvalidated;
  }

  /**
   * Updates the form with the current page, and name
   */
	
	function navMod(){
    //add postback formelement
    $attribs["name"] = "navigation";
    $attribs["class"] = "reqs";
    
    $this -> objXMLDoc -> createSingleElement("FORMELEMENTGROUP","AFORM",$attribs);
		//iterate through request values
		
    $this -> addStyroformElements("styropage", "", "hidden", (string)$this -> page, "", "", "FALSE", "reqs", "", "navigation");
    $this -> addStyroformElements("styroname", "", "hidden", (string)$this -> name, "", "", "FALSE", "reqs", "", "navigation");
    
  }
  
  /**
  * Adds POST Variables to the form, appending if the form doesn't contain
  * an appropriate formelement container.  
  * @name  postMod
  */
  function postMod(){
    //add postback formelement
    $attribs["name"] = "postback";
    $attribs["class"] = "reqs";
    
    $this -> objXMLDoc -> createSingleElement("FORMELEMENTGROUP","AFORM",$attribs);
		
		//dump($_POST);
    //iterate through request values
    foreach($_POST as $key => $value){
      
      if ($this -> debug > 0) {
        echo ($key . " EQUALS " . $value .": ");
      }
      $isAttrib = false;
          
      if ((substr_count($key, "SUBMIT") < 1) && (strtoupper($key) != "SHOWERRORS") && ($key != "styropage")) {
        if ($this -> checkCompound($key)) {
         $isAttrib = true;
			   $itemXMLEquiv = $this -> getCompound( $key );
			   $key = $itemXMLEquiv[0];
         $attrib = $itemXMLEquiv[1];
			   $value = $_POST[$key."|".$attrib."|comp"];
			  }
        if (is_array($value)) {
          $key .= "[]";
        }
        if ($this -> objXMLDoc -> query("//FORMELEMENT[NAME='".$key."']")) {
          if ($this -> debug > 0) {
            echo (" AND IS IN THE FORM: " . $this -> name);
          }
          if ($isAttrib) {
            if ($this -> debug > 0) {
              echo (" SETTING ATTRIBUTES: ");
            }
            $this -> addStyroformAttribute($key,$attrib,$value);
          } else {
            if ($this -> debug > 0) {
              echo (" SETTING VALUE: ");
            }
            if (is_array($value)) {
              foreach($value as $thekey=>$theval) {
                $this -> addStyroformDefault($key, $theval);
              }
            } else {
              $this -> addStyroformDefault($key, $value);
            }
          }
        } else if ($this -> addpost_values != "SELF") {
          if ($this -> debug > 0) {
            echo (" AND IS NOT IN THE FORM ");
          }
          if ($this -> skip_missing_post) {
            if ($this -> debug > 0) {
              echo (" :: SKIPPING DUE TO MISSING POST VAR ");
            }
            return;
          }
          if (! $this -> objXMLDoc -> query("//FORMELEMENT[NAME='" . $key. "']")) {
            if ($isAttrib) {
  				    $this -> addStyroformElements($key."|".$attrib."|comp", "", "hidden", $value, "", "", "FALSE", "", "", "postback");
  				  } else {
              if (is_array($value)) {
                foreach($value as $thekey=>$theval) {
                  //echo($thekey.$theval);
                  $this -> addStyroformElements($key, "", "hidden", $theval, "", "", "FALSE", "", "", "postback");
                }
                
              } else {
                //echo($key."=".str_replace("&","&amp;",$value)."<br />");
                $this -> addStyroformElements($key, "", "hidden", str_replace("&","&amp;",$value), "", "", "FALSE", "", "", "postback");
              }
            }
          }
        }
      }
      if ($this -> debug > 0) {
       echo ("<br />");
      }
    }
  }
	
  /**
  * Adds an element to the removal queue, using the name element from the form element
  *   
  * @name removeItem 
  * @param string $name
  */
	function removeItem($name) {
    array_push($this -> items_to_remove,$name);
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
  * @name  setErrorNotice  
  * @param string $message
  */
	function setErrorNotice($message) {
    $this -> isvalidated = false;
    $this -> validated = false;
    $this -> errornotice = $message;
  }
  
  /**
  * Adds an element to the error array stored in Session
 
  * If the business process rules need to flag a specific field, use this 
  * to set the tuple, and it will be reflected in the XML.
  * @name addError  
  * @param string $name - Form Element Name
  * @param string $message - Display Message for Error
  */
	function addError($name,$message) {
    if (! isset($_SESSION["errors"])) {
      $_SESSION["errors"] = array();
    }
    $_SESSION["errors"][$name] = $message;
  }
  
  /**
  * Adds QueryString Variables to the form, appending if the form doesn't contain
  * an appropriate formelement container.  
  * @name  getMod
  */
  function getMod(){
    //add postback formelement
    $attribs["name"] = "querystring";
    $attribs["class"] = "reqs";
    
    $this -> objXMLDoc -> createSingleElement("FORMELEMENTGROUP","AFORM",$attribs);
		
		//iterate through request values
    foreach($_GET as $key => $value){
      if(substr_count($key, "SUBMIT") < 1){
        if ($this -> objXMLDoc -> query("//FORMELEMENT[NAME='".$key."']")) {
          $this -> addStyroformDefault($key, $value);
        } else {
          $this -> addStyroformElements($key, "", "hidden", $value, "", "", "FALSE", "", "", $attribs["name"]);
        }
      }
    }
  }
  
  /**
  * Set the "errornotice" form element to visible.
  * @name ErrorNoticeMod 
  */
  function ErrorNoticeMod(){
  	if (isset($this -> errornotice)) {
  	  //iterate through session values
  		$path = "//FORMELEMENT[NAME='errornotice']/DEFAULT";
  	  $this -> objXMLDoc -> setValueByPath($path, 0, $this -> errornotice);
  	  $this -> objXMLDoc -> setPathAttribute($path, 0, "visible", "true");
  	  $this -> errornotice = null;
    } 
  }
  
  /**
  * Add the global "errors" element group to the Form XML Object
  * and place any session "errors" into the group as separate elements. 
  * Also, rewinds the form to the previous page,
  * as these errors stop the form from being successfully processed  
  * @name ErrorMod
  */
  function ErrorMod(){
    //add postback formelement
    $attribs["name"] = "errors";
    
    $this -> objXMLDoc -> createSingleElement("FORMELEMENTGROUP","AFORM",$attribs);
		
		if (isset($_SESSION["errors"]) && $_SESSION["errors"] != "") {
		
		  //iterate through session values
  		foreach($_SESSION["errors"] as $key => $value){
  		  if (! $this -> objXMLDoc -> setPathAttribute("//FORMELEMENT[NAME='" . $key . "']/DISPLAYCLASS", 0, "error", "TRUE")) {
          $this->addStyroformElements($key, "", "error", $value, "", "", "FALSE", "", "", "errors");
        }
      }
      if ($value == "") {
        $value = "There was an error!";
      }
      $this -> setErrorNotice($value);
      if ($this -> page > 0) {
        $this -> page--;
        $this -> moveForm();
      }
    }
    
    unset($_SESSION["errors"]);
  }
  
  /**
  * Mark a specific form element as having an error.
  * @name ErrorElementMod
  */
  function ErrorElementMod(){
    $this -> objXMLDoc -> setPathAttribute("//FORMELEMENT[NAME='" . $elementname . "']/DISPLAYCLASS", 0, "error", "TRUE");
  }
 
  /**
  * Add appropriate session variables to the Form Object
  * @name SessionMod  
  */
  function SessionMod(){
    //add postback formelement
    $attribs["name"] = "session";
    $attribs["class"] = "reqs";
    
    $this -> objXMLDoc -> createSingleElement("FORMELEMENTGROUP","AFORM",$attribs);
		
		if ($_SESSION) {
  		//iterate through session values
  		foreach($_SESSION as $key => $value){
        if (in_array($key, $this -> session_vars )) {
          $this->addStyroformElements($key, "", "hidden", $value, "", "", "FALSE", "", "", "session");
        }
      }
    }
    
  }
  
  /**
  * Add an Element Group Form Element Container
  * @name addStyroformElementGroup
  * @param string $path - XPath for Element Group Placement
  * @param string $name - Name to put in "name" attrubute
  * @param string $array - Array of attributes for Group
  * @param string $id - ID to put in "id" attribute, optional
  */
  function addStyroformElementGroup( $path, $name, $array, $id=null ) {
    //add postback formelement
    $attribs["name"] = $name;
    if ($id) {
      $attribs["id"] = $id;
    }
    
    $elementgroup = $this -> objXMLDoc -> createSingleElementByPath("FORMELEMENTGROUP",$path,$attribs);
		
    if ($array) {
  		//iterate through session values
  		foreach($array as $item){
  		    $theseattribs = null;
          if ($item["attribs"]) {
            $theseattribs = $item["attribs"];
          }
          $this->addStyroformElements($item["name"],$item["display"],$item["type"],$item["default"],$item["class"],$item["vtip"],$item["required"],$item["displayclass"],$item["size"],$name,$theseattribs);
      }
    }
    
  }
 
  /**
  * Forces an error into the Form, using the Form Validator
 
  * This passes the current Dynarray to the Validator
  * @name addStyroformError
  * @param string $elementname  - Name of the Form Element
  */
  function addStyroformError ( $elementname ) {
    //instantiate object
    $this -> objValidateDoc = new XMLFormValidator();
    //run validation
    
    //Pass the dynarray for selectDB Arrays (if any)
    $this -> objValidateDoc -> dynarray = $this -> dynarray; 
    
    $this -> objValidateDoc -> createSingleError( $elementname, $this->objXMLDoc );
    
  }
  
  /**
  * Deletes and element from the Form XML Object
 
  * Uses the "items_to_remove" array to pull specific items from the Form.
  * <code> 
  *  XMLForm -> removeItem("someelementname");
  * </code>
  * @name removeStyroformElements  
  */
  function removeStyroformElements() {
    if ($this -> items_to_remove != null) {
      foreach($this -> items_to_remove as $name) {
        $elementquery = $this -> objXMLDoc -> query("//FORMELEMENTGROUP/FORMELEMENT[NAME='".$name."']");
        foreach ($elementquery as $node) {
          $node ->parentNode->removeChild($node);
        }
      }
    }
  }
  
    
  /**
  * Adds a standard Form Element Group, with a child Form Element
  * 
  *	<code> 
  * &ltFORMELEMENTGROUP&gt
  *    &ltFORMELEMENT&gt
  *      &ltNAME&gt&lt/NAME&gt
  *      &ltDISPLAY&gt&lt/DISPLAY&gt
  *      &ltTYPE&gt&lt/TYPE&gt
  *      &ltDEFAULT&gt&lt/DEFAULT&gt
  *      &ltCLASS&gt&lt/CLASS&gt
  *      &ltVTIP&gt&lt/VTIP&gt
  *      &ltREQUIRED&gt&lt/REQUIRED&gt
  *      &ltDISPLAYCLASS&gt&lt/DISPLAYCLASS&gt
  *      &ltSIZE&gt&lt/SIZE&gt
  *   &lt/FORMELEMENT&gt
  * &lt/FORMELEMENTGROUP&gt
  * </code>
  * @name addStyroformElements
  * @param string $name - Name Subnode
  * @param string $display - Display Subnode
  * @param string $atype - Type Subnode
  * @param string $default - Default Subnode
  * @param string $aclass - Class Subnode
  * @param string $vtip - Validation Type Subnode
  * @param string $required - Required Subnode
  * @param string $displayclass - Display Class Subnode
  * @param string $size - Size Subnode
  * @param string $pos - Name of the Element Group
  * @param string $attribs - Array of Attributes for the Element Group
  */
  function addStyroformElements($name, $display, $atype, $default, $aclass, $vtip, $required, $displayclass, $size, $pos, $attribs=null) {
    
    $elements_array = array(
			    "name" => $name,
			    "display" => $display,
			    "type" => $atype,
			    "default" => (string)$default,
			    "class" => $aclass,
			    "vtip" => $vtip,
			    "required" => $required,
			    "displayclass" => $displayclass,
			    "size" => $size
			    );
    
    $TEMPELEMENT_ONE = $this -> objXMLDoc -> createElement("FORMELEMENT");
    
    foreach($elements_array as $field_name => $field_value){
      $theseattribs = null;
      if ($attribs[$field_name]) {
        $theseattribs = $attribs[$field_name];
      }
      $this -> objXMLDoc -> appendSingleElement(strtoupper($field_name),$TEMPELEMENT_ONE,$theseattribs,fixString(html_entity_decode($field_value)));
      
    }
    
    if (! $this -> objXMLDoc -> query("//FORMELEMENTGROUP[@name='" . $pos . "']")) {
      $attribs["name"] = $pos;
    
      $this -> objXMLDoc -> createSingleElement("FORMELEMENTGROUP","AFORM",$attribs);
    }  
    
    $this -> objXMLDoc -> appendByPath("//FORMELEMENTGROUP[@name='" . $pos . "']",$TEMPELEMENT_ONE);
    
  }
  
  /**
  * Adds a standard Form Element Group to a Form "Snippet", with a child Form Element
  * 
  *	<code> 
  * &ltFORMELEMENTGROUP&gt
  *    &ltFORMELEMENT&gt
  *      &ltNAME&gt&lt/NAME&gt
  *      &ltDISPLAY&gt&lt/DISPLAY&gt
  *      &ltTYPE&gt&lt/TYPE&gt
  *      &ltDEFAULT&gt&lt/DEFAULT&gt
  *      &ltCLASS&gt&lt/CLASS&gt
  *      &ltVTIP&gt&lt/VTIP&gt
  *      &ltREQUIRED&gt&lt/REQUIRED&gt
  *      &ltDISPLAYCLASS&gt&lt/DISPLAYCLASS&gt
  *      &ltSIZE&gt&lt/SIZE&gt
  *   &lt/FORMELEMENT&gt
  * &lt/FORMELEMENTGROUP&gt
  * </code>
  * @name createStyroformElement
  * @param xmlObj $source - XML Document
  * @param array $array - array(name,display,atype,default,aclass,vtip,required,displayclass,size)
  */
  function createStyroformElement($source, $array) {
    
    $docRoot = $source -> documentElement -> createElement("ROOT");
    $source -> documentElement -> appendChild($docRoot);
    
    $elements_array = array(
			    "name" => $array["name"],
			    "display" => $array["display"],
			    "type" => $array["atype"],
			    "default" => (string)$array["default"],
			    "class" => $array["aclass"],
			    "vtip" => $array["vtip"],
			    "required" => $array["required"],
			    "displayclass" => $array["displayclass"],
			    "size" => $array["size"]
			    );
    
    $TEMPELEMENT_ONE = $source -> createElement("FORMELEMENT");
    $attribs = $array["attribs"];
    
    foreach($elements_array as $field_name => $field_value){
      $theseattribs = null;
      if ($attribs[$field_name]) {
        $theseattribs = $attribs[$field_name];
      }
      $source -> appendSingleElement(strtoupper($field_name),$TEMPELEMENT_ONE,$theseattribs,fixString(html_entity_decode($field_value)));
      
    }
    
    if (strlen($array["pos"]) > 0) {
      $attribs["id"] = $array["pos"];
      $source -> createSingleElement("FORMELEMENTGROUP","ROOT",$attribs);
      $source -> appendByPath("//FORMELEMENTGROUP[@id='" . $array["pos"] . "']",$TEMPELEMENT_ONE);
    } else {
      $source -> appendByPath("//ROOT",$TEMPELEMENT_ONE);
    }
  }
  
  /**
  * Adds a set of Values to a form element, specifically select, radio, or checkbox
 
  * If group is specified, a VALUEGROUP element will be appended
  * <code> 
  *  values[0]["sel_key"] = "first";
  *  values[0]["sel_value"] = "1";
  *  values[1]["sel_key"] = "second";
  *  values[1]["sel_value"] = "2";
  *  XMLForm -> addStyroformValue("someelement",values,true);
  * </code>
  * @name  addStyroformValues 
  * @param string $name - Name of Element
  * @param array $values - Array of values, array("sel_key"=>"sel_value");
  * @param boolean $group - Add optional VALUEGROUP
  */
  function addStyroformValues($name, $values, $group = false) {
    
    if ($group) {
      $TEMPELEMENT_ONE = $this -> objXMLDoc -> createElement("VALUEGROUP");
      $TEMPELEMENT_ONE -> setAttribute("class","checkinput");
    } else {
      $TEMPELEMENT_ONE = $this -> objXMLDoc -> query("//FORMELEMENT[NAME='".$name."']");
    }
    
    foreach($values as $value){
      $TEMPELEMENT_TWO = $this -> objXMLDoc -> createElement("VALUE");
      $TEMPELEMENT_TWO -> nodeValue = cleanString($value["sel_value"]);
      $TEMPELEMENT_TWO -> setAttribute("id",cleanString($value["sel_key"]));
      $TEMPELEMENT_TWO -> setAttribute("data",cleanString($value["sel_value"]));
      $TEMPELEMENT_ONE -> appendChild($TEMPELEMENT_TWO);      
    }
    
    if ($group) {
      $FINALPOS = $this -> objXMLDoc -> query("//FORMELEMENT[NAME='" . $name . "']");
      $FINALPOS -> item(0) -> appendChild($TEMPELEMENT_ONE);
    }
  }
  
  /**
  * Put a specific value in a form element, called most often from the addItem()
  * method, but could be called independently.  
 
  * Specific element types need specific types of defaults, i.e. a text element 
  * needs a string, a multi-select needs an array, a date needs a valid date.
  * @name addStyroformDefault 
  * @param string $elementname - Name for Form Element
  * @param mixed $value - Value of Element
  * @param string $atype - Deprecated
  */
  function addStyroformDefault($elementname, $value, $atype = null){
    
    $nodeset = $this -> objXMLDoc -> query( "//FORMELEMENT[NAME='".$elementname."']" );
    
    for ($i = 0; $i < $nodeset -> length; $i ++) {
      if ($this -> debug > 0) {
        echo (" SETTING VALUE FOR : ".$elementname ." = ".$value ."<br />");
      }
        
      $node = $nodeset -> item($i);

      $type = $this -> objXMLDoc -> getValueByPath("TYPE", $node);
      $auth = $this -> objXMLDoc -> getValueByPath("parent::node()/@auth", $node);
      $vtip = $this -> objXMLDoc -> getValueByPath("VTIP", $node);
      
      // append DEFAULT and DISPLAY nodes if it doesn't exist, as we're going to try to set its value later here
      if(!$this -> objXMLDoc -> selectSingleNode("DEFAULT")) {
        $this -> objXMLDoc -> appendSingleElement("DEFAULT", $node);
      }
      if(!$this -> objXMLDoc -> selectSingleNode("DISPLAY")) {
        $this -> objXMLDoc -> appendSingleElement("DISPLAY", $node);
      }
      
      if (($type == "checkbox") || ($type == "checkdb") || ($type == "radiodb") || ($type == "radio") && ((string)$value != null)) {
        $default = $this -> objXMLDoc -> getPathValue("//FORMELEMENT[NAME='".$elementname."']/DEFAULT");
        
        if (! is_array($value)) {
          $selectedArray = explode(",",$value);
        } else {
          $selectedArray = $value;
        }
        
        //"//FORMELEMENT[NAME='".$elementname."']//VALUE[(.|@value = '".trim($thisval)."')]", 0, "selected", "TRUE"
        foreach ($selectedArray as $thisval) {
          if (($thisval != "") && (! $this -> objXMLDoc -> setPathAttribute("//FORMELEMENT[NAME='".$elementname."']//VALUE[(.|@value = '".trim($thisval)."')]", $i, "selected", "TRUE"))) {
  				  //;
  				}
  				
  			}
  		} else if (($type == "display") || ($type == "html")) {
  		  
        if ($vtip == 'zipCode') {
          $value = sprintf("%05d", $value);
        }
        if ($vtip == 'money') {
          $value = sprintf("$%01.2f", $value);
        }
        
        if (is_array($value )) {
          $this -> objXMLDoc -> setValueByPath("//FORMELEMENT[NAME='$elementname']/DISPLAY",0,implode(",",$value));
        } elseif (! $this -> objXMLDoc -> setValueByPath("//FORMELEMENT[NAME='$elementname']/DISPLAY", $i ,$value)) {
    		  //;
        }
  		  
  		} else if ($type == "date") {
        $dateparts = explode("-",$value);
  			
        if (! $this -> objXMLDoc -> setPathAttribute("//FORMELEMENT[NAME='$elementname']/DEFAULT", $i, "month", sprintf("%02d",$dateparts[1]))) {
  				  //;
  			}
  			if (! $this -> objXMLDoc -> setPathAttribute("//FORMELEMENT[NAME='$elementname']/DEFAULT", $i, "day", sprintf("%02d",$dateparts[2]))) {
  				  //;
  			}
  			if (! $this -> objXMLDoc -> setPathAttribute("//FORMELEMENT[NAME='$elementname']/DEFAULT", $i, "year", $dateparts[0])) {
  				  //;
  			}
    	} else if (($type == "datetime") || ($type == "displaydate")) {
    	  $dateparts = explode("-",$value);
    	  $time = explode(" ",$dateparts[2]);
    	  $timeparts=explode(":",$time[1]);
    	  
  			if (! $this -> objXMLDoc -> setPathAttribute("//FORMELEMENT[NAME='$elementname']/DEFAULT", $i, "month", sprintf("%02d",$dateparts[1]))) {
  				  //;
  			}
  			if (! $this -> objXMLDoc -> setPathAttribute("//FORMELEMENT[NAME='$elementname']/DEFAULT", $i, "day", sprintf("%02d",$dateparts[2]))) {
  				  //;
  			}
  			if (! $this -> objXMLDoc -> setPathAttribute("//FORMELEMENT[NAME='$elementname']/DEFAULT", $i, "year", $dateparts[0])) {
  				  //;
  			}
  			if (! $this -> objXMLDoc -> setPathAttribute("//FORMELEMENT[NAME='$elementname']/DEFAULT", $i, "hour", $timeparts[0])) {
  				  //;
  			}
  			if (! $this -> objXMLDoc -> setPathAttribute("//FORMELEMENT[NAME='$elementname']/DEFAULT", $i, "min", $timeparts[1])) {
  				  //;
  			}
  			if (! $this -> objXMLDoc -> setPathAttribute("//FORMELEMENT[NAME='$elementname']/DEFAULT", $i, "sec", $timeparts[2])) {
  				  //;
  			}
  			
    	} else if ($type == "monthyear") {
        $dateparts = explode("/",$value);
  			if (! $this -> objXMLDoc -> setPathAttribute("//FORMELEMENT[NAME='$elementname']/DEFAULT", $i, "month", $dateparts[0])) {
  				  //;
  			}
  			if (! $this -> objXMLDoc -> setPathAttribute("//FORMELEMENT[NAME='$elementname']/DEFAULT", $i, "year", $dateparts[1])) {
  				  //;
  			}
    	} else if (($type == "select") || ($type == "state") || ($type == "state_ca") ||($type == "country") || ($type == "selectdb")) {
        
        if (is_array($value )) {
          
          $this -> objXMLDoc ->createAndAppendByPath("//FORMELEMENT[NAME='$elementname']","DEFAULTVALUES","VALUE",$value,$i);
          
        } elseif (! $this -> objXMLDoc -> setPathAttribute("//FORMELEMENT[NAME='$elementname']/DEFAULT", $i, "selected", cleanString($value))) {
  				  //;
  			}
    	} else if ($type == "dojo") {
    	  $species = $this -> objXMLDoc -> getValue($this -> objXMLDoc -> query("//FORMELEMENT[NAME='".$elementname."']/TYPE/@species"), $i);
    	  if ($species == "select") {
          $this -> objXMLDoc -> setValueByPath("//FORMELEMENT[NAME='$elementname']/DEFAULT",$i,addslashes($value["name"]));
          $this -> objXMLDoc -> setPathAttribute("//FORMELEMENT[NAME='$elementname']/DEFAULT", $i, "selected", $value["id"]);
  			} else {
          if ($this -> objXMLDoc -> setValueByPath("//FORMELEMENT[NAME='$elementname']/DEFAULT",$i,$value)) {
    		    $this -> objXMLDoc -> setValueByPath("//FORMELEMENT[NAME='$elementname']/DISPLAY",$i,"block");
    		  }
        }
      } else if (($type == "selectdisplay") || ($type == "displayselect") || ($type == "displaycheckradio")) {
        
        if (is_array($value)) {
          foreach ($value as $thisval) {
          if (($thisval != "") && (! $this -> objXMLDoc -> setPathAttribute("//FORMELEMENT[NAME='".$elementname."']//VALUE[(.|@id|@value = '".trim($thisval)."')]", $i, "selected", "TRUE"))) {
  				  //;
  				}
  			}
        } else {
          if (! $this -> objXMLDoc -> setValueByPath("//FORMELEMENT[NAME='$elementname']/DEFAULT",$i,$value)) {
      		  //;
          }
          if (($value != "") && (! $this -> objXMLDoc -> setPathAttribute("//FORMELEMENT[NAME='".$elementname."']//VALUE[(.|@id|@value = '".trim($value)."')]", $i, "selected", "TRUE"))) {
  				  //;
				  }
        }     	
      } elseif (is_array($value)) {
    	  if ($this -> debug > 0) {
          echo (" SETTING VALUE: ");
        }
        foreach($value as $val) {
          $this -> objXMLDoc -> setValueByPath("//FORMELEMENT[NAME='$elementname']/DEFAULT",$i,$val);
        }
      } else {
        if ($this -> debug > 0) {
          echo (" SETTING VALUE: ");
        }
        
        if ($vtip == 'zipCode') {
          $value = sprintf("%05d", $value);
        }
        if ($vtip == 'money') {
          $value = sprintf("%01.2f", $value);
        }
        if (! $this -> objXMLDoc -> setValueByPath("//FORMELEMENT[NAME='$elementname']/DEFAULT",$i,$value)) {
    		  //;
        }
      }
    
    }
  }
  
  /**
  * As the name suggests, adds an attribute to a specific form element.
 
  * Some form elements need "default", or "selected" attributes. Useful also for
  * custom XSL Transformations.  
  * @name  addStyroformAttribute  
  * @param string $elementname - Name of Form Element
  * @param string $attribute - Name of Attribute
  * @param string $value - Value of Attribute
  * @param string $item - Subnode within the Element Group  
  */
  function addStyroformAttribute($elementname, $attribute, $value, $item="DEFAULT" ){
    $this -> objXMLDoc -> setPathAttribute("//FORMELEMENT[NAME='".$elementname."']/".$item, 0, $attribute, $value);
  }
  
  /**
  * Styroform can display a list of data, for CRUD type operations. 
 
  * Use this to add a list to the form, vis:
  * <code> 
  *	llistarray["attribs"] = array("attribname1"=>"attribvalue1","attribname2"=>"attribvalue2");
  *	listarray["items"][0] = array("name1"=>"value2","name2"=>"value2"); 
  *	listarray["items"][1] = array("name1"=>"value2","name2"=>"value2"); 
  *	listarray["items"][2] = array("name1"=>"value2","name2"=>"value2"); 
  *	listarray["items"][3] = array("name1"=>"value2","name2"=>"value2"); 
  *	XMLForm -> list = listarray;
  * </code>
  * @name addList
  */
  function addList( ) {
    if ($this -> itemlist ) {
      $listarray = $this -> itemlist;
      
      //$listarray["attribs"]["refer"] = $this -> stripQSPage();
    	$this -> objXMLDoc -> createSingleElement("LIST","LOCALPRE",$listarray["attribs"]);      
    	
    	if ($listarray["items"]) {
        foreach($listarray["items"] as $item) {
    		  $count = 0;
          foreach($item as $key => $attrib) {
    			  $tempattrib[$key] = ($attrib != "") ? $attrib : "";
    			  $count++;
    			}
    			$this -> objXMLDoc -> createSingleElement("LIST_ITEM","LIST",$tempattrib);
    		}
    	}
  	}
  }
  
  /**
  * Adds form elements to the "container" form element group. Elements come from 
  * the "container" array.
  * @name addContainer
  */
  function addContainer () {
    if ($this -> container) {
      foreach($this -> container as $key => $value)
      $this -> addStyroformElements($key, "", "container", $value, "", "", "FALSE", 0, 0, "postback");
    }
    //die();
  }
  
  /**
  * Adds an HMAC Signature to the form if the "signature" form element group exists.
  * 
  * The HMAC Key uses MCRYPT TripleDES, and contains the form name, and a timestamp.	  
  */
  function addSig () {
    if ($this -> objXMLDoc -> getPathAttribute("//FORMNAME","signature") == "false") {
      return true;
    }
    if (sfConfig::get("sf_encrypt_key") !='') {
      
      $hash = md5(rand_str());
      $enc_date = trim(base64_encode(mcrypt_encrypt(MCRYPT_TRIPLEDES, sfConfig::get("sf_encrypt_key"), $hash."=".strtotime(now()), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_TRIPLEDES, MCRYPT_MODE_ECB), MCRYPT_RAND))));
      $this -> addStyroformElements("key_sig", "", "hidden", $hash, "", "", "FALSE", 0, 0, "signature");
      $this -> addStyroformElements("expires", "", "hidden", strtotime(now()), "", "", "FALSE", 0, 0, "signature");
      $this -> addStyroformElements("data_sig", "", "hidden", $enc_date, "", "", "FALSE", 0, 0, "signature");
      $beacon_data = trim(base64_encode(mcrypt_encrypt(MCRYPT_TRIPLEDES, sfConfig::get("sf_encrypt_key"), "form=".$this -> name.",".$hash."=".strtotime(now()), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_TRIPLEDES, MCRYPT_MODE_ECB), MCRYPT_RAND))));
      $this -> addStyroformElements("beacon_sig", "", "hidden", base64_encode($enc_date), "", "", "FALSE", 0, 0, "signature");
      $this -> addStyroformElements("beacon_data", "", "hidden", base64_encode($beacon_data), "", "", "FALSE", 0, 0, "signature");
      //$this -> objXMLDoc -> saveXML();
      //die();
    }
  }
  
  /**
  * Adds a multidemensional array of values to the form.
  * 
  * Use this to populate the form values with data, viz:	  
  * <code> 
  *  XMLForm -> item["someformelement"] = "somevalue";
  *  
  * vals["a"] = 1;
  * vals["b"] = 2;
  * XMLForm -> item = vals;			  
  * </code>
  * This will then run prior to XSL Transformation, and insert appropriate
  * values to the appropriate form element.	  
  * @name addItem
  */
  function addItem () {
    /*List array is an 2-dimensional array'
    First in the column are the item names
    with the first element being the item name, 
    the second being the value*/
    
    //Need to look into this:
    //If the element is an array of hidden values, and we're OVERLOADING the hiddens
    //With an Item Array, we'd need to remove all duplicates
    //OR set specific items to be overridden
    //Most likely you'd want to override the whole set.
    //For now, we'll just skip the array if it's a pass-through
    if ($this -> item) {
      //dump($this -> item);
      $this -> nullCheckRadio();
      foreach($this -> item as $key => $value) {
        //print ($key . "=" . $value);
        if ($node = $this -> objXMLDoc -> query("//FORMELEMENT[NAME='" . $key . "']/TYPE")) {
           if ((string)$value != "") {
            if ($this -> objXMLDoc -> getValue($node,0) != "hidden") {
              $this -> addStyroformDefault($key,$value);
            } elseif ($this -> item_forcehidden) {
              $this -> addStyroformDefault($key,$value);
            }
          }
        } elseif ($this -> appenditem_values) {
          $this -> addStyroformElements($key, "", "hidden", $value, "", "", "FALSE", 0, 0, "postback");
        }
      }
    }
    //$this -> objXMLDoc -> saveXML();
    //die();
  }
      
  /**
  * Sets the default value of datetime elements if no default data already exists.
 
  * @name setDefaultDates
  */
  function setDefaultDates () {
    if ($nodes = $this -> objXMLDoc -> query("//FORMELEMENT[TYPE='datetime']/NAME"))  {
				foreach ($nodes as $node) {
				  $set = true;
          if ($this -> objXMLDoc -> query("//FORMELEMENT[NAME='" . $node -> nodeValue . "']/DEFAULT/@month")) {
            $set = false;
          }
				  if ($this -> objXMLDoc -> query("//FORMELEMENT[NAME='" . $node -> nodeValue . "']/DEFAULT/@day")) {
            $set = false;
          }
				  if ($this -> objXMLDoc -> query("//FORMELEMENT[NAME='" . $node -> nodeValue . "']/DEFAULT/@year")) {
            $set = false;
          }
				  if ($this -> objXMLDoc -> query("//FORMELEMENT[NAME='" . $node -> nodeValue . "']/DEFAULT/@hour")) {
            $set = false;
          }
				  if ($this -> objXMLDoc -> query("//FORMELEMENT[NAME='" . $node -> nodeValue . "']/DEFAULT/@min")) {
            $set = false;
          }
				  if ($set) {
				    $this -> addStyroformDefault($node -> nodeValue, now());
			    }
      }
    }
  }
        
  /**
  * Clears the preselected values of a CheckRadio Element
 
  * @name nullCheckRadio
  */
	function nullCheckRadio() {
    $checknodes = $this -> objXMLDoc -> query("//FORMELEMENT[(TYPE='checkbox') or (TYPE='radio')]/NAME");
    if ($checknodes) {
      foreach ($checknodes as $checknode) {
        if(in_array($checknode->nodeValue,$this->item)){
          $nullnodes = $this -> objXMLDoc -> query("//FORMELEMENT[NAME='".$checknode->nodeValue."']//VALUE");
          foreach ($nullnodes as $nullnode) {
            $nullnode -> setAttribute("selected", "FALSE");
          }
        }
      }
    }
  }
 
 /**
  * Adds an array to the form, for use in Select and CheckRadio elements
 
  * The array isn't a normal two-d array, make sure you use the following format:
  * <code> 
      $item[0]["sel_key"]="stinky";
      $item[0]["sel_value"]="stinky";
      $item[1]["sel_key"]="fish";
      $item[1]["sel_value"]="fish";
  * </code>
  * @name registerArray
  * @param string $name - Name of the form element
  * @param array $array - Array of names/values
  */
	function registerArray( $name, $array ) {
    $this -> dynarray[ $name ] = $array;
  }
  
  /**
  * Builds server-side dynamic select lists from MySQL Tables or Arrays
  * 
  *	@name GenerateOptions  
  * @param string $type
  */
  function generateOptions( $type ) {
    if ($nodes = $this -> objXMLDoc -> query("//FORMELEMENT[TYPE='".$type."']/NAME"))  {
				foreach ($nodes as $node) {
          $THETABLE = $this -> objXMLDoc -> query("//FORMELEMENT[NAME='" . $node -> nodeValue . "']/DEFAULT/@table");
  				$THEQUERY = $this -> objXMLDoc -> query("//FORMELEMENT[NAME='" . $node -> nodeValue . "']/DEFAULT/@query");
  				$THEOTHER = $this -> objXMLDoc -> query("//FORMELEMENT[NAME='" . $node -> nodeValue . "']/DEFAULT/@other");
  				$THEARRAY = $this -> objXMLDoc -> query("//FORMELEMENT[NAME='" . $node -> nodeValue . "']/DEFAULT/@array");
  				$FINALPOS = $this -> objXMLDoc -> query("//FORMELEMENT[NAME='" . $node -> nodeValue . "']");
          $TEMPELEMENT_ONE = $this -> objXMLDoc -> createElement("VALUES");
          //print($node -> nodeValue."<br />");
          if ($THETABLE && ($THETABLE -> length == 1)) {
            $thisselect = new XMLSelect;
            $thisselect -> table = $THETABLE ->  item(0) -> nodeValue;
  				  $values = $thisselect -> ArrayReturn();
            
            if ($THEOTHER) { $values = $this -> addOther($THEOTHER -> item(0) -> nodeValue,$values);}
  				  if (count($values["dbvalues"]) > 0) {
  				  foreach ($values["dbvalues"] as $value) {
  				    $ATTRIB = $this -> objXMLDoc -> query("//FORMELEMENT[NAME='" . $node -> nodeValue . "']/DEFAULT/@attrib");
  				    if (!$ATTRIB || ($ATTRIB -> length != 1)) {
  				      $ATTRIB="id";
  				    } else {
                $ATTRIB= $ATTRIB ->  item(0) -> nodeValue;
              }
  				    
              $TEMPELEMENT_TWO = $this -> objXMLDoc -> createElement("VALUE");
              $TEMPELEMENT_TWO -> nodeValue = cleanString($value["sel_value"]);
              $TEMPELEMENT_TWO -> setAttribute($ATTRIB,cleanString($value["sel_key"]));
              $TEMPELEMENT_TWO -> setAttribute("data",cleanString($value["sel_value"]));
              $TEMPELEMENT_ONE -> appendChild($TEMPELEMENT_TWO);
            }}
          } elseif ($THEARRAY && ($THEARRAY -> length == 1)) {
            $arrayname =  $THEARRAY ->  item(0) -> nodeValue;
            $values = $this -> dynarray[$arrayname];
            if ($THEOTHER) { $values = $this -> addOther($THEOTHER -> item(0) -> nodeValue,$values);}
  				  foreach ($values as $value) {
              $TEMPELEMENT_TWO = $this -> objXMLDoc -> createElement("VALUE");
              $TEMPELEMENT_TWO -> nodeValue = cleanString($value["sel_value"]);
              $TEMPELEMENT_TWO -> setAttribute("id",cleanString($value["sel_key"]));
              $TEMPELEMENT_TWO -> setAttribute("data",cleanString($value["sel_value"]));
              if (isset($value["onClick"])) {
                $TEMPELEMENT_TWO -> setAttribute("onclick",$value["onClick"]);
              }
              $TEMPELEMENT_ONE -> appendChild($TEMPELEMENT_TWO);
            }
          } elseif ($THEQUERY && ($THEQUERY -> length == 1)) {
            $append = true;
            $thisselect = new XMLSelect;
            $thisselect -> query = sfConfig::get("sf_lib_dir")."/widgets/" . $THEQUERY ->  item(0) -> nodeValue;
            $values = $thisselect -> queryReturn();
  				  if ($THEOTHER) { $values = $this -> addOther($THEOTHER -> item(0) -> nodeValue,$values);}
  				  if (count($values)) {
            foreach ($values as $value) {
              $TEMPELEMENT_TWO = $this -> objXMLDoc -> createElement("VALUE");
              $TEMPELEMENT_TWO -> nodeValue = cleanString($value["sel_name"]);
              $TEMPELEMENT_TWO -> setAttribute("id",cleanString($value["sel_key"]));
              $TEMPELEMENT_TWO -> setAttribute("data",cleanString($value["sel_name"]));
              $TEMPELEMENT_ONE -> appendChild($TEMPELEMENT_TWO);
            }
            }
          }
          
          $FINALPOS -> item(0) -> appendChild($TEMPELEMENT_ONE);
        }
      }
  }
  
  /**
  * Add an "Other" item to an auto-generated select list, and append to a specific
  * point in that option list.
  *	<code>
  *		addOther("last|other",array("first"=>1,"second"=>2,"third"=>3));
  * </code>		 
  * This will result in a select list, [first, second, third, other]
  * 
  *<code>
  *		addOther("0|other",array("first"=>1,"second"=>2,"third"=>3));
  * </code>		 
  * This will result in a select list, [other, first, second, third]	  
  *
  *		@name addOther 
  * @param string $nodevalue - The Value to be Added, (pos, value)
  * @param array $values - The Initial Select List
  */
  function addOther($nodevalue,$values) {
    if ($nodevalue) {
      $otherarray = explode("|",$nodevalue);
      $otherval["sel_value"] = "Other";
      if ($otherarray[1]) {
        $otherval["sel_key"] = $otherarray[1];
      } else {
        $otherval["sel_key"] = 99999;
      }
    }
    if ($otherarray[0] == "last") {
      $position = count($values["dbvalues"])+1;
    } elseif (is_numeric($otherarray[0])) {
      $position = $otherarray[0];
    } else {
      $position = 0;
    }
    
    $values["dbvalues"] = $this -> array_insert($values["dbvalues"],$position,$otherval);
    
    return $values;
  }
   
   /**
  * Pops a form element modification on the "mods" array, for later inclusion in the 
  * Form XML Object.  
  * 
  * @param string $nodename Name of FormElement (or Group)
  * @param string $modtype string Type of FormElement
  * @param string $modvalue string New Node Value
  * @param string $position int Position relative to Base FOrm
  * @param string $op string Type of operation [mod|del|hidegroup]
  */
  function setMod($nodename,$modtype,$modvalue,$position=0,$op="mod") {
    $mod["nodename"]=$nodename;
    $mod["nodetype"]=$modtype;
    $mod["nodevalue"]=$modvalue;
    $mod["nodeposition"]=$position;
    $mod["nodeop"]=$op;
    $this -> mod[]=$mod;
  }
  
   /**
  * Applies items in the mod array to the Form XML Object.
  * 
  *	Mods are of type "mod, del, hidegroup", but can be extended to have other functions
  */
  function runMod() {
    if (isset($this -> mod)) {
    
      foreach ($this -> mod as $mod) {
        switch ($mod["nodeop"]) {
          case "hidegroup":
            $this -> objXMLDoc ->  setPathAttribute("//FORMELEMENTGROUP[@id='".$mod["nodename"]."']", $mod["nodeposition"], "style","display: none");
            break;
          case "addattrib":
            $this -> objXMLDoc ->  setPathAttribute($mod["nodename"],$mod["nodeposition"],$mod["nodetype"],$mod["nodevalue"]);
            break;
          default:
          if ($mod["nodeop"] == "del") {
            $node = $this -> objXMLDoc -> query("//FORMELEMENT[NAME='".$mod["nodename"]."']");
            if ($node->length > 0) {
              $base = $node -> item(0) -> parentNode;
              $base -> removeChild($node -> item(0));
            } else {
              //dump("//FORMELEMENT[NAME='" . $mod["nodename"] . "']");
            }
            continue;
          }
      	  $node = $this -> objXMLDoc -> query("//FORMELEMENT[NAME='" . $mod["nodename"] . "']/".$mod["nodetype"]);
          $this -> objXMLDoc -> replaceData ($node -> item($mod["nodeposition"]), $mod["nodevalue"]);
          break;
        }
      }
    }
  }
  
}

?>
