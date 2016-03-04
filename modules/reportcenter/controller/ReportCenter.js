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
        },
        {
            ref: 'PDFButton',
            selector: '#reportWindow #createPdf'
        },
        {
            ref: 'ReportPanel',
            selector: '#reportWindow #reportPanel'
        },
        {
            ref: 'FilterDisplayPanel',
            selector: '#reportWindow #filterDisplayPanel'
        }
    ],

    init: function(){
        var me = this
            reportInformation = null;

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
            '#reportWindow #render':{
                click: me.onRender
            }
        });
    },

    /**
     * This will insert the reportFilter panel of the report into the ReportCenter's Report Window,
     * also will set all the configuration to the Report Window, this configurtion is loaded into
     * the DataGrid, so when it's double clicked the configuration is already there. When all is set
     * and ready go ahead and show the Window, and recalculate it's layout.
     *
     * @param record
     * @param item
     * @param index
     * @param e
     * @param eOpts
     */
    onReportCenterGridRowDblClick: function(record, item, index, e, eOpts)
    {
        // Pickup the Report Window Panel Object and it's buddies
        var reportWindow = this.getReportWindow(),
            reportPanel = this.getReportPanel(),
            me = this;

        // Save the report information into a global variable
        me.reportInformation = item.data;

        // Try to remove any previuos filterForm in the Report Window
        reportWindow.remove(Ext.ComponentQuery.query('reportFilter')[0] , true);

        // Load the filtersForm panel and inert into the Report Window
        // this is the panel where the developer create the filter for the report,
        // it should be in Sencha code.
        Ext.require('Modules.reportcenter.reports.'+me.reportInformation.reportDir+'.filtersForm');
        reportWindow.insert(
            0, Ext.create('Modules.reportcenter.reports.'+me.reportInformation.reportDir+'.filtersForm')
        );

        // Configure the initial report panel layout, and finally show it.
        reportWindow.setHeight(900);
        reportWindow.setWidth(me.reportInformation.reportWindowWidth);
        reportWindow.doLayout();
        this.getReportFilterPanel().setWidth(me.reportInformation.filterPanelWidth);
        this.getFilterDisplayPanel().setHeight(me.reportInformation.filterDisplayHeight);
        reportWindow.setTitle(_('report_window') + ' ( ' + me.reportInformation.title + ' )');
        reportWindow.show();
    },

    /**
     * This will load the report list available in GaiaEHR, installation may vary.
     *
     * @param eOpts
     */
    onReportCenterPanelBeforeShow: function(eOpts)
    {
        this.getReportCenterGrid().getStore().load();
    },

    /**
     * Tries to clear the filter display panel before hidding it also will destroys the data grid panel,
     * this will help some performance at the time of displaying it again
     */
    onReportWindowBeforeHide: function()
    {
        // Pick up all the components if the report window
        var filterDisplayPanel = Ext.ComponentQuery.query('#reportWindow #filterDisplayPanel')[0],
            reportDataGrid = Ext.ComponentQuery.query('#reportWindow #reportDataGrid')[0];

        // Clear the HTML in the filter display panel
        // and destroys the Data Grid, on the reportWindow
        if(filterDisplayPanel) filterDisplayPanel.update('', true);
        if(reportDataGrid) reportDataGrid.destroy();
    },

    /**
     * Print report event, this procedure will print the report on the printer.
     */
    onPrint: function(){
        var reportDataGrid = Ext.ComponentQuery.query('#reportWindow #reportDataGrid')[0],
            GridToHtml = new Ext.create('App.ux.grid.GridToHtml'),
            html = GridToHtml.getHtml(reportDataGrid);
        console.log(html);
    },

    onCreatePDF: function(){
        this.generateDocument('pdf');
        this.getPrintButton().disable();
    },

    onRender: function(){
        this.generateDocument('html');
        this.getPrintButton().enable();
        this.getPDFButton().enable();
    },

    /**
     * Depending on the format this will call the data and then render the final
     * product, HTML that uses Sencha widgets and PDF that will take the Sencha widgets and
     * convert them in pure HTML and finally convert it to PDF document.
     *
     * @param format
     */
    generateDocument: function(format){
        var form = this.getReportFilterPanel().getForm(),
            fields = form.getFields(),
            parameters = {},
            Index,
            sumarizedParameters,
            me = this;

        // Validate the form, check if a field as a validation rule
        if(!form.isValid()) {
            Ext.Msg.alert(_('error'), _('please_check_form'));
            return;
        }

        // Mask the window with the loading sign
        this.getReportWindow().getEl().mask(_('loading'));

        // Destroy the datagrid and clear the filterDisplayPanel
        // start all over again.
        this.onReportWindowBeforeHide();

        // Evaluates every field in the form, extract the submitFormat and other
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

        // Sumarize all the variables into a single variable and then make the rpc call will get data from the
        // server, executing the SQL statement in the report directory.
        summarizedParameters = {
            format: "json",
            site: app.user.site,
            params: JSON.stringify(parameters),
            reportInformation: JSON.stringify(me.reportInformation)
        };

        // This rpc call will get the parsed Sencha Ext.grid.panel and try to add it to reportWindow
        // object, the server will parse several files and bring back a welll formatted Sencha Ext object
        // in a string, and then run de code in JavaScript.
        ReportGenerator.buildDataGrid(summarizedParameters, function(response){
            var senchaCode;
            if(response.success)
            {
                me.getReportPanel().add(
                    eval(Ext.htmlDecode(response.data))
                );
            }
            else
            {
                Ext.Msg.alert(_('error'), 'Could not load the data grid panel.');
            }
            return;
        });

        // Request the server to dispatch the report data to show it.
        ReportGenerator.dispatchReportData(summarizedParameters, function(response){
            var dataStore;
            if(response.success)
            {
                dataStore = Ext.getStore('reportStore');
                dataStore.clearData();
                dataStore.loadData(response.data);
                me.getFilterDisplayPanel().update(response.filters.data);
            }
            else
            {
                Ext.Msg.alert(_('error'), 'Could not load the data store.');
            }
            me.getReportWindow().getEl().unmask();
            return;
        });

        // Send the request to display the report
        //Ext.Ajax.request({
        //    url: 'modules/reportcenter/dataProvider/ReportGenerator.php?site=',
        //    params: {
        //        reportDir: this.getReportFilterPanel().getItemId(),
        //        format: format,
        //        site: app.user.site,
        //        params: JSON.stringify(parameters)
        //    },
        //    success: function(response){
        //        var XSLDocument = response.responseText;
        //        me.getReportRenderPanel().update(XSLDocument, true);
        //        me.getReportWindow().getEl().unmask();
        //    },
        //    failure: function(response, opts) {
        //        me.getReportWindow().getEl().unmask();
        //        Ext.Msg.alert(_('error'), 'server-side failure with status code ' + response.status);
        //    }
        //});
    }

});
