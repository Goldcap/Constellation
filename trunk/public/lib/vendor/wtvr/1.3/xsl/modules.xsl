<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:key name="amodule-by-group" match="@group" use="AMODULE"/>

<!-- HIDE THE ATTRIBUTES FROM THE BROWSER -->
<xsl:template match="//attributes"></xsl:template>
<xsl:template match="MODULES" mode="DEADPEDAL"></xsl:template>

<!-- IN CASE THE MODULE IS BEING USED AS A STANDALONE -->
<xsl:template match="module[@scope='modules']">
  <xsl:apply-templates select="attributes/assets/JSCRIPT" />
  <xsl:apply-templates select="attributes/assets/CSSCRIPT" />
  <xsl:apply-templates select="content" />
</xsl:template>

<xsl:template match="MODULES">
  <xsl:variable name="thedoc"><xsl:value-of select="//PAGE/@docroot" />src/xml/conf.xml</xsl:variable>
	<xsl:variable name="PAGEGROUPS" select="document($thedoc)/groups"/>
	<xsl:variable name="groups" select="AMODULE"/>
	
  <xsl:for-each select="$PAGEGROUPS/group">
    <xsl:variable name="thename" select="@name" />
    <div id="{$thename}">
      <xsl:choose>
        <xsl:when test="count($groups[@group=$thename]/GROUP[@name!=$thename]) > 0">
          <xsl:for-each select="subgroup">
            <xsl:variable name="subname" select="@name" />
            <xsl:variable name="submodules" select="$groups[@group=$thename]/GROUP[@name=$subname]" />
            <div id="{$subname}">
                <xsl:choose>
                  <xsl:when test="(count($submodules) = 0)">
                    <div class="skipper"><a href="#">No Content for <xsl:value-of select="$subname" /></a></div>
                  </xsl:when>
                  <xsl:otherwise>
                    <xsl:for-each select="$submodules/following-sibling::module">
                      <xsl:apply-templates select="self::*" />
                    </xsl:for-each>
                  </xsl:otherwise>
                </xsl:choose>
            </div>
          </xsl:for-each>
        </xsl:when>
        <xsl:otherwise>
          <xsl:variable name="modules" select="$groups[@group=$thename]/GROUP[@name=$thename]" />
          <xsl:choose>
            <xsl:when test="(count($modules) = 0)">
              <!--<div class="skipper"><a href="#">No Content for <xsl:value-of select="$thename" /></a></div>-->
            </xsl:when>
            <xsl:otherwise>
              <xsl:for-each select="$modules/following-sibling::module">
                <xsl:apply-templates select="self::*" />
              </xsl:for-each>
            </xsl:otherwise>
          </xsl:choose>
        </xsl:otherwise>
      </xsl:choose>
    </div>
  </xsl:for-each>
  
</xsl:template>

<xsl:template match="module[ancestor::AMODULE/@parse='CLIENT']">
  <div id="{@name}"></div>
</xsl:template>

<xsl:template match="module[ancestor::AMODULE/@parse='SMARTY']">
  <xsl:value-of select="self::*" disable-output-escaping="yes" />
</xsl:template>

<xsl:template match="module[ancestor::AMODULE/@parse='SMARTY_INCLUDE']">
  {include file='file:<xsl:value-of select="@location" />'}
</xsl:template>

<xsl:template match="module[ancestor::AMODULE/@parse='HTML']">
  <xsl:value-of select="self::*" disable-output-escaping="yes" />
</xsl:template>

<xsl:template match="module[ancestor::AMODULE/@parse='ASYNC']">
  <xsl:call-template name="dojo_div">
    <xsl:with-param name="name"><xsl:value-of select="@name" /></xsl:with-param>
    <xsl:with-param name="toggle">none</xsl:with-param>
    <xsl:with-param name="baseurl"><xsl:value-of select="@basehref" /></xsl:with-param>
    <xsl:with-param name="onLoadEnd"><xsl:value-of select="@onLoadEnd" /></xsl:with-param>
  </xsl:call-template>
</xsl:template>

</xsl:stylesheet>
