Ext.define('App.ux.combo.Combo', {
	extend: 'Ext.form.ComboBox',
	alias: 'widget.gaiaehr.combo',
	list: null,

	initComponent: function(){
		var me = this;
		var model = me.id + 'ComboModel';

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
			}
		});

		me.store = Ext.create('Ext.data.Store', {
			model: model
		});

		Ext.apply(me, {
			editable: false,
			displayField: 'option_name',
			valueField: 'option_value',
			emptyText: i18n('select'),
			store: me.store
		});

		me.callParent(arguments);
	}
});