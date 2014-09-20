Ext.define('App.ux.combo.ActiveProviders', {
	extend: 'Ext.form.ComboBox',
	alias: 'widget.activeproviderscombo',
	displayField: 'option_name',
	valueField: 'option_value',
	editable: false,
	emptyText: i18n('select'),
	initComponent: function(){
		var me = this;

		Ext.define('ActiveProvidersModel' + this.id, {
			extend: 'Ext.data.Model',
			fields: [
				{
					name: 'option_name',
					type: 'string'
				},
				{
					name: 'option_value',
					type: 'int'
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
			model: 'ActiveProvidersModel' + this.id,
			autoLoad: true
		});

//		Ext.apply(this, {
//			editable: false,
//			queryMode: 'local',
//			emptyText: i18n('select'),
//			store: me.store
//		});
		me.callParent(arguments);
	}
}); 