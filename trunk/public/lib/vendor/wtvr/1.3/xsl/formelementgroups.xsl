<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">

<xsl:template match="FORMELEMENTGROUP">
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
		<xsl:if test="count(FORMELEMENT) > 0">
      <div id="{$theid}" class="{$theclass}">
  			<xsl:apply-templates select="FORMELEMENT" />
  		</div>
	   </xsl:if>
</xsl:template>

</xsl:stylesheet>
