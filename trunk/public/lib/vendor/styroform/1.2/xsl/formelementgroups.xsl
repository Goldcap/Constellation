<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">

<xsl:template match="FORMELEMENTGROUP" mode="tab">
  <div id="{../FORMNAME}TabContainer" dojoType="dijit.layout.TabContainer">
    <xsl:apply-templates select="self::*" />
  </div>
</xsl:template>

<xsl:template match="FORMELEMENTGROUP" mode="jquery_tabs">
  <li><a href="#{@id}"><xsl:value-of select="@jquery_tab" /></a></li>
</xsl:template>

<xsl:template match="FORMELEMENTGROUP">
  	<xsl:variable name="style">
      <xsl:choose>
    		<xsl:when test="@style != ''"><xsl:value-of select="@style" /></xsl:when>
    		<xsl:otherwise></xsl:otherwise>
    	</xsl:choose>
		</xsl:variable>
    <xsl:variable name="theclass">
      <xsl:choose>
    		<xsl:when test="@class != ''"><xsl:value-of select="@class" /></xsl:when>
    		<xsl:otherwise>row</xsl:otherwise>
    	</xsl:choose>
		</xsl:variable>
		<xsl:variable name="theid">
      <xsl:choose>
    		<xsl:when test="@id != ''"><xsl:value-of select="@id" /></xsl:when>
    		<xsl:otherwise></xsl:otherwise>
    	</xsl:choose>
		</xsl:variable>
		<xsl:variable name="onclick">
      <xsl:choose>
    		<xsl:when test="@windowshade != ''">toggle('<xsl:value-of select="@id" />')</xsl:when>
    		<xsl:otherwise></xsl:otherwise>
    	</xsl:choose>
		</xsl:variable>
		<xsl:choose>
		<xsl:when test="@tab != ''">
		  <div dojoType="dijit.layout.ContentPane" title="{@tab}">
		    <xsl:apply-templates select="FORMELEMENTGROUP" />
      	<xsl:apply-templates select="FORMELEMENT" />
		  </div>
		</xsl:when>
		<xsl:when test="(count(FORMELEMENT) > 0) or (count(FORMELEMENTGROUP) > 0)">
      <xsl:choose>
        <xsl:when test="@windowshade != ''">
        <div id="{$theid}" class="{$theclass} windowshade_closed"  style="{$style}" onclick="toggleDiv('{@windowshade}','{$theid}')">
          <xsl:apply-templates select="FORMELEMENTGROUP" />
    			<xsl:apply-templates select="FORMELEMENT" />
    		</div>
    		</xsl:when>
        <xsl:otherwise>
        <div id="{$theid}" class="{$theclass}" style="{$style}">
          <xsl:apply-templates select="FORMELEMENTGROUP" />
    			<xsl:apply-templates select="FORMELEMENT" />
    		</div>
    		</xsl:otherwise>
  		</xsl:choose>
	   </xsl:when>
	  </xsl:choose>
</xsl:template>

</xsl:stylesheet>
