Ext.define('App.ux.combo.Types', {
	extend       : 'Ext.form.ComboBox',
	alias        : 'widget.mitos.typescombobox',
	initComponent: function() {
		var me = this;

		// *************************************************************************************
		// Structure, data for Types
		// AJAX -> component_data.ejs.php
		// *************************************************************************************


		Ext.define('TypesModel', {
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
					list_id: 32
				}

			}
		});

		me.store = Ext.create('Ext.data.Store', {
			model   : 'TypesModel',
			autoLoad: true
		});

		Ext.apply(this, {
			name        : 'abook_type',
			editable    : false,
			displayField: 'option_name',
			valueField  : 'option_value',
			queryMode   : 'local',
			store       : me.store
		}, null);
		me.callParent(arguments);
	}
});