<?php
/**
 * MatchaCreateModel::connect (MatchaCreateModel Class)
 * MatchaCreateModel.php
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

class MatchaCreateModel extends Matcha
{

    /**
     * function MatchaRouter():
     * Method to serve as router.
     */
    public function MatchaRouter()
    {

    }

    /**
     * function __createModelFile($fileSenchaModel, $databaseTable = NULL):
     * Method to create the Sencha Model .js file.
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
            $jsSenchaModel .= self::t(1)."extend: 'Ext.data.Model'," . chr(13);
            $jsSenchaModel .= self::t(1)."table: { name:'$databaseTable' },".chr(13);
            $jsSenchaModel .= self::t(1)."fields: [" . chr(13);
            $jsSenchaModel .= self::t(1)."{name: 'id', type: 'int'}".chr(13);
            $jsSenchaModel .= self::t(1)."]" . chr(13);
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
            foreach($tableColumns as $column) $fields .= self::t(2).self::__renderSenchaFieldSyntax($column).chr(13);
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
    private function __renderSenchaFieldSyntax($tableColumn)
    {
        $SenchaField = '';
        switch($tableColumn['Type'])
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
        $SenchaField .= self::t(1)."{name: '".$tableColumn['Field']."', type: '".$SenchaType."'},";
        return $SenchaField;
    }

    /**
     * function t($times = NULL):
     * Method to product TAB characters
     * @param null $times
     * @return string
     */
    private function t($times = NULL)
    {
        $tabs = '';
        for ($i = 1; $i <= $times; $i++) $tabs .= chr(9);
        return $tabs;
    }

}