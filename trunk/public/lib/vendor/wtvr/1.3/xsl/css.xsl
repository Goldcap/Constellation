<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">

<xsl:template match="//module/attributes/assets/CSSCRIPT">
  <xsl:choose>
    <xsl:when test="../../../@resourcebase != ''">
      <link rel="stylesheet" href="{../../../@resourcebase}{self::*}"></link>
    </xsl:when>
    <xsl:otherwise>
      <link rel="stylesheet" href="{../../../@baseurl}html/css/{self::*}"></link>
    </xsl:otherwise>
	</xsl:choose>
</xsl:template>

<xsl:template match="CSSCRIPT">
	<link rel="stylesheet" href="{self::*}"></link>
</xsl:template>

<xsl:template name="PAGECSSCRIPT">
  <xsl:param name="name"></xsl:param>
  <link rel="stylesheet" href="/pages/{$name}/html/css/{$name}.css"></link>
</xsl:template>

</xsl:stylesheet>
