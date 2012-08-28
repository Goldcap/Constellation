<?xml version="1.0" encoding="windows-1250"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:template name="initCap">
  <xsl:param name="str"/>
  <xsl:param name="pat">_</xsl:param>
  <xsl:choose>
    <xsl:when test="contains($str,$pat)">
      <xsl:call-template name="initcaps"><xsl:with-param name="x" select="substring-before($str,$pat)" /></xsl:call-template><xsl:call-template name="initCap"><xsl:with-param name="str" select="substring-after($str,$pat)" /></xsl:call-template>
    </xsl:when>
    <xsl:otherwise>
      <xsl:call-template name="initcaps"><xsl:with-param name="x" select="$str" /></xsl:call-template>
    </xsl:otherwise>
  </xsl:choose>
</xsl:template>

<xsl:template name="initTitle">
  <xsl:param name="str"/>
  <xsl:param name="pat">_</xsl:param>
  <xsl:choose>
    <xsl:when test="contains($str,$pat)">
      <xsl:call-template name="initcaps"><xsl:with-param name="x" select="substring-before($str,$pat)" /></xsl:call-template><xsl:text> </xsl:text><xsl:call-template name="initCap"><xsl:with-param name="str" select="substring-after($str,$pat)" /></xsl:call-template>
    </xsl:when>
    <xsl:otherwise>
      <xsl:call-template name="initcaps"><xsl:with-param name="x" select="$str" /></xsl:call-template>
    </xsl:otherwise>
  </xsl:choose>
</xsl:template>

<xsl:template name="initcaps">
  <xsl:param name="x"/>
  <xsl:value-of select="translate(substring($x,1,1)
                 ,'abcdefghijklmnopqrstuvwxyz'
                 ,'ABCDEFGHIJKLMNOPQRSTUVWXYZ')"/>
  <xsl:value-of select="substring($x,2)"/>
</xsl:template>


</xsl:stylesheet>
