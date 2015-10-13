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

Ext.define('Modules.reportcenter.view.ReportCenter', {
	extend: 'App.ux.RenderPanel',
	pageTitle: _('report_center'),
    itemId: 'ReportCenterPanel',
    pageBody: [
        {
            xtype: 'gridpanel',
            itemId: 'reportCenterGrid',
            title: _('available_reports'),
            frame: false,
            store: Ext.create('Modules.reportcenter.store.ReportList'),
            columns: [
                {
                    text: _('category'),
                    dataIndex: 'category'
                },
                {
                    text: _('author'),
                    dataIndex: 'author'
                },
                {
                    text: _('report_name'),
                    dataIndex: 'report_name'
                },
                {
                    text: _('version'),
                    dataIndex: 'version'
                },
                {
                    text: _('report_description'),
                    dataIndex: 'report_description',
                    flex: 1
                }
            ]
        }
    ]

});