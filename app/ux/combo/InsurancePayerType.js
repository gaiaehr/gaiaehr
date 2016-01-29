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
				{"id": "1", "name": _('all')},
				{"id": "16", "name": _('other_hcfa')},
				{"id": "MB", "name": _('medicare_part_b')},
				{"id": "MC", "name": _('medicaid')},
				{"id": "CH", "name": _('champusva')},
				{"id": "CH", "name": _('champus')},
				{"id": "BL", "name": _('blue_cross_blue_shield')},
				{"id": "16", "name": _('feca')},
				{"id": "09", "name": _('self_pay')},
				{"id": "10", "name": _('central_certification')},
				{"id": "11", "name": _('other_nonfederal_programs')},
				{"id": "12", "name": _('ppo')},
				{"id": "13", "name": _('pos')},
				{"id": "14", "name": _('epo')},
				{"id": "15", "name": _('indemnity_insurance')},
				{"id": "16", "name": _('hmo')},
				{"id": "AM", "name": _('automobile_medical')},
				{"id": "CI", "name": _('commercial_insurance')},
				{"id": "DS", "name": _('disability')},
				{"id": "HM", "name": _('health_maintenance_organization')},
				{"id": "LI", "name": _('liability')},
				{"id": "LM", "name": _('liability_medical')},
				{"id": "OF", "name": _('other_federal_program')},
				{"id": "TV", "name": _('title_v')},
				{"id": "VA", "name": _('veterans_administration_plan')},
				{"id": "WC", "name": _('workers_compensation_health_plan')},
				{"id": "ZZ", "name": _('mutually_defined')}
			]
		});

		Ext.apply(this, {
			name        : 'freeb_type',
			editable    : false,
			displayField: 'name',
			valueField  : 'id',
			queryMode   : 'local',
			emptyText   : _('select'),
			store       : me.store
		}, null);
		me.callParent();
	}
});