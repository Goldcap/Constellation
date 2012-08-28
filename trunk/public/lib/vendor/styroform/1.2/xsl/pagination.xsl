<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:template name="SINGLE_PAGELOOP">
  <xsl:param name="page"></xsl:param>
  <xsl:param name="rpp"></xsl:param>
  <xsl:param name="ppp"></xsl:param>
  <xsl:param name="totalResults"></xsl:param>
  <xsl:param name="start">0</xsl:param>
  <!--<xsl:param name="limit">0</xsl:param>-->
  <xsl:param name="repeat">0</xsl:param>
  <xsl:param name="SELECTED">1</xsl:param>
  <xsl:param name="TYPE">SINGLE</xsl:param>
  <xsl:param name="BASEFUNC">nextPage</xsl:param>
  <xsl:param name="ATTRIBS"></xsl:param>
  <xsl:param name="BASEURL"></xsl:param>
  <xsl:param name="BASEQS">?</xsl:param>
  <xsl:param name="SITEMS"></xsl:param>
  <xsl:param name="PAGEVAR">page</xsl:param>
  <xsl:param name="FIRST">true</xsl:param>
  <xsl:variable name="THESORT">
    <xsl:if test="count($SITEMS/SORTITEM[@selected='true']) > 0">
      <xsl:call-template name="SORTLOOP">
        <xsl:with-param name="ITEMS" select="$SITEMS/SORTITEM[@selected='true']" />
      </xsl:call-template>
  </xsl:if>
  </xsl:variable>
  <xsl:variable name="THEPAGE">
    <xsl:value-of select="ceiling($start div $ppp)" />
  </xsl:variable>
 <xsl:variable name="THISMAXPAGE">
    <xsl:choose>
      <!--<xsl:when test="number($rpp) * $THEPAGE">-->
      <xsl:when test="2=1">
      </xsl:when>
      <xsl:otherwise>
        <xsl:value-of select="number($ppp) * $THEPAGE" />
      </xsl:otherwise>
    </xsl:choose>
  </xsl:variable>
  <xsl:if test="(number($SELECTED) > 1) and (number($SELECTED) = number($start))">
    <span class="pagenum_nav"><a href="{$BASEQS}{$PAGEVAR}={number($SELECTED) - 1}{$THESORT}" class="pagination_previous">&lt; Previous </a><xsl:text></xsl:text></span>
  </xsl:if>
  
  <xsl:if test="$FIRST = 'true' and $start > ($THISMAXPAGE - ($ppp - 1))">
    <xsl:call-template name="PAGE_LOOP">
      <xsl:with-param name="start" select="$THISMAXPAGE - ($ppp - 1)"/>
      <xsl:with-param name="repeat" select="$start"/>
      <xsl:with-param name="BASEQS" select="$BASEQS"/>
      <xsl:with-param name="THESORT" select="$THESORT" />
    </xsl:call-template>
  </xsl:if>
  <xsl:if test="(ceiling($totalResults div $rpp) >= number($start)) and number($THISMAXPAGE) >= number($start)">
    <xsl:choose>
      <xsl:when test="number($SELECTED) = number($start)">
        <span id="pagenum_{$start}" class="pagenum_selected"><xsl:value-of select="$start" /><xsl:text> | </xsl:text></span>
      </xsl:when>
      <xsl:otherwise>
        <span class="pagenum"><a href="{$BASEQS}{$PAGEVAR}={$start}{$THESORT}"><xsl:value-of select="$start" /></a><xsl:text> | </xsl:text></span>
      </xsl:otherwise>
    </xsl:choose>
    <xsl:if test="(ceiling($totalResults div $rpp) >= number($start)) and (number($THISMAXPAGE) = number($start)) and (ceiling($totalResults div $rpp) > number($SELECTED))">
      of <xsl:value-of select="ceiling($totalResults div $rpp)" /><xsl:text>   </xsl:text>
      <span class="pagenum_next">
      <span class="pagenum_nav"><a href="{$BASEQS}{$PAGEVAR}={$SELECTED + 1}{$THESORT}">Next &gt;</a></span>
      </span>
    </xsl:if>
    <xsl:if test="(ceiling($totalResults div $rpp) >= number($start)) and number($THISMAXPAGE) > number($start)">
      <xsl:call-template name="SINGLE_PAGELOOP">
        <xsl:with-param name="page"  select="$page"/>
        <xsl:with-param name="rpp"  select="$rpp"/>
        <xsl:with-param name="ppp"  select="$ppp"/>
        <xsl:with-param name="totalResults"  select="$totalResults"/>
        <xsl:with-param name="start" select="$start + 1"/>
        <xsl:with-param name="repeat" select="$repeat"/>
        <xsl:with-param name="SELECTED" select="$SELECTED"/>
        <xsl:with-param name="TYPE" select="$TYPE"/>
        <xsl:with-param name="BASEFUNC" select="$BASEFUNC"/>
        <xsl:with-param name="ATTRIBS" select="$ATTRIBS" />
        <xsl:with-param name="BASEURL" select="$BASEURL"/>
        <xsl:with-param name="BASEQS" select="$BASEQS"/>
        <xsl:with-param name="SITEMS" select="$SITEMS" />
        <xsl:with-param name="THESORT" select="$THESORT" />
        <xsl:with-param name="PAGEVAR" select="$PAGEVAR" />
        <xsl:with-param name="FIRST" select="false" />
      </xsl:call-template>
    </xsl:if>
  </xsl:if> 
  
</xsl:template>

<xsl:template name="PAGE_LOOP">

 <xsl:param name="start" />
 <xsl:param name="repeat" />
 <xsl:param name="BASEQS" />
 <xsl:param name="THESORT" />
 <xsl:param name="PAGEVAR">page</xsl:param>
 
 <xsl:if test="$start &lt; $repeat">
    <span class="pagenum"><a href="{$BASEQS}{$PAGEVAR}={$start}{$THESORT}"><xsl:value-of select="$start" /></a><xsl:text> </xsl:text>|<xsl:text> </xsl:text></span>
 </xsl:if>

 <!--begin_: RepeatTheLoopUntilFinished-->
 <xsl:if test="$start &lt; $repeat">
    <xsl:call-template name="PAGE_LOOP">
        <xsl:with-param name="start" select="$start + 1" />
        <xsl:with-param name="repeat" select="$repeat"/>
        <xsl:with-param name="BASEQS" select="$BASEQS"/>
        <xsl:with-param name="THESORT" select="$THESORT"/>
        <xsl:with-param name="PAGEVAR" select="$PAGEVAR"/>
    </xsl:call-template>
 </xsl:if>

</xsl:template>

<xsl:template name="MULTI_PAGELOOP">
  <xsl:param name="page"></xsl:param>
  <xsl:param name="rpp"></xsl:param>
  <xsl:param name="ppp"></xsl:param>
  <xsl:param name="totalResults"></xsl:param>
  <xsl:param name="start">0</xsl:param>
  <!--<xsl:param name="limit">0</xsl:param>-->
  <xsl:param name="repeat">0</xsl:param>
  <xsl:param name="SELECTED">1</xsl:param>
  <xsl:param name="TYPE">SINGLE</xsl:param>
  <xsl:param name="BASEFUNC">nextPage</xsl:param>
  <xsl:param name="ATTRIBS"></xsl:param>
  <xsl:param name="BASEURL"></xsl:param>
  <xsl:param name="BASEQS">?</xsl:param>
  <xsl:param name="SITEMS"></xsl:param>
  <xsl:param name="PAGEVAR">page</xsl:param>
  <xsl:variable name="THESORT">
    <xsl:if test="count($SITEMS/SORTITEM[@selected='true']) > 0">
      <xsl:call-template name="SORTLOOP">
        <xsl:with-param name="ITEMS" select="$SITEMS/SORTITEM[@selected='true']" />
      </xsl:call-template>
  </xsl:if>
  </xsl:variable>
  <xsl:variable name="limit" select="(floor(($page - 1) div $ppp) * $ppp) + $ppp" />
  
  <xsl:if test="((number($start) > $ppp) and (number($start) = (floor(($page - 1) div $ppp) * $ppp) + 1))">
    <xsl:if test="$TYPE = 'ASYNC'"><span class="pagenum_nav"><a href="javascript: {$BASEFUNC}({$start - $ppp},{$ATTRIBS});">&lt; Previous</a> </span></xsl:if>
    <xsl:if test="$TYPE = 'STATIC'"><span class="pagenum_nav"><a href="{$BASEQS}{$PAGEVAR}={$start - $ppp}{$THESORT}">&lt; Previous</a><xsl:text> </xsl:text></span></xsl:if>
  </xsl:if>
  <xsl:if test="(number($limit) >= number($start)) and (number($repeat) >= number($start))">
    <xsl:choose>
      <xsl:when test="number($SELECTED) = number($start)">
        <span id="pagenum_{$start}" class="pagenum_selected"><xsl:value-of select="$start" /> | </span>
      </xsl:when>
      <xsl:otherwise>
        <xsl:if test="$TYPE = 'ASYNC'"><span id="pagenum_{$start}" class="pagenum"><a href="javascript: {$BASEFUNC}({$start},{$ATTRIBS});"><xsl:value-of select="$start" /></a> | </span></xsl:if>
        <xsl:if test="$TYPE = 'STATIC'"><span class="pagenum"><a href="{$BASEQS}{$PAGEVAR}={$start}{$THESORT}"><xsl:value-of select="$start" /></a> | </span></xsl:if>
      </xsl:otherwise>
    </xsl:choose>
    <xsl:if test="(number($repeat) > number($start)) and (number($start) = (number($limit)))">
      of <xsl:value-of select="floor($totalResults div $rpp)" /><xsl:text>   </xsl:text>
      <span class="pagenum_next">
      <xsl:if test="$TYPE = 'ASYNC'"><span class="pagenum_nav"><a href="javascript: {$BASEFUNC}({$start + 1},{$ATTRIBS});">Next &gt;</a></span></xsl:if>
      <xsl:if test="$TYPE = 'STATIC'"><span class="pagenum_nav"><a href="{$BASEQS}{$PAGEVAR}={$start + 1}{$THESORT}">Next &gt;</a></span></xsl:if>
      </span>
    </xsl:if>
    <xsl:call-template name="MULTI_PAGELOOP">
      <xsl:with-param name="page"  select="$page"/>
      <xsl:with-param name="rpp"  select="$rpp"/>
      <xsl:with-param name="ppp"  select="$ppp"/>
      <xsl:with-param name="totalResults"  select="$totalResults"/>
      <xsl:with-param name="start" select="$start + 1"/>
      <xsl:with-param name="repeat" select="$repeat"/>
      <xsl:with-param name="SELECTED" select="$SELECTED"/>
      <xsl:with-param name="TYPE" select="$TYPE"/>
      <xsl:with-param name="BASEFUNC" select="$BASEFUNC"/>
      <xsl:with-param name="ATTRIBS" select="$ATTRIBS" />
      <xsl:with-param name="BASEURL" select="$BASEURL"/>
      <xsl:with-param name="BASEQS" select="$BASEQS"/>
      <xsl:with-param name="limit" select="$limit"/>
      <xsl:with-param name="SITEMS" select="$SITEMS" />
      <xsl:with-param name="THESORT" select="$THESORT" />
      <xsl:with-param name="PAGEVAR" select="$PAGEVAR" />
    </xsl:call-template>
  </xsl:if>
  
</xsl:template>

<xsl:template name="SORTLOOP">
  <xsl:param name="ITEMS"></xsl:param>
  <xsl:for-each select="$ITEMS">&#38;sort=<xsl:value-of select="@value" /></xsl:for-each>
</xsl:template>

</xsl:stylesheet>
