<?xml version="1.0" encoding="utf-8"?>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

  <xsl:template match="MenuDataResult">
    <ul id="egNav">

      <xsl:attribute name="id">
        <xsl:text>mainMenu</xsl:text>
      </xsl:attribute>

      <xsl:apply-templates select="Item/Item"/>
    </ul>
  </xsl:template>

  <xsl:template match="Item">
    <xsl:if test="ItemId != '0'">

          <li id="menu_{ItemId}">
            <xsl:choose>
              <xsl:when test="Menu != '' and Menu/Link != ''">
                <xsl:call-template name="LinkingMenu"></xsl:call-template>
              </xsl:when>
              <xsl:when test="ItemLink != ''">
                <xsl:call-template name="JustLink"></xsl:call-template>
              </xsl:when>
              <xsl:otherwise>
                <xsl:call-template name="NonLinkingMenu"></xsl:call-template>
              </xsl:otherwise>
            </xsl:choose>
          </li>

    </xsl:if>
  </xsl:template>

  <xsl:template name="JustLink">
    
    <a>
      <xsl:call-template name="GetSelected"></xsl:call-template>
      <xsl:attribute name="href">
        <xsl:value-of select="ItemLink"/>
      </xsl:attribute>
      <xsl:attribute name="title">
        <xsl:value-of select="ItemDescription"/>
      </xsl:attribute>
      <xsl:value-of select="ItemTitle"/>
    </a>

  </xsl:template>

  <xsl:template name="NonLinkingMenu">
    <xsl:call-template name="GetSelected">
      <xsl:with-param name="ExtraClasses">egDrop egNoLink</xsl:with-param>
    </xsl:call-template>
    <xsl:value-of select="ItemTitle"/>
    <xsl:if test="Menu/Item">

        <ul>
          <xsl:apply-templates select="Menu/Item"/>
        </ul>
        
    </xsl:if>
  </xsl:template>

  <xsl:template name="LinkingMenu">
    <xsl:call-template name="GetSelected">
      <xsl:with-param name="ExtraClasses">egDrop</xsl:with-param>
    </xsl:call-template>
    
    <a>
      <xsl:call-template name="GetSelected"></xsl:call-template>
      <xsl:attribute name="href">
        <xsl:value-of select="Menu/Link"/>
      </xsl:attribute>
      <xsl:attribute name="title">
        <xsl:value-of select="Menu/Description"/>
      </xsl:attribute>
      <xsl:value-of select="ItemTitle"/>
    </a>
      
    <xsl:if test="Menu/Item">

        <ul>
            <xsl:apply-templates select="Menu/Item"/>
        </ul>
      
    </xsl:if>
  </xsl:template>

  <xsl:template name="GetSelected">
    <xsl:param name="ExtraClasses"></xsl:param>
    <xsl:choose>
      <xsl:when test="count(.//ItemSelected[text() = 'true']) &gt; 0 or count(.//MenuSelected[text() = 'true']) &gt; 0 or count(.//ChildMenuSelected[text() = 'true']) &gt; 0">
        <xsl:attribute name="class">
          <xsl:value-of select="$ExtraClasses"/>
          <xsl:text> egSelected</xsl:text>
          <xsl:text> egMenuLevel_</xsl:text>
          <xsl:value-of select="count(ancestor::Menu)"/>
        </xsl:attribute>
      </xsl:when>
      <xsl:otherwise>
        <xsl:attribute name="class">
          <xsl:value-of select="$ExtraClasses"/>
          <xsl:text> egNonSelected</xsl:text>
          <xsl:text> egMenuLevel_</xsl:text>
          <xsl:value-of select="count(ancestor::Menu)"/>
        </xsl:attribute>
      </xsl:otherwise>
    </xsl:choose>
  </xsl:template>
</xsl:stylesheet>