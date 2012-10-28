Ext.define('App.ux.combo.AllergiesTypes', {
	extend       : 'Ext.form.ComboBox',
	alias        : 'widget.mitos.allergiestypescombo',
	initComponent: function() {
		var me = this;

		Ext.define('AllergiesTypesComboModel', {
			extend: 'Ext.data.Model',
			fields: [
				{name: 'allergy_type', type: 'string' }
			],
			proxy : {
				type       : 'direct',
				api        : {
					read: CombosData.getAllergieTypes
				}
			}
		});

		me.store = Ext.create('Ext.data.Store', {
			model   : 'AllergiesTypesComboModel',
			autoLoad: false
		});

		Ext.apply(this, {
			editable    : false,
			//queryMode   : 'local',
			displayField: 'allergy_type',
			valueField  : 'allergy_type',
			emptyText   : i18n('select'),
			store       : me.store
		}, null);
		me.callParent(arguments);
	}
});