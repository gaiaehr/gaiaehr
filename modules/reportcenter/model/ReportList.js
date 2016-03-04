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

Ext.define('Modules.reportcenter.model.ReportList', {
    extend: 'Ext.data.Model',
    fields: [
        {
            name: 'id',
            type: 'int'
        },
        {
            name: 'title',
            type: 'string'
        },
        {
            name: 'reportDir',
            type: 'string'
        },
        {
            name: 'description',
            type: 'string'
        },
        {
            name: 'category',
            type: 'string'
        },
        {
            name: 'author',
            type: 'string'
        },
        {
            name: 'version',
            type: 'string'
        },
        {
            name: 'filterPanelWidth',
            type: 'int'
        },
        {
            name: 'filterDisplayHeight',
            type: 'int'
        },
        {
            name: 'reportWindowWidth',
            type: 'int'
        }
    ]
});
