<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:template match="LIST" mode="wiki_search">
  
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
    
    <xsl:variable name="TERMDISPLAY">
      <xsl:for-each select="TERMS/TERMITEM">
        <xsl:value-of select="@value" />
      </xsl:for-each>
    </xsl:variable>
    
    <div id="titlebar" class="pagenav">
      
      <xsl:if test="@title != ''">
        <h2><xsl:value-of select="@title" /> <xsl:if test="string-length($TERMDISPLAY) > 0">- <xsl:value-of select="$TERMDISPLAY" /></xsl:if></h2>
      </xsl:if>
   
   </div>
      
   <xsl:if test="@docount != 'false' and ceiling(@totalResults div @rpp) > 1">
   
   <div class="navbar pagenav">  
        
        <span class="count">Showing Items <xsl:value-of select="$startrecord" /> to <xsl:value-of select="$maxcount" /> of <xsl:value-of select="@totalResults" /></span>
        
        <span class="pagination">
        <xsl:call-template name="SINGLE_PAGELOOP">
          <xsl:with-param name="page" select="@page" />
          <xsl:with-param name="rpp" select="@rpp" />
          <xsl:with-param name="ppp" select="@ppp" />
          <xsl:with-param name="totalResults" select="@totalResults" />
          <xsl:with-param name="start" select="@page" />
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
      
      <table>
        <tr>
          <td>
            <xsl:choose>
              <xsl:when test="count(LISTITEM) = 0">
                <table cellpadding="4">
                  <tr>
                    <td colspan="2" align="right" class="wiki_bluerule"><img src="http://ll.tattoojohnny.com/images/horizontal_rule.jpg" /></td>
                  </tr>
                  <tr>
                    <td><img src="http://ll.tattoojohnny.com/images/ttj_smallicon.jpg" width="100" height="100" /></td>
                    <td>No Items Available!</td>
                  </tr>
                  <tr>
                    <td colspan="2" align="right" class="wiki_bluerule"><img src="http://ll.tattoojohnny.com/images/horizontal_rule.jpg" /></td>
                  </tr>
                </table>
              </xsl:when>
              <xsl:otherwise>
                <xsl:apply-templates select="self::*" mode="wiki_search_table" />
              </xsl:otherwise>
            </xsl:choose>
            </td>
          </tr>
      </table>
          
    </div>
    
      
    <xsl:if test="@docount != 'false' and ceiling(@totalResults div @rpp) > 1">
      
      <div class="navbar pagenav">  
        <span class="count">Showing Items <xsl:value-of select="$startrecord" /> to <xsl:value-of select="$maxcount" /> of <xsl:value-of select="@totalResults" /></span>
        
        <span class="pagination">
        <xsl:call-template name="SINGLE_PAGELOOP">
          <xsl:with-param name="page" select="@page" />
          <xsl:with-param name="rpp" select="@rpp" />
          <xsl:with-param name="ppp" select="@ppp" />
          <xsl:with-param name="totalResults" select="@totalResults" />
          <xsl:with-param name="start" select="@page" />
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
    
  </div>
  
</xsl:template>

<xsl:template match="LIST" mode="wiki_search_table">
  
  <table cellpadding="4">
    <tr>
      <td colspan="2" align="right" class="wiki_bluerule"><img src="http://ll.tattoojohnny.com/images/horizontal_rule.jpg" /></td>
    </tr>
    <xsl:for-each select="LISTITEM">
    <tr>
      <xsl:choose>
      <xsl:when test="count(cms_object_image_url) > 0 and cms_object_image_url != ''">
        <td rowspan="3" class="wiki_image"><a href="/wiki/{cms_object_link}"><img src="{cms_object_image_url}" alt="{cms_object_title}" width="100" height="100" border="0" /></a></td>
      </xsl:when>
      <xsl:otherwise>
          <td rowspan="3" class="wiki_image"><a href="/wiki/{cms_object_link}"><img src="http://ll.tattoojohnny.com/images/ttj_smallicon.jpg" alt="{cms_object_title}" width="100" height="100" border="0" /></a></td>
      </xsl:otherwise>
      </xsl:choose>
      <td class="wiki_title"><a href="/wiki/{cms_object_link}"><xsl:value-of select="cms_object_title" disable-output-escaping="yes" /></a></td>
    </tr>
    <tr>
      <td class="wiki_date"><xsl:value-of select="cms_object_date_created"/></td>
    </tr>
    <tr>
      <td><xsl:choose><xsl:when test="cms_object_teaser != ''">
      <xsl:value-of select="cms_object_teaser" disable-output-escaping="yes" />
      </xsl:when>
      <xsl:otherwise>
      <xsl:value-of select="cms_object_text" disable-output-escaping="yes" />
      </xsl:otherwise>
      </xsl:choose>
      ...</td>
    </tr>
    <tr>
      <td colspan="2" align="right" class="wiki_bluerule"><img src="http://ll.tattoojohnny.com/images/horizontal_rule.jpg" /></td>
    </tr>
    </xsl:for-each>
  </table>
  
</xsl:template>

</xsl:stylesheet>
