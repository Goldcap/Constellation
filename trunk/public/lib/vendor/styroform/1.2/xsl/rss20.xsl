<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet version="1.1" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

	<xsl:output method="html" /> 		 
	<xsl:variable name="title" select="/rss/channel/title"/>	
			
	<xsl:template match="/">
		<xsl:apply-templates select="rss/channel"/>				
	</xsl:template>

	<xsl:template match="channel">
		<body>			
			<xsl:if test="contains($title, 'BBC Radio 1')">
				<a href="{link}">
					<img src="{image/url}" width="120" height="60" alt="BBC Radio 1" border="0" vspace="10" />
				</a>
				<br />
	    </xsl:if>
			
			<div class="topbox">					
				
				<div class="mainbox">
					<div class="itembox">
						<div class="paditembox">
							<xsl:apply-templates select="item"/>
						</div>
					</div>
				</div>
							
			</div>
		</body>
	</xsl:template>
	
	<xsl:template match="item">
		<div id="item">
			<a href="{link}" class="item"><xsl:value-of select="title"/></a>
			<br/>
			<xsl:value-of select="description" />
		</div>			
	</xsl:template>
</xsl:stylesheet>
