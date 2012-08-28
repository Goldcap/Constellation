<?xml version="1.0" encoding='UTF-8'?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns="http://www.w3.org/1999/xhtml" xmlns:doc="http://xsltsl.org/xsl/documentation/1.0" exclude-result-prefixes="doc" version = "1.0">

<xsl:template name="stringSub">
  <xsl:param name="string" />
  <xsl:param name="from" />
  <xsl:param name="to" />
  <xsl:if test="contains($string,$from)">
    <xsl:value-of select="substring-before($string,$from)" /><xsl:value-of select="$to" /><xsl:call-template name="stringSub"><xsl:with-param name="string"><xsl:value-of select="substring-after($string,$from)" /></xsl:with-param><xsl:with-param name="from"><xsl:value-of select="$from" /></xsl:with-param><xsl:with-param name="to"><xsl:value-of select="$to" /></xsl:with-param></xsl:call-template>
  </xsl:if>
  <xsl:if test="not(contains($string,$from))"><xsl:value-of select="$string" /></xsl:if>
</xsl:template>

</xsl:stylesheet>
