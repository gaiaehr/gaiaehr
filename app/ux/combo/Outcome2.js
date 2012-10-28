Ext.define('App.ux.combo.Outcome2', {
	extend       : 'Ext.form.ComboBox',
	alias        : 'widget.mitos.outcome2combo',
	initComponent: function() {
		var me = this;

		Ext.define('Outcome2model', {
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
					list_id: 74
				}
			}
		});

		me.store = Ext.create('Ext.data.Store', {
			model   : 'Outcome2model',
			autoLoad: true
		});

		Ext.apply(this, {
			editable    : false,
			queryMode   : 'local',
			displayField: 'option_name',
			valueField  : 'option_value',
			emptyText   : i18n('select'),
			store       : me.store
		}, null);
		me.callParent(arguments);
	}
});