<?

/**
 *WTVRMailFilter.php, Styroform XML Form Controller
 * 
 * XML Form Generator and Validator
 * in-code documentation through DocBlocks and tags.
 * @author Andy Madsen <amadsen@motormeme.com>
 * @version 1.2
 * @package XMLForm
 */
// WTVRMailFilter

/**
* Inherits the includes from WTVR
* You can use Styroform XML or DOM
*/
include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLFormUtils.php");

/**
 * The Form Controller class, responsible for drawing the form, calling the validator, adding "Items" and "ItemLists", and navigating through formsets
 * @package WTVRMailFilter
 * @subpackage classes
 */
class WTVRFilter extends GlobalBase {
  
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $documentElement 
  */
  var $documentElement;
  
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $p_object  
  */
  var $VARIABLES;
  
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $p_object  
  */
  var $p_object;
  
  
  /**
  * Form Constructor.
  * The formsettings array should look like this, either passed in the constructor or via WTVR:
  * Pass the DOM Tree to the Constructor
  * <code> 
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  * </code>
  * @name Constructor
  * @param string $object - FPO copy here
  * @param string $nodevalue - FPO copy here
  * @param array $persistence  - Array with both Formset, and XSL Doc
  */
  function __construct( $vars ) {
    parent::__construct();
    $this -> VARIABLES = $vars;
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
  * @name xml 
  */
  function persistence() {
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
  * @name xml 
  */
  function xml( $object, $persistence ) {
    $this -> p_object = $persistence;
    
    if (is_string($object)) {
      $this -> documentElement = new DOMDocument();
      $this -> documentElement -> loadXML ( $object );
    }  else {
      $this -> documentElement = $object;
    }
    
    return $this -> documentElement;
  }
  
  /**
  * Form Destructor.
  * The formsettings array should look like this, either passed in the constructor or via WTVR:
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * <code> 
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  * </code>
  * @name Destructor.
  * @param array $formsettings  - Array with both Formset, and XSL Doc
  */
  function __destruct() {
    
  }
}

?>
