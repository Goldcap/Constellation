<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:key name="LISTITEMS" match="LISTITEM" use="@styrogroup_val" />

<xsl:template match="LIST">
  
  <div id="{@name}_wrapper">
    
    <div id="titlebar" class="pagenav">
      
      <xsl:if test="@title != ''">
        <span class="row"><span id="formhead" class="headline display"><xsl:value-of select="@title" /></span></span>
      </xsl:if>
      
      <xsl:if test="textbody != ''">
        <p><xsl:value-of select="textbody" disable-output-escaping="yes" /></p>
      </xsl:if>
      
      <xsl:if test="@allow_add != 'false'">
      	<xsl:choose>
				<xsl:when test="@script_default != ''">
				<p><a href="{@script_default}/detail/0">Add a new <xsl:value-of select="@name" /></a></p>
      	</xsl:when>
      	<xsl:otherwise>
				<p><a href="{@script}/detail/0">Add a new <xsl:value-of select="@name" /></a></p>
      	</xsl:otherwise>
      	</xsl:choose>
      </xsl:if>
    
    </div>
         
    <xsl:if test="@docount != 'false' and ceiling(@totalResults div @rpp) > 1">
      
    <div class="navbar pagenav">
      
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
          <xsl:with-param name="PAGEVAR" select="@pagevar" />
        </xsl:call-template>
      </span>
       
      <!--<xsl:call-template name="MULTI_PAGELOOP">
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
        </xsl:call-template>-->
    
    </div>
    
    </xsl:if>
      
    <xsl:if test="count(SORTITEMS/SORTITEM) > 0">
      <form action="{@url}" method="get">
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
      <xsl:choose>
        <xsl:when test="//formwrapper != ''">
          <form name="{//formwrapper}" method="POST" action="#">
            <xsl:apply-templates select="self::*" mode="css" />
            <div style="float: left; padding-top: 20px;">
              <span>
                <input type="image" src="/images/submit.gif" />
              </span>
            </div>
            <input type="hidden" name="listform" value="{//formwrapper}" />
          </form>
        </xsl:when>
        <xsl:otherwise>
          <xsl:if test="@type = 'css'">
          <xsl:apply-templates select="self::*" mode="css" />
          </xsl:if>
          <xsl:if test="@type = 'table'">
          <xsl:apply-templates select="self::*" mode="table" />
          </xsl:if>
        </xsl:otherwise>
      </xsl:choose>
    </div>
    
  </div>

  <xsl:if test="textfooter != ''">
      <div class="row">
        <p><xsl:value-of select="textfooter" disable-output-escaping="yes" /></p>
      </div>
  </xsl:if> 
      
</xsl:template>

<xsl:template match="LIST" mode="table">
  <div id="{@classid}">
  <table border="1">
    <xsl:choose>
      <xsl:when test="count(LISTITEM) = 0">
        <xsl:call-template name="NOTABLE">
          <xsl:with-param name="name" select="@name" />
        </xsl:call-template>
      </xsl:when>
      <xsl:otherwise>
        <xsl:apply-templates select="LISTITEM" mode="table" />
      </xsl:otherwise>
    </xsl:choose>
  </table>
	</div>
  
</xsl:template>

<xsl:template match="LISTITEM" mode="table">
  <xsl:if test="(position() = 1) and (../@header!='false')">
  <tr>
    <xsl:apply-templates select="self::*[1]/@*" mode="table_name" />  
  </tr>
  </xsl:if>
  <tr>
    <xsl:apply-templates select="@*" mode="table_data" />  
  </tr>
</xsl:template>

<xsl:template name="NOTABLE">
  <xsl:param name="name">items</xsl:param>
  <tr>
    <td>No <xsl:value-of select="$name" />s available</td>
  </tr>
</xsl:template>

<xsl:template match="@*" mode="table_name">
    <td>
      <xsl:value-of  select="name()"/>
    </td>
</xsl:template>

<xsl:template match="@*" mode="table_data">  
    <td>
      <xsl:call-template name="split">
        <xsl:with-param name="str" select="."/>
        <xsl:with-param name="pat" select="'|'"/>
      </xsl:call-template>
    </td>
</xsl:template>

<!-- CSS LISTS -->

<xsl:template match="LIST" mode="css">
  <div id="list">
  <xsl:choose>
    <xsl:when test="count(LISTITEM) = 0">
      <xsl:call-template name="NOCSS">
          <xsl:with-param name="name" select="@name" />
        </xsl:call-template>
    </xsl:when>
    <xsl:otherwise>
      <xsl:if test="count(GROUPITEM) != 0">
        <xsl:apply-templates select="LISTITEM[@styrogroup_val and generate-id(.)=generate-id(key('LISTITEMS', @styrogroup_val))]" mode="css" />
      </xsl:if>
      <xsl:if test="count(GROUPITEM) = 0">
        <xsl:apply-templates select="LISTITEM" mode="css" />
      </xsl:if>
    </xsl:otherwise>
  </xsl:choose>
    
  </div>
</xsl:template>

<xsl:template name="NOCSS">
  <xsl:param name="name">items</xsl:param>
  <div class="listitem listitem_1">
     <span class="entry">
      <span class="column_Entry">
      No <xsl:value-of select="$name" />s available.
      </span>
     </span>
  </div>
  
</xsl:template>

<xsl:template match="LISTITEM" mode="css">
  <xsl:if test="(position() = 1) and (../@header!='false')">
  <div class="listitem name">
    <xsl:apply-templates select="self::*[1]/@*" mode="css_name" />
  </div>
  </xsl:if>
  <xsl:variable name="styrogroup" select="@styrogroup_val" />
  <xsl:if test="(count(../GROUP/GROUPITEM) != 0) and (count(preceding-sibling::LISTITEM[@styrogroup_val = $styrogroup]) = 0)">
    <div class="listitem"><span class="column_Group"><xsl:value-of select="@styrogroup_val" /></span></div>
  </xsl:if>
  
  <div class="listitem listitem_{position() mod 2}">
    <xsl:apply-templates select="@*" mode="css_data" />
  </div>
  
</xsl:template>

<xsl:template match="@*" mode="css_name">
  <xsl:variable name="colname"><xsl:value-of select="name()"/></xsl:variable>
  <xsl:choose>
  <xsl:when test="../../HIDDENITEMS/HIDDENITEM[@value=$colname]/@value = $colname">
  </xsl:when>
  <xsl:otherwise>
    <span class="entry">
      <span class="column_{translate($colname, ' ', '')}">
      <xsl:value-of  select="translate($colname, '_', ' ')"/>
      </span>
    </span>
  </xsl:otherwise>
  </xsl:choose>
</xsl:template>

<xsl:template match="@*" mode="css_data">  
  <xsl:variable name="colname"><xsl:value-of select="name()"/></xsl:variable>
  
  <xsl:choose>
  <xsl:when test="../../HIDDENITEMS/HIDDENITEM[@value=$colname]/@value = $colname">
  </xsl:when>
  <xsl:otherwise>
    <span class="entry">
      <span class="column_{translate($colname, ' ', '')}">
        <xsl:choose>
        <xsl:when test="$colname = 'checkbox'">
        <input type="checkbox" name="checkbox[]" value="{.}" />
        </xsl:when>
        <xsl:when test="../../@links='js'">
        <xsl:call-template name="split_flop">
          <xsl:with-param name="str" select="."/>
          <xsl:with-param name="pat" select="'|'"/>
        </xsl:call-template>
        </xsl:when>
        <xsl:otherwise>
        <xsl:call-template name="split">
          <xsl:with-param name="str" select="."/>
          <xsl:with-param name="pat" select="'|'"/>
        </xsl:call-template>
        </xsl:otherwise>
        </xsl:choose>
      </span>
    </span>
  </xsl:otherwise>
  </xsl:choose>
</xsl:template>

</xsl:stylesheet>
