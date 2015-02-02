Ext.define('App.model.Patients', {
    extend: 'Ext.data.Model',
    config: {
        fields: [
            {name: 'pid',          type: 'string'},
            {name: 'name',        type: 'string'},
            {name: 'poolArea',      type: 'string'},
            {name: 'photoSrc',   type: 'auto'},
            {name: 'zoneTimeIn',       type: 'auto'}
        ]
    }
});
