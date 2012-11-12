Ext.define('App.store.Patients', {
    extend: 'Ext.data.Store',
    config: {
        fields: ['from_user', 'profile_image_url', 'text', 'created_at'],

        pageSize: 20,
        autoLoad: false,

        proxy: {
            type: 'jsonp',
            url: 'http://localhost/gaiaehr/data/mobileRouter.php',

            extraParams: {
                action: 'PoolArea',
                method: 'getPatientsByPoolAreaAccess',
                len: 1
            },

            reader: {
                type: 'json',
                rootProperty: 'data'
            }
        }
    }
});