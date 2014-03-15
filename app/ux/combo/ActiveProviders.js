Ext.define('App.ux.combo.ActiveProviders', {
	extend: 'Ext.form.ComboBox',
	alias: 'widget.activeproviderscombo',
	initComponent: function(){
		var me = this;

		Ext.define('ActiveProvidersModel', {
			extend: 'Ext.data.Model',
			fields: [
				{
					name: 'option_name',
					type: 'string'
				},
				{
					name: 'option_value',
					type: 'string'
				}
			],
			proxy: {
				type: 'direct',
				api: {
					read: 'User.getActiveProviders'
				}
			}
		});

		me.store = Ext.create('Ext.data.Store', {
			model: 'ActiveProvidersModel',
			autoLoad: true
		});

		Ext.apply(this, {
			editable: false,
			queryMode: 'local',
			displayField: 'option_name',
			valueField: 'option_value',
			emptyText: i18n('select'),
			store: me.store
		});
		me.callParent(arguments);
	}
}); 