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
    static public function CreateModelFile($fileModel)
    {
        try
        {

            $file = self::$__app.'/';

            $file = (string)str_replace('App.', '', $file);
            $file = str_replace('.', '/', $file);
            if(!file_exists(self::$__app.'/'.$file.'.'.$type)) throw new Exception('Sencha file "'.self::$__app.'/'.$file.'.'.$type.'" not found.');
            return (string)file_get_contents(self::$__app.'/'.$file.'.'.$type);

            file_put_contents($file, $jsSenchaModel, LOCK_EX);
            return true;
        }
        catch(Exception $e)
        {
            MatchaErrorHandler::__errorProcess($e);
            return false;
        }
    }
}