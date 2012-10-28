Ext.define('App.ux.combo.Pharmacies', {
	extend       : 'Ext.form.ComboBox',
	alias        : 'widget.mitos.pharmaciescombo',
	initComponent: function() {
		var me = this;

		Ext.define('PharmaciesComboModel', {
			extend: 'Ext.data.Model',
			fields: [
				{name: 'option_name' },
				{name: 'option_value' }
			],
			proxy : {
				type       : 'direct',
				api        : {
					read: CombosData.getActivePharmacies
				}
			}
		});

		me.store = Ext.create('Ext.data.Store', {
			model   : 'PharmaciesComboModel',
			autoLoad: false
		});

		Ext.apply(this, {
			editable    : false,
			//queryMode   : 'local',
			displayField: 'option_name',
			valueField  : 'option_value',
			emptyText   : i18n('select'),
			store       : me.store
		}, null);
		me.callParent(arguments);
	}
});