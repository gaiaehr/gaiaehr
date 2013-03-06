/**
 GaiaEHR (Electronic Health Records)
 Copyright (C) 2013 Certun, inc.

 This program is free software: you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation, either version 3 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

Ext.define( 'App.model.administration.AclUserRoles',
{
    extend : 'Ext.data.Model',
    table: {
        name:'acluserroles',
        engine:'InnoDB',
        autoIncrement:1,
        charset:'utf8',
        collate:'utf8_bin',
        comment:'Access List User Roles'
    },
    fields : [
    {
        name : 'id',
        type : 'int',
        dataType : 'bigint',
        len: 20,
        primaryKey : true,
        autoIncrement : true,
        allowNull : false,
        store: true,
        comment: 'Access Control List ID'
    },
    {
        name : 'role_id',
        type : 'int',
        dataType : 'bigint',
        len: 20,
        store: true,
        comment: 'Role ID'
    },
    {
        name : 'add_date',
        type : 'date',
        dataType : 'timestamp',
        allowNull : false,
        store: true,
        defaultValue: 'CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
        comment: 'Added UNIX TimeStamp'
    }]
});