Ext.define('App.ux.combo.BillingFacilities', {
	extend       : 'Ext.form.ComboBox',
	alias        : 'widget.mitos.billingfacilitiescombo',
	initComponent: function() {
		var me = this;

		Ext.define('BillingFacilitiesComboModel', {
			extend: 'Ext.data.Model',
			fields: [
				{name: 'option_name', type: 'string' },
				{name: 'option_value', type: 'int' }
			],
			proxy : {
				type: 'direct',
				api : {
					read: 'CombosData.getBillingFacilities'
				}
			}
		});

		me.store = Ext.create('Ext.data.Store', {
			model   : 'BillingFacilitiesComboModel',
			autoLoad: true
		});

		Ext.apply(this, {
			editable    : false,
			queryMode   : 'local',
			valueField  : 'option_value',
			displayField: 'option_name',
			emptyText   : _('select'),
			store       : me.store
		}, null);
		me.callParent(arguments);
	}
});