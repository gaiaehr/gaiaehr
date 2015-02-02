Ext.define('App.ux.combo.YesNoNa', {
	extend       : 'Ext.form.ComboBox',
	alias        : 'widget.mitos.yesnonacombo',
	initComponent: function() {
		var me = this;

		Ext.define('yesnonaModel', {
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
					list_id: 93
				}
			}
		});

		me.store = Ext.create('Ext.data.Store', {
			model   : 'yesnonaModel',
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