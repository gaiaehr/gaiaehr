<?php
/**
 * GaiaEHR (Electronic Health Records)
 * Copyright (C) 2015 TRA NextGen, Inc.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace modules\reportcenter\dataProvider;

class ReportGenerator
{

    /**
     * @var
     */
    private $request;

    /**
     * @var
     */
    public $reportDir;

    /**
     * @var
     */
    public $format;

    /**
     * @var
     */
    private $conn;

    /**
     * @var string
     */
    private $site;

    private $reportParameters;

    /**
     * Start
     * This baby, will activate Matcha::connect to connect to the database
     *
     * @return bool|string
     */
    function start()
    {
        try
        {
            $this->site = $_SESSION['user']['site'];
            if(!defined('_GaiaEXEC')) define('_GaiaEXEC', 1);
            require_once('../registry.php');

            // Try to connect to the database automatically. By just including the
            // MatchaHelper and the configuration file of the site, it will
            // connect to database.
            require_once("../sites/$this->site/conf.php");
            require_once('../classes/MatchaHelper.php');

            // Load the Array2XML library, this will create excellent and well formatted XML's for our hybrid
            // counterpart, this to create XSL pages. This is only used when XSL reporting is implied.
            require_once('../classes/Array2XML.php');

            return true;
        }
        catch(\Exception $Error)
        {
            error_log($Error->getMessage());
            return $Error->getMessage();
        }
    }

    function dispatchReportFilterPanel($summarizedParameters)
    {
        try
        {
            // Decode the reportInformation JSON to itself.
            $reportParameters = json_decode($summarizedParameters->params, true);
            $reportInformation = json_decode($summarizedParameters->reportInformation);

            // Build the filter display content for the sencha filter display panel
            $reportParameters[] = [
                'name' => 'title',
                'value' => $reportInformation->title
            ];
            $htmlTemplate = self::__buildFilterHTML($reportParameters, $reportInformation);
            array_pop($reportParameters);

            // Clean the string from unnecessary characters from the code, and also do some
            // sort of minify
            $htmlTemplate = self::__clearString($htmlTemplate);

            // Return the filters HTML representation.
            return [
                'success' => true,
                'data' => $htmlTemplate
            ];

        }
        catch(\Exception $Error)
        {
            error_log($Error->getMessage());
            return $Error->getMessage();
        }
    }

    /**
     * Build the data grid panel for the report, displaying the data extracted by
     * getXMLDocument method
     * $summarizedParameters = Is variable that comes from Sencha
     *
     * @param null $summarizedParameters
     * @return array
     * @throws \Exception
     */
    function buildDataGrid($summarizedParameters = null)
    {
        try
        {
            // Decode the reportInformation JSON to itself.
            $reportInformation = json_decode($summarizedParameters->reportInformation);

            // Try to load the data grid to build the Data Grid Panel
            $filePointer = "../modules/reportcenter/resources/dataGridPanel.js";
            if(file_exists($filePointer) && is_readable($filePointer))
            {
                // Load the dataGridPanel.js template file
                $dataGridPanel = file_get_contents($filePointer);

                // Load the report specifications json file
                $reportConfiguration = json_decode(file_get_contents(
                    "../modules/reportcenter/reports/$reportInformation->reportDir/reportSpec.json"
                ));

                $this->start();

                // Verify and check that the gridFields space are declared in the
                // report specification file
                if(!is_object($reportConfiguration->gridFields))
                {
                    throw new \Exception('No Sencha gridField are declared on the report specification file.');
                }

                // Replace the /*fieldColumns*/ in the data grid panel file, and write down the our build
                // Sencha column definitions.
                $resultSenchaDefinition = str_ireplace(
                    "/*fieldColumns*/",
                    self::__senchaColumnDefinition($reportConfiguration->gridFields),
                    $dataGridPanel
                );

                // Replaces the /*fieldStore*/ in the data store, and write down our build
                // Sencha store definitions, we are almost done.
                $resultSenchaDefinition = str_ireplace(
                    "/*fieldStore*/",
                    self::__senchaStoreDefinition($reportConfiguration->gridFields),
                    $resultSenchaDefinition
                );

                // If the page configuration is set, then start adding the configuration to the
                // dataGrid and dataStore
                if(isset($reportConfiguration->page))
                {
                    // Write down the configuration into the dataStore
                    // [page]
                    $resultSenchaDefinition = str_ireplace(
                        "/*dataGridConfig*/",
                        self::__gridPaging($reportConfiguration->page),
                        $resultSenchaDefinition
                    );
                    // Write down the configuration into the dataGrid
                    // [page]
                    $resultSenchaDefinition = str_ireplace(
                        "/*dataStoreConfig*/",
                        self::__storePagin($reportConfiguration->page),
                        $resultSenchaDefinition
                    );
                    $resultSenchaDefinition = str_ireplace(
                        "/*remoteSort*/",
                        'remoteSort: true,',
                        $resultSenchaDefinition
                    );
                }
                else
                {
                    $resultSenchaDefinition = str_ireplace(
                        "/*remoteSort*/",
                        'remoteSort: false,',
                        $resultSenchaDefinition
                    );
                }

                // Clean the string from unnecessary characters from the code, and also do some
                // sort of minify
                $resultSenchaDefinition = self::__clearString($resultSenchaDefinition);

                // Return the successfully parsed filter display panel back to Sencha.
                // and Sencha, and JavaScript will eval this JavaScript code and make it available and
                // be used by the reporting system
                return [
                    'success' => true,
                    'data' => $resultSenchaDefinition
                ];
            }
            else
            {
                throw new \Exception('Error reading & loading the Data Grid Panel, make sure is readable.');
            }
        }
        catch(\Exception $Error)
        {
            error_log($Error->getMessage());
            throw new \Exception($Error->getMessage());
        }
    }

    /**
     * Dispatch data to the report, depending on the report usage it will return XML or JSON valid
     * data, JSON when Sencha report is used. When XML is used, is when XSL is used.
     * $summarizedParameters = Is variable that comes from Sencha
     *
     * @param $summarizedParameters
     * @return array|string
     */
    function dispatchReportData($summarizedParameters)
    {
        try
        {
            // Decode the reportInformation JSON to itself.
            $extra = $summarizedParameters->filter[0]->value;
            $reportParameters = json_decode($extra->params, true);
            $reportInformation = json_decode($extra->reportInformation);

            // Prepare the variables for file operations
            $filePointer = "../modules/reportcenter/reports/$reportInformation->reportDir/reportStatement.sql";
            $PrepareField = [];

            if(file_exists($filePointer) && is_readable($filePointer))
            {
                // Load the report specifications json file
                $reportInformation = json_decode(file_get_contents(
                    "../modules/reportcenter/reports/$reportInformation->reportDir/reportSpec.json"
                ));

                // Load all the report information on memory, keep in memory almost all the methods
                // in this class use that this variable to extract portions of the configuration
                // information.
                $this->start();

                // Important connection parameter, this will allow multiple
                // prepare tags with the same name.
                $this->conn = \Matcha::getConn();
                $this->conn->setAttribute(\PDO::ATTR_EMULATE_PREPARES, true);

                // Get the report SQL statement content
                $fileContent = file_get_contents($filePointer);

                // Copy all the request variables into the Prepared Values,
                // also check if it came from the grid form and normal form.
                // This because we need to do a POST-PREPARE the SQL statement
                $parameters = $reportParameters;
                foreach($parameters as $field)
                {
                    $PrepareField[':'.$field['name']]['operator'] = (isset($field['operator']) ? $field['operator'] : '=');
                    $PrepareField[':' . $field['name']]['value'] = $field['value'];
                }

                // Copy all the request filter variables to the XML,
                // also check if it came from the grid form and normal form.
                // This because we need to do a POST-PREPARE the SQL statement
                foreach ($parameters as $field)
                {
                    $ReturnFilter[$field['name']]['operator'] = (isset($field['operator']) ? $field['operator'] : '=');
                    $ReturnFilter[$field['name']]['value'] = $field['value'];
                }

                // Prepare all the variable fields in the SQL Statement
                $PreparedSQL = self::__postPrepare($fileContent, $PrepareField);
                $Queries = explode(';', $PreparedSQL);

                // Run all the SQL Statement separated by `;` in the file
                $records = null;
                foreach($Queries as $Query)
                {
                    if(strlen(trim($Query)) > 0)
                    {
                        // Is just a SET @ variable, if yes query but not try to
                        // fetch any records. SET does not return any dataSet
                        if(self::__checkIfVariable($Query))
                        {
                            $this->conn->query($Query);
                        }
                        else
                        {
                            // Check if the page configuration exists, if yes try to look
                            // for an :ux-pagination in the SQL statement and replace it
                            // with SQL commands for the paging, keep in mind that we need
                            // to run 2 sql statements, one to get the total of records for sencha
                            // and the other one to get only the records need for display
                            if(isset($reportInformation->page))
                            {
                                // Compile the sorting sql statement
                                if(isset($summarizedParameters->sort))
                                {
                                    $sortStatement = "ORDER BY ";
                                    foreach($summarizedParameters->sort as $sortField)
                                    {
                                        $sortStatement .= "$sortField->property $sortField->direction,";
                                    }
                                    $sortStatement = substr($sortStatement, 0, -1);
                                    $sortStatement .= " ";
                                    $Query = str_ireplace(
                                        ':ux-sort',
                                        $sortStatement,
                                        $Query
                                    );
                                }
                                else
                                {
                                    $Query = str_ireplace(
                                        ':ux-sort',
                                        '',
                                        $Query
                                    );
                                }

                                // Get the result records
                                $SQL = $this->conn->prepare(str_ireplace(
                                    ':ux-pagination',
                                    " LIMIT $summarizedParameters->start, $summarizedParameters->limit",
                                    $Query
                                ));
                                $SQL->execute();
                                $ResultRecords[] = $SQL->fetchAll(\PDO::FETCH_ASSOC);

                                // Get the totals
                                $SQL = $this->conn->prepare(str_ireplace(
                                    ':ux-pagination',
                                    "",
                                    $Query
                                ));
                                $SQL->execute();
                                $records[] = $SQL->fetchAll();
                                $Total = count($records[count($records) - 1]);
                            }
                            else
                            {
                                $SQL = $this->conn->prepare($Query);
                                $SQL->execute();
                                $ResultRecords[] = $SQL->fetchAll(\PDO::FETCH_ASSOC);
                                $Total = count($ResultRecords[count($ResultRecords) - 1]);
                            }
                        }
                    }
                }
                // When format value is used in the parameters object dispatch the
                // data in XML format, or in JSON format
                //
                // XML::filters - The filters used in the filter panel
                // XML::record - The actual records extracted from the data base
                //
                // JSON::success - True when seccess, yeah!!
                // JSON::filters - The filters used in the filter panel
                // JSON::total - The total records in the data
                // JSON::data - The actual records extracted from the data base
                if($extra->format == 'xml')
                {
                    $ExtraAttributes['xml-stylesheet'] = 'type="text/xsl" href="report.xsl"';
                    \Array2XML::init('1.0', 'UTF-8', true, $ExtraAttributes);
                    $xml = \Array2XML::createXML('records', array(
                        'record' => $records[count($records[0]) - 1]
                    ));
                    return [
                        'success' => true,
                        'data' => $xml->saveXML()
                    ];
                }
                elseif($extra->format == 'json')
                {
                    return [
                        'success' => true,
                        'total' => $Total,
                        'data' => $ResultRecords[count($ResultRecords) - 1]
                    ];
                }
            }
            else
            {
                throw new \Exception('Error: Not SQL Statement file was found or readable.');
            }
        }
        catch(\Exception $Error)
        {
            error_log($Error->getMessage());
            return [
                'success' => false,
                'error' => $Error->getMessage()
            ];
        }
    }

    /**
     * Will look for all the matches with html comment <!--filter_name--> and replace with filter values
     * from the filterPanel
     *
     * @param $filters
     * @param $reportInformation
     * @return mixed|string
     * @throws \Exception
     */
    private function __buildFilterHTML($filters, $reportInformation)
    {
        try
        {
            // Load the filter html tamplate, but check for it existence of the template file
            $filePointer = "../modules/reportcenter/reports/$reportInformation->reportDir/filterHTML.html";
            if(!file_exists($filePointer) && !is_readable($filePointer))
                throw new \Exception('Filter HTML template not found or is readable.');
            $htmlTemplate = file_get_contents($filePointer);

            // Replace the filter key pairs. <!--filter_name-->
            foreach($filters as $filter)
                $htmlTemplate = str_ireplace('<!--'.$filter['name'].'-->', $filter['value'], $htmlTemplate);
            return $htmlTemplate;
        }
        catch(\Exception $Error)
        {
            error_log($Error->getMessage());
            throw new \Exception($Error->getMessage());
        }
    }

    /**
     * Method to set the pagin init configuration for the Data Store
     * if no configuration is found in the report configuration json
     * return blank, to delete the dataStoreConfig
     * @param $pagingConfiguration
     * @return string
     */
    private function __storePagin($pagingConfiguration)
    {
        $returnDataStoreConfiguration = '';
        if(isset($pagingConfiguration))
        {
            $returnDataStoreConfiguration .= "pageSize: $pagingConfiguration->limit";
        }
        return $returnDataStoreConfiguration;
    }

    /**
     * Method to set the Paging Init configuration for a Data Grid.
     * if no configuration is found in the report configuration json
     * return blank, to delete the dataGridConfig
     *
     * @param $pagingConfiguration
     * @return string
     */
    private function __gridPaging($pagingConfiguration)
    {
        $returnDataGridConfiguration = '';
        if(isset($pagingConfiguration))
        {
            $returnDataGridConfiguration .= "dockedItems: [{";
            $returnDataGridConfiguration .= "xtype: 'pagingtoolbar',";
            $returnDataGridConfiguration .= "store: dataGridStore,";
            $returnDataGridConfiguration .= "dock: 'bottom',";
            $returnDataGridConfiguration .= "displayInfo: true";
            $returnDataGridConfiguration .= "}],";
        }
        return $returnDataGridConfiguration;
    }


    /**
     * Build up the Sencha Store definition string
     * fields : Object[]/String[]
     * An object of field definition which define all the store fields
     *
     * @param null $storeFields
     * @return bool|string
     */
    private function __senchaStoreDefinition($storeFields = null)
    {
        // If gridFields is not set exit the method.
        if(!is_object($storeFields)) return false;

        $senchaDefinition = '';
        foreach($storeFields as $Index => $storeField)
        {
            $senchaDefinition .= '{ ';
            $senchaDefinition .= "name: '$Index',";
            $senchaDefinition .= "type: '$storeField->type'";
            $senchaDefinition .= ' },';
        }

        // Delete the last comma, we don't need it.
        $senchaDefinition = substr($senchaDefinition, 0, -1);

        return $senchaDefinition;
    }

    /**
     * Build up the Sencha Column definition string
     * Ext.grid.column.Column[]/Object
     * An array of column definition objects which define all columns that appear in this grid.
     * Each column definition provides the header text for the column, and a definition of where
     * the data for that column comes from.
     *
     * @param null $gridFields
     * @return bool
     */
    private function __senchaColumnDefinition($gridFields = null)
    {
        // If gridFields is not set exit the method.
        if(!is_object($gridFields)) return false;

        // Loop through the gridFields to write the entire column
        // definition.
        $senchaDefinition = '';
        foreach($gridFields as $Index => $gridField)
        {
            $senchaDefinition .= '{ ';
            $senchaDefinition .= "text: '$gridField->name',";
            $senchaDefinition .= "dataIndex: '$Index',";
            $senchaDefinition .= "align: '$gridField->align'";
            if(isset($gridField->width)) $senchaDefinition .= ", width: $gridField->width";
            if(isset($gridField->flex)) $senchaDefinition .= ", flex: $gridField->flex";
            $senchaDefinition .= ' },';
        }

        // Delete the last comma, we don't need it.
        $senchaDefinition = substr($senchaDefinition, 0, -1);

        return $senchaDefinition;
    }

    /**
     * Clean any giving string from unnecessary characters
     * @param $code
     * @return mixed
     */
    private function __clearString($code)
    {
        $buffer = str_ireplace("\r\n", '', $code);
        $buffer = str_ireplace("\n\r", '', $buffer);
        $buffer = str_ireplace("\r", '', $buffer);
        $buffer = str_ireplace("\n", '', $buffer);
        $buffer = str_ireplace("  ", '', $buffer);
        return $buffer;
    }

    /**
     * Check in the SQL statement if the current line is a variable, if not return false.
     * @param $Statement
     * @return bool
     */
    private function __checkIfVariable($Statement)
    {
        preg_match('/(?:set)+[^@]*@*/i', $Statement, $matches);
        if(count($matches) >= 1) return true;
        return false;
    }

    /**
     * Process the SQL statement to put in place the variables [:var] and put the real value
     * also it smart enough to write single quote when it is alpha-numeric and no quotes when
     * is number.
     *
     * @param string $sqlStatement
     * @param array $variables
     * @return mixed|string
     */
    private function __postPrepare($sqlStatement = '', $variables = [])
    {
        foreach($variables as $key => $variable)
        {
            $prepareKey = trim($key);
            if(is_numeric($variable['value']))
            {
                $prepareVariable = $variable['value'];
            }
            elseif($variable['value'] == null)
            {
                $prepareVariable = "null";
            }
            else
            {
                $prepareVariable = "'{$variable['value']}'";
            }
            $sqlStatement = str_ireplace($prepareKey, $prepareVariable, $sqlStatement);
            $sqlStatement = str_ireplace($prepareKey.'_operator', $variable['operator'], $sqlStatement);
        }
        return $sqlStatement;
    }
}
