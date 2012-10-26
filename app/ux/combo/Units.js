Ext.define('App.ux.combo.Units', {
	extend       : 'Ext.form.ComboBox',
	alias        : 'widget.mitos.unitscombo',
	initComponent: function() {
		var me = this;

		Ext.define('UnitsModel', {
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
					list_id: 38
				}
			}
		});

		me.store = Ext.create('Ext.data.Store', {
			model   : 'UnitsModel',
			autoLoad: true
		});

		Ext.apply(this, {
			//editable    : false,
			queryMode   : 'local',
			valueField  : 'option_value',
			displayField: 'option_name',
			//emptyText   : 'Select',
			store       : me.store
		}, null);
		me.callParent(arguments);
	}
});