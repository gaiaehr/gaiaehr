var dataGridStore = new Ext.create('Ext.data.Store', {
    storeId: 'reportStore',
    remoteSort: false,
    autoLoad  : false,
    fields: [
        /*fieldStore*/
    ],
    proxy: {
        type: 'direct',
        api: {
            read: 'ReportGenerator.dispatchReportData'
        }
    }
});

Ext.create('Ext.grid.Panel', {
    itemId: 'reportDataGrid',
    store: dataGridStore,
    region: 'center',
    rowLines: false,
    columnLines: true,
    flex: 1,
    columns: [
        /*fieldColumns*/
    ]
});
