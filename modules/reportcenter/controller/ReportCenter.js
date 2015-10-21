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
        },
        {
            ref: 'ReportRenderPanel',
            selector: '#reportWindow #reportRender'
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
            '#reportWindow':{
                beforehide: me.onReportWindowBeforeHide
            },
            '#reportWindow #print':{
                click: me.onPrint
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

    onReportWindowBeforeHide: function(){
        this.getReportRenderPanel().update('', true);
    },

    onPrint: function(){

    },

    onCreatePDF: function(){
        this.generateDocument('pdf');
    },

    onCreateHTML: function(){
        this.generateDocument('html');
    },

    onCreateText: function(){
        this.generateDocument('text');
    },

    generateDocument: function(format){
        var form = this.getReportFilterPanel().getForm(),
            fields = form.getFields(),
            parameters = {},
            Index,
            me = this;

        if(!form.isValid()) {
            Ext.Msg.alert(_('error'), _('please_check_form'));
            return;
        }

        parameters.reportDir = this.getReportFilterPanel().getItemId();
        parameters.format = format;

        for(Index = 0; Index < fields.items.length; Index++) {
            parameters[Index] = {};
            switch(fields.items[Index].xtype){
                case 'datefield':
                    parameters[Index].name = fields.items[Index].name;
                    if(fields.items[Index].submitFormat) {
                        parameters[Index].value = Ext.util.Format.date(
                            fields.items[Index].value, fields.items[Index].submitFormat
                        );
                    } else {
                        parameters[Index].value = fields.items[Index].value;
                    }
                    break;
                default:
                    parameters[Index].name = fields.items[Index].name;
                    parameters[Index].value = fields.items[Index].value;
                    break;
            }
        }

        Ext.Ajax.request({
            url: 'modules/reportcenter/dataProvider/ReportGenerator.php',
            params: {
                params: JSON.stringify(parameters)
            },
            success: function(response){
                var XSLDocument = response.responseText;
                me.getReportRenderPanel().update(XSLDocument, true);
            }
        });
    }

});