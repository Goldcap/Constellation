<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">


<xsl:template match="LIST" mode="stats_search">
  
  <div class="{@name}_wrapper">
    <div id="titlebar" class="pagenav">
      
      <xsl:if test="@title != ''">
        <span class="row"><span id="formhead" class="headline display"><xsl:value-of select="@title" /></span></span>
      </xsl:if>
      
    </div>
    <xsl:choose>
    
    <xsl:when test="count(LISTITEM) > 0">
        
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
          
          <div class="navbar pagenav">  
            <span class="count">Showing Items <xsl:value-of select="$startrecord" /> to <xsl:value-of select="$maxcount" /> of <xsl:value-of select="@totalResults" /></span>
          </div>
        </xsl:if>
        
        <div id="results">
       
        <div class="downloads listitem name">
          <span class="entry"><span class="column_order">Order Number</span></span>
          <span class="entry"><span class="column_order">Name</span></span>
          <span class="entry"><span class="column_order">Email</span></span>
          <span class="entry"><span class="column_order_date">Date</span></span>
          <!--<span class="entry"><span class="column_order_date">MOne Date</span></span>-->
          <span class="entry"><span class="column_download">Order Total</span></span>
          <span class="entry"><span class="column_status">Order Type</span></span>
          <span class="entry"><span class="column_status">Order Status</span></span>
       </div>
    
        <xsl:for-each select="LISTITEM">
          <div class="listitem listitem_{position() mod 2}">
            <span class="entry"><span class="column_order">
              <a href="/orders/transaction/{user_order_id}"><xsl:value-of select="user_order_id" /></a>
            </span></span>
            <span class="entry"><span class="column_order">
              <xsl:value-of select="user_order_user_fname" /> 
            </span></span>
            <span class="entry"><span class="column_order">
              <xsl:value-of select="user_order_user_lname" />
            </span></span>
            
            <!--
            <span class="entry"><span class="column_order">
              <a href="/orders/detail/{user_order_guid}"><xsl:value-of select="user_order_guid" /></a>
            </span></span>
            -->
            <span class="entry"><span class="column_order_date">
              <xsl:value-of select="user_order_date" />
            </span></span>
            <!--<span class="entry"><span class="column_order_date">
              <xsl:value-of select="mone_date" />
            </span></span>-->
            <span class="entry"><span class="column_download">
              <xsl:choose>
                <xsl:when test="(user_order_vtype = 'Paypal Refund') or (user_order_vtype = 'Paypal WPP Refund') ">
                  <span style="color:orange">$<xsl:value-of select="user_order_total_fs" /></span>
                </xsl:when>
                <xsl:when test="(user_order_vtype = 'Paypal Payment') or (user_order_vtype = 'Paypal WPP Payment') ">
                  <span style="color:green">$<xsl:value-of select="user_order_total_fs" /></span>
                </xsl:when>
                <xsl:otherwise>
                  $<xsl:value-of select="user_order_total_fs" />
                </xsl:otherwise>
              </xsl:choose>
            </span></span>
            <span class="entry"><span class="column_status">
              <xsl:choose>
                <xsl:when test="user_order_vtype = 'I'">
                  Enquiry
                </xsl:when>
                <xsl:when test="(user_order_vtype = 'Paypal Refund') or (user_order_vtype = 'Paypal WPP Refund') ">
                  <span style="color:orange"><xsl:value-of select="user_order_vtype" /></span>
                </xsl:when>
                <xsl:when test="(user_order_vtype = 'Paypal Payment') or (user_order_vtype = 'Paypal WPP Payment') ">
                  <span style="color:green"><xsl:value-of select="user_order_vtype" /></span>
                </xsl:when>
                <xsl:otherwise>
                <xsl:value-of select="user_order_vtype" />
                </xsl:otherwise>
              </xsl:choose>
            </span></span>
            <span class="entry"><span class="column_status">
              <xsl:choose>
                <xsl:when test="user_order_status=3">
                  <span style="color:green">Captured</span>
                </xsl:when>
                <xsl:when test="user_order_status=2">
                  <span style="color:green">Completed</span>
                </xsl:when>
                <xsl:when test="user_order_status=1">
                  <span style="color:orange">Fraud (AVS) Alert</span>
                </xsl:when>
                <xsl:when test="user_order_status=0">
                  <span style="color:magenta">Pending Review</span>
                </xsl:when>
                <xsl:when test="user_order_status=-1">
                  <span class="error">Declined/Cancelled</span>
                </xsl:when>
                <xsl:when test="user_order_status=-2">
                  <span class="error">Declined/Cancelled</span>
                </xsl:when>
                 <xsl:when test="user_order_status=-4">
                  <span class="error">Voice Auth Suggested (<xsl:value-of select="user_order_fraud_score" />)</span>
                </xsl:when>
                 <xsl:when test="user_order_status=-8">
                  <span class="error">Duplicate Capture Error</span>
                </xsl:when>
                <xsl:otherwise>
                  <span class="error">Fraud (<xsl:value-of select="user_order_fraud_score" />) </span>
                </xsl:otherwise>
              </xsl:choose>
              Status Is: <xsl:value-of select="user_order_status" />
            </span></span>
          </div>
        </xsl:for-each>
   
   </div>
    
    </xsl:when>
    <xsl:otherwise>
      <div class="downloads listitem name">
        No Orders
      </div>
    </xsl:otherwise>
    </xsl:choose>
    
    
  </div>
  
</xsl:template>


<xsl:template match="LIST" mode="order_search">
  
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
    <xsl:choose>
    <xsl:when test="count(LISTITEM) > 0">
    
        <div class="downloads listitem name">
          <span class="entry"><span class="column_order_date">Date</span></span>
          <span class="entry"><span class="column_order">Order Number</span></span>
          <span class="entry"><span class="column_download">Order Total</span></span>
          <span class="entry"><span class="column_status">Order Status</span></span>
          <span class="entry"><span class="column_status">Order Process</span></span>
       </div>
       
        <xsl:for-each select="LISTITEM">
          <div class="listitem listitem_{position() mod 2}">
            <span class="entry"><span class="column_order_date">
              <xsl:value-of select="user_order_date" />
            </span></span>
            <span class="entry"><span class="column_order">
              <a href="/orders/detail/{user_order_guid}"><xsl:value-of select="user_order_guid" /></a>
            </span></span>
            <span class="entry"><span class="column_download">
              $<xsl:value-of select="user_order_total_fs" />
            </span></span>
            <span class="entry"><span class="column_status">
              <xsl:choose>
                <xsl:when test="user_order_status=3 and user_order_download=1">
                  <span style="color:green">Captured/Downloaded</span>
                </xsl:when>
                <xsl:when test="user_order_status=3 and user_order_process=1 and user_order_download=0">
                  <span style="color:green">Captured/Shipped</span>
                </xsl:when>
                <xsl:when test="user_order_status=3 and user_order_process=1 and user_order_download=0">
                  <span style="color:green">Captured/Shipped</span>
                </xsl:when>
                <xsl:when test="user_order_status=3 and user_order_process=0 and user_order_download=0">
                  <span style="color:green">Captured/Pending Shipment</span>
                </xsl:when>
                <xsl:when test="user_order_status=2 and user_order_download=1">
                  <span style='color:green'>Approved/Downloaded</span>
                </xsl:when>
                <xsl:when test="user_order_status=2 and user_order_process=1 and user_order_download=0">
                  <span style='color:green'>Approved/Shipped</span>
                </xsl:when>
                <xsl:when test="user_order_status=2 and user_order_process=0 and user_order_download=0">
                  <span style='color:orange'>Approved/Pending Shipment</span>
                </xsl:when>
                <xsl:when test="user_order_status=1">
                  <span style="color:orange">Fraud Alert</span>
                </xsl:when>
                <xsl:when test="user_order_status=0">
                  <span style="color:magenta">On Hold</span>
                </xsl:when>
                <xsl:when test="user_order_status=-1">
                  <span class="error">Declined</span>
                </xsl:when>
                <xsl:when test="user_order_status=-2">
                  <span class="error">User Data Error</span>
                </xsl:when>
                <xsl:when test="user_order_status=-3">
                  <span class="error">Fraud Error</span>
                </xsl:when>
                <xsl:when test="user_order_status=-4">
                  <span class="error">Voice Auth Suggested (<xsl:value-of select="user_order_fraud_score" />)</span>
                </xsl:when>
                <xsl:when test="user_order_status=-7">
                  <span class="#cccccc">Cancelled</span>
                </xsl:when>
                <xsl:otherwise>
                  <span class="error">Fraud (<xsl:value-of select="user_order_fraud_score" />) </span>
                </xsl:otherwise>
              </xsl:choose>
            </span></span>
            <span class="entry"><span class="column_status">
              <xsl:choose>
                <xsl:when test="user_order_download=0 and user_order_process=1">
                  <span style='color:green'>Shipped</span>
                </xsl:when>
                <xsl:when test="user_order_download=0 and user_order_process=0">
                  <span style='color:green'>Pending Shipment</span>
                </xsl:when>
                <xsl:when test="user_order_download=1 and user_order_status=3">
                  <span style='color:red'>Downloaded</span>
                </xsl:when>
                <xsl:when test="user_order_download=1 and user_order_status=2">
                  <span style='color:red'>Downloaded</span>
                </xsl:when>
                <xsl:when test="user_order_download=1 and user_order_status=1">
                  <span style='color:red'>Not Downloaded</span>
                </xsl:when>
                <xsl:when test="user_order_download=1 and user_order_status=0">
                  <span style='color:red'>Not Downloaded</span>
                </xsl:when>
                <xsl:when test="user_order_status=-7">
                  <span style='color:cyan'>Voided/Cancelled</span>
                </xsl:when>
                <xsl:otherwise>
                  In Process
                </xsl:otherwise>
              </xsl:choose>
            </span></span>
          </div>
        </xsl:for-each>
    </xsl:when>
    <xsl:otherwise>
      <div class="downloads listitem name">
        No Orders
      </div>
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
          <xsl:with-param name="SITEMS" select="SORTITEMS" />
        </xsl:call-template>
        
        <span class="count">Showing Items <xsl:value-of select="$startrecord" /> to <xsl:value-of select="$maxcount" /> of <xsl:value-of select="@totalResults" /></span>
      </xsl:if>
      
  </div>
  
</xsl:template>

</xsl:stylesheet>
