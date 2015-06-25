Ext.define('App.ux.combo.ActiveProviders', {
	extend: 'Ext.form.ComboBox',
	xtype: 'activeproviderscombo',
	displayField: 'option_name',
	valueField: 'option_value',
	editable: false,
	emptyText: _('select'),
	initComponent: function(){
		var me = this;

		Ext.define('ActiveProvidersModel' + this.id, {
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
				},
				{
					name: 'option_name',
					type: 'string',
					convert: function(v, record){
						return record.data.title + ' ' + record.data.lname + ', ' + record.data.fname + ' ' + record.data.mname;
					}
				},
				{
					name: 'option_value',
					type: 'int',
					convert: function(v, record){
						return record.data.id;
					}
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

		me.callParent(arguments);
	}
}); 