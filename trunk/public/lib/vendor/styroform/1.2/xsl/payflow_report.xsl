<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:template match="reportingEngineResponse">
    <strong>Payflow Sales Report (<xsl:value-of select="getDataResponse/reportId" />)</strong><br />
    <xsl:apply-templates select="getDataResponse/reportDataRow" />

</xsl:template>

<xsl:template match="reportDataRow">
  <div class="listitem listitem_{position() mod 2}">
    <span style="float: left; width: 120px">
      <xsl:value-of select="columnData[@colNum='1']" />
    </span>
    <span style="float: left; width: 150px">
      <xsl:value-of select="columnData[@colNum='2']" />
    </span>
    <span style="float: left; width: 100px">
      <xsl:value-of select="columnData[@colNum='9']" />
    </span>
    <span style="float: left; width: 100px">
      <xsl:value-of select="columnData[@colNum='5']" />
    </span>
    <span style="float: left; width: 100px">
      <xsl:value-of select="columnData[@colNum='10']" />
    </span>
  </div>
    <!--
    -
<getDataResponse>
<reportId>RE0081242578</reportId>
-
<reportDataRow rowNum="1">
-
<columnData colNum="1">
<data>VLEF4C26DEFB</data>
</columnData>
-
<columnData colNum="2">
<data>2009-09-04 00:00:28</data>
</columnData>
-
<columnData colNum="3">
<data>Authorization</data>
</columnData>
-
<columnData colNum="4">
<data>Visa</data>
</columnData>
-
<columnData colNum="5">
<data>4238XXXXXXXX9258</data>
</columnData>
-
<columnData colNum="6">
<data>0710</data>
</columnData>
-
<columnData colNum="7">
<data>1694</data>
</columnData>
-
<columnData colNum="8">
<data>0</data>
</columnData>
-
<columnData colNum="9">
<data>Approved</data>
</columnData>
-
<columnData colNum="10">
-
<data>
Order: TTJ-5529195-20090903900|Skus: APF-00009|Product Cost: 16.94|Shipping Cost: 0
</data>
</columnData>
-
<columnData colNum="11">
<data>Maxmind Score: 1.59 This order is low risk</data>
</columnData>
-
<columnData colNum="12">
<data>173.1.121.221</data>
</columnData>
-
<columnData colNum="13">
<data>003273</data>
</columnData>
-
<columnData colNum="14">
<data>2472 Beckham Lane </data>
</columnData>
-
<columnData colNum="15">
<data>29720</data>
</columnData>
-
<columnData colNum="16">
<data>USD</data>
</columnData>
</reportDataRow>

    -->
    
</xsl:template>

<xsl:template match="LIST" mode="paypal_search">
  
  <div id="{@name}_wrapper">
    
    <div id="titlebar" class="pagenav">
      
      <xsl:if test="@title != ''">
        <span class="row"><span id="formhead" class="headline display"><xsl:value-of select="@title" /></span></span>
      </xsl:if>
      
      <xsl:if test="textbody != ''">
        <p><xsl:value-of select="textbody" disable-output-escaping="yes" /></p>
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
        <div id="list">
          <div class="listitem name">
            <span class="user_order_date">Date</span>
            <span class="user_order_id">ID</span>
            <span class="user_order_user_fname">Name</span>
            <span class="user_order_user_lname">Email</span>
            <span class="user_order_guid">Trans ID</span>
            <span class="user_order_total_fs">Amount</span>
          </div>
          <xsl:choose>
            <xsl:when test="count(LISTITEM) = 0">
              <xsl:call-template name="NOCSS">
                  <xsl:with-param name="name" select="@name" />
                </xsl:call-template>
            </xsl:when>
            <xsl:otherwise>
              <xsl:apply-templates select="LISTITEM" mode="payflow" />
            </xsl:otherwise>
          </xsl:choose>
          
        </div>
    </div>
    
  </div>

  <xsl:if test="textfooter != ''">
      <div class="row">
        <p><xsl:value-of select="textfooter" disable-output-escaping="yes" /></p>
      </div>
  </xsl:if> 
      
</xsl:template>

<xsl:template match="LISTITEM" mode="payflow">
  <div class="listitem listitem_{position() mod 2}">
    <span class="user_order_date"><xsl:value-of select="user_order_date"/></span>
    <span class="user_order_id"><xsl:value-of select="user_order_id"/></span>
    <span class="user_order_user_fname"><xsl:value-of select="user_order_user_fname"/></span>
    <span class="user_order_user_lname"><xsl:value-of select="user_order_user_lname"/></span>
    <span class="user_order_guid"><a href="http://www.tattoojohnny.com/download/paypal?tx={user_order_guid}"><xsl:value-of select="user_order_guid"/></a></span>
    <span class="user_order_total_fs"><xsl:value-of select="user_order_total_fs"/></span>
  </div>
</xsl:template>

</xsl:stylesheet>
