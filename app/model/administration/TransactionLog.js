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

Ext.define('App.model.administration.TransactionLog', {
    extend: 'Ext.data.Model',
    table: {
        name: 'audit_transaction_log',
        comment: 'Data INSERT UPDATE DELETE Logs'
    },
    fields: [
        {
            name: 'id',
            type: 'int'
        },
        {
            name: 'date',
            type: 'date',
            dateFormat: 'Y-m-d H:i:s',
            comment: 'Date of the event'
        },
        {
            name: 'pid',
            type: 'int',
            comment: 'Patient ID'
        },
        {
            name: 'eid',
            type: 'int',
            comment: 'Encounter ID'
        },
        {
            name: 'uid',
            type: 'int',
            comment: 'User ID'
        },
        {
            name: 'fid',
            type: 'int',
            comment: 'Facility ID'
        },
        {
            name: 'event',
            type: 'string',
            len: 10,
            comment: 'Event UPDATE INSERT DELETE'
        },
        {
            name: 'table_name',
            type: 'string',
            len: 60
        },
        {
            name: 'sql_string',
            type: 'string',
            dataType: 'mediumtext'
        },
        {
            name: 'data',
            type: 'array',
            dataType: 'mediumtext',
            comment: 'serialized data',
            convert: function (v, record) {
                return record.serializeEventData(v);
            }
        },
        {
            name: 'ip',
            type: 'string',
            len: 40
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
            convert: function (v, record) {
                var str = '';
                if (record.data.user_title) str += record.data.user_title + ' ';
                if (record.data.user_fname) str += record.data.user_fname + ' ';
                if (record.data.user_mname) str += record.data.user_mname + ' ';
                if (record.data.user_lname) str += record.data.user_lname;
                return str;
            }
        },
        {
            name: 'patient_name',
            type: 'string',
            store: false,
            convert: function (v, record) {
                var str = '';
                if (record.data.patient_title) str += record.data.patient_title + ' ';
                if (record.data.patient_fname) str += record.data.patient_fname + ' ';
                if (record.data.patient_mname) str += record.data.patient_mname + ' ';
                if (record.data.patient_lname) str += record.data.patient_lname;
                return str;
            }
        },
        {
            name: 'is_valid',
            type: 'bool',
            store: false
        },
        {
            name: 'checksum',
            type: 'string',
            len: 80
        }
    ],
    proxy: {
        type: 'direct',
        api: {
            read: 'TransactionLog.getLogs'
        },
        reader: {
            root: 'data'
        }
    },
    serializeEventData: function (data) {
        var str = '';
        Ext.Object.each(data, function (key, value) {
            str += key + ' - ' + value + '<br>';
        });
        return str;


    }
});
