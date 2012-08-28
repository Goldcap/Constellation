<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="text" indent="no" omit-xml-declaration="yes" standalone="yes" />

<xsl:template match="ROOT">
  <xsl:text disable-output-escaping="yes">&lt;&#63;php</xsl:text>//##
  
  <xsl:apply-templates select="table" />
  
  <xsl:text disable-output-escaping="yes">&#63;&gt;</xsl:text>
</xsl:template>

<xsl:template match="table">
   //##
   class <xsl:call-template name="initCap"><xsl:with-param name="str" select="//table/@name" /></xsl:call-template>CrudBase extends Utils_PageWidget { <xsl:apply-templates select="pages" />//##
   //## 
   var <xsl:text disable-output-escaping="yes">&#36;</xsl:text><xsl:call-template name="initCap"><xsl:with-param name="str" select="//table/@name" /></xsl:call-template>;//##
   //## 
   <xsl:call-template name="properties" />//##
   //##
   <xsl:call-template name="constructor" />
   <xsl:call-template name="hydrator" />
   <xsl:call-template name="propertyloads" />//##
   <xsl:call-template name="get" />
   <xsl:call-template name="set" />
   <xsl:call-template name="save" />
   <xsl:call-template name="delete" />
   <xsl:call-template name="populate" />
   <xsl:call-template name="checkUnique" />
   <xsl:call-template name="destructor" />
   }
</xsl:template>

<xsl:template name="constructor">
function __construct( <xsl:text disable-output-escaping="yes">&#36;</xsl:text>context, <xsl:text disable-output-escaping="yes">&#36;</xsl:text>id ) {//##
    parent::__construct( <xsl:text disable-output-escaping="yes">&#36;</xsl:text>context );//##
    //If no ID, try to match from session or get/post Scope//##
    if (! is_numeric(<xsl:text disable-output-escaping="yes">&#36;</xsl:text>id)) {//##
      <xsl:text disable-output-escaping="yes">&#36;</xsl:text>id = <xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> get<xsl:call-template name="initCap"><xsl:with-param name="str" select="//column[@primaryKey='true']/@name" /></xsl:call-template>();//##
    }//##
    //##
    if ((<xsl:text disable-output-escaping="yes">&#36;</xsl:text>id) <xsl:text disable-output-escaping="yes">&#38;</xsl:text><xsl:text disable-output-escaping="yes">&#38;</xsl:text> (<xsl:text disable-output-escaping="yes">&#36;</xsl:text>id != 0)) {//##
      <xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text><xsl:call-template name="initCap"><xsl:with-param name="str" select="//table/@name" /></xsl:call-template> = <xsl:call-template name="initCap"><xsl:with-param name="str" select="//table/@name" /></xsl:call-template>Peer::retrieveByPK( <xsl:text disable-output-escaping="yes">&#36;</xsl:text>id );//##
    } else {//##
      <xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> <xsl:call-template name="initCap"><xsl:with-param name="str" select="//table/@name" /></xsl:call-template> = new <xsl:call-template name="initCap"><xsl:with-param name="str" select="//table/@name" /></xsl:call-template>;//##
    }//##
  }//##
  //##
</xsl:template>

<xsl:template name="hydrator">
  function hydrate( <xsl:text disable-output-escaping="yes">&#36;</xsl:text>id ) {//##
      <xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text><xsl:call-template name="initCap"><xsl:with-param name="str" select="//table/@name" /></xsl:call-template> = <xsl:call-template name="initCap"><xsl:with-param name="str" select="//table/@name" /></xsl:call-template>Peer::retrieveByPK( <xsl:text disable-output-escaping="yes">&#36;</xsl:text>id );//##
  }//##
  //##
</xsl:template>

<xsl:template name="properties">
  	 <xsl:for-each select="//column">
      <xsl:call-template name="property"><xsl:with-param name="columnname" select="@name" /></xsl:call-template>
     </xsl:for-each>  
</xsl:template>

<xsl:template name="propertyloads">
  	 <xsl:for-each select="//column">
      <xsl:call-template name="propertyload">
        <xsl:with-param name="colname" select="@name" />
        <xsl:with-param name="itemname" select="//table/@name" />
      </xsl:call-template>
     </xsl:for-each>  
</xsl:template>

<xsl:template name="get">
  <xsl:variable name="itemname"><xsl:call-template name="initCap"><xsl:with-param name="str" select="//table/@name" /></xsl:call-template></xsl:variable>
  function read( <xsl:text disable-output-escaping="yes">&#36;</xsl:text>id = false ) {//##
    <xsl:text disable-output-escaping="yes">&#36;</xsl:text>itemarray = array();//##
    //##
    if (<xsl:text disable-output-escaping="yes">&#36;</xsl:text>id) {//##
    <xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text><xsl:value-of select="$itemname" /> = <xsl:call-template name="initCap"><xsl:with-param name="str" select="//table/@name" /></xsl:call-template>Peer::retrieveByPK( <xsl:text disable-output-escaping="yes">&#36;</xsl:text>id );//##
    }//##
    //##
    if (<xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> <xsl:value-of select="$itemname" /> ) {//##
       //##
    	 <xsl:for-each select="//column">
        <xsl:call-template name="columnget"><xsl:with-param name="columnname" select="@name" /><xsl:with-param name="itemname" select="$itemname" /></xsl:call-template>
       </xsl:for-each>  
      //##
      return <xsl:text disable-output-escaping="yes">&#36;</xsl:text>itemarray;//##
      }//##
      return false;//##
    }//##
  //##
</xsl:template>

<xsl:template name="set">
  <xsl:variable name="itemname"><xsl:call-template name="initCap"><xsl:with-param name="str" select="//table/@name" /></xsl:call-template></xsl:variable>
  function write( <xsl:text disable-output-escaping="yes">&#36;</xsl:text>id = false ) {//##
    if (<xsl:text disable-output-escaping="yes">&#36;</xsl:text>id) {//##
      <xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> <xsl:value-of select="$itemname" /> = <xsl:call-template name="initCap"><xsl:with-param name="str" select="//table/@name" /></xsl:call-template>Peer::retrieveByPK( <xsl:text disable-output-escaping="yes">&#36;</xsl:text>id );//##
    } elseif (! <xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text><xsl:value-of select="$itemname" />) {//##
      <xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> <xsl:value-of select="$itemname" /> = new <xsl:call-template name="initCap"><xsl:with-param name="str" select="//table/@name" /></xsl:call-template>;//##
    }//##
    
    //##
  	 <xsl:for-each select="//column">
      <xsl:call-template name="columnset"><xsl:with-param name="columnname" select="@name" /><xsl:with-param name="itemname" select="$itemname" /></xsl:call-template>
     </xsl:for-each>  
    //##
    <xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> save();//##   
  //##
  }//##
  //##
</xsl:template>

<xsl:template name="delete">
  <xsl:variable name="itemname"><xsl:call-template name="initCap"><xsl:with-param name="str" select="//table/@name" /></xsl:call-template></xsl:variable>
  <xsl:variable name="colprop"><xsl:call-template name="initCap"><xsl:with-param name="str" select="$itemname" /></xsl:call-template></xsl:variable>
  function remove( <xsl:text disable-output-escaping="yes">&#36;</xsl:text>id = false) {//##
    //##
    if (<xsl:text disable-output-escaping="yes">&#36;</xsl:text>id) {//##
      <xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> <xsl:value-of select="$itemname" /> = <xsl:value-of select="$colprop" />Peer::retrieveByPK(<xsl:text disable-output-escaping="yes">&#36;</xsl:text>id);//##
    }//##
    //##
    if (! <xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> <xsl:value-of select="$itemname" /> ) {//##
      return;//##
    }//##
    //##
    <xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> <xsl:value-of select="$itemname" /> -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> delete();//##
    //##
    //##
    if (<xsl:call-template name="variable"><xsl:with-param name="type" select="'session'" /><xsl:with-param name="name" select="$itemname+'_id'" /></xsl:call-template>){//##
       <xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text>context -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> getUser()-<xsl:text disable-output-escaping="yes">&gt;</xsl:text>getAttributeHolder()-<xsl:text disable-output-escaping="yes">&gt;</xsl:text>remove('<xsl:value-of select="$itemname" />_id');//##
    }//##
    //##
    return true;//##
    //##
  }//##
  //##
</xsl:template>

<xsl:template name="save">
  <xsl:variable name="itemname"><xsl:call-template name="initCap"><xsl:with-param name="str" select="//table/@name" /></xsl:call-template></xsl:variable>
  function save() {//##
    <xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> <xsl:value-of select="$itemname" /> -<xsl:text disable-output-escaping="yes">&gt;</xsl:text>save();//## 
  }//##
  //##
</xsl:template>

<xsl:template name="destructor">
  function __destruct() {//##
  //##
  }//##
  //##
</xsl:template>

<xsl:template name="property">
  <xsl:param name="columnname">empty</xsl:param>
   var <xsl:text disable-output-escaping="yes">&#36;</xsl:text><xsl:value-of select="$columnname" />;//##
</xsl:template>

<xsl:template name="variable">
  <xsl:param name="type">get</xsl:param>
  <xsl:param name="name">empty</xsl:param>
  <xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> <xsl:value-of select="$type" />Var("<xsl:value-of select="$name" />")
</xsl:template>

<xsl:template name="propertyload">
  <xsl:param name="colname">empty</xsl:param>
  <xsl:param name="itemname">empty</xsl:param>
  <xsl:variable name="colprop"><xsl:call-template name="initCap"><xsl:with-param name="str" select="$colname" /></xsl:call-template></xsl:variable>
  function get<xsl:value-of select="$colprop" />() {//##
    if ((<xsl:call-template name="variable"><xsl:with-param name="type" select="'post'" /><xsl:with-param name="name" select="$colname" /></xsl:call-template>) || (<xsl:call-template name="variable"><xsl:with-param name="type" select="'post'" /><xsl:with-param name="name" select="$colname" /></xsl:call-template> === "")) {//##
      return <xsl:call-template name="variable"><xsl:with-param name="type" select="'post'" /><xsl:with-param name="name" select="$colname" /></xsl:call-template>;//##
    } elseif ((<xsl:call-template name="variable"><xsl:with-param name="type" select="'get'" /><xsl:with-param name="name" select="$colname" /></xsl:call-template>) || (<xsl:call-template name="variable"><xsl:with-param name="type" select="'get'" /><xsl:with-param name="name" select="$colname" /></xsl:call-template> === "")) {//##
      return <xsl:call-template name="variable"><xsl:with-param name="type" select="'get'" /><xsl:with-param name="name" select="$colname" /></xsl:call-template>;//##
    } elseif ((<xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> <xsl:call-template name="initCap"><xsl:with-param name="str" select="//table/@name" /></xsl:call-template>) || (<xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> <xsl:call-template name="initCap"><xsl:with-param name="str" select="//table/@name" /></xsl:call-template> === "")){//##
      return <xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> <xsl:call-template name="initCap"><xsl:with-param name="str" select="$itemname" /></xsl:call-template> -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> get<xsl:value-of select="$colprop" />();//##
    } elseif ((<xsl:call-template name="variable"><xsl:with-param name="type" select="'session'" /><xsl:with-param name="name" select="$colname" /></xsl:call-template>) || (<xsl:call-template name="variable"><xsl:with-param name="type" select="'session'" /><xsl:with-param name="name" select="$colname" /></xsl:call-template> == "")) {//##
      return <xsl:call-template name="variable"><xsl:with-param name="type" select="'session'" /><xsl:with-param name="name" select="$colname" /></xsl:call-template>;//##
    } else {//##
      return false;//##
    }//##
  }//##
  //##
  function set<xsl:value-of select="$colprop" />( <xsl:text disable-output-escaping="yes">&#36;</xsl:text>str ) {//##
    <xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> <xsl:call-template name="initCap"><xsl:with-param name="str" select="//table/@name" /></xsl:call-template> -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> set<xsl:value-of select="$colprop" />( <xsl:text disable-output-escaping="yes">&#36;</xsl:text>str );//##
  }//##
  //##
</xsl:template>

<xsl:template name="columnget">
  <xsl:param name="columnname">empty</xsl:param>
  <xsl:param name="itemname">empty</xsl:param>
  <xsl:variable name="colprop"><xsl:call-template name="initCap"><xsl:with-param name="str" select="$columnname" /></xsl:call-template></xsl:variable>
  
  <xsl:variable name="colprop_get"><xsl:text disable-output-escaping="yes">WTVRcleanString(&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> <xsl:value-of select="$itemname" />-<xsl:text disable-output-escaping="yes">&gt;</xsl:text>get<xsl:value-of select="$colprop" />())</xsl:variable>
  <xsl:variable name="colprop_getdate">formatDate(<xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> <xsl:value-of select="$itemname" />-<xsl:text disable-output-escaping="yes">&gt;</xsl:text>get<xsl:value-of select="$colprop" />('%Y-%m-%d %T'),"TS")</xsl:variable>
  
  <xsl:variable name="colitem_array"><xsl:text disable-output-escaping="yes">&#36;</xsl:text>itemarray["<xsl:value-of select="$columnname" />"]</xsl:variable>
  
  <xsl:choose>
    <!--Date Population -->
    <xsl:when test="//column[@name=$columnname]//parameter[@name='Type']/@value = 'datetime'">
      //Be sure to format this date for the output type in Styroform, defaults to 'date'//##
      (<xsl:value-of select="$colprop_get" disable-output-escaping="yes" />) <xsl:text disable-output-escaping="yes">&#63; </xsl:text>  <xsl:value-of select="$colitem_array"  disable-output-escaping="yes" /> = <xsl:value-of select="$colprop_getdate"  disable-output-escaping="yes" /> : null;//##
    </xsl:when>
    <xsl:when test="//column[@name=$columnname]//parameter[@name='Type']/@value = 'tinyint(10)'">
      (is_numeric(<xsl:value-of select="$colprop_get" disable-output-escaping="yes" />)) <xsl:text disable-output-escaping="yes">&#63; </xsl:text> <xsl:value-of select="$colitem_array"  disable-output-escaping="yes" /> = <xsl:value-of select="$colprop_get"  disable-output-escaping="yes"/> : null;//##
    </xsl:when>
    <xsl:when test="//column[@name=$columnname]//parameter[@name='Type']/@value = 'int(10)'">
      (is_numeric(<xsl:value-of select="$colprop_get" disable-output-escaping="yes" />)) <xsl:text disable-output-escaping="yes">&#63; </xsl:text> <xsl:value-of select="$colitem_array"  disable-output-escaping="yes" /> = <xsl:value-of select="$colprop_get"  disable-output-escaping="yes"/> : null;//##
    </xsl:when>
    <xsl:when test="//column[@name=$columnname]//parameter[@name='Type']/@value = 'int(11)'">
      (is_numeric(<xsl:value-of select="$colprop_get" disable-output-escaping="yes" />)) <xsl:text disable-output-escaping="yes">&#63; </xsl:text> <xsl:value-of select="$colitem_array"  disable-output-escaping="yes" /> = <xsl:value-of select="$colprop_get"  disable-output-escaping="yes"/> : null;//##
    </xsl:when>
    <xsl:when test="//column[@name=$columnname]//parameter[@name='Type']/@value = 'float'">
      (is_numeric(<xsl:value-of select="$colprop_get" disable-output-escaping="yes" />)) <xsl:text disable-output-escaping="yes">&#63; </xsl:text> <xsl:value-of select="$colitem_array"  disable-output-escaping="yes" /> = sprintf("%01.2f",<xsl:value-of select="$colprop_get"  disable-output-escaping="yes"/>) : null;//##
    </xsl:when>
    <xsl:otherwise>
      (<xsl:value-of select="$colprop_get" disable-output-escaping="yes" />) <xsl:text disable-output-escaping="yes">&#63; </xsl:text> <xsl:value-of select="$colitem_array"  disable-output-escaping="yes" /> = <xsl:value-of select="$colprop_get"  disable-output-escaping="yes"/> : null;//##
    </xsl:otherwise>
  </xsl:choose>
</xsl:template>

<xsl:template name="columnset">
  <xsl:param name="columnname">empty</xsl:param>
  <xsl:param name="itemname">empty</xsl:param>
  <xsl:variable name="colprop"><xsl:call-template name="initCap"><xsl:with-param name="str" select="$columnname" /></xsl:call-template></xsl:variable>
  
  <xsl:variable name="colpost"><xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> get<xsl:value-of select="$colprop" />()</xsl:variable>
  <xsl:variable name="colpost_date"><xsl:text disable-output-escaping="yes">&#36;</xsl:text><xsl:value-of select="$itemname" /><xsl:value-of select="$columnname" /></xsl:variable>
  
  <xsl:variable name="colcheck">(<xsl:value-of select="$colpost"  disable-output-escaping="yes" />)</xsl:variable>
  <xsl:variable name="colcheck_date">( <xsl:text disable-output-escaping="yes">&#36;</xsl:text><xsl:value-of select="$itemname" /><xsl:value-of select="$columnname" /> != "01/01/1900 00:00:00" )</xsl:variable>
  
  <xsl:variable name="colprop_set"><xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> <xsl:value-of select="$itemname" />-<xsl:text disable-output-escaping="yes">&gt;</xsl:text>set<xsl:value-of select="$colprop" />( WTVRcleanString( <xsl:value-of select="$colpost" />) )</xsl:variable>	
  <xsl:variable name="colprop_setdate"><xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> <xsl:value-of select="$itemname" />-<xsl:text disable-output-escaping="yes">&gt;</xsl:text>set<xsl:value-of select="$colprop" />( formatDate(<xsl:value-of select="$colpost_date" />, "TS" )) : <xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> <xsl:value-of select="$itemname" />-<xsl:text disable-output-escaping="yes">&gt;</xsl:text>set<xsl:value-of select="$colprop" />( null )</xsl:variable>	
  
  <xsl:choose>
    <!--Date Population -->
    <xsl:when test="//column[@name=$columnname]//parameter[@name='Type']/@value = 'datetime'">
      if (is_valid_date( <xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> <xsl:value-of select="$itemname" />-<xsl:text disable-output-escaping="yes">&gt;</xsl:text>get<xsl:value-of select="$colprop" />())) {//##
        <xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> <xsl:value-of select="$itemname" />-<xsl:text disable-output-escaping="yes">&gt;</xsl:text>set<xsl:value-of select="$colprop" />( formatDate(<xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> get<xsl:value-of select="$colprop" />(), "TS" ));//##
      } else {//##
      <xsl:value-of select="$colpost_date" disable-output-escaping="yes" /> = <xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> sfDateTime( "<xsl:value-of select="$columnname" />" );//##
      <xsl:value-of select="$colcheck_date" disable-output-escaping="yes" /> <xsl:text disable-output-escaping="yes">&#63; </xsl:text> <xsl:value-of select="$colprop_setdate" disable-output-escaping="yes" />;//##
      }//##
    </xsl:when>
    <xsl:when test="//column[@name=$columnname]//parameter[@name='Type']/@value = 'float'">
      (is_numeric(<xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text>get<xsl:value-of select="$colprop" />())) <xsl:text disable-output-escaping="yes">&#63; </xsl:text> <xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> <xsl:value-of select="$itemname" />-<xsl:text disable-output-escaping="yes">&gt;</xsl:text>set<xsl:value-of select="$colprop" />( (float) <xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> get<xsl:value-of select="$colprop" />() ) : null;//##
    </xsl:when>
    <xsl:otherwise>
      <xsl:value-of select="$colcheck" disable-output-escaping="yes" /> <xsl:text disable-output-escaping="yes">&#63; </xsl:text> <xsl:value-of select="$colprop_set" disable-output-escaping="yes" /> : null;//##
    </xsl:otherwise>
  </xsl:choose>
  
</xsl:template>

<xsl:template name="populate">
  <xsl:param name="columnname"><xsl:call-template name="initCap"><xsl:with-param name="str" select="//table/@name" /></xsl:call-template></xsl:param>
  <xsl:variable name="colprop"><xsl:call-template name="initCap"><xsl:with-param name="str" select="$columnname" /></xsl:call-template></xsl:variable>
  
  //Pass an key value pair of <xsl:text disable-output-escaping="yes">&#36;</xsl:text>key-><xsl:text disable-output-escaping="yes">&#36;</xsl:text>value pairs//##
  //equivalent to colnames and check values//##
  //if ALL the array is matched in a data column//##
  //this will return true//##
  function populate( <xsl:text disable-output-escaping="yes">&#36;</xsl:text>key, <xsl:text disable-output-escaping="yes">&#36;</xsl:text>value ) {//##
    <xsl:text disable-output-escaping="yes">&#36;</xsl:text>c = new Criteria();//##
    //##
    <xsl:text disable-output-escaping="yes">&#36;</xsl:text>name = "<xsl:value-of select="$colprop" />Peer::".strtoupper(<xsl:text disable-output-escaping="yes">&#36;</xsl:text>key);//##
    eval("\<xsl:text disable-output-escaping="yes">&#36;</xsl:text>c-<xsl:text disable-output-escaping="yes">&gt;</xsl:text>add(".<xsl:text disable-output-escaping="yes">&#36;</xsl:text>name.",\<xsl:text disable-output-escaping="yes">&#36;</xsl:text>value);");//##
    //##
    <xsl:text disable-output-escaping="yes">&#36;</xsl:text>c-<xsl:text disable-output-escaping="yes">&gt;</xsl:text>setDistinct();//##
    <xsl:text disable-output-escaping="yes">&#36;</xsl:text><xsl:value-of select="$columnname" /> = <xsl:value-of select="$colprop" />Peer::doSelect(<xsl:text disable-output-escaping="yes">&#36;</xsl:text>c);//##
    //##
    if (count(<xsl:text disable-output-escaping="yes">&#36;</xsl:text><xsl:value-of select="$columnname" />) <xsl:text disable-output-escaping="yes">&gt;</xsl:text>= 1) {//##
      <xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> <xsl:value-of select="$columnname" /> = <xsl:text disable-output-escaping="yes">&#36;</xsl:text><xsl:value-of select="$columnname" />[0];//##
      return true;//##
    } else {//##
      <xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> <xsl:call-template name="initCap"><xsl:with-param name="str" select="//table/@name" /></xsl:call-template> = new <xsl:call-template name="initCap"><xsl:with-param name="str" select="//table/@name" /></xsl:call-template>();//##
      return false;//##
    }//##
  }//##
  //##
</xsl:template>

<xsl:template name="checkUnique">
  <xsl:param name="columnname"><xsl:call-template name="initCap"><xsl:with-param name="str" select="//table/@name" /></xsl:call-template></xsl:param>
  <xsl:variable name="colprop"><xsl:call-template name="initCap"><xsl:with-param name="str" select="$columnname" /></xsl:call-template></xsl:variable>
  
  //Pass an array of <xsl:text disable-output-escaping="yes">&#36;</xsl:text>key-><xsl:text disable-output-escaping="yes">&#36;</xsl:text>value pairs//##
  //equivalent to colnames and check values//##
  //if ALL the array is matched in a data column//##
  //this will return true//##
  function checkUnique( <xsl:text disable-output-escaping="yes">&#36;</xsl:text>vals ) {//##
    <xsl:text disable-output-escaping="yes">&#36;</xsl:text>c = new Criteria();//##
    //##
    foreach (<xsl:text disable-output-escaping="yes">&#36;</xsl:text>vals as <xsl:text disable-output-escaping="yes">&#36;</xsl:text>key =<xsl:text disable-output-escaping="yes">&gt;</xsl:text> <xsl:text disable-output-escaping="yes">&#36;</xsl:text>value) {//##
      <xsl:text disable-output-escaping="yes">&#36;</xsl:text>name = "<xsl:value-of select="$colprop" />Peer::".strtoupper(<xsl:text disable-output-escaping="yes">&#36;</xsl:text>key);//##
      eval("\<xsl:text disable-output-escaping="yes">&#36;</xsl:text>c-<xsl:text disable-output-escaping="yes">&gt;</xsl:text>add(".<xsl:text disable-output-escaping="yes">&#36;</xsl:text>name.",\<xsl:text disable-output-escaping="yes">&#36;</xsl:text>value);");//##
    }//##
    //##
    <xsl:text disable-output-escaping="yes">&#36;</xsl:text>c-<xsl:text disable-output-escaping="yes">&gt;</xsl:text>setDistinct();//##
    <xsl:text disable-output-escaping="yes">&#36;</xsl:text><xsl:value-of select="$columnname" /> = <xsl:value-of select="$colprop" />Peer::doSelect(<xsl:text disable-output-escaping="yes">&#36;</xsl:text>c);//##
    //##
    if (count(<xsl:text disable-output-escaping="yes">&#36;</xsl:text><xsl:value-of select="$columnname" />) <xsl:text disable-output-escaping="yes">&gt;</xsl:text>= 1) {//##
      <xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> <xsl:value-of select="$columnname" /> = <xsl:text disable-output-escaping="yes">&#36;</xsl:text><xsl:value-of select="$columnname" />[0];//##
      return true;//##
    } else {//##
      <xsl:text disable-output-escaping="yes">&#36;</xsl:text>this -<xsl:text disable-output-escaping="yes">&gt;</xsl:text> <xsl:call-template name="initCap"><xsl:with-param name="str" select="//table/@name" /></xsl:call-template> = new <xsl:call-template name="initCap"><xsl:with-param name="str" select="//table/@name" /></xsl:call-template>();//##
      return false;//##
    }//##
  }//##
  //##
</xsl:template>
 
<xsl:include href="initcap.xsl" />
<xsl:include href="deadpedal_attributes.xsl" />

</xsl:stylesheet>
