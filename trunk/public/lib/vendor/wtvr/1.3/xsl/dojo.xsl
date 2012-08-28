<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">

<xsl:template name="ASYNCJSCRIPT">
</xsl:template>

<xsl:template name="ASYNCINIT">
  <script type="text/javascript">
    dojo.addOnLoad(function(){<xsl:for-each select="//module[ancestor::AMODULE/@parse='ASYNC']"> var <xsl:value-of select="@name" />_contentPane = dojo.widget.byId("<xsl:value-of select="@name" />_contentPane"); toggle(<xsl:value-of select="@name" />_contentPane);</xsl:for-each>});
  </script>
</xsl:template>

<xsl:template name="CONTENTPANEINIT">
  <!--<script type="text/javascript">
    dojo.addOnLoad(function(){
      <xsl:for-each select="//dojo">
      var <xsl:value-of select="@name" />_<xsl:value-of select="@type" /> = dojo.widget.byId("<xsl:value-of select="@name" />_<xsl:value-of select="@type" />");
      </xsl:for-each>
    });
  </script>-->
</xsl:template>

<xsl:template match="dojo">
  <xsl:choose>
    <xsl:when test="@type='accordionContainer'">
      <div dojoType="{@type}" executeScripts="true" labelNodeClass="label" containerNodeClass="accBody" style="border: 2px solid green;" id="accordionPane">
        <xsl:apply-templates select="dojo" />
      </div>
    </xsl:when>
    <xsl:otherwise>
      <xsl:call-template name="dojo_div">
        <xsl:with-param name="name"><xsl:choose><xsl:when test="@name != ''"><xsl:value-of select="@name" /></xsl:when><xsl:otherwise></xsl:otherwise></xsl:choose></xsl:with-param>
        <xsl:with-param name="title"><xsl:choose><xsl:when test="@title != ''"><xsl:value-of select="@title" /></xsl:when><xsl:otherwise></xsl:otherwise></xsl:choose></xsl:with-param>
        <xsl:with-param name="type"><xsl:choose><xsl:when test="@type != ''"><xsl:value-of select="@type" /></xsl:when><xsl:otherwise>contentPane</xsl:otherwise></xsl:choose></xsl:with-param>
        <xsl:with-param name="toggle"><xsl:choose><xsl:when test="@toggle != ''"><xsl:value-of select="@toggle" /></xsl:when><xsl:otherwise>fade</xsl:otherwise></xsl:choose></xsl:with-param>
        <xsl:with-param name="toggleDuration"><xsl:choose><xsl:when test="@toggleDuration != ''"><xsl:value-of select="@toggleDuration" /></xsl:when><xsl:otherwise>1000</xsl:otherwise></xsl:choose></xsl:with-param>
        <xsl:with-param name="display"><xsl:choose><xsl:when test="@display != ''"><xsl:value-of select="@display" /></xsl:when><xsl:otherwise>none</xsl:otherwise></xsl:choose></xsl:with-param>
        <xsl:with-param name="preload"><xsl:choose><xsl:when test="@preload != ''"><xsl:value-of select="@preload" /></xsl:when><xsl:otherwise>false</xsl:otherwise></xsl:choose></xsl:with-param>
        <xsl:with-param name="resizable"><xsl:choose><xsl:when test="@resizable != ''"><xsl:value-of select="@resizable" /></xsl:when><xsl:otherwise>false</xsl:otherwise></xsl:choose></xsl:with-param>
        <xsl:with-param name="baseurl"><xsl:choose><xsl:when test="@baseurl != ''"><xsl:value-of select="@baseurl" /></xsl:when><xsl:otherwise></xsl:otherwise></xsl:choose></xsl:with-param>
        <xsl:with-param name="onLoad"><xsl:choose><xsl:when test="@onLoad != ''"><xsl:value-of select="@onLoad" /></xsl:when><xsl:otherwise>loadDefault.show(arguments[0], this, loader );</xsl:otherwise></xsl:choose></xsl:with-param>
        <xsl:with-param name="onLoadEnd"><xsl:choose><xsl:when test="@onLoadEnd != ''"><xsl:value-of select="@onLoadEnd" /></xsl:when><xsl:otherwise>void(0);</xsl:otherwise></xsl:choose></xsl:with-param>
      </xsl:call-template>
    </xsl:otherwise>
  </xsl:choose>
</xsl:template>


<xsl:template name="dojo_tooltip">
  <xsl:param name="name"></xsl:param>
  <xsl:param name="title"></xsl:param>
  <xsl:param name="baseurl"></xsl:param>
  <xsl:param name="class"></xsl:param>
  <xsl:choose>
    <xsl:when test="$baseurl != ''">
      <script type="text/javascript">
        <xsl:comment>
        dojo.addOnLoad(function() {createTooltip('<xsl:value-of select="$name" />',null,'<xsl:value-of select="$baseurl" />','<xsl:value-of select="$class" />')});
        </xsl:comment>
      </script>
    </xsl:when>
    <xsl:otherwise>
      <script type="text/javascript">
        <xsl:comment>
        dojo.addOnLoad(function() {createTooltip('<xsl:value-of select="$name" />','<xsl:value-of select="$title" />',null,'<xsl:value-of select="$class" />')});
        </xsl:comment>
      </script>
    </xsl:otherwise>
  </xsl:choose>
</xsl:template>

<xsl:template name="dojo_div">
  <xsl:param name="name"></xsl:param>
  <xsl:param name="title"></xsl:param>
  <xsl:param name="type">contentPane</xsl:param>
  <xsl:param name="toggle">fade</xsl:param>
  <xsl:param name="toggleDuration">1000</xsl:param>
  <xsl:param name="display">none</xsl:param>
  <xsl:param name="preload">false</xsl:param>
  <xsl:param name="resizable">false</xsl:param>
  <xsl:param name="baseurl">null</xsl:param>
  <xsl:param name="onLoad">loadDefault.show(arguments[0], this, loader);</xsl:param>
  <xsl:param name="onLoadEnd">void(0);</xsl:param>
  <xsl:param name="unloadFunction">null</xsl:param>
  <xsl:choose>
    <xsl:when test="$baseurl = 'null'">
      <div dojoType="{$type}" id="{$name}_{$type}" executeScripts="true" scriptScope="true" hasShadow="true" displayMinimizeAction="true" resizable="{$resizable}" title="{$title}" toggle="{$toggle}" toggleDuration="{$toggleDuration}" class="{$name}_{$type}" cacheContent="false" refreshOnShow="true" style="display: {$display};" preload="{$preload}" href="{$baseurl}" onDownloadStart="{$onLoad}" onDownloadEnd="{$onLoadEnd}" unloadFunction="{@unloadFunction}" ></div>
    </xsl:when>
    <xsl:otherwise>
      <div dojoType="{$type}" id="{$name}_{$type}" executeScripts="true" scriptScope="true" hasShadow="true" displayMinimizeAction="true" resizable="{$resizable}" title="{$title}" toggle="{$toggle}" toggleDuration="{$toggleDuration}" class="{$name}_{$type}" cacheContent="false" refreshOnShow="true" style="display: {$display};" preload="{$preload}" href="{$baseurl}" onDownloadStart="{$onLoad}" onDownloadEnd="{$onLoadEnd}" unloadFunction="{@unloadFunction}" >
      <xsl:for-each select="dojo">
        <xsl:call-template name="dojo_div">
          <xsl:with-param name="name"><xsl:choose><xsl:when test="@name != ''"><xsl:value-of select="@name" /></xsl:when><xsl:otherwise></xsl:otherwise></xsl:choose></xsl:with-param>
          <xsl:with-param name="title"><xsl:choose><xsl:when test="@type != ''"><xsl:value-of select="@type" /></xsl:when><xsl:otherwise>contentPane</xsl:otherwise></xsl:choose></xsl:with-param>
          <xsl:with-param name="type"><xsl:choose><xsl:when test="@type != ''"><xsl:value-of select="@type" /></xsl:when><xsl:otherwise>contentPane</xsl:otherwise></xsl:choose></xsl:with-param>
          <xsl:with-param name="toggle"><xsl:choose><xsl:when test="@toggle != ''"><xsl:value-of select="@toggle" /></xsl:when><xsl:otherwise>fade</xsl:otherwise></xsl:choose></xsl:with-param>
          <xsl:with-param name="toggleDuration"><xsl:choose><xsl:when test="@toggleDuration != ''"><xsl:value-of select="@toggleDuration" /></xsl:when><xsl:otherwise>1000</xsl:otherwise></xsl:choose></xsl:with-param>
          <xsl:with-param name="display"><xsl:choose><xsl:when test="@display != ''"><xsl:value-of select="@display" /></xsl:when><xsl:otherwise>none</xsl:otherwise></xsl:choose></xsl:with-param>
          <xsl:with-param name="preload"><xsl:choose><xsl:when test="@preload != ''"><xsl:value-of select="@preload" /></xsl:when><xsl:otherwise>false</xsl:otherwise></xsl:choose></xsl:with-param>
          <xsl:with-param name="resizable"><xsl:choose><xsl:when test="@resizable != ''"><xsl:value-of select="@resizable" /></xsl:when><xsl:otherwise>false</xsl:otherwise></xsl:choose></xsl:with-param>
          <xsl:with-param name="baseurl"><xsl:choose><xsl:when test="@baseurl != ''"><xsl:value-of select="@baseurl" /></xsl:when><xsl:otherwise></xsl:otherwise></xsl:choose></xsl:with-param>
          <xsl:with-param name="onLoad"><xsl:choose><xsl:when test="@onLoad != ''"><xsl:value-of select="@onLoad" /></xsl:when><xsl:otherwise>loadDefault.show(arguments[0], this, loader );</xsl:otherwise></xsl:choose></xsl:with-param>
          <xsl:with-param name="onLoadEnd"><xsl:choose><xsl:when test="@onLoadEnd != ''"><xsl:value-of select="@onLoadEnd" /></xsl:when><xsl:otherwise>void(0);</xsl:otherwise></xsl:choose></xsl:with-param>
          <xsl:with-param name="unloadFunction"><xsl:choose><xsl:when test="@unloadFunction != ''"><xsl:value-of select="@unloadFunction" /></xsl:when><xsl:otherwise>null</xsl:otherwise></xsl:choose></xsl:with-param>
        </xsl:call-template>
      </xsl:for-each>
    </div>
    </xsl:otherwise>
  </xsl:choose>
</xsl:template>

</xsl:stylesheet>
