<?xml version='1.0'?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:php="http://php.net/xsl">
    <xsl:template match="/">
        <html>
            <HEAD>
                <link rel="stylesheet" type="text/css" href="modules/reportcenter/reports/PatientList/resources/css/report.css" />
            </HEAD>
            <body>
                <h1>170.314 (a)(14) Patient List</h1>

                <table class="filters">
                    <tr>
                        <td>
                            <span>Provider Name:</span><xsl:value-of select="records/filters/provider_name/value"/><br/>
                            <span>Begin Date:</span><xsl:value-of select="records/filters/begin_date/value"/><br/>
                            <span>End Date:</span><xsl:value-of select="records/filters/end_date/value"/><br/>
                        </td>
                        <td>
                            <span>Allergy:</span>
                            <xsl:choose>
                                <xsl:when test="records/filters/allergy_name/value != ''">
                                    <xsl:value-of select="records/filters/allergy_name/value" />
                                </xsl:when>
                                <xsl:otherwise>
                                    All
                                </xsl:otherwise>
                            </xsl:choose><br/>
                            <span>Medication:</span>
                            <xsl:choose>
                                <xsl:when test="records/filters/medication_name/value != ''">
                                    <xsl:value-of select="records/filters/allergy_name/value" />
                                </xsl:when>
                                <xsl:otherwise>
                                    All
                                </xsl:otherwise>
                            </xsl:choose><br/>
                            <span>Problem:</span>
                            <xsl:choose>
                                <xsl:when test="records/filters/problem_name/value != ''">
                                    <xsl:value-of select="records/filters/problem_name/value" />
                                </xsl:when>
                                <xsl:otherwise>
                                    All
                                </xsl:otherwise>
                            </xsl:choose><br/>
                        </td>
                    </tr>
                </table>

                <table class="report" width="100%">
                    <tr>
                        <th class="report" style="text-align: left;">Name</th>
                        <th class="report">Gender</th>
                        <th class="report">DOB</th>
                        <th class="report">Marital Status</th>
                        <th class="report">Occupation</th>
                    </tr>
                    <xsl:for-each select="records/record">
                        <tr>
                            <td class="report" style="text-align: left;"><xsl:value-of select="title"/>&#160;<xsl:value-of select="fname"/>&#160;<xsl:value-of select="mname"/>&#160;<xsl:value-of select="lname"/></td>
                            <td class="report" style="text-align: center;"><xsl:value-of select="sex"/></td>
                            <td class="report" style="text-align: center;"><xsl:value-of select="php:function('date', 'jS M, Y', number(DOB))"/></td>
                            <td class="report" style="text-align: center;"><xsl:value-of select="marital_status"/></td>
                            <td class="report" style="text-align: center;"><xsl:value-of select="occupation"/></td>
                        </tr>
                    </xsl:for-each>
                </table>
            </body>
        </html>
    </xsl:template>
</xsl:stylesheet>