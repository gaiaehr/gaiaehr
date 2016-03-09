<?php

/**
 * GaiaEHR (Electronic Health Records)
 * Copyright (C) 2013 Certun, LLC.
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
class EncounterEventHistory {

    /**
     * @var MatchaCUP
     */
    private $t;

    function __construct()
    {
        if ($this->t == NULL)
            $this->t = MatchaModel::setSenchaModel('App.model.administration.EncounterEventHistory');
    }

    public function getLogs($params)
    {
        $records = $this->t->load($params)->leftJoin([
            'title' => 'user_title',
            'fname' => 'user_fname',
            'mname' => 'user_mname',
            'lname' => 'user_lname'
        ], 'users', 'uid', 'id')->leftJoin([
            'title' => 'patient_title',
            'fname' => 'patient_fname',
            'mname' => 'patient_mname',
            'lname' => 'patient_lname'
        ], 'patient', 'pid', 'pid')->all();

        // iterate all the returned record to see if they have a valid CRC, if it fails
        // the record has been altered.
        // crc32($date.$pid.$eid.$uid.$fid.$saveParams['event'].$table.$sql.$data.$IP)
        foreach($records['data'] as $Index => $record)
        {
            $checksum = sha1(
                $record['date'] .
                $record['pid'] .
                $record['eid'] .
                $record['uid'] .
                $record['fid'] .
                $record['event'] .
                $record['table_name'] .
                $record['sql_string'] .
                $record['data'] .
                $record['ip']
            );
            $records['data'][$Index]['valid'] = ($record['checksum'] == $checksum ? true : false);
        }
        return $records;
    }

    public function getLog($params)
    {
        $record = $this->t->load($params)->leftJoin([
            'title' => 'user_title',
            'fname' => 'user_fname',
            'mname' => 'user_mname',
            'lname' => 'user_lname'
        ], 'users', 'uid', 'id')->leftJoin([
            'title' => 'patient_title',
            'fname' => 'patient_fname',
            'mname' => 'patient_mname',
            'lname' => 'patient_lname'
        ], 'patient', 'pid', 'pid')->one();

        if($record !== false)
        {
            $checksum = sha1(
                $record['date'] .
                $record['pid'] .
                $record['eid'] .
                $record['uid'] .
                $record['fid'] .
                $record['event'] .
                $record['table_name'] .
                $record['sql_string'] .
                $record['data'] .
                $record['ip']
            );
            $record['data']['valid'] = ($record['data']['checksum'] == $checksum ? true : false);
        }
        return $record;
    }

    public function setLog(stdClass $params) {
        return $params;
    }

}
