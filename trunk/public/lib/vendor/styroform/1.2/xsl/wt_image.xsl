<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:template match="LIST" mode="WT">
  
  <div id="{@name}_wrapper">
    <xsl:variable name="startrecord"><xsl:value-of select="(@page - 1) * @rpp + 1" /></xsl:variable>
    <xsl:variable name="maxcount">
      <xsl:choose>
        <xsl:when test="($startrecord + @rpp - 1) > @totalResults">
          <xsl:value-of select="@totalResults" />
        </xsl:when>
        <xsl:otherwise>
          <xsl:value-of select="$startrecord + @rpp - 1" />
        </xsl:otherwise>
      </xsl:choose>
    </xsl:variable>
    
   <div id="titlebar" class="pagenav">
      
      <xsl:if test="@title != ''">
      <div class="row">
        <span id="formhead" class="headline display"><xsl:value-of select="@title" /></span>
      </div>
      </xsl:if>
      
   </div>
      
   <xsl:if test="@docount != 'false' and ceiling(@totalResults div @rpp) > 1">
      
   <div class="navbar pagenav">  
      
        <span class="count">Items <xsl:value-of select="$startrecord" /> to <xsl:value-of select="$maxcount" /> of <xsl:value-of select="@totalResults" /></span>
        
        <span class="pagination">
        <xsl:call-template name="MULTI_PAGELOOP">
          <xsl:with-param name="page" select="@page" />
          <xsl:with-param name="rpp" select="@rpp" />
          <xsl:with-param name="ppp" select="@ppp" />
          <xsl:with-param name="totalResults" select="@totalResults" />
          <xsl:with-param name="start" select="(floor((@page - 1) div @ppp) * @ppp) + 1" />
          <xsl:with-param name="repeat" select="ceiling(@totalResults div @rpp)"/>
          <xsl:with-param name="SELECTED" select="@page" />
          <xsl:with-param name="TYPE" select="'STATIC'" />
          <xsl:with-param name="BASEFUNC" select="'nextPage'" />
          <xsl:with-param name="ATTRIBS"></xsl:with-param>
          <xsl:with-param name="BASEURL"><xsl:value-of select="@url" /></xsl:with-param>
          <xsl:with-param name="BASEQS"><xsl:value-of select="@query_string" /></xsl:with-param>
          <xsl:with-param name="SITEMS" select="SORTITEMS" />
        </xsl:call-template>
        </span>
   
    </div>     
    
    </xsl:if>
    
    <xsl:if test="count(SORTITEMS/SORTITEM) > 0">
    <form action="#" method="get">
    <span class="sort">
      Sort by: <select name="sort" class="sortselect" onChange="this.form.submit();">
        <xsl:for-each select="SORTITEMS/SORTITEM">
        <xsl:choose>
        <xsl:when test="@selected='true' and (count(preceding-sibling::*[@selected='true']) = 0)">
        <option value="{@value}" selected="selected"><xsl:value-of select="@description" /></option>
        </xsl:when>
        <xsl:otherwise>
        <option value="{@value}"><xsl:value-of select="@description" /></option>
        </xsl:otherwise>
        </xsl:choose>
        </xsl:for-each>
      </select>
    </span>
    </form>
    </xsl:if>
    
    <div id="results">
     
     
    <table width="500" cellpadding="20" border="0">
     <xsl:choose>
        <xsl:when test="count(LISTITEM) = 0">
          <tr>
          <td>
          <table cellpadding="4">
            <tr>
              <td colspan="2">Sorry, No Images Available.</td>
            </tr>
          </table>
          </td>
          </tr>
        </xsl:when>
        <xsl:otherwise>
          <xsl:apply-templates select="self::*" mode="wt_image_table" />
        </xsl:otherwise>
      </xsl:choose>
    
    </table>
    </div>
    
    <div id="titlebar">
      
      <xsl:if test="@docount != 'false' and ceiling(@totalResults div @rpp) > 1">
        
        <span class="count">Items <xsl:value-of select="$startrecord" /> to <xsl:value-of select="$maxcount" /> of <xsl:value-of select="$maxcount" /></span>
      
        <span class="pagination">
        <xsl:call-template name="MULTI_PAGELOOP">
          <xsl:with-param name="page" select="@page" />
          <xsl:with-param name="rpp" select="@rpp" />
          <xsl:with-param name="ppp" select="@ppp" />
          <xsl:with-param name="totalResults" select="@totalResults" />
          <xsl:with-param name="start" select="(floor((@page - 1) div @ppp) * @ppp) + 1" />
          <xsl:with-param name="repeat" select="ceiling(@totalResults div @rpp)"/>
          <xsl:with-param name="SELECTED" select="@page" />
          <xsl:with-param name="TYPE" select="'STATIC'" />
          <xsl:with-param name="BASEFUNC" select="'nextPage'" />
          <xsl:with-param name="ATTRIBS"></xsl:with-param>
          <xsl:with-param name="BASEURL"><xsl:value-of select="@url" /></xsl:with-param>
          <xsl:with-param name="BASEQS"><xsl:value-of select="@query_string" /></xsl:with-param>
          <xsl:with-param name="SITEMS" select="SORTITEMS" />
        </xsl:call-template>
        </span>
        
      </xsl:if>
    
    </div>
    
  </div>
  
</xsl:template>

<xsl:template match="LIST" mode="wt_image_table">
    
  <xsl:for-each select="LISTITEM[position() mod 4 = 1]">
    <tr height="130">
    <xsl:apply-templates select=".|following-sibling::LISTITEM[position() &lt; 4]" mode="wt_image">
      <xsl:with-param name="apos"><xsl:value-of select="position()" /></xsl:with-param>
    </xsl:apply-templates>
    </tr>
  </xsl:for-each>
  
</xsl:template>

<xsl:template match="LISTITEM" mode="wt_image">
  <xsl:param name="apos"></xsl:param>
  <!--<LISTITEM fk_artist_id="/artist_image/detail/1|489" artist_image_name="Dita Does Dallas" artist_image_original="45547b2cedb02741441977.jpg" artist_image_description="Shower, bathroom, va-va-voom" artist_image_date_created="2010-09-20 10:45:18" artist_image_tags="Shower, Bathroom, VaVaVoom" artist_image_views="24" artist_image_directory="f/fc/fc8/fc8533f77e1478ee69d1f46c435fa752" artist_image_guid="fc8533f77e1478ee69d1f46c435fa752" artist_image_category="1" artist_image_aspect="0" artist_image_id="1"/>-->
  <td width="25%" align="center">
    <a href="{../@script}/detail/{artist_image_id}">
      <image src="/uploads/assets/gallery/{artist_image_directory}/{artist_image_guid}_thumb.jpg" border="0" /><br />
      <xsl:value-of select="artist_image_name" />
    </a>
  </td>
 <xsl:if test="(position() = last()) and (position() &lt; 4)">
    <xsl:call-template name="FillerCells">
       <xsl:with-param name="cellCount" select="4 - position()"/>
       <xsl:with-param name="fillSize" select="'25'"/>
    </xsl:call-template>
 </xsl:if>
</xsl:template>

</xsl:stylesheet>
