<?xml version='1.0'?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:php="http://php.net/xsl">
    <xsl:template match="/">
        <html>
            <HEAD>
                <link rel="stylesheet" type="text/css" href="modules/reportcenter/reports/PatientList/resources/css/report.css" />
            </HEAD>
            <body>
                <div style="padding: 5px">
                    <h1>170.314 (a)(14) Patient List</h1>
                    <p>
                        <span class="filter"><strong>Provider Name:</strong></span><xsl:value-of select="records/filters/provider_name/value"/><br/>
                        <span class="filter"><strong>Begin Date:</strong></span><xsl:value-of select="records/filters/begin_date/value"/><br/>
                        <span class="filter"><strong>End Date:</strong></span><xsl:value-of select="records/filters/end_date/value"/><br/>
                    </p>
                    <table class="report" width="100%">
                        <tr>
                            <th class="report" style="text-align: left;">Name</th>
                            <th class="report">Gender</th>
                            <th class="report">DOB</th>
                            <th class="report">Marital Status</th>
                        </tr>
                        <xsl:for-each select="records/record">
                            <tr>
                                <td class="report" style="text-align: left;"><xsl:value-of select="title"/>&#160;<xsl:value-of select="fname"/>&#160;<xsl:value-of select="mname"/>&#160;<xsl:value-of select="lname"/></td>
                                <td class="report" style="text-align: center;"><xsl:value-of select="sex"/></td>
                                <td class="report" style="text-align: center;"><xsl:value-of select="php:function('date', 'jS M, Y', number(DOB))"/></td>
                                <td class="report" style="text-align: center;"><xsl:value-of select="marital_status"/></td>
                            </tr>
                        </xsl:for-each>
                    </table>
                </div>
            </body>
        </html>
    </xsl:template>
</xsl:stylesheet>