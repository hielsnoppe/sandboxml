<?xml version="1.0"?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:template match="/">
        <xsl:sequence select="sum(/items/item/(value * quantity))"/>
    </xsl:template>
</xsl:stylesheet>
