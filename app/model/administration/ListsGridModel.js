/*
 GaiaEHR (Electronic Health Records)
 Lists.js
 Copyright (C) 2012 Ernesto Rodriguez

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

Ext.define('App.model.administration.ListsGridModel', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id',type: 'int'},
        {name: 'title',type: 'string'},
        {name: 'active',type: 'bool'},
        {name: 'in_use',type: 'bool'}
    ],
    proxy: {
        type: 'direct',
        api: {
            read: Lists.getLists,
            create: Lists.addList,
            update: Lists.updateList,
            destroy: Lists.deleteList
        }
    }
});