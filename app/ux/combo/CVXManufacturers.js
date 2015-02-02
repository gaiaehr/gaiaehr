Ext.define('App.ux.combo.CVXManufacturers', {
	extend       : 'Ext.form.ComboBox',
	alias        : 'widget.cvxmanufacturerscombo',
	initComponent: function() {
		var me = this;

		Ext.define('CVXManufacturersComboModel', {
			extend: 'Ext.data.Model',
			fields: [
				{name: 'mvx_code', type: 'string'},
				{name: 'manufacturer', type: 'string'}
			],
			proxy : {
				type: 'direct',
				api : {
					read: Immunizations.getMvx
				}
			}
		});

		me.store = Ext.create('Ext.data.Store', {
			model   : 'CVXManufacturersComboModel',
			autoLoad: false
		});

		Ext.apply(this, {
			editable    : false,
			valueField  : 'mvx_code',
			displayField: 'manufacturer',
			emptyText   : i18n('select'),
			store       : me.store
		}, null);
		me.callParent(arguments);
	}
});