<?xml version="1.0" encoding='UTF-8'?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="html" version="1.0" encoding="UTF-8" omit-xml-declaration="no" standalone="yes" doctype-public="-//W3C//DTD HTML 4.01 Transitional//EN" doctype-system="http://www.w3.org/TR/html4/loose.dtd"/>

<xsl:template match="//PAGE">
	<html>
		
	<xsl:apply-templates select="HEAD" />
	
	<xsl:call-template name="BODY_BROWSER" />
	
	</html>
</xsl:template>

<xsl:template match="HEAD">
	<head>
	
	<title><xsl:value-of select="TITLE" /></title>
	<meta name="baseurl" content="{//PAGE/@baseurl}" />
	<xsl:apply-templates select="META" />
	
	
	<xsl:apply-templates select="JSCRIPT" />
	<xsl:apply-templates select="//module[@baseurl!='']/attributes/assets/JSCRIPT" />
	
	<xsl:apply-templates select="CSSCRIPT" />
	<xsl:apply-templates select="//module[@baseurl!='']/attributes/assets/CSSCRIPT" />
  
  </head>
</xsl:template>

<xsl:template name="BODY_BROWSER">
  <xsl:choose>
    <xsl:when test="(//@browsername = 'Explorer') and (//@browserversion != '7.0')">
      <div id="container">
      <xsl:apply-templates select="BODY" />
      </div>
    </xsl:when>
    <xsl:otherwise>
      <xsl:apply-templates select="BODY" />
    </xsl:otherwise>
  </xsl:choose>
	
</xsl:template>

<xsl:template match="BODY">
	<xsl:element name="body">
	 
  	<div id="base_wrapper">
      
    	  <xsl:apply-templates select="//attributes" />
      	
        <xsl:if test="count(//AMODULE) > 0">
          <xsl:apply-templates select="//MODULES" />
      	</xsl:if>
        
        <xsl:apply-templates select="//EMAIL" />
      	
    </div>
  </xsl:element>
</xsl:template>

</xsl:stylesheet>
