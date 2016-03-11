var dataGridStore = new Ext.create('Ext.data.Store', {
    storeId: 'reportStore',
    autoLoad  : false,
    remoteFilter: true,
    fields: [
        /*fieldStore*/
    ],
    /*remoteSort*/
    proxy: {
        type: 'direct',
        api: {
            read: 'ReportGenerator.dispatchReportData'
        },
        reader: {
            type: 'json',
            root: 'data'
        }
    },
    /*dataStoreConfig*/
});

Ext.create('Ext.grid.Panel', {
    itemId: 'reportDataGrid',
    store: dataGridStore,
    region: 'center',
    rowLines: false,
    columnLines: true,
    /*dataGridConfig*/
    columns: [
        /*fieldColumns*/
    ]
});
