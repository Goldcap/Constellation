<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="xml" version="1.0" encoding="UTF-8" omit-xml-declaration="no" standalone="yes" doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN" doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd" />

<xsl:template match="widget[@name='Account']/content">
  <xsl:apply-templates select="AFORM" />
  <xsl:apply-templates select="LIST" />
</xsl:template>

<xsl:template match="LIST" mode="download_search">
  
  <div id="{@name}_wrapper">
    <div id="titlebar">
      
      <xsl:if test="@title != ''">
        <span class="row"><span id="formhead" class="headline display"><xsl:value-of select="@title" /></span></span>
      </xsl:if>
      
    </div>
    
    <div id="results">
      
        <div class="downloads listitem name">
          <span class="entry"><span class="column_design">Design Info</span></span>
          <!--<span class="entry"><span class="column_resize">Resize Design</span></span>-->
          <span class="entry"><span class="column_reference">Reference</span></span>
          <span class="entry"><span class="column_reference">Stencil</span></span>
          <span class="entry"><span class="column_remaining">Time Remaining</span></span>
       </div>
        
       <xsl:choose>
        <xsl:when test="count(LISTITEM) = 0">
          <div class="listitem listitem_1">
            <span class="entry">
            No items available
            </span>
          </div>
        </xsl:when>
        <xsl:otherwise>
        <xsl:for-each select="LISTITEM">
          <div class="listitem listitem_{position() mod 2}">
            <span class="entry"><span class="column_design">
              Artist: <xsl:value-of select="user_order_product_artist_fname" /> <xsl:value-of select="user_order_product_artist_lname" /><br />
              Sku: <xsl:value-of select="sku" />
            </span></span>
            <span class="entry"><span class="column_image">
              <a href="/account_image/designs/{sku}/color/color/download/false" target="_new"><img src="{user_order_product_product_image_path}/{sku}/{sku}_color.jpg" border="0" /></a><br />
            </span></span>
            <span class="entry"><span class="column_download">
              <a href="/account/designs/{sku}/color/color/download/true" target="_new">Download</a><br />
              <a href="/account_image/designs/{sku}/color/color/download/false" target="_new">View</a>
            </span></span>
            <span class="entry"><span class="column_image">
              <a href="/account_image/designs/{sku}/color/bw/download/false" target="_new"><img src="{user_order_product_product_image_path}/{sku}/{sku}_stensil.jpg" border="0" /></a>
            </span></span>
            <span class="entry"><span class="column_download">
              <a href="/account/designs/{sku}/color/bw/download/true" target="_new">Download</a><br />
              <a href="/account_image/designs/{sku}/color/bw/download/false" target="_new">View</a>
            </span></span>
            <span class="entry"><span class="column_remaining"><xsl:value-of select="user_order_product_date_purchased" /> Days</span></span>
          </div>
        </xsl:for-each>
        </xsl:otherwise>
      </xsl:choose>
    </div>
    
    <xsl:if test="@docount != 'false' and ceiling(@totalResults div @rpp) > 1">
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
        
        <span class="count">Showing Items <xsl:value-of select="$startrecord" /> to <xsl:value-of select="$maxcount" /> of <xsl:value-of select="@totalResults" /></span>
      </xsl:if>
      
      <div class="account-help">
        <p>
          <a href="/faq/downloads" class="account-link">Download FAQ</a>
          <br />
          Have questions about your download? Check out our Frequently Asked Questions.
        </p>
        
        <p>
          <a href="/account/wishlist" class="account-link">View Wishlist</a>
          <br />
          See what's in your Wish List.
        </p>
        
        <p>
          <a href="/store/Tattoo Test Drive" class="account-link">Test Drive My Tattoo</a>
          <br />
          Give yourself a satisfaction guarantee, avoid mistakes, and have fun test-driving your tattoo.
        </p>
      </div>

  </div>
  
</xsl:template>

<xsl:template match="LIST" mode="free_search">
  
  <div id="{@name}_wrapper">
    <div id="titlebar">
      
      <xsl:if test="@title != ''">
        <span class="row"><span id="formhead" class="headline display"><xsl:value-of select="@title" /></span></span>
      </xsl:if>
      
      
    </div>
    
    <div id="results">
      
        <div class="downloads listitem name">
          <span class="entry"><span class="column_image">Design</span></span>
          <span class="entry"><span class="column_design">Product</span></span>
          <!--<span class="entry"><span class="column_resize">Resize Design</span></span>-->
          <span class="entry"><span class="column_download">Download Design</span></span>
          <span class="entry"><span class="column_view">View/Print from Browser</span></span>
          <span class="entry"><span class="column_remaining">Time Remaining</span></span>
       </div>
       
       <xsl:choose>
        <xsl:when test="count(LISTITEM) = 0">
          <div class="listitem listitem_1">
            <span class="entry">
            No items available
            </span>
          </div>
        </xsl:when>
        <xsl:otherwise>
        <xsl:for-each select="LISTITEM">
          <div class="listitem listitem_{position() mod 2}">
            <span class="entry"><span class="column_design">
              Artist: <xsl:value-of select="user_order_product_artist_fname" /> <xsl:value-of select="user_order_product_artist_lname" /><br />
              Sku: <xsl:value-of select="sku" />
            </span></span>
            <span class="entry"><span class="column_image">
              <a href="/account/designs/{sku}/color/color/download/false" target="_new"><img src="{user_order_product_product_image_path}/{sku}/{sku}_color.jpg" border="0" /></a><br />
            </span></span>
            <span class="entry"><span class="column_download">
              <a href="/account/designs/{sku}/color/color/download/true" target="_new">Download</a><br />
              <a href="/account/designs/{sku}/color/color/download/false" target="_new">View In Browser</a>
            </span></span>
            <span class="entry"><span class="column_image">
              <a href="/account/designs/{sku}/color/bw/download/false" target="_new"><img src="{user_order_product_product_image_path}/{sku}/{sku}_stensil.jpg" border="0" /></a>
            </span></span>
            <span class="entry"><span class="column_download">
              <a href="/account/designs/{sku}/color/bw/download/true" target="_new">Download</a><br />
              <a href="/account/designs/{sku}/color/bw/download/false" target="_new">View In Browser</a>
            </span></span>
            <span class="entry"><span class="column_remaining"><xsl:value-of select="user_order_product_date_purchased" /> Days</span></span>
          </div>
        </xsl:for-each>
        </xsl:otherwise>
      </xsl:choose>
    </div>
    
    <xsl:if test="@docount != 'false' and ceiling(@totalResults div @rpp) > 1">
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
        
        <span class="count">Showing Items <xsl:value-of select="$startrecord" /> to <xsl:value-of select="$maxcount" /> of <xsl:value-of select="@totalResults" /></span>
      </xsl:if>
      
  </div>
  
</xsl:template>

<xsl:template match="LIST" mode="wishlist_search">
  
  <div id="{@name}_wrapper">
    <div id="titlebar">
      
      <xsl:if test="@title != ''">
        <span class="row"><span id="formhead" class="headline display"><xsl:value-of select="@title" /></span>[<a href="/account/wishlist/clear">Remove All Items</a>]</span>
      </xsl:if>
    </div>
    <div id="results">
      
        <div class="downloads listitem name">
          <span class="entry"><span class="column_image">Design</span></span>
          <span class="entry"><span class="column_design">Artist</span></span>
          <span class="entry"><span class="column_remaining">Product Sku</span></span>
       </div>
      
      <xsl:choose>
        <xsl:when test="count(LISTITEM) = 0">
          <div class="listitem listitem_1">
            <span class="entry">
            No items available
            </span>
          </div>
        </xsl:when>
        <xsl:otherwise> 
        <xsl:for-each select="LISTITEM">
          <div class="listitem listitem_{position() mod 2}">
            <span class="entry"><span class="column_image">
              <a href="/product/{wishlist_product_sku}"><img src="{wishlist_product_image_path}/{wishlist_product_sku}/{wishlist_product_sku}_color.jpg" border="0" /></a><br />
            </span></span>
            <span class="entry"><span class="column_design">
              <xsl:value-of select="wishlist_product_artist_name" /><br />
            </span></span>
            <span class="entry"><span class="column_remaining"><xsl:value-of select="wishlist_product_sku" /></span></span>
          </div>
        </xsl:for-each>
        </xsl:otherwise>
      </xsl:choose>
    </div>
    
    <xsl:if test="@docount != 'false' and ceiling(@totalResults div @rpp) > 1">
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
        
        <span class="count">Showing Items <xsl:value-of select="$startrecord" /> to <xsl:value-of select="$maxcount" /> of <xsl:value-of select="@totalResults" /></span>
      </xsl:if>
      
  </div>
  
</xsl:template>

<xsl:template match="LIST" mode="user_order_search">
  
  <!--
  <user_order_id>111</user_order_id>
  <user_order_guid>TTJ-000111-200905211605</user_order_guid>
  -
  <user_order_product>
  -
  <user_order_product_child>
  TTJ-0001,,4.17,/images/products/T/TT/TTJ,3.89,1,3.89,3.89
  </user_order_product_child>
  </user_order_product>
  <user_order_total_fs>3.89</user_order_total_fs>
  <user_order_status>1</user_order_status>
  -->
  <div id="{@name}_wrapper">
    <div id="titlebar">
      
      <xsl:if test="@title != ''">
        <span class="row"><span id="formhead" class="headline display"><xsl:value-of select="@title" /></span></span>
      </xsl:if>
      
    </div>
    
    <div id="results">
      
        <div class="downloads listitem name">
          <span class="entry"><span class="column_order_date">Order Date</span></span>
          <span class="entry"><span class="column_user_order">Order Number</span></span>
       </div>
       
        <xsl:for-each select="LISTITEM">
          <div class="listitem listitem_{position()}">
            <span class="entry"><span class="column_order_date">
              <xsl:value-of select="user_order_date" />
            </span></span>
            <span class="entry"><span class="column_user_order">
              <a href="/account/orders/{user_order_guid}"><xsl:value-of select="user_order_guid" /></a>
            </span></span>
          </div>
          <div class="downloads listitem name">
            <span class="column_order_date white">.
            </span>
            <span class="column_user_order">
            Total:  $<xsl:value-of select="user_order_total_fs" />
            Status: <xsl:choose>
                <xsl:when test="user_order_status=1"><span style="color:orange">Pending Review</span> </xsl:when>
                <xsl:when test="user_order_status=2 and user_order_process=0"> <span style="color:blue">Pending Shipment</span> </xsl:when>
                <xsl:when test="user_order_status=2 and user_order_process=1"> <span style="color:green">Shipped</span> </xsl:when>
                <xsl:when test="user_order_status=3 and user_order_process=0"> <span style="color:green">Pending Shipment</span> </xsl:when>
                <xsl:when test="user_order_status=3 and user_order_process=1"> <span style="color:green">Shipped</span> </xsl:when>
                <xsl:when test="user_order_status=-1"> <span style="color: red">Declined</span> </xsl:when>
                <xsl:otherwise> <span style="color:orange">Pending Review</span> </xsl:otherwise>
              </xsl:choose>
            </span>
         </div>
        </xsl:for-each>
    </div>
    
    <xsl:if test="@docount != 'false' and ceiling(@totalResults div @rpp) > 1">
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
        
        <span class="count">Showing Items <xsl:value-of select="$startrecord" /> to <xsl:value-of select="$maxcount" /> of <xsl:value-of select="@totalResults" /></span>
      </xsl:if>
      
  </div>
  
</xsl:template>

<xsl:include href="../../../vendor/styroform/1.2/xsl/includes.xsl" />

</xsl:stylesheet>
