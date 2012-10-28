//******************************************************************************
// ClientListReport.js
// Client List (Patient) Report
// v0.0.1
//
// Author: Gino Rivera Falu (GI Technologies)
// Modified:
//
// GaiaEHR (Electronic Health Records) 2012
//******************************************************************************
Ext.define('Modules.reportcenter.view.ReportPanel', {
    extend: 'App.ux.RenderPanel',
    id: 'panelReportPanel',
    pageTitle: i18n('report_center'),
    pageLayout: {
        type: 'vbox',
        align: 'stretch'
    },
    initComponent: function(){
        var me = this;


        me.renderContainer = Ext.create('Ext.panel.Panel',{
            flex:1,
            border:true
        });
        // END PDF Panel
        //-----------------------------------------------------------------------
        // Filter panel for the report
        //-----------------------------------------------------------------------
        me.formPanel = Ext.create('Ext.form.Panel', {
            bodyPadding: 10,
            margin: '0 0 3 0',
            collapsible: true,
            buttonAlign: 'left',
            title: i18n('filter'),
            // Draw the buttons to render and clear the report panel view.
            buttons: [
                {
                    text: i18n('generate_report'),
                    iconCls: 'icoReport',
                    scope: me,
                    handler: me.generateReport
                },
                '-',
                {
                    text: i18n('get_pdf'),
                    iconCls: 'icoReport',
                    disabled:true,
                    itemId:'pdf',
                    scope: me,
                    handler: me.generatePDF
                },
                '-',
                {
                    text: i18n('reset'),
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
                        text: i18n('back'),
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
        me.formPanel.removeAll();
        me.formPanel.add(config.items);
        me.resetRenderContainer();
        say(config);


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
//        me.resetRenderContainer();
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
