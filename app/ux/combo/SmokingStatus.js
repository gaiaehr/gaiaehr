Ext.define('App.ux.combo.SmokingStatus', {
	extend: 'Ext.form.ComboBox',
	alias: 'widget.mitos.smokingstatuscombo',
	editable: false,
	displayField: 'option_name',
	valueField: 'option_value',
	emptyText: i18n('select'),

	initComponent: function(){
		var me = this;

		Ext.define('smokingstatusModel', {
			extend: 'Ext.data.Model',
			fields: [
				{name: 'option_name', type: 'string' },
				{name: 'option_value', type: 'string' }
			],
			proxy: {
				type: 'direct',
				api: {
					read: 'CombosData.getOptionsByListId'
				},
				extraParams: {
					list_id: 58
				}
			}
		});

		me.store = Ext.create('Ext.data.Store', {
			model: 'smokingstatusModel',
			autoLoad: false
		});


		me.callParent(arguments);
	}
});