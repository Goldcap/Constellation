<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="xml" indent="yes" omit-xml-declaration="yes" standalone="yes" />
<xsl:preserve-space elements="*"/>

<xsl:template match="ROOT">
  <xsl:text disable-output-escaping="yes">&lt;&#63;php</xsl:text>//##
  //##
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");//##
  //##
  //Data Abstraction classes as needed from propel//##
  //require_once 'crud/<xsl:value-of select="//class/@name" />_crud.php';//##
  //##
  <xsl:apply-templates select="class" />//##
  <xsl:text disable-output-escaping="yes">&#63;&gt;</xsl:text>
</xsl:template>

<xsl:template match="class">
 class <xsl:value-of select="@name" />_PageWidget extends Widget_PageWidget {//##
	//##
  var <xsl:text disable-output-escaping="yes">&#36;XMLForm</xsl:text>;//##
	var <xsl:text disable-output-escaping="yes">&#36;crud</xsl:text>;//##
	//##
	function __construct( <xsl:text disable-output-escaping="yes">&#36;wvars</xsl:text>, <xsl:text disable-output-escaping="yes">&#36;pvars</xsl:text>, <xsl:text disable-output-escaping="yes">&#36;context</xsl:text> ) {//##
    <xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> widget_vars = <xsl:text disable-output-escaping="yes">&#36;wvars</xsl:text>;//##
    <xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> page_vars = <xsl:text disable-output-escaping="yes">&#36;pvars</xsl:text>;//##
    <xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> widget_name = str_replace("_PageWidget","",get_class(<xsl:text disable-output-escaping="yes">&#36;</xsl:text>this));//##
    <xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> XMLForm = new XMLForm(<xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> widget_name);//##
	  <xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> XMLForm -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> item_forcehidden = true;//##
    <xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> crud = new <xsl:call-template name="initCap"><xsl:with-param name="str" select="@name" /></xsl:call-template>Crud( <xsl:text disable-output-escaping="yes">&#36;</xsl:text>context );//##
    parent::__construct( <xsl:text disable-output-escaping="yes">&#36;</xsl:text>context );//##
  }//##
//##
	function parse() {//##
	 //##
	 //return <xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> widget_vars;//##
   //##
	  if (<xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> XMLForm -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> isPosted()) {  //##
      <xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> doPost();//##
    }//##
    <xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> doGet();//##
    //##
    return <xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> drawPage();//##
    //##
  }//##
//##
  function doPost(){//##
     //##
     if (<xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> XMLForm -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> validateForm()) {//##
        switch (<xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> getFormMethod()) {//##
          case "submit"://##
          <xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> crud -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> write();//##
          break;//##
          case "delete"://##
          <xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> crud -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> remove();//##
          break;//##
        }//##
      }//##
    //##
  }//##
//##
  function doGet(){//##
    //##
    if ((<xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text>getOp() == "detail") <xsl:text disable-output-escaping="yes">&amp;&amp;</xsl:text> (<xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> getId()<xsl:text disable-output-escaping="yes">&gt;</xsl:text>=0) <xsl:text disable-output-escaping="yes">&amp;&amp;</xsl:text> (<xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> crud)) {//##
      <xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> pushItem();//##
    }//##
    //##
  }//##
//##
  function drawPage(){//##
    //##
    if ((<xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text>getOp() == "detail") <xsl:text disable-output-escaping="yes">&amp;&amp;</xsl:text> (<xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> getId()<xsl:text disable-output-escaping="yes">&gt;</xsl:text>=0) <xsl:text disable-output-escaping="yes">&amp;&amp;</xsl:text> (<xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> crud)) {//##
      return <xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> returnForm();//##
    } elseif (<xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text>getOp() == "list" ) {//##
      return <xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> returnList();//##
    }//##
    //##
  }//##
//##
	
}//##
</xsl:template>

<xsl:include href="../../../styroform/1.2/xsl/initcap.xsl" />

</xsl:stylesheet>
