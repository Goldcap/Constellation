<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="html" indent="yes" omit-xml-declaration="yes" standalone="yes" />

<xsl:template match="ROOT">
<xsl:text disable-output-escaping="yes">&lt;&#63;php</xsl:text>//##
 return array ( //##
  <xsl:apply-templates select="//item" />
);
<xsl:text disable-output-escaping="yes">&#63;&gt;</xsl:text>
</xsl:template>

<xsl:template match="item">
  <!--<xsl:variable name="theuals">file:///var/www/html/sites/whitman/lib/conf/ual.xml</xsl:variable>-->
  <xsl:variable name="theuals"><xsl:value-of select="//confloc/@location" /></xsl:variable>
  <xsl:variable name="UALS" select="document($theuals)/uals"/>
  <xsl:variable name="thisual" select="@ual" />
  <xsl:variable name="ual_val"><xsl:choose><xsl:when test="$UALS/ual[@name=$thisual]/@value !=''"><xsl:value-of select="$UALS/ual[@name=$thisual]/@value" /></xsl:when><xsl:otherwise>0</xsl:otherwise></xsl:choose></xsl:variable>
  '<xsl:value-of select="@name" />' =<xsl:text disable-output-escaping="yes">&gt;</xsl:text> <xsl:value-of select="$ual_val" /><xsl:if test="position() != count(//item)">,</xsl:if>//##
</xsl:template>
<!--<xsl:value-of select="$ual_val" />-->
<xsl:include href="deadpedal_attributes.xsl" />

</xsl:stylesheet>
