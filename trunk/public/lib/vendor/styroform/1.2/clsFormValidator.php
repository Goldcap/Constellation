<?php
/**
 * clsFormValidator.php, Styroform XML Form Controller
 * XML Form Generator and Validator.
 * @author Andy Madsen <amadsen@operislabs.com>
 * @version 1.2
 * @package com.Operis.Styroform
 */

/**
 * The Form Validator class, responsible for reading and validating form elements. This class is replicated in JS for client-side validation.
 */
class XMLFormValidator {

  /** 
  * Count of errors discovered during validation
  *   
  * @access public
  * @var int
  * @name $err
  */
  var $err;
 
 /** 
  * Parent form, passed as an XML Doc
  * @access public
  * @var array
  * @name $objXMLDoc 
  */
  var $objXMLDoc; 
  
  /** 
  * Array of XML Form Elements in the form
  *   
  * @access public
  * @var nodelist
  * @name $objElements
  */
  var $objElements;
  
  /** 
  * XML Object Containing Validation Types
  *   
  * @access public
  * @var XML Obj
  * @name $objXMLValidDoc  
  */
  var $objXMLValidDoc;
	
	/** 
  * Path to XML File for XML Validators
  *   
  * @access public
  * @var XML Obj
  * @name $objXMLValidDoc  
  */
  var $theXMLValidDoc;  
  
  /** 
  * XML Object Containing "Compound" Form Elements
  * 
  * These elements are normally dates, with multiple formelements, 
  * i.e. Day, Month, Year	as separate select lists
  *   
  * @access public
  * @var XML Obj
  * @name $objXMLCompDoc  
  */
  var $objXMLCompDoc;  
  
  /** 
  * Path to XML File for XML Compound Element Types
  *   
  * @access public
  * @var array
  * @name $theXMLCompDoc  
  */
  var $theXMLCompDoc;  
  
  /** 
  * The current form element being validated
  *   
  * @access public
  * @var node element
  * @name $currentElement
  */
  var $currentElement;
  
  /** 
  * Validation Type of the current form element
  *   
  * @access public
  * @var string
  * @name $vtip
  */
  var $vtip;
  
  /** 
  * Required param of the current form element
  *   
  * @access public
  * @var string
  * @name $vreq
  */
  var $vreq;
  
  /** 
  * The name of the current form element
  *   
  * @access public
  * @var string
  * @name $name  
  */
  var $name;
  
  /** 
  * The value of the current form element
  *   
  * @access public
  * @var mixed
  * @name $value
  */
  var $value;
  
  /** 
  * The size attribute of the current form element
  *   
  * @access public
  * @var string
  * @name $size  
  */
  var $size;
  
  /** 
  * The type attribute of the current form element
  *   
  * @access public
  * @var string
  * @name $atype 
  */
  var $atype;
  
  /** 
  * Potential values for a Select/Checkradio element
  *   
  * @access public
  * @var array
  * @name $values  
  */
  var $values;
  
  /** 
  * Name marker for compound elements, is split from compound name using a pipe delimiter
  *   
  * @access public
  * @var string
  * @name $tagname
  */
  var $tagname;
  
  /** 
  * Type marker for compound elements, is split from compound type using a pipe delimiter
  *   
  * @access public
  * @var string
  * @name $tagatype  
  */
  var $tagatype;
  
  /** 
  * Attribute marker for compound elements, is split from compound type using a pipe delimiter
  * 
  *	@access public
  * @var array
  * @name $tagattr  
  */
  var $tagattr;
  
  /** 
  * The validation pattern for a specific form element, taken from ObjXMLValidDoc
  *   
  * @access public
  * @var regex
  * @name $thePat  
  */
  var $thePat;
  
  /** 
  * Result of regex validation on a specific form element 
  *  
  * @access public
  * @var boolean
  * @name $gotIt  
  */
  var $gotIt;
  
  /** 
  * If a form element is to be validated, this is set to true
  *  
  * @access public
  * @var boolean
  * @name $doval  
  */
  var $doval;
  
  /** 
  * Should validation errors be displayed to the end user
  *   
  * @deprecated
  *	@access public
  * @var boolean
  * @name $showerrors  
  */
  var $showerrors; 
  
  /** 
  * Result of all form validation, skipping elements with VNN or FALSE requirement
  *  
  * @access public
  * @var boolean
  * @name $validated  
  */
  var $validated;
  
  /** 
  * Should the Validator be run in debug mode. Debug STOPS ALL OUTPUT.
  *  
  * @access public
  * @var int
  * @name $debug  
  */
  var $debug;
  
  /** 
  * Should the debug output be send in CLI Color Mode
  *  
  * @deprecated
  *	@access public
  * @var boolean
  * @name $debug  
  */
  var $terminal_debug;
  
  /** 
  * Configuration variable declaring the base location of XML Assets
  *   
  * @access public
  * @var string
  * @name $assetlocation
  */
  var $assetlocation;
  
  /** 
  * Array of form element types to be used in "select", selectdb, state, country, etc...
  *   
  * @var array
  * @name $select_types  
  */
  var $select_types = array();
  
  /** 
  * Array of form element types to be used in "compounds", monthday, monthyear, etc...
  *   
  * @access public
  * @var array
  * @name $compound_types  
  */
  var $compound_types = array();
	
  /** 
  * Array of Dynamic Selects for select and checkradio values
  *   
  * @access public
  * @var array
  * @name $dynarray 
  */
  var $dynarray = array();
	
  /** 
  * Specific location used in framework integrations
  *   
  * @access public
  * @var array
  * @name $module 
  */
  var $module;
  
  
  /** 
  * Array of fields that encountered an error, name only
  *   
  * @access public
  * @var array
  * @name $step
  */
  var $errors;
  
	/**
	* Form Constructor.
	* 
	* @name Constructor  
	* @param string $module - Framework location of module or widget
	*/
  function __construct( $module = null ){
    if (in_array('basedir', $GLOBALS) && strlen($GLOBALS['basedir']) > 0) {
      $this -> assetlocation = $GLOBALS['basedir'];
    }
    if ($module != null) {
      $this -> module = $module;
    }
    $this -> debug = 0;
    $this -> err = 0;
    $this -> errors = Array();
    $this -> validated = false;
    $this -> setValidators();
    $this -> setTypes();
    $this -> terminal_debug = false;
  }

  /**
  * Loads the validator regex patterns from an external XML File
  *
  * @name  setValidators  
  */
  function setValidators() {
  	$this -> theXMLValidDoc = "xml/StyroformValidators.xml";
    $this -> objXMLValidDoc = new XML();
  	$this -> objXMLValidDoc -> loadXML($this -> theXMLValidDoc);
	}
	
  /**
  * Loads the compound and select types from an external XML File
  *   
  * @name setTypes  
  */
	function setTypes() {
  	$this -> theXMLCompDoc = dirname(__FILE__)."/xml/StyroformTypes.xml";
    $this -> objXMLCompDoc = new XML();
  	$this -> objXMLCompDoc -> loadXML($this -> theXMLCompDoc);
  	$this -> setSelectors();
  	$this -> setCompounds();
	}
	
  /**
  * Assigns the select types to an array from the Parsed XML Object
  *   
  * @name  setSelectors
  */
	function setSelectors() {
    $i = 0;
  	foreach ($this -> objXMLCompDoc -> query("//selects/selector/name") as $node ) {
      $this -> select_types[$i] = $this -> objXMLCompDoc -> getNodeValue ($node);
      $i++;
    }
  }
  
  /**
  * Assigns the compound types to an array from the Parsed XML Object
  *
  *	 @name setCompounds
  */
  function setCompounds() {
    $i = 0;
    foreach ($this -> objXMLCompDoc -> getElementsByTagName("compound") as $node ) {
  	  $j = 0;
      $type = array();
      foreach ( $this -> objXMLCompDoc -> getElementsByTagName("element",$node) as $elementnode ) {
        $element[0] = $this -> objXMLCompDoc -> getNodeAttribute($elementnode,"name" );
        $element[1] = $this -> objXMLCompDoc -> getNodeAttribute($elementnode,"type" );
        $type[$j] =  $element;
        $j++;
      }
      $compounds[$i] = $this -> objXMLCompDoc -> getNodeAttribute($node,"name");
      $types[$i] = $type;
      $i++;
    }
    $this -> compound_types[0] = $compounds;
    $this -> compound_types[1] = $types;

  }
  
  /**
  * Assigns the Parent XML Form and pulls all form elements onto the local nodelist
  *
  * @name initValidation  
  * @param string $objXML
  */
	function initValidation( $objXML ) {
		$this -> objXMLDoc = $objXML;
		$this -> showerrors = ($this -> objXMLDoc -> getNodeContent("//SHOWERRORS") == "TRUE") ? true : false;
    $this -> objElements = $this -> objXMLDoc -> getElementsByTagName("FORMELEMENT");
	}
	
  /**
  * Iterate through the form elements and validate each using the regex patterns
  *
  *
  * @name checkForm
  */
  function checkForm(){
    
    if (! $this -> readSig()) {
      redirect("error");
    }
    
    foreach($this->objElements as $node){
      $this -> currentElement = $node;
      $this -> initCurrentItem();
      if ($this -> isCompound()) {
        $this -> doDebug(": THIS IS A COMPOUND ELEMENT: ");
        foreach ($this -> compound_types[1][$this -> whichCompound()] as $element) {
          $this -> doDebug("NAME IS : ".$this -> tagname."|".$element[0]."|comp"." ");
          $this -> name = $this -> tagname."|".$element[0]."|comp";
          $this -> value = @$_POST[$this -> name];
          $this -> tagattr = $element[0];
          $this -> atype = $element[1];
          $this -> validateItem();
        }
      } else {
        $this -> validateItem();
      }
      
    }
    
    $this -> setGlobalErrorNotice();
    
    if ($this -> err > 0) {
      $this -> doDebug("Total Errors: ". $this -> err, false);
    } else {
      $this -> doDebug("Total Errors: ". $this -> err);
    }
    if ($this -> validated) {
      (sfConfig::get("app_form_beacons")) ? beacon( false, "Server Sumbit for Form:: ".$_POST["styroname"], "Server POSTED" ) : null;
    } else {
      (sfConfig::get("app_form_beacons")) ? beacon( false, "Server Sumbit for Form:: ".$_POST["styroname"], "Server INVALID DATA" ) : null;
    }
    
    return $this -> validated;
  }
  
  
  /**
  * Decode the HMAC Signature and see if we're posting from the right place
  *   
  * @name readSig
  */
  function readSig() {
    if ($this -> objXMLDoc -> getPathAttribute("//FORMNAME","signature") == "false") {
      return true;
    }
    if (sfConfig::get("sf_encrypt_key") !='') {
      
      $hash = $_POST["key_sig"];
      $expires = $_POST["expires"];
      $hmac_sig = $_POST["data_sig"];
      
      $val = trim(mcrypt_decrypt(MCRYPT_TRIPLEDES, sfConfig::get("sf_encrypt_key"), base64_decode($hmac_sig), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_TRIPLEDES, MCRYPT_MODE_ECB), MCRYPT_RAND)));
      
      if ($val == $hash."=".$expires) {
        return true;
      } else {
        $this -> err++;
        return false;
      }
    }
    return true;
  }
  
  /**
  * Create an Error Element in the Form
 
  * @name createSingleError  
  * @param string $elementname - Name of the Form element
  * @param string $objXML - Form Object (Optional)
  */
  function createSingleError( $elementname, $objXML=false) {
    if ( $objXML ) {
      $this -> objXMLDoc = $objXML;
    }
    if (! $this -> objXMLDoc ) {
      die("Must pass the XML Document to the Validator");
    }
		$this -> showerrors = ($this -> objXMLDoc -> getNodeContent("//SHOWERRORS") == "TRUE") ? true : false;
    $element = $this -> objXMLDoc -> query("//FORMELEMENT[NAME='".$elementname."']");
    $this -> currentElement =  $element -> item(0);
    $this -> initCurrentItem();
    $this -> createError();
  }
  
  /**
  * Parse the Current Element Node and assign attributes locally
  * 
  *Attributes are:
  * <code>
  * 	VTIP - Validation Type
  * 	REQUIRED - TRUE|FALSE|VNN (Value Not Null)
  * 	NAME - Name of Current Element	  
  * 	SIZE - Used for Text Element Validation
  * 	TYPE - Type of Element	  
  * </code>				  
 
  * @name initCurrentItem
  */
  function initCurrentItem() {
    $this -> vtip = $this -> objXMLDoc -> getValueByTagName("VTIP",$this -> currentElement);
    $this -> vreq = $this -> objXMLDoc -> getValueByTagName("REQUIRED",$this -> currentElement);
    $this -> name = $this -> objXMLDoc -> getValueByTagName("NAME",$this -> currentElement);
    $this -> size = $this -> objXMLDoc -> getValueByTagName("SIZE",$this -> currentElement);
    $this -> tagname = $this -> name;
    $this -> atype = $this -> objXMLDoc -> getValueByTagName("TYPE",$this -> currentElement);
    if (in_array($this -> atype,$this -> select_types)) {
      @$this -> value = $_POST[str_replace("[]","",$this -> name)];
    } else {
      @$this -> value = $_POST[str_replace("[]","",$this -> name)];
    }
    $this -> tagatype = $this -> atype;
    $this -> setValues();
  }
  
  /**
  * Reads the values of the current item, if a select, or compound element
  *   
  * @name setValues
  */
  function setValues() {
    if ($this -> atype == "selectdb") {
      $THETABLE = $this -> objXMLDoc -> getAttributeValueByPath("//FORMELEMENT[NAME='" . $this -> name . "']/DEFAULT", "table");
      $THEARRAY = $this -> objXMLDoc -> getAttributeValueByPath("//FORMELEMENT[NAME='" . $this -> name . "']/DEFAULT", "array");
  		if ($THETABLE) {
        $thisselect = new XMLSelect;
        $thisselect -> table = $THETABLE;
			  $this -> values = $thisselect -> ArrayReturn();
      } elseif ($THEARRAY) {
        $this -> values = $this -> dynarray[$THEARRAY];
      }
    } elseif ($this -> atype == "state") {
      $statesdoc = $_SERVER["DOCUMENT_ROOT"]."/".$this -> assetlocation."xml/states.xml";
      $states = new XML();
    	$states -> loadXML($statesdoc,true);
    	$i = 0;
    	foreach ($states -> query("//ASTATE") as $node ) {
        $state[$i] = $node -> getAttribute ("abbr");
        $i++;
      }
      $this -> values = $state;
    } else {
      $this -> values = count($this -> objXMLDoc -> getElementsByTagName("VALUE",$this -> currentElement));
    }
  }
  
  /**
  * Validates a form element's value against the regex pattern, or optionally a
  * function if available.
  *  
  * @name validateItem
  */
  function validateItem(){
    
    $this -> doDebug($this -> name . "=" . $this -> value. " :REQ-> ". $this -> vreq . " :EVAL-> ". $this -> vtip );
    
    if ($this -> isSelect()) {
      if ($this -> isMultiple()) {
        $this -> atype = "selectmultiple";
      } else {
        $this -> atype = "selectone";
      }
    }
    
    $this -> regExFetch();
    
    if (($this -> vtip != "") && (strlen($this -> vtip) > 0)) {
			$this -> doval = true;
		} else {
   		$this -> doval = false;
		}
		
		$this -> doDebug(" :DOVAL-> " . (($this -> doval) ? "YES" : "NO"));
		
    if (($this -> vreq == "VNN") && (strlen($this -> value > 0))) {
      $this -> vreq = "TRUE";
    } elseif (($this -> vreq == "VNN") && (strlen($this -> value == 0))) {
      $this -> vreq = "TRUE";
      $this -> doval = false;
    }
    
    if (($this -> doval) && ($function_name = $this -> ValidType())) {
				$this -> doDebug(" testing as ".$function_name."... ");
        
        if (substr($this -> vtip,0,2) == "js") {
          //This "Variable Function" is similar to an "eval" statement
          //But is specific to PHP as far as I know
          //Uses the first element as the function name
          //Will also pass the "name" of the form element in play to the function
          if (! $function_name[0]( $function_name[1] ) ) {
           $this -> createError();
      		} else {
      			$this -> resetField();
      		}
		    } else {
          $this->$function_name();
        }
    }

  }
  
  /**
  * Determines which function to call to perform validation, using the element VTIP
  * attribute. Note, js validation returns an identical function name for PHP.
 
  * @name validType
  */
  function validType() {
		if (substr($this -> vtip,0,2) == "js") {
		  $function = str_replace("js:", "", $this -> vtip );
      preg_match("/(.*)\((.*)\)/", $function, $attributes);
      //Funny results from this regex, but it does the split
      $thefunction[0] = str_replace(" ","",$attributes[1]);
      $thefunction[1] = str_replace($attributes[1],"",$attributes[0]);
      $thefunction[1] = str_replace(array("(",")"),"",$thefunction[1]);
      $thefunction[1] = str_ireplace("this.currentelement","\$this->name",$thefunction[1]);
      return $thefunction;
      //return substr($this -> vtip,3,-2);
    } elseif ($this -> atype == 'image') {
      return false;
    } elseif (($this -> atype == "file") || ($this -> atype == "selectmultiple") || ($this -> atype == "selectone") || ($this -> atype == "text") || ($this -> atype == "textarea") || ($this -> atype = "checkbox") || ($this -> atype == "radio") || ($this -> atype == "")) {
			return "validate_" . $this -> atype ;
		} else {
   	  return false;
		}
	}
  
  /**
  * Pulls the actual regular expression from the Validation Types
 	*
  * @name regExFetch
  */
  function regExFetch() {
    if (($this -> vtip != "") && ($this -> vtip != "OMIT") &&  (! is_numeric($this -> vtip)) && (substr($this -> vtip,2) != "js")) {
			$this -> thePat = $this -> objXMLValidDoc -> getNodeContent("//validator[type='" . $this -> vtip . "']/expression");
			//var_dump($this -> thePat);
			//echo("<br />");
      //$this -> thePat = substr($regPat,-2);
		} else {
			$this -> thePat = ".*";
		}
  }
  
	/***** VALIDATORS ******/
  
  
  /**
  * Generic validation function for non-compliant form element validation
  *  
  * @namevalidate_
  */
    function validate_() {
	 return true;
  }
  
  /**
  * Validates File Input Elements
  *  
  * @name validate_file
  */
  function validate_file() {
   $mime = $_FILES["FILE_".$this->name]["type"];
   $size = $_FILES["FILE_".$this->name]["size"];
	 return true;
  }
  
  /**
  * Validates Text Input Elements
  *  
  * @name validate_text
  */
  function validate_text(){
    
   if ($this -> vtip == 'truncURL') {
    $initStart = $this -> regExTest($this -> value, "/^http(s)?/");
	  if ($initStart) {
	    $theval = str_replace("https://", "", $this -> value);
  	  $theval = str_replace("http://", "", $this -> value);
  	  if ($theval == "") {
  	    $this -> gotIt = true;
      } else {
        $this -> gotIt = $this -> regExTest($theval, $this -> thePat);
      }
    } else {
      $this -> gotIt = false;
    }
  } elseif (substr($this -> vtip,0,2) != "js") {
			if (($this -> vtip == "phoneNumber") && (strlen($this -> value) > 0)) {
				$this -> value = str_replace("-","",$this -> value);
				$this -> value = str_replace("(","",$this -> value);
				$this -> value = str_replace(")","",$this -> value);
				$this -> value = str_replace(".","",$this -> value);
				$this -> setValue ($this -> name,$this -> value);
				if ((substr($this -> value,1) == 0) || (substr($this -> value,1) == 1)) {
					$this -> setValue ($this -> name,substr($this -> value,1,strlen($this -> value)));
				}
			}
			$this -> gotIt = $this -> regExTest($this -> value , $this -> thePat);
		} else {
		  $funcname = str_replace("js:","", $this -> vtip);
			$theresult = $this -> $funcname();
			if ($theresult) {
				$this -> gotIt = true;
			} else {
				$this -> setDisplayError($this -> name);
				$this -> setValue ($this -> name . "_error",$theresult);
				$this -> gotIt = false;
			}
		}
		
		$this -> doDebug($this -> name . "( Req:". $this -> vreq ." ) --> ".(($this -> gotIt) ? "PASSED" : "FAILED" . " ( Vtip " .$this -> vtip . " ) VALUE of '".$this -> value."'"), (((! $this -> gotIt) && ($this -> vreq == "TRUE")) ? false : true));
		
    if ((! $this -> gotIt) || ($this -> gotIt == "")) {
      $this -> createError();
		} else {
			$this -> resetField();
		}
    
  }

  /**
  * Validates Textarea Input Elements
  *  
  * @name validate_textarea
  */
  function validate_textarea(){
    if ((($this -> size > 0) && (strlen($this -> value) > $this -> size)) || (! $this -> RegExTest($this -> value, $this -> thePat))) {
			$this -> createError();
		} else {
			$this -> resetField();
		}
    
  }
  
  /**
  * Validates Checkbox Input Elements
  *  
  * @name validate_checkbox
  */
  function validate_checkbox(){
    if (is_numeric($this -> vtip)) {
			if ((($this -> vreq == "TRUE") && ($this -> fieldCheckCount() == 0)) || ($this -> fieldCheckCount() > $this -> vtip)) {
				//$this -> err++;??
				$this -> createError();
			} else {
			 	$this -> resetField();
			}
		}
  }
  
  /**
  * Validates Radio Input Elements
  *  
  * @name  validate_radio
  */
  function validate_radio(){
    if ((($this -> vtip > 0) && ($this -> fieldChecked() == $this -> vreq)) || ($this -> vtip == 0)) {
  		if (! $this -> fieldChecked()) {
  			$this -> createError();
  		} else {
  			$this -> resetField();
  		}
  	}
  }
  
  /**
  * Validates Select Input Elements
  *  
  * @name validate_selectone
  */
  function validate_selectone() {
    if (is_array($this -> value)) {
      foreach($this -> value as $val) {
        if (($val == $this -> vtip) || (strlen($val) == 0)) {
    			$this -> createError();
    			$this -> gotIt = false;
    		} else {
       		$this -> resetField();
       		$this -> gotIt = true;
      	}
        $this -> doDebug($this -> name . "( Req:". $this -> vreq ." ) --> ".(($this -> gotIt) ? "PASSED" : "FAILED" . " ( Vtip " .$this -> vtip . " ) VALUE of '".$this -> value."'"), (((! $this -> gotIt) && ($this -> vreq == "TRUE")) ? false : true));		
      }
    } else {
      if (($this -> value == $this -> vtip) || (strlen($this -> value) == 0)) {
  			$this -> createError();
  			$this -> gotIt = false;
  		} else {
     		$this -> resetField();
     		$this -> gotIt = true;
    	}
    	$this -> doDebug($this -> name . "( Req:". $this -> vreq ." ) --> ".(($this -> gotIt) ? "PASSED" : "FAILED" . " ( Vtip " .$this -> vtip . " ) VALUE of '".$this -> value."'"), (((! $this -> gotIt) && ($this -> vreq == "TRUE")) ? false : true));
			
    }
    
    
  }
  
  /**
  * Validates Multiple Select Input Elements
  *  
  * @name  validate_selectmultiple
  */
  function validate_selectmultiple() {
    
    $tmp = explode("|",$this -> vtip);

    $thevtip = $tmp[0];
    if(count($tmp) >= 2)
    {
      $thevtot = $tmp[1];
    }
    else
    {
      $thevtot = 0;
    }
    
    $erra = false;
    $allkeys[0] = $this -> objXMLDoc -> getValueByTagName("DEFAULT",$this -> currentElement);
    $allvals[0] = 0;
    $allkeys = $this->shift($allkeys,$this->values,"sel_key");
    $allvals = $this->shift($allvals,$this->values,"sel_value");
    
    //if one of the selected values = the restricted value
    if ($thevtip != "null") {
      foreach ($this->value as $value) {
        if ((array_search($value,$allvals,true)) && ($this -> vreq == "TRUE")) {
         if ($this -> value[array_search($value,$allvals,true)] == $thevtip) {
           $erra = true;
           break;
         }
        }
      }
    }
    //if the total selected is greater than the total allowed
    //if zero, there is no limit
    if ($thevtot != 0) {
      if ((count($this->value) > $thevtot) && ($this -> vreq == "TRUE")) {
        $erra = true;
      }
    }
    
    if ($erra) {
  		$this -> createError();
  		$this -> gotIt = false;
    } else {
    	//this.currentElement.thevalue = document.forms[theForm.name].elements[name].options[document.forms[theForm.name].elements[name].selectedIndex].value
    	$this ->resetField();
    	$this -> gotIt = true;
    }
  }
  
  /**
  * Validates Submit Elements
  *  
  * @name validate_submit
  */
  function validate_submit() {
    return true;
  }
  
  
  /**
  * Used to assign array values from select arrays into a more managable format
  *  
  *	@name shift  
  * @param array $source
  * @param array $array
  * @param string $key
  */
  function shift($source, $array, $key) { 
    if($array)
    {
      foreach ($array as $val) { 
        $source[] = $val[$key];
      }
    }
    return $source;
  }
  
  /**
  * Counts the number of checked elements were submitted in a form
  *  
  * @name fieldCheckCount
  */
  function fieldCheckCount() {
    if (! is_array($this -> value)) {
      $thevalues = explode(",", $this -> value);
		} else {
      $thevalues = $this -> value;
    }
    return count($thevalues);
	}
	
 /**
  * Determines with field index is selected in a checkradio form element
  *  
  *	@name fieldChecked
  */
	function fieldChecked() {
	  $thevalues = explode(",", $this -> value);
		if ((count($thevalues) + 1) > 0) {
			return count($thevalues) + 1;
		} else {
   		return false;
		}
	}
	
 /**
  * Pushes an error onto the error array, and increments the error count
  *
  *		@name createError
  */
	function createError() {
	  $this -> errors[$this -> name] = ($this -> value == "") ? "null" : $this -> vtip ;
	 
    $this -> setError( $this -> tagname );
		if ($this -> vreq == "TRUE") {
			$this -> err++;
      $this -> doDebug("FAILED (".$this -> thePat. ") --> ERRORS: ".(($this -> err)));
		} else {
      $this -> doDebug("FAILED --> ERRORS Stay the Same");
    }
	}
	
  /**
  * Deprecated, pulled from the JS Validator Prototype
  *
  *		@name  resetField
  */
	function resetField(){
    // echo "reset<BR>";
  }
  
  /**
  * Sets the state of the form validator for this form. If any errors are found,
  * this is set to false
  *	  
  * @name setGlobalErrorNotice
  */
  function setGlobalErrorNotice() {
	 if ($this -> err > 0) {
	    $this -> setError("errornotice");
			//$_SESSION["styroform_error"] = "POST:".$this -> page;
      $this -> validated = false;
		} else {
			$this -> validated = true;
		}
	}
	
  /**
  *	Applies and error attribute to the "CLASS" subnode of the XML Form Element
  *		 
  *	@name setError
  * @param string $elementname - FPO copy here
  */
	function setError( $elementname ) {
	  if ($this -> isCompound()) {
      $this -> objXMLDoc -> setPathAttribute("//FORMELEMENT[NAME='" . $elementname . "']/CLASS", 0, "error_".$this -> tagattr, "TRUE" );
    } else {
	    $this -> objXMLDoc -> setPathAttribute("//FORMELEMENT[NAME='" . $elementname . "']/CLASS", 0, "error", "TRUE");
	  }
	}
	
  /**
  *	Applies and error attribute to the "DISPLAYCLASS" subnode of the XML Form Element
  *		 
  * @name setDisplayError
  * @param string $elementname - FPO copy here
  */
	function setDisplayError( $elementname ) {
		$this -> objXMLDoc -> setPathAttribute("//FORMELEMENT[NAME='" . $elementname . "']/DISPLAYCLASS", 0, "error", "TRUE");
	}
	
  /**
  * Sets the default value of a form element
  *
  *	@name setValue 
  * @param string $elementname
  * @param string $value
  */
	function setValue( $elementname, $value ) {
		$this -> objXMLDoc -> setValueByPath("//FORMELEMENT[NAME='" . $elementname . "']/DEFAULT", 0, $value);
	}
	
  /**
  * Convenience method to determine if an element is a select element
  *
  *	@name isSelect
  */
	function isSelect(){
	  if (in_array($this->atype,$this -> select_types)){
      return true;
    }
    return false;
  }
  
  /**
  * Convenience method to determine if an element is a multiple select element
  *
  *	@name isMultiple
  */
  function isMultiple(){
    if ($this -> objXMLDoc -> getAttributeValueByPath ( "//FORMELEMENT[NAME='" . $this -> name . "']/TYPE","multiple" ) != false) {
      return true;
    }
    return false;
  }
  
  /**
  * Convenience method to determine if an element is a compound element
  *
  * @name isCompound
  */
  function isCompound() {
    if (in_array($this->tagatype,$this -> compound_types[0])){
      return true;
    } else {
      //echo("<br />This type is: ".$this->tagatype);
      //echo("<br />Evaluates as: ".in_array($this->tagatype,$this -> compound_types[0])."<br />");
    }
    return false;
  }
  
  /**
  * Returns the compound subtype of a specific compound type
  *	 
  *	@name whichCompound
  */
  function whichCompound() {
    return array_search($this->atype,$this -> compound_types[0]);
  }
   
  /**
  * Performs a regular expression match
  *   
  * @name  regExTest  
  * @param string $str - String Value
  * @param string $expr - Regex Pattern
  */
  function regExTest($str, $expr){
    if ($expr == '') {
      die($this -> name . " has no valid test expression.");
    }
    if (is_array($str)) {
      $str = $str[0];
    }
    if (preg_match($expr, $str) > 0) {
      return true;
    }
    return false;
  }
  
  /**
  * Performs a regex replace on a string
  *  
  * @name regExEval  
  * @param string $str - String
  * @param string $expr - Pattern
  */
  function regExEval($str,$expr) {
		return preg_replace($expr,$str,"$1");
	}
	
	/**
  * Prints debug messages either in Color CLI format, or as text
  *  
  * @name regExEval  
  * @param string $message - FPO copy here
  * @param string $result - FPO copy here
  */
  function doDebug($message,$result=true) {
    if ($this -> debug == 2) {
      if ($this -> terminal_debug) {
        if ($result) {
          cli_text($message,"cyan");
        } else {
          cli_text($message,"red");
        }
      } else {
        echo($message."<br />");
      }
    }
    if ($this -> debug == 1) {
      if ($this -> terminal_debug) {
        if (! $result) {
          cli_text($message,"red");
        }
      } else {
        echo($message."<br />");
      }
    }
  }
  
}


?>
