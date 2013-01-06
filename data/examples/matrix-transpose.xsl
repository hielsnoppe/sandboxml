<?xml version="1.0" encoding="UTF-8"?>
<xsl:transform version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:template match="matrix">
		<matrix>
			<xsl:call-template name="nth-row">
				<xsl:with-param name="n" select="1" />
			</xsl:call-template>
		</matrix>
	</xsl:template>
	<xsl:template name="nth-row">
		<xsl:param name="n" />
		<xsl:if test="row/cell[$n]">
			<row><xsl:for-each select="row">
				<cell><xsl:value-of select="cell[$n]" /></cell>
			</xsl:for-each></row>
			<xsl:call-template name="nth-row">
				<xsl:with-param name="n" select="$n+1" />
			</xsl:call-template>
		</xsl:if>
	</xsl:template>
</xsl:transform>

