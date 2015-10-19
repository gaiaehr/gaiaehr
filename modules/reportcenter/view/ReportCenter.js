/**
 * GaiaEHR (Electronic Health Records)
 * Copyright (C) 2015 TRA NextGen, Inc.
 */

Ext.define('Modules.reportcenter.view.ReportCenter', {
	extend: 'App.ux.RenderPanel',
	pageTitle: _('report_center'),
    itemId: 'ReportCenterPanel',
    requires: [
        'Modules.reportcenter.view.ReportPanel'
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
                    dataIndex: 'report_name',
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
                    dataIndex: 'report_description',
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
            width: 800,
            height: 900,
            layout: 'fit',
            maximizable: false,
            maximized: false,
            minimizable: false,
            modal: false,
            items:[
                {
                    xtype: 'panel',
                    region: 'center',
                    border: true,
                    html: '<p>Report render!</p>'
                }
            ]
        }
    ]

});