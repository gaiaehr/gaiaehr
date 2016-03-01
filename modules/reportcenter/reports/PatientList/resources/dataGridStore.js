var dataGridStore = new Ext.create('Ext.data.Store', {
    storeId:'reportStore',
    fields:[

    ],
    proxy: {
        type: 'direct',
        reader: {
            type: 'json',
            root: 'items'
        }
    }
});
