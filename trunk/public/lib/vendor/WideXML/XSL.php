<?php

/**
 * XSL.php, Styroform XML Form Controller
 * 
 * XML Form Generator and Validator
 * in-code documentation through DocBlocks and tags.
 * @author Andy Madsen <amadsen@motormeme.com>
 * @version 1.2
 * @package XMLForm
 */
// XSL
class XSL {

  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $xslt
  */
  var $xslt;
  
  /** 
  * Array of XML Forms in an ordered FormSet
  * @access public
  * @var array
  * @name $xslDoc 
  */
  var $xslDoc;
  
  /**
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum
  * <code> 
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  *  $fpo = something();
  * </code>
  * @name  XSL
  */
  function XSL(){
    $this -> xslt = new XSLTProcessor;
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
  * @name  convertDoc
  * @param string $xsl - FPO copy here
  * @param string $xml_obj - FPO copy here
  */
  function convertDoc($xsl,$xml_obj,$nodt=false) {
    $this -> importStyleSheet($xsl);
    if ($nodt) {
      return preg_replace("/<[^>]DOCTYPE[^<]*>/","",$this -> transformToXML($xml_obj));
    } else {
      return $this -> transformToXML($xml_obj);
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
  * @name  saveDoc
  * @param string $xsl - FPO copy here
  * @param string $xml_obj - FPO copy here
  * @param string $path - FPO copy here
  */
  function saveDoc($xsl,$xml_obj, $path) {
    $this -> importStyleSheet($xsl);
    //cli_text("XSL: ".$xsl,"red");
    return $this -> transformToURI($xml_obj, $path);
    
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
  * @name  importStyleSheet
  * @param string $xsl - FPO copy here
  */
  function importStyleSheet($xsl){
    $this -> xslt = new XSLTProcessor;
    $this -> xslDoc = new DomDocument();
    $this -> xslDoc -> load($xsl);
    return $this -> xslt -> importStylesheet( $this -> xslDoc );
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
  * @name  transformToXML
  * @param string $xml_obj - FPO copy here
  */
  function transformToXML($xml_obj){
    return $this -> xslt -> transformToXML($xml_obj);
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
  * @name  transformToURI
  * @param string $xml_obj - FPO copy here
  * @param string $path - FPO copy here
  */
  function transformToURI($xml_obj, $path){
    return $this -> xslt -> transformToURI($xml_obj, 'file://'.$path);
  }

}

?>
