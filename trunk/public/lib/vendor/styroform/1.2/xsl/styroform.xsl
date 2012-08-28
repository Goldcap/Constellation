<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:template match="AFORM">
  
  <div id="{FORMNAME}_wrapper" class="{WRAPPERCLASS} styroform">
  
  <xsl:variable name="theprotocol">
    <xsl:choose>
      <xsl:when test="FORMACTION/@secure = 'true'">https://<xsl:value-of select="//@hostname" /></xsl:when>
      <xsl:otherwise></xsl:otherwise>
    </xsl:choose>
  </xsl:variable>
  <xsl:variable name="theurl">
    <xsl:choose>
      <xsl:when test="FORMACTION != '$self'"><xsl:value-of select="FORMACTION" /></xsl:when>
      <xsl:otherwise><xsl:value-of select="//@script" /></xsl:otherwise>
    </xsl:choose>
  </xsl:variable>
  <xsl:variable name="theqs">
    <xsl:choose>
      <xsl:when test="(FORMACTION/@raw = 'true') or (FORMACTION = '$self')"></xsl:when>
      <xsl:otherwise>&amp;ts=<xsl:value-of select="//@timestamp" /></xsl:otherwise>
    </xsl:choose>
  </xsl:variable>
  
  <xsl:element name="form">
    <xsl:choose>
      <xsl:when test="FORMMETHOD != ''">
      	<xsl:attribute name="method"><xsl:value-of select="FORMMETHOD" /></xsl:attribute>
      </xsl:when>
      <xsl:otherwise>
        <xsl:attribute name="method">post</xsl:attribute>
      </xsl:otherwise>
    </xsl:choose>
    <xsl:attribute name="name">
      <xsl:value-of select="FORMNAME" />
    </xsl:attribute>
    <xsl:attribute name="id">
      <xsl:value-of select="FORMNAME" />
    </xsl:attribute>
    <xsl:attribute name="action">
      <xsl:value-of select="$theprotocol" /><xsl:value-of select="$theurl" /><xsl:value-of select="$theqs" />
    </xsl:attribute>
    <xsl:choose>
      <xsl:when test="FORMACTION/@noenc='true'"></xsl:when>
      <xsl:otherwise><xsl:attribute name="enctype">multipart/form-data</xsl:attribute></xsl:otherwise>
    </xsl:choose>
    <xsl:choose>
      <xsl:when test="FORMACTION/@onSubmit != ''">
      	<xsl:attribute name="onsubmit"><xsl:value-of select="FORMACTION/@onSubmit" /></xsl:attribute>
      </xsl:when>
      <xsl:when test="JSVALIDATE = 'TRUE'">
      	<xsl:attribute name="onsubmit">return checkForm(thisValidator,"<xsl:value-of select="FORMNAME" />")</xsl:attribute>
      </xsl:when>
      <xsl:otherwise>
      </xsl:otherwise>
    </xsl:choose>
    <xsl:attribute name="class">
      <xsl:value-of select="FORMCLASS" />
    </xsl:attribute>
    
    <!-- DOJO TABS -->
    <xsl:apply-templates select="FORMELEMENTGROUP[@tabheader='true']" />
    <!-- JQUERY TABS -->
    <xsl:apply-templates select="FORMELEMENTGROUP[@jquery_tabheader='true']" />
    
    <xsl:choose>
       <xsl:when test="count(FORMELEMENTGROUP[@tab != '']) > 0">
  		  <div id="TabContainer" dojoType="dijit.layout.TabContainer">
          <xsl:apply-templates select="FORMELEMENTGROUP[not(@tabheader) and not(@tabfooter)]" />
        </div>
       </xsl:when>
       <xsl:when test="count(FORMELEMENTGROUP[@jquery_tab != '']) > 0">
        <script>
      	$(document).ready(function() {
      		$( "#TabContainer" ).tabs();
      	});
      	</script>
  		  <div id="TabContainer">
          <ul>
            <xsl:apply-templates select="FORMELEMENTGROUP[@jquery_tab != '']" mode="jquery_tabs" />
          </ul>
          <xsl:apply-templates select="FORMELEMENTGROUP[not(@jquery_tabheader) and not(@jquery_tabfooter)]" />
        </div>
       </xsl:when>
  	   <xsl:otherwise>
  	     <xsl:apply-templates select="FORMELEMENTGROUP[not(@tabheader) and not(@tabfooter)]" />
  	   </xsl:otherwise>
	  </xsl:choose>
	  
	  <!-- DOJO TABS -->
	  <xsl:apply-templates select="FORMELEMENTGROUP[@tabfooter='true']" />
	  <!-- JQUERY TABS -->
    <xsl:apply-templates select="FORMELEMENTGROUP[@jquery_tabfooter='true']" />
	  
	<input type="hidden" name="showerrors" value="{//SHOWERRORS}" />
	
  </xsl:element>
  </div>
</xsl:template>

</xsl:stylesheet>
