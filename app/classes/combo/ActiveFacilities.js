Ext.define('App.classes.combo.ActiveFacilities', {
	extend       : 'Ext.form.ComboBox',
	alias        : 'widget.mitos.activefacilitiescombo',
	initComponent: function() {
		var me = this;

		Ext.define('ActiveFacilitiesComboModel', {
			extend: 'Ext.data.Model',
			fields: [
				{name: 'option_name', type: 'string' },
				{name: 'option_value', type: 'int' }
			],
			proxy : {
				type: 'direct',
				api : {
					read: CombosData.getActiveFacilities
				}
			}
		});

		me.store = Ext.create('Ext.data.Store', {
			model   : 'ActiveFacilitiesComboModel',
			autoLoad: true
		});

		Ext.apply(this, {
			editable    : false,
			queryMode   : 'local',
			valueField  : 'option_value',
			displayField: 'option_name',
			emptyText   : 'Select',
			store       : me.store
		}, null);
		me.callParent(arguments);
	}
});