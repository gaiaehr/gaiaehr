Ext.define('App.ux.combo.PrescriptionTypes', {
	extend: 'Ext.form.ComboBox',
	alias: 'widget.mitos.prescriptiontypes',
	initComponent: function(){
		var me = this;

		Ext.define('PrescriptionTypesModel', {
			extend: 'Ext.data.Model',
			fields: [
				{name: 'option_name', type: 'string' },
				{name: 'option_value', type: 'string' }
			],
			proxy: {
				type: 'direct',
				api: {
					read: 'CombosData.getOptionsByListId'
				},
				extraParams: {
					list_id: 89
				}
			}
		});

		me.store = Ext.create('Ext.data.Store', {
			model: 'PrescriptionTypesModel',
			autoLoad: true
		});

		Ext.apply(this, {
			editable: false,
			queryMode: 'local',
			displayField: 'option_name',
			valueField: 'option_value',
			emptyText: _('select'),
			store: me.store
		});
		me.callParent(arguments);
	}
});