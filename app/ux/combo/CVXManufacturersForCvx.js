Ext.define('App.ux.combo.CVXManufacturersForCvx', {
	extend       : 'Ext.form.ComboBox',
	alias        : 'widget.cvxmanufacturersforcvxcombo',
	initComponent: function() {
		var me = this;

		Ext.define('CVXManufacturersForCvxComboModel', {
			extend: 'Ext.data.Model',
			fields: [
				{name: 'mvx_code', type: 'string'},
				{name: 'manufacturer', type: 'string'}
			],
			proxy : {
				type: 'direct',
				api : {
					read: Immunizations.getMvxForCvx
				}
			}
		});

		me.store = Ext.create('Ext.data.Store', {
			model   : 'CVXManufacturersForCvxComboModel',
			autoLoad: false
		});

		Ext.apply(this, {
            queryMode:'local',
			valueField  : 'mvx_code',
			displayField: 'manufacturer',
			emptyText   : i18n('select'),
			store       : me.store
		}, null);
		me.callParent(arguments);
	}
});