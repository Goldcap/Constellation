<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="xml" indent="yes" omit-xml-declaration="no" standalone="yes" />

<xsl:variable name="source_location"><xsl:value-of select="local_libroot" />/schema.xml</xsl:variable>
<xsl:variable name="controlurl"><xsl:value-of select="controlurl" /></xsl:variable>

<xsl:template match="table">
  <map table="{@name}" result="{@name}_list_datamap">
  	 <xsl:call-template name="columnlist">
        <xsl:with-param name="columns" select="column" />
     </xsl:call-template>
     <criterion limit="0" distinct="true">
        <ascorderby column="{column[vendor/parameter[@name='Key']/@value = 'PRI']/@name}" />
        <xsl:comment>
        &lt;descorderby column="column_name" /&gt;
        &lt;criteria value="0" column="column_name"/&gt;
        </xsl:comment>
      </criterion>
      <xsl:apply-templates select="foreign-key" />
      <pagination>
        <pagesperpage value="5"/>
        <recordssperpage value="5"/>
        <page value="1" var="page" />
        <sort var="sort" />
      </pagination>
      <xsl:call-template name="maplinks" />
  </map>
</xsl:template>

<xsl:template name="columnlist">
  <xsl:param name="columns"></xsl:param>
  <xsl:param name="foreign">false</xsl:param>
  
  <xsl:for-each select="$columns">
    <xsl:sort select="vendor/parameter[@name='Key']/@value"/>
    <xsl:variable name="aname"><xsl:value-of select="@name" /></xsl:variable>
    <xsl:variable name="apos"><xsl:value-of select="position()" /></xsl:variable>
    <xsl:variable name="atype"><xsl:value-of select="@type" /></xsl:variable>
    <xsl:variable name="asize"><xsl:value-of select="@size" /></xsl:variable>
    <xsl:variable name="akey"><xsl:value-of select="vendor/parameter[@name='Key']/@value" /></xsl:variable>
    
    <xsl:call-template name="column">
      <xsl:with-param name="thepos" select="$apos" />
      <xsl:with-param name="thename" select="$aname" />
      <xsl:with-param name="thetype" select="$atype" />
      <xsl:with-param name="thesize" select="$asize" />
      <xsl:with-param name="thekey" select="$akey" />
      <xsl:with-param name="foreign" select="$foreign" />
    </xsl:call-template>
  </xsl:for-each>
</xsl:template>

<xsl:template name="column">
  <xsl:param name="thepos"></xsl:param>
  <xsl:param name="thename"></xsl:param>
  <xsl:param name="thetype"></xsl:param>
  <xsl:param name="thesize"></xsl:param>
  <xsl:param name="thekey"></xsl:param>
  <xsl:param name="foreign">false</xsl:param>
  <xsl:choose>
  <xsl:when test="$foreign='true'">
    <foreigncolumn column="{$thename}" type="{$thetype}" size="{$thesize}" key="{$thekey}" order="{$thepos}" />
  </xsl:when>
  <xsl:otherwise>
    <column column="{$thename}" type="{$thetype}" size="{$thesize}" key="{$thekey}" order="{$thepos}" />
  </xsl:otherwise>
  </xsl:choose>
</xsl:template>

<xsl:template match="foreign-key">
  <xsl:variable name="SOURCE" select="document($source_location)/database" />
  
  <xsl:variable name="foreignTable" select="@foreignTable" />
  <join table="{$foreignTable}" local="{reference/@local}" foreign="{reference/@foreign}">
    <xsl:call-template name="columnlist">
      <xsl:with-param name="columns" select="$SOURCE/table[@name = $foreignTable]/column[vendor/parameter[@name='Key']/@value = '']" />
      <xsl:with-param name="foreign" select="'true'" />
     </xsl:call-template>
  </join>
</xsl:template>

<xsl:template name="maplinks">
  <maplinks>
    <maplink column="{//column[vendor/parameter[@name='Key'][@value != 'PRI'][1]]/@name}" base="{$controlurl}/{//@name}/detail/">
      <attribute name="{//column[vendor/parameter[@name='Key'][@value = 'PRI'][1]]/@name}" />
    </maplink>
  </maplinks>
</xsl:template>

<xsl:include href="deadpedal_attributes.xsl" />

</xsl:stylesheet>
