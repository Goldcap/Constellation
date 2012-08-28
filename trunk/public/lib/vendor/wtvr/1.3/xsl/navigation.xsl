<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:template name="more">
  <xsl:param name="ref">0</xsl:param>
  <xsl:param name="div">false</xsl:param>
  <xsl:param name="class">morelink</xsl:param>
  <xsl:param name="name"></xsl:param>
  <xsl:choose>
  <xsl:when test="$div = 'true'">
    <div id="{$class}">
      <xsl:call-template name="domore">
        <xsl:with-param name="ref" select="$ref" />
        <xsl:with-param name="class" select="$class" />
        <xsl:with-param name="name" select="$name" />
      </xsl:call-template>
    </div>
  </xsl:when>
  <xsl:otherwise>
    <xsl:call-template name="domore">
      <xsl:with-param name="ref" select="$ref" />
      <xsl:with-param name="class" select="$class" />
        <xsl:with-param name="name" select="$name" />
    </xsl:call-template>
  </xsl:otherwise>
  </xsl:choose>
</xsl:template>

<xsl:template name="domore">
  <xsl:param name="ref">0</xsl:param>
  <xsl:param name="class">morelink</xsl:param>
  <xsl:param name="name"></xsl:param>
  <span class="{$class}">
    <xsl:choose>
      <xsl:when test="$name != ''">
        <span class="gridLinkLink"><a href="javascript: void(0)" onclick="{$ref}" id="{$name}"><img src="http://ll.tattoojohnny.com/images/more.gif" border="0" /></a></span>
      </xsl:when>
      <xsl:otherwise>
        <span class="gridLinkLink"><a href="javascript: void(0)" onclick="{$ref}"><img src="http://ll.tattoojohnny.com/images/more.gif" border="0" /></a></span>
      </xsl:otherwise>
    </xsl:choose>
  </span>
</xsl:template>

</xsl:stylesheet>
