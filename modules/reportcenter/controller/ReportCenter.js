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
            },
            '#reportWindow #createText':{
                click: me.onCreateText
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
     * Print report event, this procedure will print the report in the printer.
     * TODO: Finish me.
     */
    onPrint: function(){
        var iframe = Ext.ComponentQuery.query('#reportWindow #reportRender')[0].el;
        say(iframe);
        //iframe.contentWindow.document.execCommand('print', false, null);
    },

    onCreatePDF: function(){
        var panelClass = Ext.getClass(this.getReportFilterPanel()).superclass.self.getName();
        switch(panelClass){
            case 'Ext.form.Panel':
                this.generateDocument('pdf');
                break;
            case 'Ext.grid.Panel':
                this.generateFromGrid('pdf');
                break;
        }
        this.getPrintButton().disable();
    },

    onCreateHTML: function(){
        var panelClass = Ext.getClass(this.getReportFilterPanel()).superclass.self.getName();
        switch(panelClass){
            case 'Ext.form.Panel':
                this.generateDocument('html');
                break;
            case 'Ext.grid.Panel':
                this.generateFromGrid('html');
                break;
        }
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

        // Create some extra parameter to send to the server.
        parameters.reportDir = this.getReportFilterPanel().getItemId();
        parameters.format = format;
        parameters.grid = false;

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
            url: 'modules/reportcenter/dataProvider/ReportGenerator.php',
            params: {
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

    },

    /**
     * If the form is a grid containing [filter, operator, & values]
     * process the grid as a form.
     * @param grid
     */
    generateFromGrid: function(format){
        var store = this.getReportFilterPanel().getStore(),
            records = store.getRange(),
            parameters = {};

        this.getReportWindow().getEl().mask(_('loading'));

        // Create some extra parameter to send to the server.
        parameters.reportDir = this.getReportFilterPanel().getItemId();
        parameters.format = format;
        parameters.grid = true;

        // Evaluates every field in the form, extract the submitFormat and other
        // things.
        for(var Index in records) {
            parameters[Index] = {
                'name': records[Index].getData(true)['name'],
                'operator': records[Index].getData(true)['operator'],
                'value': records[Index].getData(true)['value']
            };
        }

        // Send the request to display the report
        Ext.Ajax.request( {
            url: 'modules/reportcenter/dataProvider/ReportGenerator.php',
            params: {
                site: app.user.site,
                params: JSON.stringify(parameters)
            },
            success: function(response) {
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