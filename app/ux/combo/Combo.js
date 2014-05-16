Ext.define('App.ux.combo.Combo', {
	extend: 'Ext.form.ComboBox',
	alias: 'widget.gaiaehr.combo',
	displayField: 'option_name',
	valueField: 'option_value',
	emptyText: i18n('select'),
	editable: false,

	/**
	 * List ID
	 */
	list: null,
	/**
	 * Auto Load Store
	 */
	loadStore: false,


	initComponent: function(){
		var me = this,
			model = me.id + 'ComboModel';

		Ext.define(model, {
			extend: 'Ext.data.Model',
			fields: [
				{
					name: 'option_name',
					type: 'string'
				},
				{
					name: 'option_value',
					type: 'string'
				},
				{
					name: 'code',
					type: 'string'
				},
				{
					name: 'code_type',
					type: 'string'
				}
			],
			proxy: {
				type: 'direct',
				api: {
					read: 'CombosData.getOptionsByListId'
				},
				extraParams: {
					list_id: me.list
				}
			},
			idProperty: 'option_value'
		});

		me.store = Ext.create('Ext.data.Store', {
			model: model,
			autoLoad: me.loadStore
		});

		me.callParent(arguments);
	}
});