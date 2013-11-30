/**
 GaiaEHR (Electronic Health Records)
 Copyright (C) 2013 Certun, LLC.

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

Ext.define('App.model.patient.encounter.snippetTree', {
    extend: 'Ext.data.Model',
    table: {
        name:'soap_snippets',
        comment:'Snippet Tree'
    },
    fields:[
        {name: 'id',       type: 'string'},
        {name: 'parentId', type: 'string'},
        {name: 'index',    type: 'int'},
        {name: 'text',     type: 'string'},
        {name: 'category', type: 'string'},
        {name: 'leaf',     type: 'bool'}
    ],
    proxy : {
        type: 'direct',
        api : {
            read: Snippets.getSoapSnippetsByCategory,
            create: Snippets.addSoapSnippets,
            update: Snippets.updateSoapSnippets,
            destroy: Snippets.deleteSoapSnippets
        }
    }
});