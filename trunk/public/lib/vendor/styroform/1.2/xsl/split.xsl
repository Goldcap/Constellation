<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:template match="doc/elem">
  <xsl:call-template name="split">
    <xsl:with-param name="str" select="."/>
    <xsl:with-param name="pat" select="','"/>
  </xsl:call-template>
</xsl:template>

<xsl:template name="split">
  <xsl:param name="str"/>
  <xsl:param name="pat">,</xsl:param>
  <xsl:choose>
    <xsl:when test="contains($str,$pat)">
      <a href="{substring-before($str,$pat)}"><xsl:value-of select="substring-after($str,$pat)" disable-output-escaping="yes" /></a>
    </xsl:when>
    <xsl:otherwise>
      <xsl:value-of select="$str" disable-output-escaping="yes" />
    </xsl:otherwise>
  </xsl:choose>
</xsl:template>

<xsl:template name="split_flop">
  <xsl:param name="str"/>
  <xsl:param name="pat">,</xsl:param>
  <xsl:choose>
    <xsl:when test="contains($str,$pat)">
      <a onclick="openLink('{substring-before($str,$pat)}')"><xsl:value-of select="substring-after($str,$pat)" disable-output-escaping="yes" /></a>
    </xsl:when>
    <xsl:otherwise>
      <xsl:value-of select="$str" disable-output-escaping="yes" />
    </xsl:otherwise>
  </xsl:choose>
</xsl:template>

<!--<xsl:template name="str:split">
        <xsl:param name="string" select="''" />
  <xsl:param name="pattern" select="' '" />
  <xsl:choose>
    <xsl:when test="not($string)" />
    <xsl:when test="not($pattern)">
      <xsl:call-template name="str:_split-characters">
        <xsl:with-param name="string" select="$string" />
      </xsl:call-template>
    </xsl:when>
    <xsl:otherwise>
      <xsl:call-template name="str:_split-pattern">
        <xsl:with-param name="string" select="$string" />
        <xsl:with-param name="pattern" select="$pattern" />
      </xsl:call-template>
    </xsl:otherwise>
  </xsl:choose>
</xsl:template>

<xsl:template name="str:_split-characters">
  <xsl:param name="string" />
  <xsl:if test="$string">
    <token><xsl:value-of select="substring($string, 1, 1)" /></token>
    <xsl:call-template name="str:_split-characters">
      <xsl:with-param name="string" select="substring($string, 2)" />
    </xsl:call-template>
  </xsl:if>
</xsl:template>

<xsl:template name="str:_split-pattern">
  <xsl:param name="string" />
  <xsl:param name="pattern" />
  <xsl:choose>
    <xsl:when test="contains($string, $pattern)">
      <xsl:if test="not(starts-with($string, $pattern))">
        <token><xsl:value-of select="substring-before($string, $pattern)"
/><
/token>
      </xsl:if>
      <xsl:call-template name="str:_split-pattern">
        <xsl:with-param name="string" select="substring-after($string,
$patte
rn)" />
        <xsl:with-param name="pattern" select="$pattern" />
      </xsl:call-template>
    </xsl:when>
    <xsl:otherwise>
      <token><xsl:value-of select="$string" /></token>
    </xsl:otherwise>
  </xsl:choose>
</xsl:template>-->

</xsl:stylesheet>
