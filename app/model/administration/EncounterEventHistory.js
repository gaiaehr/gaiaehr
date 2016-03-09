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

Ext.define('App.model.administration.EncounterEventHistory', {
    extend: 'Ext.data.Model',
    table: {
        name: 'encounter_event_history',
        comment: 'Encounter Event History'
    },
    fields: [
        {
            name: 'c',
            type: 'int',
            comment: 'Encounter Event History ID'
        },
        {
            name: 'eid',
            type: 'int',
            comment: 'Encounter ID',
            index: true
        },
        {
            name: 'pid',
            type: 'int',
            comment: 'Patient ID',
            index: true
        },
        {
            name: 'uid',
            type: 'int',
            comment: 'User ID',
            index: true
        },
        {
            name: 'fid',
            type: 'int',
            comment: 'Facility ID',
            index: true
        },
        {
            name: 'event',
            type: 'string',
            len: 200,
            comment: 'Event description'
        },
        {
            name: 'user_title',
            type: 'string',
            store: false
        },
        {
            name: 'user_fname',
            type: 'string',
            store: false
        },
        {
            name: 'user_mname',
            type: 'string',
            store: false
        },
        {
            name: 'user_lname',
            type: 'string',
            store: false
        },
        {
            name: 'patient_title',
            type: 'string',
            store: false
        },
        {
            name: 'patient_fname',
            type: 'string',
            store: false
        },
        {
            name: 'patient_mname',
            type: 'string',
            store: false
        },
        {
            name: 'patient_lname',
            type: 'string',
            store: false
        },
        {
            name: 'user_name',
            type: 'string',
            store: false,
            convert: function(v, record){
                var str = '';
                if(record.data.user_title) str += record.data.user_title + ' ';
                if(record.data.user_fname) str += record.data.user_fname + ' ';
                if(record.data.user_mname) str += record.data.user_mname + ' ';
                if(record.data.user_lname) str += record.data.user_lname;
                return str;
            }
        },
        {
            name: 'patient_name',
            type: 'string',
            store: false,
            convert: function(v, record){
                var str = '';
                if(record.data.patient_title) str += record.data.patient_title + ' ';
                if(record.data.patient_fname) str += record.data.patient_fname + ' ';
                if(record.data.patient_mname) str += record.data.patient_mname + ' ';
                if(record.data.patient_lname) str += record.data.patient_lname;
                return str;
            }
        },
        {
            name: 'date',
            type: 'date',
            dateFormat: 'Y-m-d H:i:s',
            comment: 'Date of the event'
        }
    ],
    proxy: {
        type: 'direct',
        api: {
            read: 'EncounterEventHistory.getLogs',
            create: 'EncounterEventHistory.setLog',
            update: 'EncounterEventHistory.setLog'
        },
        reader: {
            totalProperty: 'totals',
            root: 'data'
        }
    }
});
