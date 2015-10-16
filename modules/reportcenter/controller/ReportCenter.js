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

Ext.define('Modules.reportcenter.controller.ReportCenter', {
	extend: 'Ext.app.Controller',
	refs: [
		{
			ref: 'ReportCenterPanel',
			selector: '#ReportCenterPanel'
		},
        {
            ref: 'ReportCenterGrid',
            selector: '#reportCenterGrid'
        },
        {
            ref: 'ReportPanel',
            selector: '#reportPanel'
        },
        {
            ref: 'ReportWindow',
            selector: '#reportWindow'
        },
        {
            ref: 'ReportFilters',
            selector: '#reportWindow #reportFilters'
        }
	],

    init: function(){
        var me = this;

        me.control({
            '#reportCenterGrid': {
                itemdblclick: me.onReportCenterGridRowDblClick
            },
            '#ReportCenterPanel':{
                beforeshow: me.onReportCenterPanelBeforeShow
            }
        });
    },

    onReportCenterGridRowDblClick: function(record, item, index, e, eOpts){
        this.getReportFilters().removeAll();
        say(item.data.reportDir);
        this.getReportFilters().insert(0, Ext.create('Modules.reportcenter.reports.'+item.data.reportDir+'.filtersForm'));
        this.getReportWindow().show();
        this.getReportWindow().setTitle(_('report_window') + ' ( ' + item.data.report_name + ' )');
    },

    onReportCenterPanelBeforeShow: function(eOpts){
        this.getReportCenterGrid().getStore().load();
    }

});