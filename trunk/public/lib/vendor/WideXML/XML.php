<?php

/**
 * XML.php, Styroform XML Form Controller
 * 
 * XML Form Generator and Validator
 * in-code documentation through DocBlocks and tags.
 * @author Andy Madsen <amadsen@motormeme.com>
 * @version 1.2
 * @package XMLForm
 */
// XML
class XML {

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
  * @name $url
  */
  var $url;
  
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $xpath 
  */
  var $xpath;
  
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $node 
  */
  var $node;
  
    
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
*/
  function __construct () {
      $this -> documentElement = new DomDocument();
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
  * @name  loadXML
  * @param string $xml - FPO copy here
  * @param string $lib - FPO copy here
  */
  function loadXML($xml,$lib=true,$debug=false,$force_string=false) {
    
    if ($xml != false) {
      $xmltrace = $xml;
      
      if (( substr($xml,0,2) != "<?" ) && (! $force_string)) {
        // check for file
        if (! $xml = file_get_contents($xml,$lib)) {
          echo("couldn't load ".$xmltrace." from lib? ".$lib);
          die();
        }
      } 
      if ( is_string($xml) ) {
        
        $this -> documentElement = new DomDocument();
        if (! $this -> documentElement -> loadXML($xml)) {
          dump("not loaded".$xml);
        }
        //if($debug)
          //$this -> documentElement -> saveXML();
        return true;
      } else {
        $this -> documentElement = new DomDocument();
        return true;
        
        //if ($this -> documentElement = $xml) {
        //  return true;
        //}
      }
      
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
  * @name  appendValues
  * @param string $location - FPO copy here
  * @param string $node - FPO copy here
  */
  function appendValues($location,$node){
    $TEMPELEMENT_ONE = $this -> query($location);
    if ($TEMPELEMENT_ONE -> item(0)) {
			$TEMPELEMENT_ONE -> item(0) -> appendChild($node);
    }
    //$this -> _debug();
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
  * @name  createElement
  * @param string $name - FPO copy here
  */
  function createElement($name) {
    return $this -> documentElement -> createElement($name);
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
  * @name  createSingleElement
  * @param string $elementname - FPO copy here
  * @param string $position - FPO copy here
  * @param string $attribs - FPO copy here
  * @param string $thevalue - FPO copy here
  * @param string $index - FPO copy here
  */
  function createSingleElement($elementname,$position,$attribs=false,$thevalue=false,$index=0) {
    $TEMPELEMENT_ONE = $this -> documentElement -> createElement($elementname);
    if ($attribs) {
      foreach ($attribs as $key => $value) {
        $TEMPELEMENT_ONE -> setAttribute(str_replace(" ","_",$key), $value);
      }
    }
    if ($thevalue) {
      //$TEMPELEMENT_ONE -> createCDATASection($thevalue);
      $TEMPELEMENT_ONE -> nodeValue = $thevalue;
    }
    
		$TEMPELEMENT_TWO = $this -> documentElement -> getElementsByTagname($position);
		
		$TEMPELEMENT_TWO -> item($index) -> appendChild($TEMPELEMENT_ONE);
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
  * @name  createSingleElement
  * @param string $elementname - FPO copy here
  * @param string $position - FPO copy here
  * @param string $attribs - FPO copy here
  * @param string $thevalue - FPO copy here
  * @param string $index - FPO copy here
  */
  function createSingleCdataElement($elementname,$position,$attribs=false,$thevalue=false,$index=0) {
    $TEMPELEMENT_ONE = $this -> documentElement -> createElement($elementname);
    if ($attribs) {
      foreach ($attribs as $key => $value) {
        $TEMPELEMENT_ONE -> setAttribute($key, $value);
      }
    }
    if ($thevalue) {
      //$TEMPELEMENT_ONE -> createCDATASection($thevalue);
      $this -> appendCdata($TEMPELEMENT_ONE,$thevalue);
    }
    
		$TEMPELEMENT_TWO = $this -> documentElement -> getElementsByTagname($position);
		$TEMPELEMENT_TWO -> item($index) -> appendChild($TEMPELEMENT_ONE);
  }
  
    /**
   * Append Caracter Data to a node and check for a javascript node
   *
   * @param DOMElement $appendToNode
   * @param string $text
   */
  function appendCdata($appendToNode, $text)
  {
      if (strtolower($appendToNode->nodeName) == 'script') {  // Javascript hack
          $cm = $appendToNode->ownerDocument->createTextNode("\n//");
          $ct = $appendToNode->ownerDocument->createCDATASection("\n" . $text . "\n//");
          $appendToNode->appendChild($cm);
          $appendToNode->appendChild($ct);
      } else {  // Normal CDATA node
          $ct = $appendToNode->ownerDocument->createCDATASection($text);
          $appendToNode->appendChild($ct);
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
  * @name  createSingleElementByPath
  * @param string $elementname - FPO copy here
  * @param string $path - FPO copy here
  * @param string $attribs - FPO copy here
  * @param string $thevalue - FPO copy here
  * @param string $parent - FPO copy here
  */
  function createSingleElementByPath($elementname,$path,$attribs=false,$thevalue=false,$parent="//AFORM") {
    
    $TEMPELEMENT_ONE = $this -> documentElement -> createElement($elementname);
    if ($attribs) {
      foreach ($attribs as $key => $value) {
        $TEMPELEMENT_ONE -> setAttribute($key, $value);
      }
    }
    if ($thevalue) {
      $TEMPELEMENT_ONE -> nodeValue = $thevalue;
    }
    
    $results = $this -> query($path);
    
    // Insert the new element
    if ($results -> length != 1) {
      $parent = $this -> query($parent);
      if ($parent -> length > 0) {
        $parent->item(0)->appendChild($TEMPELEMENT_ONE);
      }
    } elseif ($results -> length == 1) {
      $results->item(0)->parentNode->insertBefore($TEMPELEMENT_ONE, $results->item(0)); 
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
  * @name  appendSingleElement
  * @param string $elementname - FPO copy here
  * @param string $node - FPO copy here
  * @param string $attribs - FPO copy here
  * @param string $value - FPO copy here
  */
  function appendSingleElement($elementname,$node,$attribs=null,$value=null) {
    $TEMPELEMENT_ONE = $this -> documentElement -> createElement($elementname);
    
    if ($attribs != null) {
      foreach ($attribs as $key => $avalue) {
        $TEMPELEMENT_ONE -> setAttribute($key, $avalue);
      }
    }
    if ($value != null) {
      //$TEMPELEMENT_TWO = $this -> documentElement -> createCDATASection($value);
      //$TEMPELEMENT_ONE -> appendChild($TEMPELEMENT_TWO);
      $newnode = $this -> documentElement ->createTextNode($value);
      $TEMPELEMENT_ONE -> appendChild( $newnode );
    }
		$node -> appendChild($TEMPELEMENT_ONE);
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
  * @name  appendByPath
  * @param string $path - FPO copy here
  * @param string $node - FPO copy here
  * @param string $pos=0 - FPO copy here
  */
  function appendByPath($path,$node,$pos=0) {
    $FINALPOS = $this -> query($path);
    
    if ($FINALPOS) {
      $FINALPOS -> item($pos) -> appendChild($node);
      return true;
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
  * @name  createAndAppendByPath
  * @param string $path - FPO copy here
  * @param string $elementname - FPO copy here
  * @param string $nodename - FPO copy here
  * @param string $values - FPO copy here
  * @param string $pos=0 - FPO copy here
  */
  
  function createAndAppendByPath($path,$elementname,$nodename,$values,$pos=0) {
    $FINALPOS = $this -> query($path);
    
    $TEMPELEMENT_ONE = $this -> documentElement -> createElement($elementname);
    
    foreach($values as $value) {
      $TEMPTWO = $this -> documentElement -> createElement($nodename);
      $TEMPTWO -> nodeValue = $value;
      $TEMPELEMENT_ONE -> appendChild($TEMPTWO);
    }
    
    $FINALPOS -> item($pos) -> appendChild($TEMPELEMENT_ONE);
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
  * @name  appendToElement
  * @param string $element - FPO copy here
  * @param string $node - FPO copy here
  */
  function appendToElement($element,$node) {
    return $element -> appendChild($node);
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
  * @name  removeSingleElementByPath
  * @param string $path - FPO copy here
  * @param string $index - FPO copy here
  */
  function removeSingleElementByPath($path,$index=0) {
    
    $results = $this -> query($path);
    
    // Insert the new element
    if ($results -> length != 0) {
      $module = $results->item($index);
      //dump($module);
      //$rent = $results->item($index)->parentNode;
      $module -> parentNode->removeChild($module);
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
  * @name  setAttribute
  * @param string $node - FPO copy here
  * @param string $index - FPO copy here
  * @param string $attr_name - FPO copy here
  * @param string $attr_val - FPO copy here
  */
  function setAttribute($node, $index, $attr_name, $attr_val) {
    $results = $this -> documentElement -> getElementsByTagname($node);
    if (($results) && ($results -> length > $index)) {
      $results -> item($index) -> setAttribute($attr_name, $attr_val);
      return true;
    } else {
      //die($node);
      return false;
    }
  }
  
  function setPathAttribute($path, $index, $attr_name, $attr_val) {
    
    $results = $this -> query($path);
    
    if (($results) && ($results -> length > $index)) {
      $results -> item($index) -> setAttribute($attr_name, $attr_val);
      return true;
    } else {
      //die($node);
      return false;
    }
  }
  
  function removePathAttribute($path, $index, $attr_name) {
    $results = $this -> query($path);
    
    if (($results) && ($results -> length > $index)) {
      $results -> item($index) -> removeAttribute($attr_name);
      return true;
    } else {
      //die($node);
      return false;
    }
  }
  
   function removeNodesByName($name, $count=0) {
    $results = $this -> documentElement -> getElementsByTagName($name);
    
    if ($results -> length > 0) {
      $current = $results -> length;
      for ($i=0;$i<$current;$i++) {
        if (($count == 0) || (($count > 0) && ($i <= $count))) {
          $results -> item(0) -> parentNode -> removeChild( $results->item(0) );
        }
      }
      
      return true;
    } else {
      //die($node);
      return false;
    }
  }
  
  function getNodeCount( $path ) {
    $itemcount = $this -> query($path);
    return $itemcount -> length;
  }
  
  function getNodeAttribute ( $node, $attribute ) {
    return $node -> getAttribute( $attribute );
  }
  
  function getNodeAttributeByTagName ( $node, $tagname, $attribute, $index=0 ) {
    $result = $node -> getElementsByTagname($tagname);
    return $this -> getNodeAttribute( $result -> item($index), $attribute );
  }
  
  function getPathAttribute ( $path, $attribute, $index=0 ) {
    return $this -> getAttributeValueByPath ( $path,$attribute,$index );
  }
  
  function getAttributeValueByPath ( $path,$attribute, $index=0 ) {
    $node = $this -> query($path);
    if (($node) && ($this -> getNodeAttribute($node->item(0) ,$attribute))) {
      return $this -> getNodeAttribute($node->item(0) ,$attribute);
    } else {
      return false;
    }
  }
  
  function getPathValue ( $path,$index=0 ) {
    $result = $this -> query($path);
    return $this -> getValue ($result , $index );
  }
  
  function getChildNodes ($node ) {
    if ($node -> hasChildNodes()) {
      return $node -> childNodes();
    } else {
      return false;
    }
  }
  
  function replaceData($node,$value) {
    $element = $this -> documentElement -> createCDATASection ($this -> cleanString($value));
    $node -> nodeValue = "";
    $node -> appendChild($element);
    //$node -> nodeValue = htmlentities($value); 
    return true;
  }
  
  function getNodeContent($path,$index=0){
    $nodeset = $this->query($path);
    return $this -> getValue($nodeset, $index);
  }
  
  function getValue($nodeset, $index){
    if (($nodeset != null) && ($nodeset -> length > $index)) {
      return $nodeset -> item($index) -> nodeValue;
    } else {
      return "";
    } 
  }

  function getValueByTagName($tagname,$node=null,$index=0){
    if(!$node){
      $node = $this->documentElement;
    } else {
      $node = $node;
    }
    $result = $node -> getElementsByTagname($tagname);
    $value = $this -> getValue($result, $index);
    return $value;
  }
  
  function getElementsByTagName($tagname,$dom=null){
    if(!$dom){
      $dom = $this->documentElement;
    } else {
      $dom = $dom;
    }
    
    $result_array = $dom -> getElementsByTagname( $tagname );
    return $result_array;
  }
  
  function getLength($nodelist){
    return count($nodelist);
  }
  
  function getNode($nodelist, $index){
    //print_r($nodelist[$index]);
    return $nodelist -> item($index);
  }
  
  function getNodeValue($node){
    return $node -> nodeValue;
  }
  
  function setValue($nodeset, $index, $newvalue){
    if($nodeset -> item($index)){
      $this -> replaceData($nodeset -> item($index), $value);
    } 
    //return $nodelist->item($index)->nodeValue = $newvalue;
  }
  
  function setValueByPath($path, $index, $newvalue){
    $nodeset = $this -> query($path);
    if (($nodeset) && ($nodeset -> length)) {
      return $this -> replaceData($nodeset -> item($index), $newvalue);
    }
    //return $nodelist->item($index)->nodeValue = $newvalue;
  }

  function getValueByPath($path, $contextnode=null) {
    $node = $this -> selectSingleNode($path, $contextnode);
    if($node) {
      return $this -> getNodeValue($node);
    }
    else {
      return "";
    }
  }
  
  function setCdataByPath($path, $index, $text)
  {
      $nodeset = $this -> query($path);
      if (($nodeset) && ($nodeset -> length)) {
        $node = $nodeset->item($index);
        $ct = $node->ownerDocument->createCDATASection($text);
        $node->appendChild($ct);
      }
  }
  
  function importDeepNode($node,$position){
    $node = $this -> documentElement -> importNode($node, true);
    $thispos = $this -> documentElement -> getElementsByTagname( $position );
    $thispos -> item(0) -> appendChild($node);
  }
  
  //?? If nodeset = false, what do we return?
  function query($query, $contextnode = null) {
    
    if($contextnode === null) {
      $contextnode = $this -> documentElement;
    }
    
    $this -> xpath = new domxpath( $this -> documentElement );
    $result = $this -> xpath -> query($query, $contextnode );
    
    if ($result -> length > 0) {
      return $result;
    } else {
      return false;
    }
  }

  function selectSingleNode($query, $contextnode = null) {
    $nodeset = $this -> query($query, $contextnode);
    if($nodeset && $nodeset -> length > 0) {
      return $nodeset -> item(0);
    }
    else {
      return false;
    }
  }
  
  function drawXMLHeader() {
		header ("Content-type: application/xhtml+xml");
	}
	
	function _writeXML( $path ){
	  //Try to delete the previous file
    if ($this -> documentElement -> save($path)) {
      return true;
    }
    return false;
  }
  
  function saveXML(){
	  $this -> drawXMLHeader();
	  echo $this -> documentElement -> saveXML();
    die();
  }
  
  function showXML(){
	  $this -> saveXML();
  }
  
  function _saveXML(){
	  $this -> saveXML();
  }
  
  function _debug(){
    $this->_saveXML();
  }
  
  function cleanString($str,$encoded=true) {
    $str = preg_replace('/([\xc0-\xdf].)/se', "'&#' . ((ord(substr('$1', 0, 1)) - 192) * 64 + (ord(substr('$1', 1, 1)) - 128)) . ';'", $str);
    $str = preg_replace('/([\xe0-\xef]..)/se', "'&#' . ((ord(substr('$1', 0, 1)) - 224) * 4096 + (ord(substr('$1', 1, 1)) - 128) * 64 + (ord(substr('$1', 2, 1)) - 128)) . ';'", $str); 
    
    return $str;
  }
  
  function xml2Array($xpath = "/*", $xml_data = true, $context_node = NULL)
  {
    static $xml;
    static $xp;
  
    // Add /* to the end of $xpath if it's not there
    if (!preg_match("#/\*$#", $xpath))
      $xpath = preg_replace("#/*$#", "", $xpath) . "/*";
  
    // Create out temporary array that we'll use to build the final array piece by piece
    $tmp_array = array();
  
    // If this is our first time in this function, initialize the DOM objects
    if ($xml_data)
    {
      $xml = $this -> documentElement;
      $xp = new domxpath( $this -> documentElement );
    }
  
    // Get the appropriate nodes for the current path
    $nodelist = ($context_node) ? $xp->query($xpath, $context_node) : $xp->query($xpath);
  
    // This variable is used to keep track of how many times a node with the same name
    // has appeared.  It puts the appropriate count after each one.  ie: [<nodeName>][<count>]
    $counter = array();
  
    // Loop through the current list of nodes
    // If there is more than one child node in the current node with the same name,
    // create a sub array for them and add a counter.
    // E.g. [<element>] = <value>  would become [<element>][0] = <value1> and [<element>][1] = <value2>
    foreach ($nodelist AS $node)
    {
      $counter[$node->nodeName] = (isset($counter[$node->nodeName])) ? $counter[$node->nodeName] + 1 : 0;
  
      if ($xp->evaluate('count(./*)', $node) > 0)
      {
        if ($xp->evaluate('count('.$node->nodeName.')', $node->parentNode) > 1)
          $tmp_array[$node->nodeName][$counter[$node->nodeName]] = $this -> xml2Array($node->nodeName."[".($counter[$node->nodeName]+1)."]", false, $node->parentNode);
        else
          $tmp_array[$node->nodeName] = $this -> xml2Array($node->nodeName, false, $node->parentNode);
      }
      else
      {
        if ($xp->evaluate('count('.$node->nodeName.')', $node->parentNode) > 1)
          $tmp_array[$node->nodeName][$counter[$node->nodeName]] = $node->nodeValue;
        else
          $tmp_array[$node->nodeName] = $node->nodeValue;
      }
    }
  
    return $tmp_array;
  }
}

?>
