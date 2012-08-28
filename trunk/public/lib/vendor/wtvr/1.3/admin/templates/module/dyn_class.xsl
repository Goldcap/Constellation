<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="xml" indent="yes" omit-xml-declaration="yes" standalone="yes" />
<xsl:preserve-space elements="*"/>

<xsl:template match="ROOT">
  <xsl:text disable-output-escaping="yes">&lt;&#63;php</xsl:text>//##
  //##
  include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");//##
  include_once("wtvr/WTVRBaseMail.php");//##
  //##
  //Data Abstraction classes as needed from propel//##
  require_once 'crud/<xsl:value-of select="//class/@name" />_crud.php';//##
  //##
  <xsl:apply-templates select="class" />//##
  <xsl:text disable-output-escaping="yes">&#63;&gt;</xsl:text>
</xsl:template>

<xsl:template match="class">
 class <xsl:value-of select="@name" />_mod extends ModuleBase {//##
	//##
	var <xsl:text disable-output-escaping="yes">&#36;module_name</xsl:text>;//##
  var <xsl:text disable-output-escaping="yes">&#36;XMLForm</xsl:text>;//##
	var <xsl:text disable-output-escaping="yes">&#36;crud</xsl:text>;//##
	var <xsl:text disable-output-escaping="yes">&#36;VARIABLES</xsl:text>;//##
	var <xsl:text disable-output-escaping="yes">&#36;object</xsl:text>;//##
	var <xsl:text disable-output-escaping="yes">&#36;documentElement</xsl:text>;//##
	//##
	function __construct( <xsl:text disable-output-escaping="yes">&#36;object</xsl:text>, <xsl:text disable-output-escaping="yes">&#36;vars</xsl:text> ) {//##
    parent::__construct( <xsl:text disable-output-escaping="yes">&#36;vars</xsl:text> );//##
    <xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> object = <xsl:text disable-output-escaping="yes">&#36;object</xsl:text>;//##
    <xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> VARIABLES = <xsl:text disable-output-escaping="yes">&#36;vars</xsl:text>;//##
    <xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> module_name = str_replace("_mod","",get_class(<xsl:text disable-output-escaping="yes">&#36;</xsl:text>this));//##
    <xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> XMLForm = new XMLForm(<xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> module_name);//##
	  <xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> crud = new <xsl:value-of select="@name" />_crud( <xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> VARIABLES, <xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> greedyVar("id") );//##
  }//##
//##
	function xml() {//##
	 //##
	 <xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> loadXML();//##
   //##
	  if (<xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> XMLForm -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> isPosted()) {  //##
      <xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> doPost();//##
    } else {//##
      <xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> doGet();//##
    }//##
    //##
    <xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> drawPage();//##
    //##
    return <xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> returnXML();//##
	}//##
//##
  function doPost(){//##
     //##
     if (<xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> XMLForm -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> validateForm()) {//##
        switch (<xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> getFormMethod()) {//##
          case "submit"://##
          <xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> crud -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> set();//##
          break;//##
          case "delete"://##
          <xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> crud -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> delete();//##
          break;//##
        }//##
      }//##
    //##
  }//##
//##
  function doGet(){//##
    //##
    if (<xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text>getAction() == "detail" <xsl:text disable-output-escaping="yes">&amp;&amp;</xsl:text> <xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> ifVar("id") <xsl:text disable-output-escaping="yes">&amp;&amp;</xsl:text> <xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> crud) {//##
      <xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> pushItem();//##
    }//##
    //##
  }//##
//##
  function drawPage(){//##
    //##
    if (<xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text>getAction() == "detail" <xsl:text disable-output-escaping="yes">&amp;&amp;</xsl:text> <xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> ifVar("id") <xsl:text disable-output-escaping="yes">&amp;&amp;</xsl:text> <xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> crud) {//##
      <xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> returnForm();//##
    } elseif (<xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text>getAction() == "list" ) {//##
      <xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> returnList();//##
    }//##
    //##
  }//##
//##
	/<xsl:text disable-output-escaping="yes">&#42;</xsl:text>//##
  function conf() {//##
		<xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> loadConf();//##
		<xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> documentConf -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> setPathAttribute("//module[@name='<xsl:value-of select="@name" />_list']", 0, "name", "greeting");//##
		return <xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> returnConf();//##
	}//##
//##
	function xsl() {//##
		//die("xsl");//##
	}//##
	<xsl:text disable-output-escaping="yes">&#42;</xsl:text>///##
}//##
</xsl:template>

<xsl:include href="../conf/deadpedal_attributes.xsl" />

</xsl:stylesheet>
