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
	
	<xsl:variable name="ASYNCDOJO"><xsl:value-of select="count(//module[ancestor::AMODULE/@parse='ASYNC'])" /></xsl:variable>
  <xsl:variable name="CONTENTPANEDOJO"><xsl:value-of select="count(//dojo)" /></xsl:variable>
	<xsl:variable name="COMBODOJO"><xsl:value-of select="count(//FORMELEMENT[TYPE='dojo'][TYPE/@species='comboBox'])" /></xsl:variable>
	<xsl:variable name="SELECTDOJO"><xsl:value-of select="count(//FORMELEMENT[TYPE='dojo'][TYPE/@species='select'])" /></xsl:variable>
	
  <xsl:if test="($ASYNCDOJO > 0) or ($CONTENTPANEDOJO > 0) or ($COMBODOJO > 0) or ($SELECTDOJO > 0)">
    <xsl:call-template name="ASYNCJSCRIPT" />
	</xsl:if>
	
	<xsl:if test="($ASYNCDOJO > 0)">
  	<xsl:call-template name="ASYNCINIT" />
  </xsl:if>
  
  <xsl:if test="($CONTENTPANEDOJO > 0)">
  	<xsl:call-template name="CONTENTPANEINIT" />
  </xsl:if>
  
  <xsl:if test="($COMBODOJO > 0)">
  	<xsl:call-template name="DOJOCOMBOINIT" />
  </xsl:if>
  
  <xsl:if test="($SELECTDOJO > 0)">
  	<xsl:call-template name="DOJOSELECTINIT" />
  </xsl:if>
  
  <!--
  <xsl:call-template name="PAGEJSCRIPT">
    <xsl:with-param name="name" select="//PAGE/@name" />
    <xsl:with-param name="gzip_js" select="//PAGE/@gzip_js" />
	</xsl:call-template>
  -->
  
	<xsl:if test="//@dojo_debug = 'TRUE'">
  	<script language="JavaScript" type="text/javascript">
  		<xsl:comment>
      // Dojo configuration
  		djConfig = { 
  			isDebug: true
  		};
  		</xsl:comment>
  	</script>
	</xsl:if>
	
	<xsl:apply-templates select="CSSCRIPT" />
	<xsl:apply-templates select="//module[@baseurl!='']/attributes/assets/CSSCRIPT" />
	
  <!--
  <xsl:call-template name="PAGECSSCRIPT">
	 <xsl:with-param name="name" select="//PAGE/@name" />
	</xsl:call-template>
  -->
  
  <xsl:comment>
  <![CDATA[[if IE 6]>
  <link rel="stylesheet" href="/css/ie6.css" />
  <![endif]]]>
  </xsl:comment>
  
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
	 
		<xsl:if test="ONLOAD != ''">
			<xsl:attribute name="onload"><xsl:value-of select="ONLOAD" /></xsl:attribute>
		</xsl:if>
		<div dojoType="contentPane" id="placeholder"></div>
  	<div id="base_wrapper">
      <div class="skipnav"><a href="#content">Skip to main content</a></div>
    	
    	  <xsl:comment>
        <![CDATA[[if IE 6]>
        <div id="container">
        <![endif]]]>
        </xsl:comment>
        
        <xsl:apply-templates select="//attributes" />
      	
        <xsl:if test="count(//AMODULE) > 0">
          <xsl:apply-templates select="//MODULES" />
      	</xsl:if>
        
        <xsl:apply-templates select="//EMAIL" />
      	
        <xsl:comment>
        <![CDATA[[if IE 6]>
        </div>
        <![endif]]]>
        </xsl:comment>
    </div>
  </xsl:element>
</xsl:template>

</xsl:stylesheet>
