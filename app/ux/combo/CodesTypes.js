Ext.define('App.ux.combo.CodesTypes', {
	extend       : 'Ext.form.ComboBox',
	alias        : 'widget.mitos.codestypescombo',
	initComponent: function() {
		var me = this;

		Ext.define('CodesTypesModel', {
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
					list_id: 56
				}
			}
		});

		me.store = Ext.create('Ext.data.Store', {
			model   : 'CodesTypesModel',
			autoLoad: true
		});

		Ext.apply(this, {
			editable    : false,
			queryMode   : 'local',
			valueField  : 'option_value',
			displayField: 'option_name',
			emptyText   : i18n('select'),
			store       : me.store
		}, null);
		me.callParent(arguments);
	}
});