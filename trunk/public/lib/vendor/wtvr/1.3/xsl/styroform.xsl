<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:template match="AFORM">
  
  <div id="{FORMNAME}_wrapper" class="{WRAPPERCLASS}">
  <xsl:element name="form">
    <xsl:choose>
      <xsl:when test="FORMMETHOD != ''">
      	<xsl:attribute name="method"><xsl:value-of select="FORMMETHOD" /></xsl:attribute>
      </xsl:when>
      <xsl:otherwise>
        <xsl:attribute name="method">post</xsl:attribute>
      </xsl:otherwise>
    </xsl:choose>
    <xsl:attribute name="name">
      <xsl:value-of select="FORMNAME" />
    </xsl:attribute>
    <xsl:attribute name="id">
      <xsl:value-of select="FORMNAME" />
    </xsl:attribute>
    <xsl:attribute name="action">
      <xsl:choose>
        <xsl:when test="FORMACTION != '$self'"><xsl:value-of select="FORMACTION" />&amp;ts=<xsl:value-of select="//@timestamp" /></xsl:when>
        <xsl:otherwise><xsl:value-of select="//@script" />/?ts=<xsl:value-of select="//@timestamp" /></xsl:otherwise>
      </xsl:choose>
    </xsl:attribute>
    <xsl:attribute name="enctype">multipart/form-data</xsl:attribute>
    <xsl:choose>
      <xsl:when test="FORMACTION/@onSubmit != ''">
      	<xsl:attribute name="onsubmit"><xsl:value-of select="FORMACTION/@onSubmit" /></xsl:attribute>
      </xsl:when>
      <xsl:when test="JSVALIDATE = 'TRUE'">
      	<xsl:attribute name="onsubmit">return checkForm(thisValidator,"<xsl:value-of select="FORMNAME" />")</xsl:attribute>
      </xsl:when>
      <xsl:otherwise>
      </xsl:otherwise>
    </xsl:choose>
    <xsl:attribute name="class">
      <xsl:value-of select="FORMCLASS" />
    </xsl:attribute>

    <xsl:apply-templates select="FORMELEMENTGROUP" />
	
	<input type="hidden" name="showerrors" value="{//SHOWERRORS}" />
	
  </xsl:element>
  </div>
</xsl:template>


<xsl:include href="formelementgroups.xsl" />
<xsl:include href="formelements.xsl" />
<xsl:include href="formelements_dojo.xsl" />


</xsl:stylesheet>
