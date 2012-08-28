<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:template match="LIST" mode="press_result">
  
  <div id="press_wrapper">
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
    
    <div id="titlebar">
      
      <xsl:if test="@title != ''">
        <h2>Tattoo Johnny <xsl:value-of select="@title" /></h2>
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
        
      <!--<xsl:if test="count(SORTITEMS/SORTITEM) > 0">
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
      </xsl:if>-->
      
    </div>
    
    </xsl:if>
     
    <div id="results">
      
      <table border="1">
        <xsl:choose>
          <xsl:when test="count(LISTITEM) = 0">
            <table cellpadding="4">
              <tr>
                <td colspan="2">Sorry, No Items Available. <a href="http://www.tattoojohnny.com">Click Here</a> to return to the Main site.</td>
              </tr>
            </table>
          </xsl:when>
          <xsl:otherwise>
            <xsl:apply-templates select="self::*" mode="title_result_table" />
          </xsl:otherwise>
        </xsl:choose>
      </table>
          
    </div>
    
    <xsl:if test="@docount != 'false' and ceiling(@totalResults div @rpp) > 1">
    
    <div class="navbar pagenav">
        
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
     
    </div>
    
    </xsl:if>
    
  </div>
  
</xsl:template>

<xsl:template match="LIST" mode="title_result_table">
  
  <table cellpadding="4">
    
    <xsl:apply-templates select="LISTITEM" mode="title_result" />
    
  </table>
  
</xsl:template>

<xsl:template match="LISTITEM" mode="title_result">
  
  <tr>
    <td><a href="{../@script}/{cms_object_link}" target="_new"><xsl:value-of select="cms_object_title" disable-output-escaping="yes" /></a></td>
  </tr>
  <tr>
    <td><xsl:value-of select="cms_object_text" disable-output-escaping="yes" /></td>
  </tr>
  <tr>
    <td><hr /></td>
  </tr>
  
</xsl:template>

</xsl:stylesheet>
