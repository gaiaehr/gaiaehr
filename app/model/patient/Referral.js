/** * Generated dynamically by Matcha::Connect * Create date: 2014-01-07 22:55:50 */Ext.define('App.model.patient.Referral',{
    extend: 'Ext.data.Model',
    table: {
        name: 'encounter_referrals',
        comment: 'Encounter Referrals'
    },
    fields: [
        {
            name: 'id',
            type: 'int'
        },
        {
            name: 'pid',
            type: 'int'
        },
        {
            name: 'eid',
            type: 'int'
        },
        {
            name: 'uid',
            type: 'int'
        },
        {
            name: 'date',
            type: 'date',
            dateFormat: 'Y-m-d H:i:s'
        }
    ],
    proxy: {
        type: 'direct',
        api: {
            read: Encounter.getReferrals,
            create: Encounter.addReferral,
            update: Encounter.updateReferral
        }
    },
    belongsTo: {
        model: 'App.model.patient.Encounter',
        foreignKey: 'eid'
    }
});