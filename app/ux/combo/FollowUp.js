Ext.define('App.ux.combo.FollowUp', {
    extend       : 'Ext.form.ComboBox',
    alias        : 'widget.mitos.followupcombo',
    initComponent: function() {
        var me = this;

        Ext.define('FollowUpModel', {
            extend: 'Ext.data.Model',
            fields: [
                {name: 'option_name', type: 'string' },
                {name: 'option_value', type: 'string' }
            ],
            proxy : {
                type       : 'direct',
                api        : {
                    read: CombosData.getOptionsByListId
                },
                extraParams: {
                    list_id: 90
                }
            }
        });

        me.store = Ext.create('Ext.data.Store', {
            model   : 'FollowUpModel',
            autoLoad: true
        });

        Ext.apply(this, {
            editable    : false,
            queryMode   : 'local',
            displayField: 'option_name',
            valueField  : 'option_value',
            emptyText   : _('select'),
            store       : me.store
        }, null);
        me.callParent(arguments);
    }
});
