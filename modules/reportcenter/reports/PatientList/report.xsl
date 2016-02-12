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
                            <span>Provider Name:</span>
                            <xsl:choose>
                                <xsl:when test="records/filters/provider_name/value != ''">
                                    <xsl:value-of select="records/filters/provider_name/value" />
                                </xsl:when>
                                <xsl:otherwise>
                                    All
                                </xsl:otherwise>
                            </xsl:choose>
                            <br/>
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
                                    <xsl:value-of select="records/filters/medication_name/value" />
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
                        <th class="report" style="text-align: left;">Provider</th>
                        <th class="report" style="text-align: left;">Patient</th>
                        <th class="report">Gender</th>
                        <th class="report">DOB</th>
                        <th class="report">Marital Status</th>
                        <th class="report">Occupation</th>
                        <th class="report">Race</th>
                        <th class="report">Ethnicity</th>
                        <th class="report">Allergies</th>
                        <th class="report">Problems</th>
                        <th class="report">Medications</th>
                    </tr>
                    <xsl:choose>
                    <xsl:when test="count(records/record/title) > 0">
                        <xsl:for-each select="records/record">
                            <tr>
                                <td class="report" style="text-align: left;"><xsl:value-of select="ProviderName"/></td>
                                <td class="report" style="text-align: left;"><xsl:value-of select="title"/>&#160;<xsl:value-of select="fname"/>&#160;<xsl:value-of select="mname"/>&#160;<xsl:value-of select="lname"/></td>
                                <td class="report" style="text-align: center;"><xsl:value-of select="sex"/></td>
                                <td class="report" style="text-align: center;"><xsl:value-of select="DateOfBirth"/></td>
                                <td class="report" style="text-align: center;"><xsl:value-of select="marital_status"/></td>
                                <td class="report" style="text-align: center;"><xsl:value-of select="occupation"/></td>
                                <td class="report" style="text-align: center;"><xsl:value-of select="Race"/></td>
                                <td class="report" style="text-align: center;"><xsl:value-of select="Ethnicity"/></td>
                                <td class="report" style="text-align: center;"><xsl:value-of select="allergy"/></td>
                                <td class="report" style="text-align: center;"><xsl:value-of select="problem_name"/></td>
                                <td class="report" style="text-align: center;"><xsl:value-of select="medication_name"/></td>
                            </tr>
                        </xsl:for-each>
                    </xsl:when>
                    <xsl:otherwise>
                        <tr>
                            <td colspan="11" style="text-align: center;">
                                <span>No records were found.</span>
                            </td>
                        </tr>
                    </xsl:otherwise>
                    </xsl:choose>
                </table>
            </body>
        </html>
    </xsl:template>
</xsl:stylesheet>
