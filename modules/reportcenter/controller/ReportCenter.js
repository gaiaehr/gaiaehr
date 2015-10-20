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
            ref: 'ReportFilterPanel',
            selector: '#reportWindow reportFilter'
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
            },
            '#reportWindow #close':{
                click: me.onClose
            },
            '#reportWindow #createPdf':{
                click: me.onCreatePDF
            },
            '#reportWindow #createHtml':{
                click: me.onCreateHTML
            },
            '#reportWindow #createText':{
                click: me.onCreateText
            }
        });
    },

    onReportCenterGridRowDblClick: function(record, item, index, e, eOpts){
        this.getReportWindow().remove('reportFilter');
        Ext.require('Modules.reportcenter.reports.'+item.data.reportDir+'.filtersForm');
        this.getReportWindow().insert(
            0, Ext.create('Modules.reportcenter.reports.'+item.data.reportDir+'.filtersForm')
        );
        this.getReportWindow().show();
        this.getReportWindow().setTitle(_('report_window') + ' ( ' + item.data.report_name + ' )');
    },

    onReportCenterPanelBeforeShow: function(eOpts){
        this.getReportCenterGrid().getStore().load();
    },

    onClose: function(){
        this.getReportWindow().close();
    },

    onCreatePDF: function(){
        var form = this.getReportFilterPanel().getForm(),
            fields = form.getFields(),
            parameters = {},
            Index;

        for(Index = 0; Index < fields.items.length; Index++) {
            parameters[Index] = {};
            parameters[Index].name = fields.items[Index].name;
            parameters[Index].value = fields.items[Index].value;
        }

        parameters.reportDir = this.getReportFilterPanel().getItemId();

        Ext.Ajax.request({
            url: 'modules/reportcenter/dataProvider/ReportGenerator.php',
            params: parameters,
            success: function(response){
                var text = response.responseText;
                // process server response here
            }
        });
    },

    onCreateHTML: function(){
        var form = this.getReportFilterPanel().getForm(),
            fields = form.getFields();
    },

    onCreateText: function(){
        var form = this.getReportFilterPanel().getForm(),
            fields = form.getFields();
    }

});