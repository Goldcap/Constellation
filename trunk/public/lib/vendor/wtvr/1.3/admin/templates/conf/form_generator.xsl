<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="xml" indent="yes" omit-xml-declaration="no" standalone="yes" />

<xsl:template match="table">
  <widget name="{@name}" baseurl="/modules/{@name}/">
  	<!--<attributes>
      <assets>
        <CSSCRIPT></CSSCRIPT>
    		<JSCRIPT></JSCRIPT>
  	  </assets>
    </attributes>-->
  	<content>
      <AFORM>
        <FORMNAME><xsl:value-of select="@name" /></FORMNAME>
        <FORMACTION>$self</FORMACTION>
        <JSVALIDATE>TRUE</JSVALIDATE>
        <FORMCLASS/>
        <SERVERVALIDATE>TRUE</SERVERVALIDATE>
        <PASSTHROUGHVALUES>TRUE</PASSTHROUGHVALUES>
        <QUERYVALUES>TRUE</QUERYVALUES>
        <SHOWERRORS>FALSE</SHOWERRORS>
        <REQS>FALSE</REQS>
        <xsl:call-template name="formtitle" />
        <xsl:call-template name="columnlist">
          <xsl:with-param name="columns" select="column" />
        </xsl:call-template>
        <xsl:call-template name="formfooter" />
      </AFORM>
    </content>
  </widget>
</xsl:template>

<xsl:template name="formtitle">
  <FORMELEMENTGROUP>
    <FORMELEMENT>
      <NAME/>
      <DISPLAY>Title Copy Goes Here</DISPLAY>
      <TYPE>display</TYPE>
      <DEFAULT/>
      <CLASS/>
      <VTIP/>
      <REQUIRED/>
      <DISPLAYCLASS>formsubhead</DISPLAYCLASS>
      <SIZE/>
    </FORMELEMENT>
  </FORMELEMENTGROUP>
  <FORMELEMENTGROUP>
      <FORMELEMENT>
        <NAME>formhead</NAME>
        <DISPLAY>Form Name</DISPLAY>
        <TYPE>display</TYPE>
        <DEFAULT/>
        <CLASS/>
        <VTIP/>
        <REQUIRED/>
        <DISPLAYCLASS>headline</DISPLAYCLASS>
        <SIZE/>
      </FORMELEMENT>
      <FORMELEMENT>
        <NAME>requirenotice</NAME>
        <DISPLAY>* required fields</DISPLAY>
        <TYPE>display</TYPE>
        <DEFAULT/>
        <CLASS/>
        <VTIP/>
        <REQUIRED/>
        <DISPLAYCLASS>reqmessage</DISPLAYCLASS>
        <SIZE/>
      </FORMELEMENT>
    </FORMELEMENTGROUP>
    <FORMELEMENTGROUP>
      <FORMELEMENT>
        <NAME>errornotice</NAME>
        <DISPLAY/>
        <TYPE>errornotice</TYPE>
        <DEFAULT>Please complete fields outlined in red.</DEFAULT>
        <CLASS>errornotice</CLASS>
        <VTIP/>
        <REQUIRED/>
        <DISPLAYCLASS>col2</DISPLAYCLASS>
        <SIZE/>
      </FORMELEMENT>
    </FORMELEMENTGROUP>
    <FORMELEMENTGROUP>
      <FORMELEMENT>
        <NAME/>
        <DISPLAY/>
        <TYPE>hr</TYPE>
        <DEFAULT/>
        <CLASS/>
        <VTIP/>
        <REQUIRED/>
        <DISPLAYCLASS>linebreak</DISPLAYCLASS>
        <SIZE/>
      </FORMELEMENT>
    </FORMELEMENTGROUP>
</xsl:template>

<xsl:template name="formfooter">
  <FORMELEMENTGROUP>
    <FORMELEMENT>
      <NAME>submit</NAME>
      <DISPLAY/>
      <TYPE>submitimage</TYPE>
      <DEFAULT>/images/submit.gif</DEFAULT>
      <CLASS>submitter</CLASS>
      <VTIP/>
      <REQUIRED>FALSE</REQUIRED>
      <DISPLAYCLASS/>
      <SIZE/>
    </FORMELEMENT>
    <FORMELEMENT>
      <NAME>delete</NAME>
      <DISPLAY/>
      <TYPE>submitimage</TYPE>
      <DEFAULT>/images/delete.gif</DEFAULT>
      <CLASS>submitter</CLASS>
      <VTIP/>
      <REQUIRED>FALSE</REQUIRED>
      <DISPLAYCLASS/>
      <SIZE/>
    </FORMELEMENT>
  </FORMELEMENTGROUP>
</xsl:template>

<xsl:template name="columnlist">
  <xsl:param name="columns"></xsl:param>
  
  <xsl:for-each select="$columns">
    <xsl:sort select="vendor/parameter[@name='Key']/@value"/>
    <xsl:variable name="aname"><xsl:value-of select="@name" /></xsl:variable>
    <xsl:variable name="adisplay"></xsl:variable>
    <xsl:variable name="atype">
      <xsl:choose>
        <xsl:when test="(@type = 'INTEGER') and (vendor/parameter[@name='Key']/@value = 'PRI')">hidden</xsl:when>
        <xsl:when test="(@type = 'INTEGER') and (count(//foreign-key/reference[@local=$aname]) = 1)">selectdb</xsl:when>
        <xsl:when test="(@type = 'VARCHAR')">text</xsl:when>
        <xsl:otherwise>text</xsl:otherwise>
      </xsl:choose>
    </xsl:variable>
    <xsl:variable name="ftable">
      <xsl:choose>
        <xsl:when test="(@type = 'INTEGER') and (count(//foreign-key/reference[@local=$aname]) = 1)"><xsl:value-of select="//foreign-key[reference/@local=$aname]/@foreignTable" /></xsl:when>
        <xsl:otherwise></xsl:otherwise>
      </xsl:choose>
    </xsl:variable>
    <xsl:variable name="adefault">
      <xsl:choose>
        <xsl:when test="(@type = 'INTEGER') and (count(//foreign-key/reference[@local=$aname]) = 1)">Select One</xsl:when>
        <xsl:otherwise></xsl:otherwise>
      </xsl:choose>
    </xsl:variable>
    <xsl:variable name="aclass">input</xsl:variable>
    <xsl:variable name="avtip">
      <xsl:choose>
        <xsl:when test="(@type = 'INTEGER') and (count(//foreign-key/reference[@local=$aname]) = 1)">0</xsl:when>
        <xsl:otherwise><xsl:value-of select="@type" /></xsl:otherwise>
      </xsl:choose>
    </xsl:variable>
    <xsl:variable name="areq">
      <xsl:choose>
        <xsl:when test="vendor/parameter[@name='Key']/@Null = 'YES'">FALSE</xsl:when>
        <xsl:otherwise>TRUE</xsl:otherwise>
      </xsl:choose>
    </xsl:variable>
    <xsl:variable name="adisplayclass">
      <xsl:choose>
        <xsl:when test="vendor/parameter[@name='Key']/@Null = 'YES'">FALSE</xsl:when>
        <xsl:otherwise>col2</xsl:otherwise>
      </xsl:choose>
    </xsl:variable>
    <xsl:variable name="asize"><xsl:value-of select="@size" /></xsl:variable>
    
    <xsl:call-template name="column">
      <xsl:with-param name="thename" select="$aname" />
      <xsl:with-param name="thedisplay" select="$adisplay" />
      <xsl:with-param name="thetype" select="$atype" />
      <xsl:with-param name="thetable" select="$ftable" />
      <xsl:with-param name="thedefault" select="$adefault" />
      <xsl:with-param name="theclass" select="$aclass" />
      <xsl:with-param name="thevtip" select="$avtip" />
      <xsl:with-param name="thereq" select="$areq" />
      <xsl:with-param name="thedisplayclass" select="$adisplayclass" />
      <xsl:with-param name="thesize" select="$asize" />
    </xsl:call-template>
  </xsl:for-each>
</xsl:template>

<xsl:template name="column">
  <xsl:param name="thename"></xsl:param>
  <xsl:param name="thedisplay"></xsl:param>
  <xsl:param name="thetype"></xsl:param>
  <xsl:param name="thetable"></xsl:param>
  <xsl:param name="thedefault"></xsl:param>
  <xsl:param name="theclass"></xsl:param>
  <xsl:param name="thevtip"></xsl:param>
  <xsl:param name="thereq"></xsl:param>
  <xsl:param name="thedisplayclass"></xsl:param>
  <xsl:param name="thesize"></xsl:param>
  
  <xsl:if test="$thetype = 'hidden'">
  <FORMELEMENTGROUP>
    <FORMELEMENT>
      <NAME/>
      <DISPLAY/>
      <TYPE>hr</TYPE>
      <DEFAULT/>
      <CLASS/>
      <VTIP/>
      <REQUIRED/>
      <DISPLAYCLASS>linebreak_float</DISPLAYCLASS>
      <SIZE/>
    </FORMELEMENT>
  </FORMELEMENTGROUP>
  </xsl:if>
  <FORMELEMENTGROUP>
    <xsl:if test="$thetype != 'hidden'">
    <FORMELEMENT>
      <NAME/>
      <DISPLAY><xsl:call-template name="initTitle"><xsl:with-param name="str" select="$thename" /></xsl:call-template></DISPLAY>
      <TYPE>display</TYPE>
      <DEFAULT/>
      <CLASS/>
      <VTIP/>
      <REQUIRED/>
      <DISPLAYCLASS>col1</DISPLAYCLASS>
      <SIZE/>
    </FORMELEMENT>
    </xsl:if>
    <FORMELEMENT>
      <NAME><xsl:value-of select="$thename" /></NAME>
      <DISPLAY><xsl:value-of select="$thedisplay" /></DISPLAY>
      <TYPE><xsl:value-of select="$thetype" /></TYPE>
      <xsl:choose>
        <xsl:when test="$thetype = 'selectdb'"><DEFAULT table="{$thetable}"><xsl:value-of select="$thedefault" /></DEFAULT></xsl:when>
        <xsl:otherwise><DEFAULT><xsl:value-of select="$thedefault" /></DEFAULT></xsl:otherwise>
      </xsl:choose>
      <CLASS><xsl:value-of select="$theclass" /></CLASS>
      <VTIP><xsl:value-of select="$thevtip" /></VTIP>
      <REQUIRED><xsl:value-of select="$thereq" /></REQUIRED>
      <DISPLAYCLASS><xsl:value-of select="$thedisplayclass" /></DISPLAYCLASS>
      <SIZE><xsl:value-of select="$thesize" /></SIZE>
    </FORMELEMENT>
    <xsl:if test="$thetype != 'hidden'">
    <FORMELEMENT>
      <NAME><xsl:value-of select="$thename" />_help</NAME>
      <DISPLAY><xsl:value-of select="$thename" /> is entered here.</DISPLAY>
      <TYPE species="toolTip">jquery_tooltip</TYPE>
      <DEFAULT>/images/Neu/16x16/status/dialog-question.png</DEFAULT>
      <CLASS>icon16</CLASS>
      <VTIP></VTIP>
      <REQUIRED></REQUIRED>
      <DISPLAYCLASS>col2c</DISPLAYCLASS>
      <SIZE></SIZE>
    </FORMELEMENT>
    </xsl:if>
  </FORMELEMENTGROUP>
</xsl:template>

<xsl:include href="initcap.xsl" />
<xsl:include href="deadpedal_attributes.xsl" />

</xsl:stylesheet>
