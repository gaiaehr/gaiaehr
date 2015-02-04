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

Ext.define('Modules.reportcenter.view.ReportPanel', {
    extend: 'App.ux.RenderPanel',
    id: 'panelReportPanel',
    pageTitle: _('report_center'),
    pageLayout: {
        type: 'vbox',
        align: 'stretch'
    },
    initComponent: function(){
        var me = this;


        /**
         * This is were the PDF or dataGrid will be rendered
         * @type {*}
         */
        me.renderContainer = Ext.create('Ext.panel.Panel',{
            flex:1,
            border:true
        });


        /**
         * Filter panel for the report
         */
        me.formPanel = Ext.create('Ext.form.Panel', {
            bodyPadding: 10,
            margin: '0 0 3 0',
            collapsible: true,
            buttonAlign: 'left',
            layout: 'column',
            // Draw the buttons to render and clear the report panel view.
            items:[{}],
            buttons: [
                {
                    text: _('generate_report'),
                    iconCls: 'icoReport',
                    scope: me,
                    handler: me.generateReport
                },
                '-',
                {
                    text: _('get_pdf'),
                    iconCls: 'icoReport',
                    disabled:true,
                    itemId:'pdf',
                    scope: me,
                    handler: me.generatePDF
                },
                '-',
                {
                    text: _('reset'),
                    iconCls: 'delete',
                    scope: me,
                    handler: me.resetRenderContainer
                },
                '->',
                {
                    text: 'Print',
                    iconCls: 'icon-print',
                    handler : function(){
                        App.ux.grid.Printer.printAutomatically = false;
                        App.ux.grid.Printer.print(me.grid);
                    }
                }
            ]
        });
        me.pageBody = [me.formPanel, me.renderContainer];
        me.callParent(arguments);
        me.getPageBody().addDocked({
                xtype: 'toolbar',
                dock: 'top',
                items: [
                    {
                        text: _('back'),
                        iconCls: 'icoArrowLeftSmall',
                        handler: me.goToReportCenter
                    }
                ]
            });
    },

    setReportPanel: function(config){
        var me = this;
        if(config.title) me.formPanel.setTitle(config.title);
        if(config.action) me.formPanel.action = config.action;
        if(config.fn) me.formPanel.reportFn = config.fn;
        if(config.store) me.store = config.store;
        if(config.columns) me.columns = config.columns;
        if(config.height) me.formPanel.setHeight(config.height);
        if(config.bodyStyle) me.formPanel.setBodyStyle(config.bodyStyle);
        if(config.border) me.formPanel.setBorder(config.border);
        me.formPanel.removeAll();
        me.formPanel.add(config.items);
        me.resetRenderContainer();
        //say(me.formPanel);
   },

    goToReportCenter: function(){
        app.MainPanel.getLayout().setActiveItem('panelReportCenter');
    },

    getGridPanel:function(){
        var me = this;
        return this.renderContainer.add(Ext.create('Ext.grid.Panel',{
            store:me.store,
            columns:me.columns,
            border:false
        }));
    },
    /**
     * PDF render panel
     * Just create the panel and do not display the PDF yet, until
     * the user click create report.
     * @return {*}
     */
    getPDFPanel:function(src){
       //-----------------------------------------------------------------------
        // PDF render panel
        // Just create the panel and do not display the PDF yet, until
        // the user click create report.
        //-----------------------------------------------------------------------
        return this.renderContainer.add(Ext.create('App.ux.ManagedIframe', {
            src: src
        }));
    },

    generateReport:function(btn){
        var me = this,
            botton= btn.up('toolbar').getComponent('pdf'),
            values = me.formPanel.getForm().getValues();
        this.renderContainer.removeAll(true);
        delete this.pdf;
        me.grid = this.getGridPanel();
        me.store.load({params:values});
        botton.setDisabled(false);
    },

    generatePDF: function(btn){
        var me = this, form = me.formPanel, params = form.getForm().getValues();
        if(typeof form.reportFn == 'function'){
            var html =App.ux.grid.GridToHtml.getHtml(me.grid);
            this.renderContainer.removeAll(true);
            delete this.grid;
            form.reportFn({html:html}, function(provider, response){

                me.pdf = me.getPDFPanel(response.result.url);
            });
        }else{
            Ext.Msg.alert('Oops!', 'No function provided');
        }
        btn.setDisabled(true);
    },

    resetRenderContainer:function(){
        this.formPanel.down('toolbar').getComponent('pdf').setDisabled(true);
        this.formPanel.getForm().reset();
        this.renderContainer.removeAll(true);
        delete this.grid;
        delete this.pdf;

    },

    /**
     * This function is called from MitosAPP.js when
     * this panel is selected in the navigation panel.
     * place inside this function all the functions you want
     * to call every this panel becomes active
     */
    onActive: function(callback){
        callback(true);
    }
});
