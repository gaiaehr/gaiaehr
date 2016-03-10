var dataGridStore = new Ext.create('Ext.data.Store', {
    storeId: 'reportStore',
    remoteSort: false,
    autoLoad  : false,
    /*dataStoreConfig*/
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
    layout: 'fit',
    /*dataGridConfig*/
    columns: [
        /*fieldColumns*/
    ]
});
