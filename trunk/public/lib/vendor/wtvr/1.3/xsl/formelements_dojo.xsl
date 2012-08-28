<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">

<xsl:template name="DOJOCOMBOINIT">
  <script type="text/javascript">
    dojo.require("dojo.widget.ComboBox");
    
    function initComboBox() {
      <xsl:for-each select="//FORMELEMENT[TYPE='dojo' and TYPE/@species='comboBox' and DEFAULT!='']">
      dojo.widget.byId('<xsl:value-of select="NAME" />').textInputNode.value = '<xsl:value-of select="DEFAULT" />';
      dojo.widget.byId('<xsl:value-of select="NAME" />').setSelectedValue(<xsl:value-of select="DEFAULT/@selected" />);
      </xsl:for-each>
    }

    dojo.addOnLoad( initComboBox );

  </script>
</xsl:template>

<xsl:template name="DOJOSELECTINIT">
  <script type="text/javascript">
    function initSelectBox() {
      <xsl:for-each select="//FORMELEMENT[TYPE='dojo'][TYPE/@species='select']">
      dojo.widget.byId('<xsl:value-of select="NAME" />').textInputNode.value = '<xsl:value-of select="DEFAULT" />';
      dojo.widget.byId('<xsl:value-of select="NAME" />').setSelectedValue(<xsl:value-of select="DEFAULT/@selected" />);
      </xsl:for-each>
    }

    dojo.addOnLoad( initSelectBox );

  </script>
</xsl:template>

<xsl:template match="FORMELEMENT[TYPE='dojo'][TYPE/@species='comboBox']">

  <span class="{DISPLAYCLASS}">
	<xsl:apply-templates select="REQUIRED" />
	<xsl:apply-templates select="DISPLAY" />
	
  <xsl:variable name="THECLASS">
		<xsl:call-template name="SETCLASS"/>
	</xsl:variable>
	
  <xsl:variable name="DataURL"><xsl:value-of select="DEFAULT/@dataUrl" /></xsl:variable>
	<select id="{NAME}" dojoType="ComboBox" style="{CLASS}" name="{NAME}" dataUrl="{$DataURL}" maxListLength="{SIZE}" onValueChanged="{DEFAULT/@onchange}"></select>
	
	<xsl:call-template name="REQS" />
	
	<xsl:if test="//JSVALIDATE != 'FALSE'">
		<xsl:if test="VTIP != 'omit'">
			<span id="vtip{NAME}_selected" class="reqs"><xsl:value-of select="VTIP" /></span>
			<span id="vreq{NAME}_selected" class="reqs"><xsl:value-of select="REQUIRED" /></span>
			<span id="error{NAME}_selected" class="errorhidden"></span>
		</xsl:if>
	</xsl:if>
	
	</span>
	
</xsl:template>

<xsl:template match="FORMELEMENT[TYPE='dojo'][TYPE/@species='select']">

  <span class="{DISPLAYCLASS}">
	<xsl:apply-templates select="REQUIRED" />
	<xsl:apply-templates select="DISPLAY" />
	
  <xsl:variable name="THECLASS">
		<xsl:call-template name="SETCLASS"/>
	</xsl:variable>
	
  <xsl:variable name="DataURL"><xsl:value-of select="DEFAULT/@dataUrl" /></xsl:variable>
	<SELECT id="{NAME}" dojoType="select" style="{CLASS}" name="{NAME}" dataUrl="{$DataURL}" autocomplete="true" forceValidOption="true" maxListLength="{SIZE}" onValueChanged="{DEFAULT/@onchange}" />
	
	<xsl:call-template name="REQS" />
	
	<xsl:if test="//JSVALIDATE != 'FALSE'">
		<xsl:if test="VTIP != 'omit'">
			<span id="vtip{NAME}_selected" class="reqs"><xsl:value-of select="VTIP" /></span>
			<span id="vreq{NAME}_selected" class="reqs"><xsl:value-of select="REQUIRED" /></span>
			<span id="error{NAME}_selected" class="errorhidden"></span>
		</xsl:if>
	</xsl:if>
	
	</span>
	
</xsl:template>

<xsl:template match="FORMELEMENT[TYPE='dojo'][TYPE/@species='contentPane']">
  <div dojoType="{TYPE/@species}" id="{NAME}_{TYPE/@species}" executeScripts="true" scriptScope="true" hasShadow="true" displayMinimizeAction="true" resizable="{DISPLAY/@resizable}" toggle="{DEFAULT/@toggle}" toggleDuration="{DEFAULT/@toggleDuration}" class="{NAME}_{TYPE/@species}" refreshOnShow="true" style="display: {DISPLAY};" href="{DEFAULT}" onDownloadStart="{VTIP}" onDownloadEnd="{REQUIRED}"></div>
</xsl:template>

<xsl:template match="FORMELEMENT[TYPE='dojo'][TYPE/@species='toolTip']">
  <xsl:choose>
    <xsl:when test="DISPLAY/@href != ''">
      <span dojoType="tooltip" connectId="{NAME}" href="{DISPLAY/@href}" toggle="explode" toggleDuration="100" class="{CLASS}"></span>
    </xsl:when>
    <xsl:otherwise>
      <span dojoType="tooltip" connectId="{NAME}" caption="{DISPLAY}" toggle="explode" toggleDuration="100" class="{CLASS}"></span>
    </xsl:otherwise>
  </xsl:choose>
</xsl:template>

<xsl:template match="FORMELEMENT[TYPE='dojo'][TYPE/@species='Editor2']">
  <xsl:variable name="style"><xsl:choose><xsl:when test="CLASS/@style != ''"><xsl:value-of select="CLASS/@style" /></xsl:when><xsl:otherwise>border: 1px solid #dfdfdf; height: 17em;</xsl:otherwise></xsl:choose></xsl:variable>
	<xsl:variable name="height"><xsl:choose><xsl:when test="CLASS/@height != ''"><xsl:value-of select="CLASS/@height" /></xsl:when><xsl:otherwise>15em</xsl:otherwise></xsl:choose></xsl:variable>
	<xsl:choose>
	  <xsl:when test="//@browsername != 'Safari'">
    	<span class="{DISPLAYCLASS}">
    	<xsl:apply-templates select="REQUIRED" />
    	<xsl:apply-templates select="DISPLAY" />
	    
      <div dojoType="{TYPE/@species}" name="editor2dojo{NAME}" id="editor2dojo{NAME}" class="{CLASS}" height="{$height}" style="{$style}" toolbarTemplatePath="/js/dojo/src/widget/templates/GoodcircleToolbarOneline.html"><xsl:value-of select="DEFAULT" /></div>
  		<textarea name="editor2{NAME}" id="editor2{NAME}" class="reqs"></textarea>
  		<input type="hidden" name="{NAME}" id="{NAME}" class="reqs" value="" />
      <xsl:if test="//JSVALIDATE != 'FALSE'">
    		<xsl:if test="VTIP != 'omit'">
    			<span id="vtipeditor2{NAME}" class="reqs"><xsl:value-of select="VTIP" /></span>
    			<span id="vreqeditor2{NAME}" class="reqs"><xsl:value-of select="REQUIRED" /></span>
    			<span id="erroreditor2{NAME}" class="errorhidden"></span>
    			<span id="vtip{NAME}" class="reqs">notEmpty</span>
    			<span id="vreq{NAME}" class="reqs">FALSE</span>
    			<span id="error{NAME}" class="errorhidden"></span>
    		</xsl:if>
    	 </xsl:if>
	     </span>
  	</xsl:when>
  	<xsl:otherwise>
  	 <span class="{DISPLAYCLASS}">
    	<xsl:apply-templates select="REQUIRED" />
    	<xsl:apply-templates select="DISPLAY" />
    		<textarea name="{NAME}" class="{CLASS}" id="{NAME}" title="{TITLE}"><xsl:value-of select="DEFAULT" /></textarea>
    		<xsl:call-template name="REQS" />
    	</span>
  	</xsl:otherwise>
  	</xsl:choose>
</xsl:template>

</xsl:stylesheet>
