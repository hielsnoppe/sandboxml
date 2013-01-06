<?xml version="1.0" encoding="UTF-8"?>
<xsl:transform version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:template match="/items">
		<xsl:call-template name="recursivesum">
			<xsl:with-param name="items" select="*" />
		</xsl:call-template>
	</xsl:template>

	<xsl:template name="recursivesum">
		<xsl:param name="items" />
		<xsl:param name="sum" select="0" />
		<xsl:variable name="head" select="$items[1]" />
		<xsl:variable name="tail" select="$items[position()>1]" />
		<xsl:variable name="thissum" select="$head/value * $head/quantity" />
		<xsl:choose>
			<xsl:when test="not($tail)">
				<xsl:value-of select="$sum+$thissum" />
			</xsl:when>
			<xsl:otherwise>
				<xsl:call-template name="recursivesum">
					<xsl:with-param name="sum" select="$sum+$thissum" />
					<xsl:with-param name="items" select="$tail" />
				</xsl:call-template>
			</xsl:otherwise>
		</xsl:choose>
	</xsl:template>
</xsl:transform>
