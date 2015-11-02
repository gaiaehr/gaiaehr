<?xml version='1.0'?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" >
    <xsl:template match="/">
        <html>
            <HEAD>
                <link rel="stylesheet" type="text/css" href="modules/reportcenter/reports/PatientList/resources/css/report.css" />
            </HEAD>
            <body>
                <div style="padding: 5px">
                    <h1>170.314(G)(2) Patient List</h1>
                    <p>
                        <span class="filter"><strong>Provider Name:</strong> </span><xsl:value-of select="records/filters/provider_name"/><br/>
                        <span class="filter"><strong>Begin Date:</strong> </span><xsl:value-of select="records/filters/begin_date"/><br/>
                        <span class="filter"><strong>End Date:</strong> </span><xsl:value-of select="records/filters/end_date"/><br/>
                    </p>
                    <table class="report" width="100%">
                        <tr>
                            <th class="report" style="text-align: left;">Description</th>
                            <th class="report">Denominator</th>
                            <th class="report">Numerator</th>
                            <th class="report">Percent</th>
                        </tr>
                        <xsl:for-each select="records/record">
                            <tr>
                                <td class="report"><xsl:value-of select="DESCRIPTION"/></td>
                                <td class="report" style="text-align: center;"><xsl:value-of select="DENOM"/></td>
                                <td class="report" style="text-align: center;"><xsl:value-of select="NUME"/></td>
                                <td class="report" style="text-align: center;"><xsl:value-of select="PERCENT"/>%</td>
                            </tr>
                        </xsl:for-each>
                    </table>
                </div>
            </body>
        </html>
    </xsl:template>
</xsl:stylesheet>