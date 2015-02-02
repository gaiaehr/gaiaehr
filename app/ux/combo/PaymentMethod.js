Ext.define('App.ux.combo.PaymentMethod', {
	extend       : 'Ext.form.ComboBox',
	alias        : 'widget.mitos.paymentmethodcombo',
	initComponent: function() {
		var me = this;

		Ext.define('PaymentMethodModel', {
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
					list_id: 51
				}
			}
		});

		me.store = Ext.create('Ext.data.Store', {
			model   : 'PaymentMethodModel',
			autoLoad: true
		});

		Ext.apply(this, {
			editable    : false,
			queryMode   : 'local',
			displayField: 'option_name',
			valueField  : 'option_value',
			emptyText   : i18n('select'),
			store       : me.store
		}, null);
		me.callParent(arguments);
	}
});