<?php
/**
 * Matcha::connect
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

class MatchaModel extends Matcha
{

    /**
     * Variables needed by MatchaModel
     */
    public static $__senchaModel;

    /**
     * function MatchaRouter():
     * Method to serve as a router.
     */
    public function MatchaRouter()
    {

    }

    /**
     * function SenchaModel($fileModel):
     * This method will create the table and fields if does not exist in the database
     * also this is the brain of the micro ORM.
     */
    static protected function __SenchaModel($fileModel)
    {
        // skip this entire routine if freeze option is true
        if(self::$__freeze) return true;
        try
        {
            // get the the model of the table from the sencha .js file
            self::$__senchaModel = self::__getSenchaModel($fileModel);
            if(!self::$__senchaModel['fields']) throw new Exception('There are no fields set.');

            // check if the table property is an array, if not get back the array is a table string.
            $table = (string)(is_array(self::$__senchaModel['table']) ? self::$__senchaModel['table']['name'] : self::$__senchaModel['table']);

            // verify the existence of the table if it does not exist create it
            $recordSet = self::$__conn->query("SHOW TABLES LIKE '".$table."';");
            if(isset($recordSet)) self::__createTable($table);

            // Remove from the model those fields that are not meant to be stored
            // on the database-table and remove the id from the workingModel.
            $workingModel = (array)self::$__senchaModel['fields'];

            // if id property is not set in sencha model look for propertyId.
            if($workingModel[MatchaUtils::__recursiveArraySearch('id', $workingModel)] === false) unset($workingModel[self::__recursiveArraySearch('id', $workingModel)]);
            foreach($workingModel as $key => $SenchaModel) if(isset($SenchaModel['store']) && $SenchaModel['store'] === false) unset($workingModel[$key]);

            // get the table column information and remove the id column
            $recordSet = self::$__conn->query("SHOW FULL COLUMNS IN ".$table.";");
            $tableColumns = $recordSet->fetchAll(PDO::FETCH_ASSOC);
            unset($tableColumns[self::__recursiveArraySearch('id', $tableColumns)]);

            $columnsTableNames = array();
            $columnsSenchaNames = array();

            // get all the column names of each model
            foreach($tableColumns as $column) $columnsTableNames[] = $column['Field'];
            foreach($workingModel as $column) $columnsSenchaNames[] = $column['name'];

            // get all the column that are not present in the database-table
            $differentCreateColumns = array_diff($columnsSenchaNames, $columnsTableNames);
            $differentDropColumns = array_diff($columnsTableNames, $columnsSenchaNames);

            // check if the table has columns, if not create them.
            if( count($tableColumns) <= 1 )
            {
                self::__createAllColumns($workingModel);
                return true;
            }
            // Verify that all the columns does not have difference
            // between field names
            elseif( count($differentCreateColumns) || count($differentDropColumns) )
            {
                // add columns to the table
                foreach($differentCreateColumns as $column) self::__createColumn($workingModel[self::__recursiveArraySearch($column, $workingModel)]);
                // remove columns from the table
                foreach($differentDropColumns as $column) self::__dropColumn( $column );
            }
            // if everything else passes, check for differences in the columns.
            else
            {
                // modify the table columns if is not equal to the Sencha Model
                foreach($tableColumns as $column)
                {
                    $change = 'false';
                    foreach($workingModel as $SenchaModel)
                    {
                        // if the field is found, start the comparison
                        if($SenchaModel['name'] == $column['Field'])
                        {
                            // the following code will check if there is a dataType property if not, take the type instead
                            // on the model and parse it too.
                            $modelDataType = (isset($SenchaModel['dataType']) ? $SenchaModel['dataType'] : $SenchaModel['type']);
                            if($modelDataType == 'string') $modelDataType = 'varchar';
                            if($modelDataType == 'bool' && $modelDataType == 'boolean') $modelDataType = 'tinyint';

                            // check for changes on the field type is a obligatory thing
                            if(strripos($column['Type'], $modelDataType) === false) $change = 'true'; // Type

                            // check if there changes on the allowNull property,
                            // but first check if it's used on the sencha model
                            if(isset($SenchaModel['allowNull']))
                                if( $column['Null'] == ($SenchaModel['allowNull'] ? 'YES' : 'NO') ) $change = 'true'; // NULL

                            // check the length of the field,
                            // but first check if it's used on the sencha model.
                            if(isset($SenchaModel['len']))
                                if($SenchaModel['len'] != filter_var($column['Type'], FILTER_SANITIZE_NUMBER_INT)) $change = 'true'; // Length

                            // check if the default value is changed on the model,
                            // but first check if it's used on the sencha model
                            if(isset($SenchaModel['defaultValue']))
                                if($column['Default'] != $SenchaModel['defaultValue']) $change = 'true'; // Default value

                            // check if the primary key is changed on the model,
                            // but first check if the primary key is used on the sencha model.
                            if(isset($SenchaModel['primaryKey']))
                                if($column['Key'] != ($SenchaModel['primaryKey'] ? 'PRI' : '') ) $change = 'true'; // Primary key

                            // check if the auto increment is changed on the model,
                            // but first check if the auto increment is used on the sencha model.
                            if(isset($SenchaModel['autoIncrement']))
                                if($column['Extra'] != ($SenchaModel['autoIncrement'] ? 'auto_increment' : '') ) $change = 'true'; // auto increment

                            // check if the comment is changed on the model,
                            // but first check if the comment is used on the sencha model.
                            if(isset($SenchaModel['comment']))
                                if($column['Comment'] != $SenchaModel['comment']) $change = 'true';

                            // Modify the column on the database
                            if($change == 'true') self::__modifyColumn($SenchaModel);
                        }
                    }
                }
            }
            return true;
        }
        catch(PDOException $e)
        {
            MatchaErrorHandler::__errorProcess($e);
            return false;
        }
    }

    /**
     * __getSenchaModel($fileModel):
     * This method is used by SechaModel method to get all the table and column
     * information inside the Sencha Model .js file
     */
    static protected function __getSenchaModel($fileModel)
    {
        try
        {
            // Getting Sencha model as a namespace
            $senchaModel = self::__getFileContent($fileModel);
            // clean comments and unnecessary Ext.define functions
            $senchaModel = preg_replace("((/\*(.|\n)*?\*/|//(.*))|([ ](?=(?:[^\'\"]|\'[^\'\"]*\')*$)|\t|\n|\r))", '', $senchaModel);
            $senchaModel = preg_replace("(Ext.define\('[A-Za-z0-9.]*',|\);|\"|proxy(.|\n)*},)", '', $senchaModel);
            // wrap with double quotes to all the properties
            $senchaModel = preg_replace('/(,|\{)(\w*):/', "$1\"$2\":", $senchaModel);
            // wrap with double quotes float numbers
            $senchaModel = preg_replace("/([0-9]+\.[0-9]+)/", "\"$1\"", $senchaModel);
            // replace single quotes for double quotes
            // TODO: refine this to make sure doesn't replace apostrophes used in comments. example: don't
            $senchaModel = preg_replace("(')", '"', $senchaModel);

            $model = (array)json_decode($senchaModel, true);
            if(!count($model)) throw new Exception("Something whent wrong converting it to an array, a bad lolo.");

            // check if there are a defined table from the model
            if(!isset($model['table'])) throw new Exception("Table property is not defined on Sencha Model. 'table:'");

            // check if there are a defined fields from the model
            if(!isset($model['fields'])) throw new Exception("Fields property is not defined on Sencha Model. 'fields:'");
            return $model;
        }
        catch(Exception $e)
        {
            MatchaErrorHandler::__errorProcess($e);
            return false;
        }
    }

    /**
     * function __getFileContent($file, $type = 'js'):
     * Load a Sencha Model from .js file
     */
    static protected function __getFileContent($file, $type = 'js')
    {
        try
        {
            $file = (string)str_replace('App.', '', $file);
            $file = str_replace('.', '/', $file);
            if(!file_exists(self::$__app.'/'.$file.'.'.$type)) throw new Exception('Sencha file "'.self::$__app.'/'.$file.'.'.$type.'" not found.');
            return (string)file_get_contents(self::$__app.'/'.$file.'.'.$type);
        }
        catch(Exception $e)
        {
            MatchaErrorHandler::__errorProcess($e);
            return false;
        }
    }

    /**
     * function __setSenchaModelData($fileData):
     * Method to grab data and insert it into the table.
     * it uses MatchaThread to do batches of 500 records at the same
     * time.
     * TODO: Needs more work.
     */
    static protected function __setSenchaModelData($fileData)
    {
        try
        {
            $dataArray = json_decode(self::__getFileContent($fileData, 'json'), true);
            if(!count($dataArray)) throw new Exception("Something went wrong converting it to an array, a bad lolo.");
            $table = (string)(is_array(self::$__senchaModel['table']) ? self::$__senchaModel['table']['name'] : self::$__senchaModel['table']);
            $columns = 'INSERT INTO `'.$table.'` (`'.implode('`,`', array_keys($dataArray[0]) ).'`) VALUES ';

            $rowCount = 0;
            $valuesEncapsulation = '';
            foreach($dataArray as $key => $data)
            {
                $values  = array_values($data);
                foreach($values as $index => $val) if($val == null) $values[$index] = 'NULL';
                $valuesEncapsulation  .= '(\''.implode('\',\'',$values).'\')';
                if( $rowCount == 500 || $key == end(array_keys($dataArray)))
                {
                    // check if Threads PHP Class exists if does not exist
                    // run the SQL in normal fashion
                    if(class_exists('MatchaThreads'))
                    {
                        $thread = new MatchaThreads();
                        $thread->sqlStatement = $columns.$valuesEncapsulation.';';
                        $thread->start();
                    }
                    else
                    {
                        Matcha::$__conn->query($columns.$valuesEncapsulation.';');
                    }
                    $valuesEncapsulation = '';
                    $rowCount = 0;
                }
                else
                {
                    $valuesEncapsulation .= ', ';
                    $rowCount++;
                }
            }
            return true;
        }
        catch(Exception $e)
        {
            return MatchaErrorHandler::__errorProcess($e);
        }
    }

    /**
     * function setSenchaModel($senchaModel = array()):
     * The first thing to do, to begin using Matcha
     * This will load the Sencha Model to Matcha and do it's magic.
     */
    static public function setSenchaModel($senchaModel = array())
    {
        try
        {
            if(self::__SenchaModel($senchaModel))
            {
                $MatchaCUP = new MatchaCUP;
                $MatchaCUP->setModel(self::$__senchaModel);
                return $MatchaCUP;
            }
        }
        catch(Exception $e)
        {
            return MatchaErrorHandler::__errorProcess($e);
        }
    }

    /**
     * function __createModelFile($fileSenchaModel, $databaseTable = NULL):
     * Method to create the Sencha Model .js file into the filesystem
     * @param $fileSenchaModel
     * @param null $databaseTable
     * @return bool
     * @throws Exception
     */
    private function __createModelFile($fileSenchaModel, $databaseTable = NULL)
    {
        try
        {
            // compose the directory structure
            $dirLastKey = array_pop(explode('.', $fileSenchaModel));
            $modelDir = str_replace('.'.$dirLastKey, '', $fileSenchaModel);
            $modelDir = str_replace('App.', '', $modelDir);

            // check if the directory does not exist, if not create it.
            if(!opendir( self::$__app.'/'.strtolower(str_replace('.', '/', $modelDir) ) ))
            {
                $result = mkdir(self::$__app.'/'.strtolower(str_replace('.', '/', $modelDir) ), 0775, true );
                if( !$result ) throw new Exception('Could not create the directory.');
            }

            // compose the Sencha Model .js for the first time
            $jsSenchaModel = (string)"Ext.define('".$fileSenchaModel."', {" . chr(13);
            $jsSenchaModel .= MatchaUtils::t(1)."extend: 'Ext.data.Model'," . chr(13);
            $jsSenchaModel .= MatchaUtils::t(1)."table: { name:'$databaseTable' },".chr(13);
            $jsSenchaModel .= MatchaUtils::t(1)."fields: [" . chr(13);
            $jsSenchaModel .= MatchaUtils::t(1)."{name: 'id', type: 'int'}".chr(13);
            // TODO: Write the rest of the sencha fields here.
            $jsSenchaModel .= MatchaUtils::t(1)."]" . chr(13);
            $jsSenchaModel .= '});' . chr(13);

            // create the Sencha Model .js file for the first time
            $file = self::$__app.'/'.strtolower(str_replace('.', '/', $modelDir) ).'/'.$dirLastKey.'.js';
            if(!file_put_contents($file, $jsSenchaModel)) throw new Exception('Could not create the Sencha Model file.');
            return true;
        }
        catch(Exception $e)
        {
            MatchaErrorHandler::__errorProcess($e);
            return false;
        }
    }

    private function __RemoveSenchaColumn($Model, $field, $forceRemove = false)
    {

    }

    private function __createSenchaColumn($Model, $field)
    {

    }

    private function __ranameSenchaColumn($model, $field)
    {

    }

    /**
     * function __createModelTable($fileSenchaModel, $databaseTable = NULL):
     * Method to create the model on the table.
     * @param $fileSenchaModel
     * @param null $databaseTable
     * @param array $fields
     * @return bool
     */
    private function __createModelTable($fileSenchaModel, $databaseTable = NULL, $fields = array())
    {
        try
        {
            $recordSet = self::$__conn->query("SHOW FULL COLUMNS IN ".$databaseTable.";");
            $tableColumns = $recordSet->fetchAll(PDO::FETCH_ASSOC);
            $fields = '';
            foreach($tableColumns as $column) $fields .= MatchaUtils::t(2).self::__renderSenchaFieldSyntax($column).chr(13);
        }
        catch(PDOException $e)
        {
            MatchaErrorHandler::__errorProcess($e);
            return false;
        }
    }

    /**
     * function __renderSenchaFieldSyntax($tableColumn):
     * Method to render the syntax for the Sencha Model fields
     */
    public function __renderSenchaFieldSyntax($tableColumn)
    {
        $SenchaField = '';
        switch( strtoupper(strstr($tableColumn['Type'], '(', true)) )
        {
            case 'BIT'; case 'TINYINT'; case 'SMALLINT'; case 'MEDIUMINT'; case 'INT'; case 'INTEGER'; case 'BIGINT':
                $SenchaType = 'int';
                break;
            case 'REAL'; case 'DOUBLE'; case 'FLOAT'; case 'DECIMAL'; case 'NUMERIC':
                $SenchaType = 'float';
                break;
            case 'DATE'; case 'TIME'; case 'TIMESTAMP'; case 'DATETIME'; case 'YEAR':
                $SenchaType = 'date';
                break;
            case 'CHAR'; case 'VARCHAR':
                $SenchaType = 'string';
                break;
        }
        $SenchaField .= MatchaUtils::t(1)."{name: '".$tableColumn['Field']."', type: '".$SenchaType."'},";
        return $SenchaField;
    }

}