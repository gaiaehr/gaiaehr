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
        },
        {
            ref: 'PrintButton',
            selector: '#reportWindow #print'
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
            }
        });
    },

    onReportCenterGridRowDblClick: function(record, item, index, e, eOpts){
        this.getReportWindow().remove(Ext.ComponentQuery.query('reportFilter')[0] , true);
        Ext.require('Modules.reportcenter.reports.'+item.data.reportDir+'.filtersForm');
        this.getReportWindow().insert(
            0, Ext.create('Modules.reportcenter.reports.'+item.data.reportDir+'.filtersForm')
        );
        this.getReportWindow().setHeight(item.data.height);
        this.getReportWindow().setWidth(item.data.width);
        this.getReportFilterPanel().setHeight(item.data.filterHeight);
        this.getReportWindow().show();
        this.getReportWindow().setTitle(_('report_window') + ' ( ' + item.data.title + ' )');
    },

    onReportCenterPanelBeforeShow: function(eOpts){
        this.getReportCenterGrid().getStore().load();
    },

    onReportWindowBeforeHide: function(){
        this.getReportRenderPanel().update('', true);
    },

    /**
     * Print report event, this procedure will print the report on the printer.
     * TODO: Finish me.
     */
    onPrint: function(){
        var iframe = Ext.ComponentQuery.query('#reportWindow #reportRender')[0].el;
        say(iframe);
        //iframe.contentWindow.document.execCommand('print', false, null);
    },

    onCreatePDF: function(){
        this.generateDocument('pdf');
        this.getPrintButton().disable();
    },

    onCreateHTML: function(){
        this.generateDocument('html');
        this.getPrintButton().enable();
    },

    generateDocument: function(format){
        var form = this.getReportFilterPanel().getForm(),
            fields = form.getFields(),
            parameters = {},
            Index,
            me = this;

        // Validate the form, check if a field as a validation rule
        if(!form.isValid()) {
            Ext.Msg.alert(_('error'), _('please_check_form'));
            return;
        }

        this.getReportWindow().getEl().mask(_('loading'));

        // Evaluates every field in the form, extrat the submitFormat and other
        // things.
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

        // Send the request to display the report
        Ext.Ajax.request({
            url: 'modules/reportcenter/dataProvider/ReportGenerator.php?site=',
            params: {
                reportDir: this.getReportFilterPanel().getItemId(),
                format: format,
                site: app.user.site,
                params: JSON.stringify(parameters)
            },
            success: function(response){
                var XSLDocument = response.responseText;
                me.getReportRenderPanel().update(XSLDocument, true);
                me.getReportWindow().getEl().unmask();
            },
            failure: function(response, opts) {
                me.getReportWindow().getEl().unmask();
                Ext.Msg.alert(_('error'), 'server-side failure with status code ' + response.status);
            }
        });

    }
});
