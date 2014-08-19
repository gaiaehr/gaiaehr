Ext.define('App.ux.combo.ReferringProviders', {
	extend: 'Ext.form.ComboBox',
	alias: 'widget.referringproviderscombo',
	displayField: 'fullname',
	valueField: 'id',
	initComponent: function(){
		var me = this;

		Ext.define('ReferringProvidersModel', {
			extend: 'Ext.data.Model',
			fields: [
				{
					name: 'id',
					type: 'int'
				},
				{
					name: 'title',
					type: 'string'
				},
				{
					name: 'fname',
					type: 'string'
				},
				{
					name: 'mname',
					type: 'string'
				},
				{
					name: 'lname',
					type: 'string'
				},
				{
					name: 'fullname',
					type: 'string',
					convert: function(v, record){
						return record.data.title + ' ' + record.data.lname + ', ' + record.data.fname + ' ' + record.data.mname;
					}
				}
			],
			proxy: {
				type: 'direct',
				api: {
					read: 'ReferringProviders.getReferringProviders'
				}
			}
		});

		me.store = Ext.create('Ext.data.Store', {
			model: 'ReferringProvidersModel',
			autoLoad: false
		});

		Ext.apply(this, {
			emptyText: i18n('select'),
			store: me.store
		});

		me.callParent(arguments);
	}
});