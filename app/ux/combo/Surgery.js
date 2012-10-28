Ext.define('App.ux.combo.Surgery', {
	extend       : 'Ext.form.ComboBox',
	alias        : 'widget.mitos.surgerycombo',
	initComponent: function() {
		var me = this;

		Ext.define('SurgeryModel', {
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
					list_id: 76
				}
			}
		});

		me.store = Ext.create('Ext.data.Store', {
			model   : 'SurgeryModel',
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