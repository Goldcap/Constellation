<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="xml" indent="yes" omit-xml-declaration="yes" standalone="yes" />
<xsl:preserve-space elements="*"/>

<xsl:template match="ROOT">
  <xsl:text disable-output-escaping="yes">&lt;&#63;php</xsl:text>//##
  <xsl:apply-templates select="class" />//##
  <xsl:text disable-output-escaping="yes">&#63;&gt;</xsl:text>
</xsl:template>

<xsl:template match="class">
 class <xsl:value-of select="@name" />_page extends PageBase {//##
	//##
	var <xsl:text disable-output-escaping="yes">&#36;documentElement</xsl:text>;//##
	//##
	function __construct(<xsl:text disable-output-escaping="yes">&#36;object</xsl:text>, <xsl:text disable-output-escaping="yes">&#36;vars</xsl:text> ) {//##
    parent::__construct( <xsl:text disable-output-escaping="yes">&#36;vars</xsl:text> );//##
		<xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> object = <xsl:text disable-output-escaping="yes">&#36;object</xsl:text>;//##
    <xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> VARIABLES = <xsl:text disable-output-escaping="yes">&#36;vars</xsl:text>;//##
    <xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> page_name = str_replace("_page","",get_class(<xsl:text disable-output-escaping="yes">&#36;</xsl:text>this));//##
	}//##
	//##
	/<xsl:text disable-output-escaping="yes">&#42;</xsl:text>//##
	function xhtml() {//##
		//##
	}//##
//##
	function xml() {//##
		<xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> loadXML();//##
		return <xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> returnXML();//##
	}//##
//##
	function conf() {//##
		<xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> loadConf();//##
		<xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> documentConf -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> setPathAttribute("//module[@name='<xsl:value-of select="@name" />_list']", 0, "name", "greeting");//##
		return <xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> returnConf();//##
	}//##
//##
	function xsl() {//##
		die("xsl");//##
	}//##
	<xsl:text disable-output-escaping="yes">&#42;</xsl:text>///##
}//##
</xsl:template>

<xsl:include href="../conf/deadpedal_attributes.xsl" />

</xsl:stylesheet>
