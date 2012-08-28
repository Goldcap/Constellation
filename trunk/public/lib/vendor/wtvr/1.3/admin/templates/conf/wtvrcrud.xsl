<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="text" indent="no" omit-xml-declaration="yes" standalone="yes" />

<xsl:template match="ROOT">
  <xsl:text disable-output-escaping="yes">&lt;&#63;php</xsl:text>//##
  //## 
  <xsl:apply-templates select="table" />
  //## 
  <xsl:text disable-output-escaping="yes">&#63;&gt;</xsl:text>
</xsl:template>

<xsl:template match="table">
   //##
   class <xsl:call-template name="initCap"><xsl:with-param name="str" select="//table/@name" /></xsl:call-template>Crud extends <xsl:call-template name="initCap"><xsl:with-param name="str" select="//table/@name" /></xsl:call-template>CrudBase { //##
   //## 
    <xsl:call-template name="constructor" />
   }//## 
  //## 
</xsl:template>

<xsl:template name="constructor">
  function __construct( <xsl:text disable-output-escaping="yes">&#36;</xsl:text>context, <xsl:text disable-output-escaping="yes">&#36;</xsl:text>id = false ) {//##
    parent::__construct( <xsl:text disable-output-escaping="yes">&#36;</xsl:text>context, <xsl:text disable-output-escaping="yes">&#36;</xsl:text>id );//##
    
    ////##
  }//##
  //##
</xsl:template>

<xsl:include href="initcap.xsl" />
<xsl:include href="deadpedal_attributes.xsl" />

</xsl:stylesheet>
