/**
 * GaiaEHR (Electronic Health Records)
 * Copyright (C) 2015 TRA NextGen, Inc.
 */

Ext.define('Modules.reportcenter.view.ReportCenter', {
	extend: 'App.ux.RenderPanel',
	pageTitle: _('report_center'),
    itemId: 'ReportCenterPanel',
    requires: [
        'App.ux.grid.GridToHtml'
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
            layout: 'border',
            maximizable: true,
            maximized: false,
            minimizable: false,
            modal: false,
            autoScroll: false,
            items:[
                {
                    xtype: 'panel',
                    region: 'center',
                    itemId: 'reportPanel',
                    autoScroll: false,
                    items:[
                        {
                            xtype: 'panel',
                            frame: false,
                            border: false,
                            itemId: 'filterDisplayPanel',
                            region: 'north',
                            html: '',
                            autoScroll: true
                        }
                    ]
                }
            ],
            dockedItems: [{
                xtype: 'toolbar',
                dock: 'top',
                items: [
                    {
                        xtype: 'button',
                        text: _('render'),
                        itemId: 'render'
                    },
                    '->',
                    {
                        xtype: 'button',
                        text: _('create_pdf'),
                        itemId: 'createPdf',
                        disabled: true
                    },
                    '-',
                    {
                        xtype: 'button',
                        text: _('print'),
                        itemId: 'print',
                        disabled: true
                    }
                ]
            }]
        }
    ]

});
