Ext.define('App.classes.combo.InsurancePayerType', {
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
				{"id": "1", "name": "All"},
				{"id": "16", "name": "Other HCFA"},
				{"id": "MB", "name": "Medicare Part B"},
				{"id": "MC", "name": "Medicaid"},
				{"id": "CH", "name": "ChampUSVA"},
				{"id": "CH", "name": "ChampUS"},
				{"id": "BL", "name": "Blue Cross Blue Shield"},
				{"id": "16", "name": "FECA"},
				{"id": "09", "name": "Self Pay"},
				{"id": "10", "name": "Central Certification"},
				{"id": "11", "name": "Other Non-Federal Programs"},
				{"id": "12", "name": "Preferred Provider Organization (PPO)"},
				{"id": "13", "name": "Point of Service (POS)"},
				{"id": "14", "name": "Exclusive Provider Organization (EPO)"},
				{"id": "15", "name": "Indemnity Insurance"},
				{"id": "16", "name": "Health Maintenance Organization (HMO) Medicare Risk"},
				{"id": "AM", "name": "Automobile Medical"},
				{"id": "CI", "name": "Commercial Insurance Co."},
				{"id": "DS", "name": "Disability"},
				{"id": "HM", "name": "Health Maintenance Organization"},
				{"id": "LI", "name": "Liability"},
				{"id": "LM", "name": "Liability Medical"},
				{"id": "OF", "name": "Other Federal Program"},
				{"id": "TV", "name": "Title V"},
				{"id": "VA", "name": "Veterans Administration Plan"},
				{"id": "WC", "name": "Workers Compensation Health Plan"},
				{"id": "ZZ", "name": "Mutually Defined"}
			]
		});

		Ext.apply(this, {
			name        : 'freeb_type',
			editable    : false,
			displayField: 'name',
			valueField  : 'id',
			queryMode   : 'local',
			emptyText   : 'Select',
			store       : me.store
		}, null);
		me.callParent();
	}
});