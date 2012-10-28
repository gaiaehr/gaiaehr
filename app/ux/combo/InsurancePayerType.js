Ext.define('App.ux.combo.InsurancePayerType', {
	extend       : 'Ext.form.ComboBox',
	alias        : 'widget.mitos.insurancepayertypecombo',
	initComponent: function() {
		var me = this;

		// *************************************************************************************
		// Structure, data for Insurance Payer Types
		// AJAX -> component_data.ejs.php
		// *************************************************************************************
		me.store = Ext.create('Ext.data.Store', {
			fields: ['id', 'name'],
			data  : [
				{"id": "1", "name": i18n('all')},
				{"id": "16", "name": i18n('other_hcfa')},
				{"id": "MB", "name": i18n('medicare_part_b')},
				{"id": "MC", "name": i18n('medicaid')},
				{"id": "CH", "name": i18n('champusva')},
				{"id": "CH", "name": i18n('champus')},
				{"id": "BL", "name": i18n('blue_cross_blue_shield')},
				{"id": "16", "name": i18n('feca')},
				{"id": "09", "name": i18n('self_pay')},
				{"id": "10", "name": i18n('central_certification')},
				{"id": "11", "name": i18n('other_nonfederal_programs')},
				{"id": "12", "name": i18n('ppo')},
				{"id": "13", "name": i18n('pos')},
				{"id": "14", "name": i18n('epo')},
				{"id": "15", "name": i18n('indemnity_insurance')},
				{"id": "16", "name": i18n('hmo')},
				{"id": "AM", "name": i18n('automobile_medical')},
				{"id": "CI", "name": i18n('commercial_insurance')},
				{"id": "DS", "name": i18n('disability')},
				{"id": "HM", "name": i18n('health_maintenance_organization')},
				{"id": "LI", "name": i18n('liability')},
				{"id": "LM", "name": i18n('liability_medical')},
				{"id": "OF", "name": i18n('other_federal_program')},
				{"id": "TV", "name": i18n('title_v')},
				{"id": "VA", "name": i18n('veterans_administration_plan')},
				{"id": "WC", "name": i18n('workers_compensation_health_plan')},
				{"id": "ZZ", "name": i18n('mutually_defined')}
			]
		});

		Ext.apply(this, {
			name        : 'freeb_type',
			editable    : false,
			displayField: 'name',
			valueField  : 'id',
			queryMode   : 'local',
			emptyText   : i18n('select'),
			store       : me.store
		}, null);
		me.callParent();
	}
});