/**
 * GaiaEHR (Electronic Health Records)
 * Copyright (C) 2015 TRA NextGen, Inc.
 */

Ext.define('Modules.reportcenter.view.ReportCenter', {
	extend: 'App.ux.RenderPanel',
	pageTitle: _('report_center'),
    itemId: 'ReportCenterPanel',
    requires: [
    ],
    pageBody: [
        // Report List
        {
            xtype: 'gridpanel',
            itemId: 'reportCenterGrid',
            title: _('available_reports'),
            frame: false,
            store: Ext.create('Modules.reportcenter.store.ReportList'),
            features: [{
                ftype:'grouping'
            }],
            columns: [
                {
                    text: _('category'),
                    dataIndex: 'category',
                    hidden: true
                },
                {
                    text: _('report_name'),
                    dataIndex: 'title',
                    width: 300
                },
                {
                    text: _('version'),
                    dataIndex: 'version'
                },
                {
                    text: _('author'),
                    dataIndex: 'author',
                    width: 250
                },
                {
                    text: _('report_description'),
                    dataIndex: 'description',
                    flex: 1
                }
            ]
        },

        // Report Viewer
        {
            xtype: 'window',
            itemId: 'reportWindow',
            closeAction: 'hide',
            hidden: true,
            title: _('report_window'),
            layout: {
                type:'hbox',
                align: 'stretch'
            },
            maximizable: true,
            maximized: false,
            minimizable: false,
            modal: false,
            items:[
                {
                    xtype: 'splitter'
                },
                {
                    xtype: 'uxiframe',
                    region: 'center',
                    shadow: 'drop',
                    bodyPadding: 5,
                    autoScroll: true,
                    itemId: 'reportRender',
                    baseCls: 'x-panel-body',
                    flex: 1,
                    split: true,
                    border: true,
                    frame: true
                }
            ],
            dockedItems: [{
                xtype: 'toolbar',
                dock: 'top',
                items: [
                    {
                        xtype: 'button',
                        text: _('create_pdf'),
                        itemId: 'createPdf'
                    },
                    '-',
                    {
                        xtype: 'button',
                        text: _('create_html'),
                        itemId: 'createHtml'
                    },
                    '->',
                    {
                        xtype: 'button',
                        text: _('print'),
                        itemId: 'print'
                    }
                ]
            }]
        }
    ]

});
