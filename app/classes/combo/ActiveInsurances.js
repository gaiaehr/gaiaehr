Ext.define('App.classes.combo.ActiveInsurances', {
	extend       : 'Ext.form.ComboBox',
	alias        : 'widget.activeinsurancescombo',
	initComponent: function() {
		var me = this;

		// *************************************************************************************
		// Structure, data for Insurance Payer Types
		// AJAX -> component_data.ejs.php
		// *************************************************************************************

        Ext.define('ActiveInsurancesComboModel', {
      			extend: 'Ext.data.Model',
      			fields: [
      				{name: 'option_name', type: 'string' },
      				{name: 'option_value', type: 'string' }
      			],
      			proxy : {
      				type: 'direct',
      				api : {
      					read: CombosData.getActiveInsurances
      				}
      			}
      		});

      		me.store = Ext.create('Ext.data.Store', {
      			model   : 'ActiveInsurancesComboModel'
      		});

		Ext.apply(this, {
			editable    : false,
			displayField: 'option_name',
			valueField  : 'option_value',
			emptyText   : i18n['select'],
			store       : me.store
		}, null);
		me.callParent();
	}
});