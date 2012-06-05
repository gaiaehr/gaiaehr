Ext.define('App.classes.combo.Allergies', {
	extend       : 'Ext.form.ComboBox',
	alias        : 'widget.mitos.allergiescombo',
	initComponent: function() {
		var me = this;

		Ext.define('AllergiesComboModel', {
			extend: 'Ext.data.Model',
			fields: [
				{name: 'id', type: 'int' },
				{name: 'allergy_name' },
				{name: 'allergy_type', type: 'string' }
			],
			proxy : {
				type       : 'direct',
				api        : {
					read: CombosData.getAllergiesByType
				}
			}
		});

		me.store = Ext.create('Ext.data.Store', {
			model   : 'AllergiesComboModel',
			autoLoad: false
		});

		Ext.apply(this, {
			editable    : false,
			queryMode   : 'local',
			displayField: 'allergy_name',
			valueField  : 'allergy_name',
			emptyText   : 'Select',
			store       : me.store
		}, null);
		me.callParent(arguments);
	}
});