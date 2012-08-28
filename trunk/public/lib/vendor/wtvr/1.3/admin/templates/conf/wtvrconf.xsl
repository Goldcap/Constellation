<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="html" indent="yes" omit-xml-declaration="yes" standalone="yes" />

<xsl:template match="ROOT">
<xsl:text disable-output-escaping="yes">&lt;&#63;php</xsl:text>//##
 return array ( 
 <xsl:apply-templates select="itemgroup" />//##
);
<xsl:text disable-output-escaping="yes">&#63;&gt;</xsl:text>
</xsl:template>

<xsl:template match="itemgroup">
'<xsl:value-of select="@scope" />' <xsl:text disable-output-escaping="yes">=&gt;</xsl:text>//##
array (//##
<xsl:apply-templates select="theitems" />
),
</xsl:template>

<xsl:template match="theitems">
<xsl:param name="itemcount"><xsl:value-of select="count(item)" /></xsl:param>
<xsl:apply-templates select="item">
  <xsl:with-param name="itemcount" value="$itemcount" />
</xsl:apply-templates>

</xsl:template>

<xsl:template match="item">
<xsl:param name="itemcount">1</xsl:param>
<xsl:param name="methcount"><xsl:value-of select="count(method)" /></xsl:param>
'<xsl:value-of select="@name" />' <xsl:text disable-output-escaping="yes">=&gt;</xsl:text> array(//##
<xsl:for-each select="method">
'<xsl:value-of select="self::*" />'<xsl:text disable-output-escaping="yes">=&gt;</xsl:text>true<xsl:if test="(position() != $methcount)">,</xsl:if>//##
</xsl:for-each>
)<xsl:if test="(position() != $itemcount)">,</xsl:if>//##
</xsl:template>

<xsl:include href="deadpedal_attributes.xsl" />

</xsl:stylesheet>
