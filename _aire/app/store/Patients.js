Ext.define('App.store.Patients', {
    extend: 'Ext.data.DirectStore',
    requires: ['App.model.Patients'],
    config: {
        model: 'App.model.Patients',
        pageSize: 20,
        autoLoad: false,

        grouper: {
           groupFn: function(record) {
               return record.get('poolArea');
           }
       },

        proxy: {
            //paramsAsHash: true,
            directFn: DataProvider.PoolArea.getPatientsByPoolAreaAccess,
            simpleSortMode: true,
            extraParams:{
                uid:null
            }
        }
    }
});