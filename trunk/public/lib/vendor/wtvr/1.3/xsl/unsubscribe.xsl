<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:template name="email_footer">
  <xsl:param name="base_url"></xsl:param>
  <xsl:param name="browse_id"></xsl:param>
  <table margin="0" padding="0">
    <tr>
      <td>
      <xsl:call-template name="viewhtml">
        <xsl:with-param name="base_url" select="$base_url" />
        <xsl:with-param name="browse_id" select="$browse_id" />
      </xsl:call-template>
      </td>
    </tr>
    <tr>
      <td>
      <xsl:call-template name="unsubscribe" />
      </td>
    </tr>
  </table>
  <xsl:call-template name="beacon" /><br />
</xsl:template>

<xsl:template name="viewhtml">
  <!--<xsl:if test="(//@generator = 'message')">-->
    <xsl:param name="base_url"></xsl:param>
    <xsl:param name="browse_id"></xsl:param>
    <xsl:param name="message">to view in a browser, </xsl:param>
    <xsl:choose>
    <xsl:when test="(//MESSAGE/IDENTIFIER/@uuid != '') and (//MESSAGE/IDENTIFIER/@uuid = 'view')">
      <font size="-2" color="#888888"><xsl:value-of select="$message" /> <a href="{$base_url}&amp;id={$browse_id}" target="_new">click here</a>.</font>
    </xsl:when>
    <xsl:otherwise>
      <font size="-2" color="#888888"><xsl:value-of select="$message" /> <a href="{$base_url}" target="_new">click here</a>.</font>
    </xsl:otherwise>
    </xsl:choose>
  <!--</xsl:if>-->
</xsl:template>

<xsl:template name="unsubscribe">
    <xsl:param name="message">to unsubscribe, </xsl:param>
    <xsl:choose>
    <xsl:when test="//RECIPIENT/@user_email != ''">
    <font size="-2" color="#888888"><xsl:value-of select="$message" /> <a href="{//PAGE/@baseurl}unsubscribe/{//RECIPIENT/@user_email}">click here</a>.</font>
    </xsl:when>
    <xsl:otherwise test="//RECIPIENT/@user_email = ''">
    <font size="-2" color="#888888"><xsl:value-of select="$message" /> <a href="{//PAGE/@baseurl}unsubscribe/enter your email address">click here</a>.</font>
    </xsl:otherwise>
    </xsl:choose>
</xsl:template>

<xsl:template name="beacon">
  <img src="{//PAGE/@baseurl}services/track/beacon/{//MESSAGE/IDENTIFIER/@uuid}/{//RECIPIENT/@user_email}" height="1" width="1" />
</xsl:template>


</xsl:stylesheet>
